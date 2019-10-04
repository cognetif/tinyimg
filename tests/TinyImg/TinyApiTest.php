<?php

use Cognetif\TinyImg\TinyApi;
use Cognetif\TinyImg\Util\SettingHelper;
use PHPUnit\Framework\TestCase;

final class TinyApiTest extends TestCase
{
    /**
     * @var TinyApi
     */
    private $tinyApi;

    public $originalFilePath;
    public $testFilePath;

    public function setUp(): void
    {
        $stub = $this->createMock(SettingHelper::class);

        $stub->method('get')
            ->willReturn($_ENV['API_KEY']);

        $this->tinyApi = new TinyApi($stub);

        $imgPath = __DIR__ . '/../image/';
        chmod($imgPath,0777);
        $origFileName = 'image.jpg';
        $testFileName = 'test.jpg';

        $this->originalFilePath = $imgPath . $origFileName;
        $this->testFilePath = $imgPath . $testFileName;
        copy($this->originalFilePath, $this->testFilePath);
    }

    public function tearDown(): void
    {
        if (file_exists( $this->testFilePath)) {
            unlink($this->testFilePath);
        }

        parent::tearDown();
    }

    public function testCanReduceImageFileSize()
    {
        $origFileSize = filesize($this->originalFilePath);
        $this->tinyApi->tinifyImage($this->testFilePath);
        $finalFileSize = filesize($this->testFilePath);
        $this->assertTrue(($origFileSize > $finalFileSize));

    }


}