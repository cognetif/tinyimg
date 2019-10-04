<?php
use Cognetif\TinyImg\Manager;
$API = new PerchAPI(1.0, 'cognetif_tinyimg');

$API->on('assets.upload_image', function ($event) use ($di){

    /** @var Manager $manager */
    $manager = $di['Manager'];
    $manager->on_upload_image($event);

});

$API->on('assets.create_image', function ($event) use ($di) {

    /** @var Manager $manager */
    $manager = $di['Manager'];
    $manager->on_create($event);

});
