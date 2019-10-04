<?php

use Cognetif\TinyImg\Util\SettingHelper;
use PHPUnit\Framework\TestCase;

final class SettingHelperTest extends TestCase
{

    private $settingHelper;

    private $apiStub;
    private $settingsStub;
    private $settingStub;

    public function setUp(): void
    {
        $this->apiStub = $this->createStub(\PerchAPI::class);
        $this->settingsStub = $this->createStub(\PerchAPI_Settings::class);
        $this->settingStub = $this->createStub(\PerchSetting::class);
        $this->settingsStub->method('get')->willReturn($this->settingStub);
        $this->apiStub->method('get')->willReturn($this->settingsStub);

        $this->settingHelper = new SettingHelper($this->apiStub);
    }

    public function testCanGetValueFromSettings() {
        $this->settingStub->method('val')->willReturn('patate');

        $actual = $this->settingHelper->get('SomeConfig');

        $this->assertEquals('patate', $actual);

    }
}