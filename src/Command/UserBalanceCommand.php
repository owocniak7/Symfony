<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\BalanceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user-balance',
    description: 'Show user balance',
)]
class UserBalanceCommand extends Command
{
    public function __construct(
        private UserRepository $userRepo,
        private BalanceService $balanceService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'User email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            $output->writeln("<error>User not found</error>");
            return Command::FAILURE;
        }

        $balance = $this->balanceService->getUserBalance($user);
        $status = $this->balanceService->isInMinus($user) ? 'MINUS' : 'PLUS';

        $output->writeln("User: $email");
        $output->writeln("Balance: $balance ($status)");
        return Command::SUCCESS;
    }
}
