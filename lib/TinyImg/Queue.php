<?php
namespace Cognetif\TinyImg;

use Cognetif\TinyImg\Traits\ConfigurableTrait;
use Cognetif\TinyImg\Util\SettingHelper;
use Tinify\Exception as TinyException;
use \PerchUtil;
use \PDOException;
use \PerchAPI_Factory;
use \PerchPaging;
use Cognetif\TinyImg\Traits\PerchTrait;

class Queue extends PerchAPI_Factory
{
    use PerchTrait, ConfigurableTrait;

    protected $table = "cognetif_tinyimg_queue";
    protected $pk = "queueID";
    protected $singular_classname = 'Cognetif\TinyImg\Job';
    protected $default_sort_column = "queueID";
    protected $default_sort_direction = "DESC";

    private $where = '';

    /** @var TinyApi */
    private $tinyApi;

    /**
     * Queue constructor.
     * @param SettingHelper $settings
     * @param TinyApi $tinyApi
     * @param PerchUtil $perchUtil
     */
    public function __construct(SettingHelper $settings, TinyApi $tinyApi, PerchUtil $perchUtil)
    {
        $this->perchUtil = $perchUtil;
        $this->settings = $settings;
        $this->tinyApi = $tinyApi;

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

    /**
     * @param int $batch
     * @return bool|Job[]
     */
    public function getBatch($batch = -1)
    {
        $Paging = false;
        $this->default_sort_direction = 'ASC';
        if ($batch > -1) {
            $Paging = new PerchPaging();
            $Paging->set_per_page($batch);
        }

        return $this->all($Paging, ' AND `status`=\'QUEUED\'');
    }

    private function detectInstallRequired()
    {
        try {
            $this->db->get_table_meta($this->table);
        } catch (PDOException $e) {
            $this->attempt_install();
        }

    }

    /**
     * @param $event
     * @return bool
     */
    public function createJobFromEvent($event)
    {

        $mode = $this->config('cognetif_tinyimg_mode');
        $asset = $event->subject;

        $this->detectInstallRequired();

        $orig_size = filesize(PERCH_SITEPATH . $asset->web_path);

        $data = [
            'file_name' => $asset->file_name,
            'file_path' => $asset->file_path,
            'web_path' => $asset->web_path,
            'orig_size' => $orig_size,
        ];

        /** @var Job $job */
        $job = $this->create($data);

        if (!$job) {
            return false;
        }

        if ($mode !== 'upload') {
            return true;
        }


        try {

            if ($this->configIsDevMode()) {
                $this->debugLog('Cognetif TinyImg - DevMode On : Skipping ' . $asset->file_name);
                return true;
            }

            $result = $this->tinyApi->tinifyImage(PERCH_SITEPATH . $asset->web_path);

            return $job->update([
                'tiny_size' => $result,
                'status' => 'DONE',
                'percent_saved' => round(100 * (1 - ($result / $orig_size)), 2),
            ]);

        } catch (TinyException $e) {

            $this->debugError('Tinify Exception Thrown');
            $this->debugError($e->getMessage());

            return $job->update([
                'status' => 'ERROR',
                'message' => 'Tinify Service Exception. Have you reached your monthly limit ?'
            ]);
        }

    }
}