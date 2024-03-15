<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\LockDownStartedNotification;
use App\Service\Action\SendEmailAlert;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class LockDownStartedNotificationHandler
{
    public function __construct(
        private SendEmailAlert $sendEmailAlert,
    ) {
    }

    public function __invoke(LockDownStartedNotification $message): void
    {
        $this->sendEmailAlert->execute();
    }
}
