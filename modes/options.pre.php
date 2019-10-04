<?php
use Cognetif\TinyImg\Manager;

/**
 * @var PerchAPI_Lang $Lang
 * @var PerchAPI_HTML $HTML
 */

$message = "";
if ($action = filter_input(INPUT_POST, 'action', FILTER_VALIDATE_REGEXP,
    ["options" => ["regexp" => "/^REQUEUE|REQUEUE-ALL|PROCESS|CLEAN|IGNORE$/"]])) {

    /** @var Manager $manager */
    $manager = $di['Manager'];
    switch ($action) {
        case 'PROCESS' :

            if ($result = $manager->run_queue()) {
                $message = $HTML->success_message($Lang->get('The queue completed successfully.'));
            } else {
                $message = $HTML->failure_message($Lang->get('The queue completed with errors.'));
            }
            break;

        case 'CLEAN' :
            $manager->clean_tinyimg_queue();
            $message = $HTML->success_message($Lang->get('The queue has been cleaned.'));
            break;

        case 'REQUEUE-ALL' :

            $manager->requeue_all_error_working();
            $message = $HTML->success_message($Lang->get('The queue has been reset.'));
            break;

        case 'IGNORE' :
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $manager->ignore($id);
            PerchSystem::redirect(PERCH_LOGINPATH . '/addons/apps/cognetif_tinyimg');
            break;
        case 'REQUEUE' :
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $manager->requeue($id);
            PerchSystem::redirect(PERCH_LOGINPATH . '/addons/apps/cognetif_tinyimg');
            break;
        default:
            break;
    }
}