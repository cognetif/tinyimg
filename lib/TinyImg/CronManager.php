<?php
namespace Cognetif\TinyImg;

use Cognetif\TinyImg\Traits\ConfigurableTrait;
use Cognetif\TinyImg\Traits\PerchTrait;
use Cognetif\TinyImg\Util\SettingHelper;
use \PerchUtil;
use \PerchScheduledTasks;

class CronManager
{
    use PerchTrait, ConfigurableTrait;

    /** PerchScheduledTasks */
    private $perchTasks;

    /** @var Manager */
    private $manager;

    /** @var RunLogger */
    private $runLogger;

    public function __construct(
        SettingHelper $settings,
        PerchUtil $perchUtil,
        Manager $manager,
        RunLogger $runLogger,
        PerchScheduledTasks $perchTasks
    ) {
        $this->settings = $settings;
        $this->perchUtil = $perchUtil;
        $this->manager = $manager;
        $this->runLogger = $runLogger;
        $this->perchTasks = $perchTasks;

    }

    public function registerTasks()
    {
        $mode = $this->config('cognetif_tinyimg_mode');

        if ($mode !== 'cron') {
            return;
        }

        $this->registerOptimizationTask();
        $this->registerCleaningTask();

    }

    private function registerOptimizationTask()
    {
        $this->perchTasks::register_task('cognetif_tinyimg', 'run_queue', $this->config('cognetif_tinyimg_minutes'),
            function () {

                if ($result = $this->manager->run_queue()) {
                    $this->runLogger->log($this->manager->getRunCount(), $result);

                    return [
                        'result' => 'OK',
                        'message' => 'TinyImg queue completed',
                    ];
                }

                return [
                    'result' => 'FAILED',
                    'message' => 'TinyImg queue failed to complete',
                ];
            });
    }

    private function registerCleaningTask()
    {
        $this->perchTasks::register_task('cognetif_tinyimg', 'clean_queue', 60 * 24, function () {
            if ($this->manager->clean_tinyimg_queue()) {
                return [
                    'result' => 'OK',
                    'message' => 'TinyImg queue cleaning complete',
                ];
            }

            return [
                'result' => 'FAILED',
                'message' => 'TinyImg queue cleaning failed to complete',
            ];
        });
    }
}