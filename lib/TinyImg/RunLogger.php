<?php
namespace Cognetif\TinyImg;

use \PDOException;
use \PerchAPI_Factory;

class RunLogger extends PerchAPI_Factory
{

    protected $table = "cognetif_tinyimg_queue_run_log";
    protected $pk = "runID";
    protected $singular_classname = 'Cognetif\TinyImg\RunLog';
    protected $default_sort_column = "runID";
    protected $default_sort_direction = "DESC";

    private $where = '';


    /**
     * RunLogger constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

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
     * @return Job[]|bool
     */
    public function all($Paging = false, $where = '')
    {
        $this->where = $where;

        return parent::all($Paging);
    }


    private function detectInstallRequired()
    {
        try {
            $this->db->get_table_meta(PERCH_DB_PREFIX . $this->table);
        } catch (PDOException $e) {
            $this->attempt_install();
        }

    }

    public function log($count, $result)
    {

        $this->detectInstallRequired();

        $data = [
            'number_processed' => $count,
            'outcome'          => $result ? 'PASS' : 'FAIL',
        ];

        $this->create($data);

    }

}