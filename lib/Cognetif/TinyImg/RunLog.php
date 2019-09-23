<?php

namespace Cognetif\TinyImg;


class RunLog extends \PerchAPI_Base
{
    const DB_TABLE = "cognetif_tinyimg_queue_run_log";
    protected $table = self::DB_TABLE;
    protected $pk    = "runID";


    public static function log($count, $result)
    {

        $api = new \PerchAPI(1.0, 'cognetif_tinyimg');
        $db  = $api->get('DB');

        self::activate($api, $db);

        $data = [
            'number_processed' => $count,
            'outcome'          => $result ? 'PASS' : 'FAIL',
        ];

        $db->insert(PERCH_DB_PREFIX . self::DB_TABLE, $data);
    }

    private static function activate($api, $db)
    {
        //Throws PDOException if table doesn't exist so catch it and activate app
        try {
            $db->get_table_meta(PERCH_DB_PREFIX . self::DB_TABLE);
        } catch (\PDOException $e) {
            $Queue = new Queue($api);
            $Queue->attempt_install();
        }


    }
}