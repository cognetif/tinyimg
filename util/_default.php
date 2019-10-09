<?php
/**
 * @var \PerchAPI_Lang $Lang
 * @var \PerchAPI_HTML $HTML
 * @var \PerchAPI_Paging $Paging
 */
require_once __DIR__ .'/autoloader.php';

require __DIR__ . '/../../../../core/inc/api.php';

require __DIR__ . '/di_container.php';

$Lang   = $di['PerchApi']->get('Lang');
$HTML   = $di['PerchApi']->get('HTML');
$Paging = $di['PerchApi']->get('Paging');

# Set the page title
$Perch->page_title = $Lang->get($title);

# Do anything you want to do before output is started
include __DIR__ . '/../modes/' . $mode . '.pre.php';

# Top layout
require PERCH_CORE . '/inc/top.php';

# Display your page
include __DIR__ . '/../modes/' . $mode . '.post.php';

# Bottom layout
require PERCH_CORE . '/inc/btm.php';

$commonCssHash = substr(md5(file_get_contents(__DIR__ . '/../assets/style.css')), 0, 8);
echo '<link rel="stylesheet" href="' . PERCH_LOGINPATH . '/addons/apps/cognetif_tinyimg/assets/style.css?' . $commonCssHash . '" >';