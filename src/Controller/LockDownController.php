<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\LockDownEndData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class LockDownController extends AbstractController
{
    #[Route('/lockdown/end', name: 'app_lockdown_end', methods: ['POST'])]
    public function end(
        #[MapRequestPayload] LockDownEndData $lockDownEnd,
    ): Response {
        if (! $this->isCsrfTokenValid('end-lockdown', $lockDownEnd->token)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        dd('todo');
    }
}
