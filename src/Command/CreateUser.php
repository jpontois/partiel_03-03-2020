<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUser extends Command
{
    protected static $defaultName = 'app:createUser';

    private $entityManager;
    private $passwordEncoder;

    public function __construct(
        string $name = null,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('This command create an admin user')
            ->addArgument('login', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('role', InputArgument::REQUIRED, 'User role (ROLE_ADMIN, ROLE_USER, ROLE_REVIEWER, ROLE_COMMUNICATION)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Confirmer la création de l\'utilisateur?',
            false, '/^(y|j)/i');

        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        $login = $input->getArgument('login');
        $password = $input->getArgument('password');
        $role = $input->getArgument('role');

        $io->note(sprintf('User login: %s', $login));
        $io->note(sprintf('User password: %s', $password));
        $io->note(sprintf('User role: %s', $role ?? ''));

        $user = new User();
        $hashedPassword = $this->passwordEncoder->encodePassword($user, $password);
        $user->setLogin($login);
        $user->setPassword($hashedPassword);
        $user->setRoles([$role]);

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $io->error('A error occured : ' . $exception->getMessage());

            return 0;
        }

        $io->success('A new user has been created');

        return 0;
    }
}
