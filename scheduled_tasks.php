<?php

use Cognetif\Tinyimg\Manager;

$Perch = PerchAdmin::fetch();

$API      = new PerchAPI(1.0, 'cognetif_tinyimg');
$Settings = $API->get('Settings');
$mode     = $Settings->get('cognetif_tinyimg_mode')->val();
$minutes  = $Settings->get('cognetif_tinyimg_minutes')->val();
$minutes  = 1;
require_once('util/autoloader.php');
if ($mode === 'cron') {

    PerchScheduledTasks::register_task('cognetif_tinyimg', 'run_queue', $minutes, 'run_tinyimg_queue');

    function run_tinyimg_queue()
    {
        if (Manager::run_queue(new PerchAPI(1.0, 'cognetif_tinyimg'))) {
            return [
                'result'  => 'OK',
                'message' => 'TinyImg queue completed',
            ];
        }

        return [
            'result'  => 'FAILED',
            'message' => 'TinyImg queue failed to complete',
        ];
    }

    PerchScheduledTasks::register_task('cognetif_tinyimg', 'clean_queue', 60 * 24, 'clean_tinyimg_queue');

    function clean_tinyimg_queue()
    {
        if (Manager::clean_tinyimg_queue(new PerchAPI(1.0, 'cognetif_tinyimg'))) {
            return [
                'result'  => 'OK',
                'message' => 'TinyImg queue cleaning complete',
            ];
        }

        return [
            'result'  => 'FAILED',
            'message' => 'TinyImg queue cleaning failed to complete',
        ];
    }
}





