<?php

namespace HeyDoc;

class Config
{
    /** @var array $config **/
    protected $config;

    /** @var Config $parent **/
    protected $parent;

    /** @var array $vars **/
    protected $vars;

    private $defaults = array(
        'theme'            => 'default',
        'debug'            => false,
        'title'            => 'HeyDoc',
        'date_modified'    => true,
        'google_analytics' => null,

        'cache_dir'  => '%root_dir%/cache',
        'theme_dirs' => array(),
    );

    /**
     * Construct Config from array
     *
     * @param array   $config  The container
     * @param Config  $parent  The parent Config
     * @param array   $vars    Vars to replace into Config values
     */
    public function __construct(array $config, Config $parent = null, array $vars = array())
    {
        $this->parent = $parent;
        $this->vars   = $vars;

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
     * Parse the array to set Config
     *
     * @param array  $config
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
                $parsedConfig->offsetSet($k, new Config($v, $this, $this->vars));
            }
            else {
                $parsedConfig->offsetSet($k, $this->parseValue($v));
            }
        }

        $this->config = $parsedConfig;
    }

    /**
     * Parse a Config string value
     *
     * @param string  $value  Value to parse
     *
     * @return string  Parsed value
     */
    private function parseValue($value)
    {
        $keys = array_map(function($v) {
            return '%' . $v . '%';
        }, array_keys($this->vars));

        return str_replace($keys, array_values($this->vars), $value);
    }

    /**
     * Check if array is an associative array
     *
     * @param array  $a  Array to check
     *
     * @return boolean
     */
    private function isAssociativeArray(array $a) {
        return is_array($a) && array_diff_key($a, array_keys(array_keys($a)));
    }
}
