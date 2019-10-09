<?php
require_once('util/autoloader.php');

use Cognetif\TinyImg\CronManager;

include(__DIR__ .'/util/di_container.php');

/** @var CronManager $cronManager */
$cronManager = $di['CronManager'];
$cronManager->registerTasks();





