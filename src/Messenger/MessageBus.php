<?php

declare(strict_types=1);

namespace App\Messenger;

use Symfony\Component\Messenger\Stamp\StampInterface;

interface MessageBus
{
    /**
     * @param  StampInterface[]  $stamps
     */
    public function dispatch(object $message, array $stamps = []): void;
}
