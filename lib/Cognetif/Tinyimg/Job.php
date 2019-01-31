<?php

namespace Cognetif\Tinyimg;

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

        $data = [
            'file_name' => $asset->file_name,
            'file_path' => $asset->file_path,
            'web_path'  => $asset->web_path,
            'orig_size' => filesize($asset->file_path),
        ];

        $id = $db->insert(PERCH_DB_PREFIX . self::DB_TABLE, $data);

        if ($mode === 'upload') {
            $result = Manager::tinify_image($api, $asset->file_path);
            $db->update(PERCH_DB_PREFIX . self::DB_TABLE, [
                'tiny_size' => $result,
                'status'    => 'DONE',
            ], 'queueID', $id);

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