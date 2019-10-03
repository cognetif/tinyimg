<?php

use Cognetif\TinyImg\Util\SettingHelper;

if (SettingHelper::isDevMode()) {
    $Alert = new PerchAlert();
    $Alert->set('warning', $Lang->get('Development Mode Active. No optimization of images will occur until deactivated within settings'));
    $Alert->output();
}

$Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

$Smartbar->add_item([
    'active' => ($mode ==='queue.list'),
    'title'  => $Lang->get('Queue'),
    'link'   => '/addons/apps/cognetif_tinyimg/',
    'icon'   => 'blocks/list',
]);


$Smartbar->add_item([
    'active' => ($mode ==='options'),
    'title'  => $Lang->get('Options'),
    'link'   => '/addons/apps/cognetif_tinyimg/options.php',
    'icon'   => 'blocks/wrench',
]);
echo $Smartbar->render();