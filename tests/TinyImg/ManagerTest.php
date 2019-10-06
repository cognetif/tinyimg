<?php

use Cognetif\TinyImg\Job;
use Cognetif\TinyImg\Manager;
use Cognetif\TinyImg\Queue;
use Cognetif\TinyImg\TinyApi;
use Cognetif\TinyImg\Util\SettingHelper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var Queue|MockObject M
     */
    private $queue;

    /**
     * @var TinyApi|MockObject
     */
    private $tinyApi;

    /**
     * @var PerchUtil|MockObject
     */
    private $perchUtil;

    /**
     * @var SettingHelper|MockObject
     */
    private $settings;

    /**
     * @var Job|MockObject
     */
    private $job;

    /**
     * @var Job[]
     */
    private $jobs = [];

    /**
     * @var array
     */
    private $testEvent = [];

    public $testImagePath;

    public function setUp(): void
    {
        $this->settings = $this->createMock(SettingHelper::class);
        $this->queue = $this->createMock(Queue::class);
        $this->job = $this->createMock(Job::class);
        $this->tinyApi = $this->createMock(TinyApi::class);
        $this->perchUtil = $this->createMock(PerchUtil::class);

        $this->manager = new Manager($this->settings, $this->queue, $this->tinyApi, $this->perchUtil);

        $this->testImagePath = PERCH_SITEPATH . 'image/image.jpg';
        $this->testEvent = [
            'created' => time(),
            'file_name' => 'image.jpg',
            'file_path' => $this->testImagePath,
            'web_path' => 'image/image.jeg',
            'status' => 'QUEUED',
            'orig_size' => '148086',
            'tiny_size' => 0,
            'percent_saved' => 0,
            'message' => 'PhpUnit Tests',
        ];

        //$this->jobs[] = new Job($this->testEvent);

    }

    public function tearDown(): void
    {
        foreach ($this->jobs as $job) {
            //$job->delete();
        }

        parent::tearDown();
    }

    public function testWillCompressGeneratedImageAlwaysWithCompressOriginalOn()
    {
        $this->settings->method('get')->with('cognetif_tinyimg_compress_original')->willReturn(true);
        $this->queue->expects($this->once())->method('createJobFromEvent')->with($this->testEvent);
        $this->manager->on_create($this->testEvent);
    }

    public function testWillCompressGeneratedImageAlwaysWithCompressOriginalOff()
    {
        $this->settings->method('get')->with('cognetif_tinyimg_compress_original')->willReturn(false);
        $this->queue->expects($this->once())->method('createJobFromEvent')->with($this->testEvent);
        $this->manager->on_create($this->testEvent);
    }


    public function testWillCompressOriginalIfConfigured()
    {
        $this->settings->method('get')->with('cognetif_tinyimg_compress_original')->willReturn(true);
        $this->queue->expects($this->once())->method('createJobFromEvent')->with($this->testEvent);
        $this->manager->on_upload_image($this->testEvent);
    }

    public function testWillSkipCompressingOriginalIfConfigured()
    {
        $this->settings->method('get')->with('cognetif_tinyimg_compress_original')->willReturn(false);
        $this->queue->expects($this->never())->method('createJobFromEvent');
        $this->manager->on_upload_image($this->testEvent);
    }

    public function testCanRequeueJobByIdIfExists() {
        $jobId = 9999999;
        $pathToImage = '/image/image.jpg';
        $this->queue->method('get_one_by')->with('queueID', $jobId)->willReturn($this->job);
        $this->job->method('get_details')->willReturn(['web_path' => $pathToImage]);

        $this->job->expects($this->once())->method('update')->with([
            'orig_size' => filesize(PERCH_SITEPATH . $pathToImage),
            'tiny_size' => 0,
            'status' => 'QUEUED',
        ]);

        $this->manager->requeue($jobId);
    }

    public function testWilNotRequeueNonExistantJob() {
        $jobId = 9999999;
        $this->queue->method('get_one_by')->with('queueID', $jobId)->willReturn(null);

        $this->job->expects($this->never())->method('update');
        $this->manager->requeue($jobId);

    }

    public function testWilNotRequeueForNoJobId() {


        $this->job->expects($this->never())->method('update');
        $this->manager->requeue(null);

    }

}