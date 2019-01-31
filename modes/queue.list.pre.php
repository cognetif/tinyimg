<?php

use Cognetif\Tinyimg\Queue;

$Paging->set_per_page(50);
$Queue = new Queue($API);
$jobs  = $Queue->all($Paging);

if (!PerchUtil::count($jobs)) {
    $Queue->attempt_install();
}