<?php

namespace Cognetif\TinyImg;

use Cognetif\TinyImg\Util\SettingHelper;
use \PerchAPI;

class Manager
{

    /**
     * @param $event
     */
    public static function on_upload_image($event)
    {
        $api = new PerchAPI(1.0, 'cognetif_tinyimg');
        $settings = $api->get('Settings');
        $originalAction = $settings->get('cognetif_tinyimg_compress_original')->val();

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
        $queue = new Queue($api);
        $jobs = $queue->getBatch(SettingHelper::getBatchSize());
        $result = true;
        $count = is_array($jobs) ? count($jobs) : 0;

        if ($jobs) {

            if (SettingHelper::isProdMode()) {
                foreach ($jobs as $job) {

                    $job->update(['status' => 'WORKING']);
                }
            }


            foreach ($jobs as $job) {
                try {
                    $details = $job->get_details();

                    if (SettingHelper::isProdMode()) {

                        $filePath = PERCH_SITEPATH . $details['web_path'];
                        try {
                            $tinySize = self::tinify_image($api, $filePath);
                            if ($details['orig_size'] > 0) {
                               $percentSaved =  round(100 * (1 - ($tinySize / $details['orig_size'])), 2);
                            } else {
                                \PerchUtil::debug('Cognetif TinyImg original file size is 0 for id' . $details['queueID'],'warning');
                                $percentSaved = -1;
                            }

                            $data = [
                                'status' => 'DONE',
                                'tiny_size' => filesize($filePath),
                                'percent_saved' => $percentSaved,
                            ];

                        } catch (\Tinify\Exception $e) {
                            \PerchUtil::debug('Tinify Exception Thrown', 'error');
                            \PerchUtil::debug($e->getMessage(), 'error');
                            $data = [
                                'status' => 'ERROR',
                                'message' => 'Tinify Service Exception. Have you reached your monthly limit ?'
                            ];
                            $result = false;
                        }
                        $job->update($data);
                    } else {
                        \PerchUtil::debug('Cognetif TinyImg - DevMode On : Skipping ' . $details['file_name']);
                    }
                } catch (\Exception $e) {
                    $result = false;
                    $data = [
                        'status' => 'ERROR',
                        'message' => $e->getMessage(),
                    ];
                    $job->update($data);

                }


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
            $job = $queue->get_one_by('queueID', $id);
            if ($job) {
                $filePath = PERCH_SITEPATH . $job->get_details()['web_path'];
                $job->update([
                    'orig_size' => filesize($filePath),
                    'tiny_size' => 0,
                    'status' => 'QUEUED',
                ]);
            }
        }
    }

    /**
     * Ignore a single job by ID
     * @param $api
     * @param $id
     */
    public static function ignore($api, $id)
    {
        if ($id) {
            $queue = new Queue($api);
            $job = $queue->get_one_by('queueID', $id);
            if ($job) {
                $job->update([
                    'status' => 'IGNORE',
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
        $apiKey = $settings->get('cognetif_tinyimg_api_key')->val();

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
        $jobs = $queue->all();

        if ($jobs) {
            foreach ($jobs as $job) {
                $filePath = PERCH_SITEPATH . $job->get_details()['web_path'];
                if (!file_exists($filePath)) {
                    $job->delete();
                }
            }
        }

        return true;
    }

    /**
     * @param $api
     * @return bool
     */
    public static function requeue_all_error_working($api)
    {
        $queue = new Queue($api);
        $jobs = $queue->all(false, ' AND `status` in (\'WORKING\',\'ERROR\')');

        if ($jobs) {
            foreach ($jobs as $job) {
                if ($job) {
                    $filePath = PERCH_SITEPATH . $job->get_details()['web_path'];
                    $success = $job->update([
                        'orig_size' => filesize($filePath),
                        'tiny_size' => 0,
                        'status' => 'QUEUED',
                    ]);

                    if (!$success) {
                        $job->update([
                            'status' => 'ERROR',
                        ]);
                    }
                }
            }
        }

        return true;
    }

}