<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Hurah\Transpile\Command\TranspileCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new TranspileCommand());
$application->run();
