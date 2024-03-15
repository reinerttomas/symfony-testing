<?php

declare(strict_types=1);

namespace App\Messenger;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

readonly class SymfonyMessageBus implements MessageBus
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @param  StampInterface[]  $stamps
     */
    public function dispatch(object $message, array $stamps = []): void
    {
        $this->messageBus->dispatch($message, $stamps);
    }
}
