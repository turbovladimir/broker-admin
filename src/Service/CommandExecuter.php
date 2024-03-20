<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandExecuter
{
    public function __construct(private KernelInterface $kernel, private LoggerInterface $commandLogger)
    {
    }

    public function executeCommand(ArrayInput $input)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();
        $this->commandLogger->info('Execute command from script', [
            'in' => $input,
            'out' => $content
        ]);
    }
}