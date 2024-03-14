<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\LockDownFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LockDownFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        LockDownFactory::new()
            ->active()
            ->create([
                'reason' => 'We have a T-Rex... and he\'s like, not in his cage!',
            ]);
    }
}
