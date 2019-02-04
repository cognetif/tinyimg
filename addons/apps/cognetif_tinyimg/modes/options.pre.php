<?php

use Cognetif\Tinyimg\Manager;

/**
 * @var \PerchAPI_Lang $Lang
 * @var \PerchAPI_HTML $HTML
 */

$message = "";
if ($action = filter_input(INPUT_POST, 'action', FILTER_VALIDATE_REGEXP,
    ["options" => ["regexp" => "/^REQUEUE|PROCESS|CLEAN$/"]])) {

    switch ($action) {
        case 'PROCESS' :
            $result = Manager::run_queue($API);
            if ($result) {
                $message = $HTML->success_message($Lang->get('The queue completed successfully.'));
            } else {
                $message = $HTML->failure_message($Lang->get('The queue completed with errors.'));
            }
            break;

        case 'CLEAN' :
            Manager::clean_tinyimg_queue($API);
            $message = $HTML->success_message($Lang->get('The queue has been cleaned.'));
            break;

        case 'REQUEUE' :
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            Manager::requeue($API, $id);
            PerchSystem::redirect(PERCH_LOGINPATH . '/addons/apps/cognetif_tinyimg');
            break;
        default:
            break;
    }
}