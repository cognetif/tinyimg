<?php
$Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

$Smartbar->add_item([
    'active' => true,
    'title'  => $Lang->get('Queue'),
    'link'   => '/addons/apps/cognetif_tinyimg/',
    'icon'   => 'blocks/list',
]);


$Smartbar->add_item([
    'active' => true,
    'title'  => $Lang->get('Options'),
    'link'   => '/addons/apps/cognetif_tinyimg/options.php',
    'icon'   => 'blocks/wrench',
]);
echo $Smartbar->render();