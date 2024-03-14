<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Dinosaur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DinosaurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dino1 = new Dinosaur('Daisy', 'Velociraptor', 2, 'Paddock A');
        $dino2 = new Dinosaur('Maverick', 'Pterodactyl', 7, 'Aviary 1');
        $dino3 = new Dinosaur('Big Eaty', 'Tyrannosaurus', 15, 'Paddock C');
        $dino4 = new Dinosaur('Dennis', 'Dilophosaurus', 6, 'Paddock B');
        $dino5 = new Dinosaur('Bumpy', 'Triceratops', 10, 'Paddock B');

        $manager->persist($dino1);
        $manager->persist($dino2);
        $manager->persist($dino3);
        $manager->persist($dino4);
        $manager->persist($dino5);
        $manager->flush();
    }
}