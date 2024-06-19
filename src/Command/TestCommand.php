<?php

namespace App\Command;

use App\Repository\UserAccessRepository;
use App\Service\Auth\Access\AccessManager;
use App\Service\Checker\Service;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[AsCommand(
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct(
        private MailerInterface $mailer,
        private AccessManager $accessManager,
        private Service $checkerService,
        private UserAccessRepository $userAccessRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

//        $this->testCheckers();
        $this->sendEmail();
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function testCheckers()
    {
        $result = $this->checkerService->checkPhone('79088715044');

        return $result;
    }

    private function testIpLimit() : void
    {
        $limits = $this->userAccessRepository->findBy(['ip' => '192.168.65.1'], ['addedAt' => 'DESC']);
    }

    private function sendEmail() : void
    {
        $email = (new TemplatedEmail())
            ->from(new Address(
//                'no-reply@xn--80aafnhi4ae.hhos.ru',
                'info@zaymirubli.ru',
                'Zaymirubli mailer'))
            ->to('sk8.killer@mail.ru')
            ->subject("Регистрация на сайте")
            ->htmlTemplate('components/confirmation_email.html.twig');

        $this->mailer->send($email);
    }
}
