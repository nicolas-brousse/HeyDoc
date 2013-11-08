<?php

namespace HeyDoc\Tests;

use HeyDoc\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = new Config(array(
            'title' => 'Lorem',
        ));

        $this->assertNotNull($config);

        $config = new Config(array(
            'path' => '%var%/toto',
        ), null, array(
            'var' => '/path/to',
        ));

        $this->assertEquals($config->get('path'), '/path/to/toto');
    }

    public function testHas()
    {
        $config = new Config(array(
            'title' => 'Lorem',
        ));

        $this->assertTrue($config->has('title'));
        $this->assertTrue($config->has('theme'));
        $this->assertFalse($config->has('unexistant.key'));
    }

    public function testGet()
    {
        $config = new Config(array(
            'title' => 'Lorem',
            'key'   => 'value',
        ));

        $this->assertEquals($config->get('title'), 'Lorem');
        $this->assertEquals($config->get('key'), 'value');
    }

    public function testAll()
    {
        $config = new Config(array(
            'title'  => 'Lorem',
            'key'    => 'value',
        ));

        $this->assertEquals($config->all(), array(
            'theme'            => 'default',
            'debug'            => false,
            'title'            => 'Lorem',
            'theme_dirs'       => array(),
            'date_modified'    => true,
            'google_analytics' => null,
            'key'              => 'value',
            'cache_dir'        => '%root_dir%/cache',
        ));

        $config = new Config(array(
            'title'  => 'Lorem',
            'key'    => 'value',
            'nested' => array(
                'k0' => 'v',
                'k1' => 'vvv',
            ),
        ));

        $this->assertEquals($config->get('nested')->all(), array(
            'k0' => 'v',
            'k1' => 'vvv',
        ));
    }

    public function testDefaults()
    {
        $config = new Config(array());

        $this->assertEquals($config->all(), array(
            'theme'            => 'default',
            'debug'            => false,
            'title'            => 'HeyDoc',
            'date_modified'    => true,
            'google_analytics' => null,

            'cache_dir'  => '%root_dir%/cache',
            'theme_dirs' => array(),
        ));
    }

    public function testCall()
    {
        $config = new Config(array(
            'title' => 'Lorem',
            'key'   => 'value',
        ));

        $this->assertEquals($config->title(), 'Lorem');
        $this->assertEquals($config->key(), 'value');
    }
}
