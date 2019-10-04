<?php
namespace Cognetif\TinyImg;

use \PerchAPI_Base;

/**
 * Class Job
 * @package Cognetif\TinyImg
 * @method get_details()
 * @method queueID()
 * @method created()
 * @method file_name()
 * @method file_path()
 * @method web_path()
 * @method status()
 * @method orig_size()
 * @method tiny_size()
 * @method percent_saved()
 * @method message()
 * @method update($data=[])
 */
class Job extends PerchAPI_Base
{
    protected $table = "cognetif_tinyimg_queue";
    protected $pk    = "queueID";

}