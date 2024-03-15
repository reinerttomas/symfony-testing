<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LockDownService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:lockdown:start',
    description: 'Lock down started',
)]
class LockDownStartCommand extends Command
{
    public function __construct(
        private readonly LockDownService $lockDownService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->lockDownService->dinosaurEscaped();

        $io->caution('Lock down started!!!!!!!');

        return Command::SUCCESS;
    }
}
