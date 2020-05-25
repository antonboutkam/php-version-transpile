<?php
namespace Hurah\Transpile\Command;

use Hurah\Transpile\Transpiler;
use Hurah\Transpile\TranspileRunner;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

final class TranspileCommand extends Command
{
    protected static $defaultName = 'transpile';

    protected function configure()
    {
        $this
            ->setDescription('Removes typed properties from classes to make code compatible with php 7.3 or 7.4')
            ->addArgument('action', InputArgument::REQUIRED, 'Options are 7.3 or 7.4')
            ->setHelp('This command makes the code compatible with php 7.3.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $verbosityLevelMap = [
            LogLevel::DEBUG => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL,
        ];
        $oLogger = new ConsoleLogger($output, $verbosityLevelMap);

        $sAction = $input->getArgument('action');
        $aVersionMap = [
            '7.3' => Transpiler::PHP7_3,
            '7.4' => Transpiler::PHP7_4,
        ];
        if(!in_array($sAction, array_keys($aVersionMap)))
        {
            $oLogger->error("Invalid argument, action must be one of 7,3 or 7.4");
            return 128;
        }

        $oLogger->info("Transpiling to php version: $sAction");

        $oCompileRunner = new TranspileRunner($oLogger, "../src", "../dist", $aVersionMap[$sAction]);
        $oCompileRunner->run();

        return 0;
    }
}
