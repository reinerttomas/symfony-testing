<?php

declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineEntityManager implements EntityManager
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function persist(object $entity): void
    {
        $this->em->persist($entity);
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
