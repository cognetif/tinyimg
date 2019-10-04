<?php

use Cognetif\TinyImg\Queue;

$Paging->set_per_page(50);

/** @var Queue */
$Queue = $di['Queue'];

$jobs  = $Queue->all($Paging);

if (!$di['PerchUtil']::count($jobs)) {
    $Queue->attempt_install();
}