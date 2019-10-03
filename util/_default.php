<?php
/**
 * @var \PerchAPI_Lang $Lang
 * @var \PerchAPI_HTML $HTML
 * @var \PerchAPI_Paging $Paging
 */

include(__DIR__ . '/../../../../core/inc/api.php');

$API    = new PerchAPI(1.0, 'cognetif_tinyimg');
$Lang   = $API->get('Lang');
$HTML   = $API->get('HTML');
$Paging = $API->get('Paging');

# Set the page title
$Perch->page_title = $Lang->get($title);

# Do anything you want to do before output is started
include(__DIR__ . '/../modes/' . $mode . '.pre.php');

# Top layout
include(PERCH_CORE . '/inc/top.php');

# Display your page
include(__DIR__ . '/../modes/' . $mode . '.post.php');

# Bottom layout
include(PERCH_CORE . '/inc/btm.php');

$commonCssHash = substr(md5(file_get_contents(__DIR__ . '/../assets/style.css')), 0, 8);
echo '<link rel="stylesheet" href="' . PERCH_LOGINPATH . '/addons/apps/cognetif_tinyimg/assets/style.css?' . $commonCssHash . '" >';