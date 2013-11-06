<?php

namespace HeyDoc;

class Config
{
    protected $config;

    /**
     *
     *
     * @param array  $config  The container
     */
    public function __construct(array $config)
    {
        $this->config = new \ArrayObject(array_replace($this->getDefaults(), $config));
    }

    public function has($key)
    {
        return $this->config->offsetExists($key);
    }

    public function get($key)
    {
        if (! $this->has($key)) {
            throw new \RuntimeException(sprintf('%s does not contain value for key "%s"', __CLASS__, $key));
        }
        return $this->config->offsetGet($key);
    }

    public function getDefaults()
    {
        return array(
            'theme'      => 'default',
            'debug'      => false,
            'title'      => 'HeyDoc',
            'theme_dirs' => array(),
            // 'cache_dir'  => getcwd() . '/cache',
        );
    }

    public function __call($name, $arguments)
    {
        return $this->get($name);
    }
}
