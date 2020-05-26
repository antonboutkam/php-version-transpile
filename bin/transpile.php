<?php
if(file_exists('../vendor/autoload.php'))
{
    require_once '../vendor/autoload.php';
}
else
{
    require_once '../../../../autoload.php';

}

use Hurah\Transpile\Command\TranspileCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new TranspileCommand());
$application->run();
