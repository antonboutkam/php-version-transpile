<?php
if(file_exists('../vendor/autoload.php'))
{
    require '../vendor/autoload.php';
}
else
{
    require '../../vendor/autoload.php';

}

use Hurah\Transpile\Command\TranspileCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new TranspileCommand());
$application->run();
