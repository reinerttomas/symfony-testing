<?php

declare(strict_types=1);

namespace App\Service\Action;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class SendEmailAlert
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function execute(): void
    {
        $email = (new Email())
            ->from('bob@dinotopia.com')
            ->to('staff@dinotopia.com')
            ->subject('PARK LOCKDOWN')
            ->text('RUUUUUUUNNNNNN!!!');

        $this->mailer->send($email);
    }
}
