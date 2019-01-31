<?php
if (!defined('PERCH_DB_PREFIX')) {
    exit('No DB prefix found');
}

$sql = "
    CREATE TABLE IF NOT EXISTS `" . PERCH_DB_PREFIX . "cognetif_tinyimg_queue` (
        `queueID` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `file_name` char(255) NOT NULL,
        `file_path` text NOT NULL,
        `web_path` text NOT NULL,
        `status` enum('QUEUED','WORKING', 'DONE','ERROR') NOT NULL DEFAULT 'QUEUED',
        `orig_size` int(10) unsigned NOT NULL DEFAULT '0',
        `tiny_size` int(10) unsigned NOT NULL DEFAULT '0',
    
        PRIMARY KEY (`queueID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
    ";

$this->db->execute($sql);

$sql = "
CREATE TABLE IF NOT EXISTS `" . PERCH_DB_PREFIX . "cognetif_tinyimg_queue_run_log` (
        `runID` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `runtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `number_processed` int(10) unsigned NOT NULL DEFAULT 0,
        `outcome` enum('PASS', 'FAIL') NOT NULL DEFAULT 'PASS',
        PRIMARY KEY (`runID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$this->db->execute($sql);
