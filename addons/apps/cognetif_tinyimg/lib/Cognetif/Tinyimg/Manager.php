<?php

namespace Cognetif\Tinyimg;

class Manager
{

    /**
     * @param $event
     */
    public static function on_upload_image($event)
    {
        $api       = new PerchAPI(1.0, 'cognetif_tinyimg');
        $settings = $api->get('Settings');
        $originalAction   = $settings->get('cognetif_tinyimg_compress_original')->val();

        if ($originalAction === '1') {
            Job::create_event($event);
        }
    }

    /**
     * @param $event
     */
    public static function on_create($event)
    {
        Job::create_event($event);
    }

    /**
     * Process the entire Queue
     * @param $api
     * @return bool
     */
    public static function run_queue($api)
    {
        $queue  = new Queue($api);
        $jobs   = $queue->get_by('status', 'QUEUED');
        $result = true;
        $count  = is_array($jobs) ? count($jobs) : 0;

        if ($jobs) {
            foreach ($jobs as $job) {

                try {
                    $filePath = $job->get_details()['file_path'];
                    self::tinify_image($api, $filePath);
                    $data = [
                        'status'    => 'DONE',
                        'tiny_size' => filesize($filePath),
                    ];
                } catch (\Exception $e) {
                    $result = false;
                    $data   = [
                        'status'  => 'ERROR',
                        'message' => $e->getMessage(),
                    ];
                }

                $job->update($data);
            }
        }

        RunLog::log($count, $result);
        return $result;
    }

    /**
     * Requeue a single job by ID
     * @param $api
     * @param $id
     */
    public static function requeue($api, $id)
    {
        if ($id) {
            $queue = new Queue($api);
            $job   = $queue->get_one_by('queueID', $id);
            if ($job) {
                $filePath = $job->get_details()['file_path'];
                $job->update([
                    'orig_size' => filesize($filePath),
                    'tiny_size' => 0,
                    'status'    => 'QUEUED',
                ]);
            }
        }
    }

    /**
     * @param \PerchAPI $api
     * @param $filePath
     * @return int
     */
    public static function tinify_image($api, $filePath)
    {
        $settings = $api->get('Settings');
        $apiKey   = $settings->get('cognetif_tinyimg_api_key')->val();

        \Tinify\setKey($apiKey);
        return \Tinify\fromFile($filePath)->toFile($filePath);
    }

    /**
     * Remove dead images from the queue
     * @param $api
     * @return bool
     */
    public static function clean_tinyimg_queue($api)
    {
        $queue = new Queue($api);
        $jobs  = $queue->all();

        if ($jobs) {
            foreach ($jobs as $job) {
                $filePath = $job->get_details()['file_path'];
                if (!file_exists($filePath)) {
                    $job->delete();
                }
            }
        }

        return true;
    }


}