<?php

namespace Cognetif\TinyImg;

use Cognetif\TinyImg\Util\SettingHelper;

class Job extends \PerchAPI_Base
{
    const DB_TABLE = "cognetif_tinyimg_queue";
    protected $table = self::DB_TABLE;
    protected $pk    = "queueID";

    public static function create_event($event)
    {

        $api      = new \PerchAPI(1.0, 'cognetif_tinyimg');
        $settings = $api->get('Settings');
        $mode     = $settings->get('cognetif_tinyimg_mode')->val();
        $asset    = $event->subject;

        $db = $api->get('DB');

        self::activate($api, $db);

        $orig_size = filesize(PERCH_SITEPATH . $asset->web_path);
        $data = [
            'file_name' => $asset->file_name,
            'file_path' => $asset->file_path,
            'web_path'  => $asset->web_path,
            'orig_size' => $orig_size,
        ];

        $id = $db->insert(PERCH_DB_PREFIX . self::DB_TABLE, $data);

        if ($mode === 'upload') {
            try {
                if (SettingHelper::isProdMode()) {
                    $result = Manager::tinify_image($api, PERCH_SITEPATH . $asset->web_path);
                    $db->update(PERCH_DB_PREFIX . self::DB_TABLE, [
                        'tiny_size' => $result,
                        'status' => 'DONE',
                        'percent_saved' => round(100 * (1 - ($result / $orig_size)), 2),
                    ], 'queueID', $id);
                } else {
                    \PerchUtil::debug('Cognetif TinyImg - DevMode On : Skipping ' . $asset->file_name);
                }
            } catch (\Tinify\Exception $e) {
                \PerchUtil::debug('Tinify Exception Thrown', 'error');
                \PerchUtil::debug($e->getMessage(), 'error');
                $db->update(PERCH_DB_PREFIX . self::DB_TABLE, [
                    'status' => 'ERROR',
                    'message' => 'Tinify Service Exception. Have you reached your monthly limit ?'
                ], 'queueID', $id);
            }
        }

    }

    private static function activate($api, $db)
    {

        try {
            $db->get_table_meta(PERCH_DB_PREFIX . self::DB_TABLE);
        } catch (\PDOException $e) {
            $Queue = new Queue($api);
            $Queue->attempt_install();
        }


    }
}