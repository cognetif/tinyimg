<?php
$API = new PerchAPI(1.0, 'cognetif_tinyimg');

$API->on('assets.upload_image', 'Cognetif\Tinyimg\Manager::on_upload_image');
$API->on('assets.create_image', 'Cognetif\Tinyimg\Manager::on_create');
