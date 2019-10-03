<?php

namespace Cognetif\TinyImg;

class Queue extends \PerchAPI_Factory
{
    protected $table = "cognetif_tinyimg_queue";
    protected $pk = "queueID";
    protected $singular_classname = 'Cognetif\TinyImg\Job';
    protected $default_sort_column = "queueID";
    protected $default_sort_direction = "DESC";

    private $where = '';

    /**
     * @return string
     */
    protected function standard_restrictions()
    {
        return $this->where;
    }

    /**
     * @param bool $Paging
     * @param string $where
     * @return array|bool|\SplFixedArray
     */
    public function all($Paging = false, $where = '')
    {
        $this->where = $where;

        return parent::all($Paging);
    }

    /**
     * @param int $batch
     * @return array|bool|\SplFixedArray
     */
    public function getBatch($batch = -1)
    {
        $Paging = false;
        $this->default_sort_direction = 'ASC';
        if ($batch > -1) {
            $Paging = new \PerchPaging();
            $Paging->set_per_page($batch);
        }

        return $this->all($Paging, ' AND `status`=\'QUEUED\'');
    }
}