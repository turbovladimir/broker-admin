<?php

namespace App\Command;

use App\Repository\UserAccessRepository;
use App\Service\Checker\Service;
use App\Service\Integration\LinkShortener\LinkShortener;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct(
        private Service $checkerService,
        private UserAccessRepository $userAccessRepository,
        private LinkShortener $linkShortener
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

        $this->testShortiner();

//        $this->testCheckers($input, $output);
        //$this->sendEmail();
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function testCheckers(InputInterface $input, OutputInterface $output)
    {
        $phone = '+79647746693';
        $result = $this->checkerService->checkPhone($phone);
        $output->writeln('Check phone: ' . $phone);
        $output->writeln($result);

        return $result;
    }

    private function testShortiner()
    {
        $s = $this->linkShortener->shorting('https://url.com');

        return $s;
    }

    private function testIpLimit() : void
    {
        $limits = $this->userAccessRepository->findBy(['ip' => '192.168.65.1'], ['addedAt' => 'DESC']);
    }

//    private function sendEmail() : void
//    {
//        $email = (new TemplatedEmail())
//            ->from(new Address(
////                'no-reply@xn--80aafnhi4ae.hhos.ru',
//                'info@zaymirubli.ru',
//                'Zaymirubli mailer'))
//            ->to('sk8.killer@mail.ru')
//            ->subject("Регистрация на сайте")
//            ->htmlTemplate('components/confirmation_email.html.twig');
//
//        $this->mailer->send($email);
//    }
}
