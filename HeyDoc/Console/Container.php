<?php

namespace HeyDoc\Console;

use HeyDoc\Container as BaseContainer;
use HeyDoc\Config;

class Container extends BaseContainer
{

    /**
     * Load services
     */
    public function load()
    {
        parent::load();

        $c = $this;

        $this['config'] = $this->share(function () use ($c) {
            $settingsFilename = realpath($c['docs_dir'] . '/settings.yml');

            return new Config(array(
                'cache_dir' => false,
            ));
        });
    }
}
