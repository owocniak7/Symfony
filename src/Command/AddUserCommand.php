<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Add a new user',
)]
class AddUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private GroupRepository $groupRepo
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'User email');
        $this->addArgument('role', InputArgument::REQUIRED, 'User role (e.g. ROLE_USER or ROLE_ADMIN)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');

        $question = new Question('Password: ');
        $question->setHidden(true);
        $password = $helper->ask($input, $output, $question);

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$role]);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        // przypisz domyślną grupę
        $group = $this->groupRepo->findOneBy([]); // pierwszy lepszy
        $user->setGroup($group);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln("<info>User '$email' created successfully!</info>");
        return Command::SUCCESS;
    }
}
