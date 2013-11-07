<?php

namespace HeyDoc;

class Config
{
    /** @var array $config **/
    protected $config;

    /**
     * Construct Config from array
     *
     * @param array  $config  The container
     */
    public function __construct(array $config)
    {
        $this->config = new \ArrayObject(array_replace($this->getDefaults(), $config));
    }

    /**
     * Ask if config contain key
     *
     * @param string  $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return $this->config->offsetExists($key);
    }

    /**
     * Get a config value with key
     *
     * @param string  $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (! $this->has($key)) {
            throw new \RuntimeException(sprintf('%s does not contain value for key "%s"', __CLASS__, $key));
        }
        return $this->config->offsetGet($key);
    }

    /**
     * Get defaults configs
     *
     * @return array
     */
    public function getDefaults()
    {
        return array(
            'theme'            => 'default',
            'debug'            => false,
            'title'            => 'HeyDoc',
            'theme_dirs'       => array(),
            'date_modified'    => true,
            'google_analytics' => null,

            // 'cache_dir'  => getcwd() . '/cache',
        );
    }

    /**
     * Magic call method
     *
     * @param string  $name  Method name
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->get($name);
    }
}
