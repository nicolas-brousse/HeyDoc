<?php

namespace HeyDoc\Tests\Renderer;

use HeyDoc\Renderer\Theme;

use Symfony\Component\Finder\SplFileInfo;

class ThemeTest extends \PHPUnit_Framework_TestCase
{
    private $themeDir;

    public function setUp()
    {
        $this->themeDir = new SplFileInfo(__DIR__ . '/../Resources/themes/test_theme', null, 'test_theme');
    }

    public function testConstruct()
    {
        $theme = new Theme($this->themeDir);

        $this->assertNotNull($theme);
    }

    public function testGetPath()
    {
        $theme = new Theme($this->themeDir);

        $this->assertEquals($theme->getPath(), realpath($this->themeDir));


        try {
            $falseThemeDir = new SplFileInfo(__DIR__ . '/../Resources/templates/test_theme', null, 'test_theme');

            $theme = new Theme($falseThemeDir);

            $this->fail('InvalidArgumentException has not be raised.');
        }
        catch (\InvalidArgumentException $e) {
        }
    }
}
