<?php

$API    = new PerchAPI(1.0, 'cognetif_tinyimg');
$Lang   = $API->get('Lang');

if ($CurrentUser->logged_in()) {
    $this->register_app('cognetif_tinyimg', 'TinyImage', 1, 'Image optimisation', '1.0');
    $this->require_version('cognetif_tinyimg', '3.0');

    $this->add_setting('cognetif_tinyimg_api_key', 'Tinify API Key', 'text');

    $this->add_setting('cognetif_tinyimg_mode', 'Optimization Mode', 'select', 'on_upload', [
        [
            'label' => $Lang->get('On Upload - Slower uploads'),
            'value' => 'upload',
        ],
        [
            'label' => $Lang->get('Cron Job - Faster uploads, have to configure cron on server'),
            'value' => 'cron',
        ],
    ]);

    $this->add_setting('cognetif_tinyimg_minutes', $Lang->get('Cron Frequency (minutes)'), 'select', 60, [
        [
            'label' => $Lang->get('5 min'),
            'value' => 5,
        ],
        [
            'label' => $Lang->get('10 min'),
            'value' => 10,
        ],
        [
            'label' => $Lang->get('15 min'),
            'value' => 15,
        ],
        [
            'label' => $Lang->get('30 min'),
            'value' => 30,
        ],
        [
            'label' => $Lang->get('60 min'),
            'value' => 60,
        ],
        [
            'label' => $Lang->get('3 hours'),
            'value' => 180,
        ],
        [
            'label' => $Lang->get('6 hours'),
            'value' => 360,
        ],
        [
            'label' => $Lang->get('12 hours'),
            'value' => 720,
        ],
        [
            'label' => $Lang->get('1 day'),
            'value' => 1440,
        ],

    ]);


    include('util/autoloader.php');
    include('util/events.php');
//    include('util/scheduled_tasks.php');

}