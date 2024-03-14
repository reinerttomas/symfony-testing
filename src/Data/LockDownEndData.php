<?php

declare(strict_types=1);

namespace App\Data;

use Symfony\Component\Validator\Constraints as Assert;

readonly class LockDownEndData
{
    public function __construct(
        #[Assert\NotBlank]
        public string $token
    ) {
    }
}
