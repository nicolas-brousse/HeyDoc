<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use HeyDoc\Parser\Parser;
use HeyDoc\Renderer\Renderer;
use HeyDoc\Renderer\ThemeCollection;
use HeyDoc\Renderer\TwigExtension;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Container extends \Pimple
{
    /**
     *
     */
    public function __construct()
    {
        if (file_exists($a = realpath(__DIR__.'/../../../../web'))) {
            $this['web_dir'] = $a;
        } else {
            $this['web_dir'] = realpath(__DIR__.'/../web');
        }
    }

    /**
     *
     *
     * @param Request  $request  The Request
     */
    public function setRequest(Request $request)
    {
        $this['request'] = $request;
        $this['web_dir'] = realpath(dirname($this['request']->server->get('SCRIPT_FILENAME')));
    }

    /**
     *
     *
     * @param string  $dir  The web directory path
     */
    public function setWebDir($dir) {
        $this['web_dir'] = realpath($dir);
    }

    /**
     * Load services
     */
    public function load()
    {
        $c = $this;

        $this['root_dir'] = realpath($c['web_dir'] . '/../');
        $this['docs_dir'] = realpath($c['root_dir'] . '/docs/');

        $this['config'] = $this->share(function () use ($c) {
            $settingsFilename = realpath($c['docs_dir'] . '/settings.yml');

            return new Config(file_exists($settingsFilename)
                ? Yaml::parse($settingsFilename)
                : array()
            );
        });

        $this['markdown_parser'] = $this->share(function () use ($c) {
            return new MarkdownExtraParser();
        });

        $this['parser'] = $this->share(function () use ($c) {
            return new Parser($c);
        });

        $this['renderer'] = $this->share(function () use ($c) {
            return new Renderer($c);
        });

        $this['tree'] = $this->share(function () use ($c) {
            return new Tree($c['docs_dir']);
        });

        $this['router'] = $this->share(function() use ($c) {
            return new Router($c);
        });

        $this['themes'] = $this->share(function () use ($c) {
            return new ThemeCollection(array_merge(
                array(__DIR__ . '/Resources/themes'),
                $c->get('config')->get('theme_dirs')
            ));
        });

        $this['twig'] = $this->share(function () use ($c) {
            $loader = new \Twig_Loader_Filesystem(array('/'));
            $twig   = new \Twig_Environment($loader, array(
                'strict_variables' => true,
                'debug'            => (boolean) $c->get('config')->get('debug'),
                'auto_reload'      => true,
                'cache'            => false,
            ));

            $twig->addExtension(new TwigExtension($c));

            return $twig;
        });
    }

    /**
     * Ask if named service exists
     *
     * @param string  $name  Name of the service to check
     *
     * @return boolean
     */
    public function has($name)
    {
        return array_key_exists($name, $this);
    }

    /**
     * Get a service from is name
     *
     * @param string  $name  Name of the service to called
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this[$name];
    }
}
