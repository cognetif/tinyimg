<?php
namespace Cognetif\TinyImg;

use Cognetif\TinyImg\Traits\ConfigurableTrait;
use Cognetif\TinyImg\Traits\PerchTrait;
use Cognetif\TinyImg\Util\SettingHelper;
use \PerchUtil;
use Tinify\Exception as TinyException;

class Manager
{
    use PerchTrait, ConfigurableTrait;

    /** @var Queue */
    private $queue;

    /** @var TinyApi */
    private $tinyApi;

    /** @var int */
    private $runCount = 0;

    public function __construct(SettingHelper $settings, Queue $queue, TinyApi $tinyApi, PerchUtil $perchUtil)
    {
        $this->perchUtil = $perchUtil;
        $this->settings = $settings;
        $this->queue = $queue;
        $this->tinyApi = $tinyApi;
    }

    /**
     * @param $event
     */
    public function on_upload_image($event)
    {
        if ($this->configCompressOriginal()) {
            $this->queue->createJobFromEvent($event);
        }
    }

    /**
     * @param $event
     */
    public function on_create($event)
    {
        $this->queue->createJobFromEvent($event);
    }

    /**
     * Process the entire Queue
     * @return bool
     */
    public function run_queue()
    {

        $jobs = $this->queue->getBatch($this->configBatchSize());
        $this->resetCount();

        $result = true;

        if (!$jobs) {
            return false;
        }

        if ($this->configIsProdMode()) {
            foreach ($jobs as $job) {
                $job->update(['status' => 'WORKING']);
            }
        }

        foreach ($jobs as $job) {


            $details = $job->get_details();

            if ($this->configIsDevMode()) {

                $this->debugLog('Cognetif TinyImg - DevMode On : Skipping ' . $details['file_name']);
                continue;

            }

            $filePath = PERCH_SITEPATH . $details['web_path'];

            try {
                $tinySize = $this->tinyApi->tinifyImage($filePath);

                if ($details['orig_size'] > 0) {
                    $percentSaved = round(100 * (1 - ($tinySize / $details['orig_size'])), 2);
                } else {
                    $this->debugError('Cognetif TinyImg original file size is 0 for id' . $details['queueID']);
                    $percentSaved = -1;
                }

                $data = [
                    'status' => 'DONE',
                    'tiny_size' => filesize($filePath),
                    'percent_saved' => $percentSaved,
                ];

            } catch (TinyException $e) {

                $this->debugError('Tinify Exception Thrown');
                $this->debugError($e->getMessage());

                $data = [
                    'status' => 'ERROR',
                    'message' => 'Tinify Service Exception. Have you reached your monthly limit ?'
                ];

                $result = false;
            }

            $job->update($data);
            $this->incCount();

        }

        return $result;
    }

    /**
     * Requeue a single job by ID
     * @param $id
     */
    public function requeue($id)
    {

        if (!$id) {
            return;
        }

        /** @var Job $job */
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $job = $this->queue->get_one_by('queueID', $id);

        if (!$job) {
            return;
        }

        $filePath = PERCH_SITEPATH . $job->get_details()['web_path'];

        $job->update([
            'orig_size' => filesize($filePath),
            'tiny_size' => 0,
            'status' => 'QUEUED',
        ]);


    }

    /**
     * Ignore a single job by ID
     * @param $id
     */
    public function ignore($id)
    {
        if (!$id) {
            return;
        }

        /** @var Job $job */
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $job = $this->queue->get_one_by('queueID', $id);

        if (!$job) {
            return;
        }

        $job->update([
            'status' => 'IGNORE',
        ]);
    }


    /**
     * Remove dead images from the queue
     * @return bool
     */
    public function clean_tinyimg_queue(): bool
    {
        $this->resetCount();
        $jobs = $this->queue->all();

        if (!$jobs) {
            return true;
        }

        foreach ($jobs as $job) {

            $this->incCount();
            $filePath = PERCH_SITEPATH . $job->get_details()['web_path'];

            if (file_exists($filePath)) {
                continue;
            }

            $job->delete();
        }

        return true;
    }


    private function resetCount(): void
    {
        $this->runCount = 0;
    }

    private function incCount(): void
    {
        $this->runCount++;
    }

    /**
     * @return bool
     */
    public function requeue_all_error_working(): bool
    {
        $this->resetCount();

        $jobs = $this->queue->all(false, ' AND `status` in (\'WORKING\',\'ERROR\')');

        if (!$jobs) {
            return true;
        }

        foreach ($jobs as $job) {

            if (!$job) {
                continue;
            }

            $data = [
                'orig_size' => filesize(PERCH_SITEPATH . $job->get_details()['web_path']),
                'tiny_size' => 0,
                'status' => 'QUEUED',
            ];

            if (!$job->update($data)) {
                $job->update(['status' => 'ERROR']);
            }

            $this->incCount();

        }

        return true;
    }

    /**
     * @return int
     */
    public function getRunCount(): int
    {
        return $this->runCount;
    }
}
