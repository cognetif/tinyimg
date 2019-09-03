<?php
$sql = "
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA='" . PERCH_DB_DATABASE . "'
    AND TABLE_NAME='" . PERCH_DB_PREFIX . "cognetif_tinyimg_queue'
    AND column_name='percent_saved'
";

if (!$db->get_value($sql)) {

    $sql = "
        ALTER TABLE `" . PERCH_DB_PREFIX . "cognetif_tinyimg_queue`
        ADD COLUMN `percent_saved` float(10,2) DEFAULT 0 AFTER `tiny_size`
    ";

    $db->execute($sql);

    $sql  = "
        UPDATE perch3_cognetif_tinyimg_queue 
        SET percent_saved = IF(tiny_size > 0, ROUND(100*(1-(tiny_size/orig_size)),2), 0) 
        WHERE percent_saved = 0
    ";

    $db->execute($sql);
}

$sql = "
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA='" . PERCH_DB_DATABASE . "'
    AND TABLE_NAME='" . PERCH_DB_PREFIX . "cognetif_tinyimg_queue'
    AND column_name='message'
";

if (!$db->get_value($sql)) {

    $sql = "
        ALTER TABLE `" . PERCH_DB_PREFIX . "cognetif_tinyimg_queue`
        ADD COLUMN `message` TEXT AFTER `percent_saved`
    ";

    $db->execute($sql);
}

