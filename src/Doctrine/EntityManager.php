<?php

declare(strict_types=1);

namespace App\Doctrine;

interface EntityManager
{
    public function persist(object $entity): void;

    public function flush(): void;
}
