<?php

use Cognetif\TinyImg\Util\Icon;
use PHPUnit\Framework\TestCase;

final class IconTest extends TestCase
{
    const BASE64DATA ='data:image/png;base64,iVBORw0KGgoAAAANS';

    private $icon;

    public function setUp(): void
    {
        $this->icon = new Icon(['icon1' => self::BASE64DATA]);
    }

    public function testEmptyStringIsRenderedForIconNotFound() {
        $html = $this->icon->get('icon2');
        $this->assertEquals('', $html);
    }

    public function testCanReturnIconNoOptions() {
        $html = $this->icon->get('icon1');
        $this->assertEquals('<img src="'. self::BASE64DATA.'" alt="" />', $html);
    }

    public function testCanReturnIconWithClass() {
        $html = $this->icon->get('icon1', ['class' => 'test1 test-2']);
        $this->assertEquals('<img src="'. self::BASE64DATA.'" alt="" class="test1 test-2" />', $html);
    }

    public function testCanReturnIconWithAllAttributes() {
        $html = $this->icon->get('icon1', [
            'class' => 'test1',
            'style' => 'color:blue',
            'height' => '25px',
            'width' => '25px',
            'alt' => 'phpUnit',
        ]);

        $this->assertEquals('<img src="'. self::BASE64DATA.'" alt="phpUnit" class="test1" style="color:blue" width="25px" height="25px" />', $html);
    }

    public function testOptionsOrderDoesntMatter() {
        $html = $this->icon->get('icon1', [
            'height' => '25px',
            'style' => 'color:blue',
            'class' => 'test1',
            'width' => '25px',
            'alt' => 'phpUnit',
        ]);

        $this->assertEquals('<img src="'. self::BASE64DATA.'" alt="phpUnit" class="test1" style="color:blue" width="25px" height="25px" />', $html);

    }

}