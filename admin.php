<?php

$API = new PerchAPI(1.0, 'cognetif_tinyimg');
$Lang = $API->get('Lang');
$db = $API->get('DB');
if ($CurrentUser->logged_in()) {
    $this->register_app('cognetif_tinyimg', 'TinyImage', 1, 'Image optimisation', '1.0');
    $this->require_version('cognetif_tinyimg', '3.0');

    $this->add_setting('cognetif_tinyimg_api_key', 'Tinify API Key', 'text');

    $this->add_setting('cognetif_tinyimg_dev_mode', 'Development Mode (On = No processing will happen)', 'select',
        'on', [
            [
                'label' => $Lang->get('On'),
                'value' => 'on',
            ],
            [
                'label' => $Lang->get('Off'),
                'value' => 'off',
            ],
        ]);

    $this->add_setting('cognetif_tinyimg_batch_size', 'Batch Size (-1 unlimited)', 'text', '-1');

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

    $this->add_setting('cognetif_tinyimg_compress_original', 'Original File Compression', 'select', 'compress_original',
        [
            [
                'label' => $Lang->get('Compress - Saves disk space but derived images are based on a compressed original.'),
                'value' => '1',
            ],
            [
                'label' => $Lang->get('No Compression - Uses more disk space but derived images are based on the true original image.'),
                'value' => '0',
            ],
        ]);

    include('util/autoloader.php');
    include('util/events.php');
    include('db/updates.php');

}
