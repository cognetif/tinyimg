<?php
require_once('util/autoloader.php');

use Cognetif\TinyImg\CronManager;
use Pimple\Container;

$di = new Container();
require_once('util/di_container.php');

/** @var CronManager $cronManager */
$cronManager = $di['CronManager'];
$cronManager->registerTasks();





