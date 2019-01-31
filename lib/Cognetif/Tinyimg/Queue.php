<?php

namespace Cognetif\Tinyimg;

class Queue extends \PerchAPI_Factory
{
    protected $table               = "cognetif_tinyimg_queue";
    protected $pk                  = "queueID";
    protected $singular_classname  = 'Cognetif\Tinyimg\Job';
    protected $default_sort_column = "queueID";
    protected $default_sort_direction = "DESC";
}