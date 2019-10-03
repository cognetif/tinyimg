<?php

use Cognetif\TinyImg\Util\Icon;
use Cognetif\TinyImg\Util\SettingHelper;

/**
 * @var \PerchAPI_Lang $Lang
 * @var \PerchAPI_HTML $HTML
 */

echo $HTML->title_panel([
    'heading' => $Lang->get('Image Optimization Options'),
], $CurrentUser);

include(__DIR__ . '/../util/_smartbar.php');

if (strlen($message) > 0) {
    echo $HTML->main_panel_start();
    echo $message;
    echo $HTML->main_panel_end();
}
echo $HTML->main_panel_start();
$batchSize = SettingHelper::getBatchSize() > -1 ? SettingHelper::getBatchSize(): 'All';
echo $HTML->heading1($Lang->get('Run a batch of jobs in queue. %s Jobs will be executed', [$batchSize]));
?>
    <form method="POST">
        <input type="hidden" name="action" value="PROCESS"/>
        <button type="submit"
                class="button button-icon"><?= PerchUI::icon('core/gear'); ?> <?= $Lang->get('Start Queue'); ?></button>
    </form>
<?php
echo $HTML->main_panel_end();
echo $HTML->main_panel_start();
echo $HTML->heading1($Lang->get('Requeue Errors and Working'));
?>
    <form method="POST">
        <input type="hidden" name="action" value="REQUEUE-ALL"/>
        <button type="submit"
                class="button button-icon"><?= PerchUI::icon('core/o-undo'); ?> <?= $Lang->get('Requeue All Issues'); ?></button>
    </form>
<?php
echo $HTML->main_panel_end();
echo $HTML->main_panel_start();
echo $HTML->heading1($Lang->get('Clean queue of dead images'));
?>
    <form method="POST">
        <input type="hidden" name="action" value="CLEAN"/>
        <button type="submit" class="button button-icon"><?= Icon::get('trash',
                ['width' => 16, 'height' => 16]); ?><?= $Lang->get('Clean Queue'); ?></button>
    </form>
<?php
echo $HTML->main_panel_end();
