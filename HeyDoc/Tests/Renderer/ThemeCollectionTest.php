<?php

namespace HeyDoc\Tests\Renderer;

use HeyDoc\Renderer\ThemeCollection;

use Symfony\Component\Finder\SplFileInfo;

class ThemeCollectionTest extends \PHPUnit_Framework_TestCase
{
    private $themeDirs;

    public function setUp()
    {
        $this->themeDirs = array(
            __DIR__ . '/../Resources/themes/'
        );
    }

    public function testConstruct()
    {
        $themeCollection = new ThemeCollection($this->themeDirs);

        $this->assertNotNull($themeCollection);
    }

    //

    public function testLoad()
    {
        $themeCollection = new ThemeCollection($this->themeDirs);

        $this->assertNotNull($themeCollection->getTheme('test_theme'));
        $this->assertNotNull($themeCollection->getTheme('test_theme_2'));

        try {
            $themeCollection->getTheme('test_theme_unknown');

            $this->fail('Exception has not be raised.');
        }
        catch (\Exception $e) {
        }
    }
}
