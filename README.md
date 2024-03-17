# symfony-testing

This project is a simple application built with Symfony which is about integration testing. [symfonycasts](https://symfonycasts.com/screencast/phpunit-integration/integration-test).

## Features

* ✅ Symfony 7
* ✅ Doctrine ORM
* ✅ DataFixtures
* ✅ Foundry
* ✅ AssetMapper
* ✅ PHPStan
* ✅ Laravel Pint (PHP Coding Standards Fixer)
* ✅ GitHub Actions
* ✅ Tests

## Installation

Install dependencies using Composer

```
composer install
```

Create your .env file from example

```
cp .env.example .env
```

Start database in Docker
```
docker compose up -d
```

## Integration Testing with Live Services

Before run any test let's talk about testing philosophy or pattern called AAA:

* Arrange
* Act
* Assert

With an integration test, the Arrange step commonly involves adding rows to your database. The Act step is where you call the method and then Assert is, of course, the assertions at the end.

```php
class LockDownRepositoryTest extends KernelTestCase
{
    #[Test]
    public function it_can_find_the_most_recent_lock_down(): void
    {
        // Arrange
        $lockDown = LockDownFactory::createOne([
            'createdAt' => new \DateTimeImmutable('-1 day'),
        ]);
        LockDownFactory::createMany(5, [
            'createdAt' => new \DateTimeImmutable('-2 day'),
        ]);

        // Act
        $lockDownMostRecent = $this->lockDownRepository->findMostRecent();

        // Assert
        self::assertNotNull($lockDownMostRecent);
        self::assertSame($lockDown->getId(), $lockDownMostRecent->getId());
    }
}
```

### Resetting the Database

Tests should be independent from each other to avoid side effects. For example, if some test modifies the database (by adding or removing an entity) it could change the results of other tests. The [DAMADoctrineTestBundle](https://github.com/dmaicher/doctrine-test-bundle) uses Doctrine transactions to let each test interact with an unmodified database. It begins a database transaction before every test and rolls it back automatically after the test finishes to undo all changes.

### Data Seeding

There are two approaches to seeding our database. The first is to write code inside the test to insert all the data. The second is to create and run a set of fixtures. The [zenstruck/foundry](https://github.com/zenstruck/foundry) uses Factory for creating entity with random data.

```php
class LockDown
{
    private int $id;
    private LockDownStatus $status;
    private string $reason;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $endedAt = null;
    
    // ...
}

/**
 * @extends ModelFactory<LockDown>
 */
final class LockDownFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'reason' => self::faker()->text(255),
            'status' => self::faker()->randomElement(LockDownStatus::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return LockDown::class;
    }

    public function active(): self
    {
        return $this->addState(['status' => LockDownStatus::ACTIVE]);
    }
}

class LockDownRepositoryTest extends KernelTestCase
{
    #[Test]
    public function it_should_be_in_lockdown(): void
    {
        LockDownFactory::new()
            ->active()
            ->create([
                'createdAt' => new \DateTimeImmutable('-1 day'),
            ]);

        self::assertTrue($this->lockDownRepository->isInLockDown());
    }
}
```

### Testing a Service

In our LockDownService when we can end current lockdown. This method call external API. We need to mock this service to avoid any API calls.

```php
class LockDownService
{
    public function __construct(
        private LockDownRepository $lockDownRepository,
        private EntityManager $em,
        private LockDownAlertSetter $lockDownAlertSetter,
    ) {
    }

    public function endCurrentLockDown(): void
    {
        $lockDown = $this->lockDownRepository->findMostRecent();

        if ($lockDown === null) {
            throw new \LogicException('There is no lock down to end');
        }

        $lockDown->setStatus(LockDownStatus::ENDED);
        $this->em->flush();

        // this call external API
        $this->lockDownAlertSetter->clearLockDownAlerts();
    }
}

class LockDownServiceTest extends KernelTestCase
{
    use Factories;

    #[Test]
    public function it_should_end_the_current_lockdown(): void
    {
        self::bootKernel();

        $lockDown = LockDownFactory::new()
            ->active()
            ->create();

        // Mock service calling external API
        $lockDownSetter = $this->createMock(LockDownAlertSetter::class);
        $lockDownSetter->expects(self::once())
            ->method('clearLockDownAlerts');
        self::getContainer()->set(LockDownAlertSetter::class, $lockDownSetter);

        $lockDownService = self::getContainer()->get(LockDownService::class);
        $lockDownService->endCurrentLockDown();
        
        self::assertSame(LockDownStatus::ENDED, $lockDown->object()->getStatus());
    }
}
```

### Testing repository

In our LockDownService when we can start lockdown when dinosaur escaped. This create new LockDown in database. In test we can assert that we insert expected number of rows.

```php
class LockDownServiceTest extends KernelTestCase
{
    use Factories;

    #[Test]
    public function it_should_start_lockdown_when_dinosaur_escaped(): void
    {
        self::bootKernel();

        $this->getLockDownService()->dinosaurEscaped();
        LockDownFactory::repository()->assert()->count(1);
    }
}
```

### Testing Emails

When dinosaur escaped we also want to send email alert. The package [zenstruck/mailer-test](https://github.com/zenstruck/mailer-test) give us the tools to test sending emails.

```php
readonly class LockDownService
{
    public function __construct(
        private EntityManager $em,
        private SendEmailAlert $sendEmailAlert,
    ) {
    }

    public function dinosaurEscaped(): void
    {
        $lockDown = new LockDown('Dino escaped... NOT good...');

        $this->em->persist($lockDown);
        $this->em->flush();

        $this->sendEmailAlert->execute();
    }
}

class LockDownServiceTest extends KernelTestCase
{
    use Factories;
    use InteractsWithMailer;

    #[Test]
    public function it_should_start_lockdown_when_dinosaur_escaped(): void
    {
        self::bootKernel();

        $this->getLockDownService()->dinosaurEscaped();
        LockDownFactory::repository()->assert()->count(1);

        self::mailer()->assertSentEmailCount(1);
        self::mailer()->assertEmailSentTo('staff@dinotopia.com', 'PARK LOCKDOWN');
    }
}
```
