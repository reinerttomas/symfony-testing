<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\DinosaurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DinosaurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DinosaurFactory::createSequence([
            [
                'name' => 'Daisy',
                'genus' => 'Velociraptor',
                'length' => 2,
                'enclosure' => 'Paddock A',
            ],
            [
                'name' => 'Maverick',
                'genus' => 'Pterodactyl',
                'length' => 7,
                'enclosure' => 'Aviary 1',
            ],
            [
                'name' => 'Big Eaty',
                'genus' => 'Tyrannosaurus',
                'length' => 15,
                'enclosure' => 'Paddock C',
            ],
            [
                'name' => 'Dennis',
                'genus' => 'Dilophosaurus',
                'length' => 6,
                'enclosure' => 'Paddock B',
            ],
            [
                'name' => 'Bumpy',
                'genus' => 'Triceratops',
                'length' => 10,
                'enclosure' => 'Paddock B',
            ],
        ]);
    }
}
