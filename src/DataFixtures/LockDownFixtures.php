<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\LockDown;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LockDownFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $lockDown = new LockDown('We have a T-Rex... and he\'s like, not in his cage!');

        $manager->persist($lockDown);
        $manager->flush();
    }
}
