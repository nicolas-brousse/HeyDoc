<?php

namespace HeyDoc;

class Config
{
    /** @var array $config **/
    protected $config;

    protected $parent;

    private $defaults = array(
        'theme'            => 'default',
        'debug'            => false,
        'title'            => 'HeyDoc',
        'theme_dirs'       => array(),
        'date_modified'    => true,
        'google_analytics' => null,

        // 'cache_dir'  => getcwd() . '/cache',
    );

    /**
     * Construct Config from array
     *
     * @param array   $config  The container
     * @param Config  $parent  The parent Config
     */
    public function __construct(array $config, Config $parent = null)
    {
        $this->parent = $parent;

        $this->parse($config);
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
     * Get the configs
     *
     * @return array
     */
    public function all()
    {
        return $this->config->getArrayCopy();
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

    /**
     *
     */
    protected function parse(array $config)
    {
        if ($this->parent === null) {
            $config = array_replace($this->defaults, $config);
        }

        $parsedConfig = new \ArrayObject();

        foreach ($config as $k=>$v)
        {

            if (is_array($v) && $this->isAssociativeArray($v)) {
                $parsedConfig->offsetSet($k, new Config($v, $this));
            }
            else {
                $parsedConfig->offsetSet($k, $v);
            }
        }

        $this->config = $parsedConfig;
    }

    private function isAssociativeArray(array $a) {
        return is_array($a) && array_diff_key($a, array_keys(array_keys($a)));
    }
}
