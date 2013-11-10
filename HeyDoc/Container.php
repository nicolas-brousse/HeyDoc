<?php

namespace HeyDoc;

use HeyDoc\Parser\Parser;
use HeyDoc\Parser\Markdown;
use HeyDoc\Highlighter\Highlighter;
use HeyDoc\Renderer\Renderer;
use HeyDoc\Renderer\ThemeCollection;
use HeyDoc\Renderer\TwigExtension;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Container extends \Pimple
{
    /**
     * Construct
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

            $vars = array(
                'root_dir' => $c['root_dir'],
                'docs_dir' => $c['docs_dir'],
                'web_dir'  => $c['web_dir'],
            );

            return new Config(file_exists($settingsFilename)
                ? Yaml::parse($settingsFilename)
                : array()
            , null, $vars);
        });

        $this['fs'] = $this->share(function () use ($c) {
            return new Filesystem();
        });

        $this['markdown_parser'] = $this->share(function () use ($c) {
            return new Markdown();
        });

        $this['highlighter'] = $this->share(function () use ($c) {
            return new Highlighter();
        });

        $this['parser'] = $this->share(function () use ($c) {
            return new Parser($c);
        });

        $this['renderer'] = $this->share(function () use ($c) {
            return new Renderer($c, array(
                'debug' => (boolean) $c->get('config')->get('debug'),
                'cache' => $c->get('config')->get('cache_dir') ? $c->get('config')->get('cache_dir') . '/renderer' : false,
            ));
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

        $this['twig_string'] = $this->share(function () use ($c) {
            $loader = new \Twig_Loader_String();
            $twig   = new \Twig_Environment($loader, array(
                'strict_variables' => true,
                'debug'            => (boolean) $c->get('config')->get('debug'),
                'auto_reload'      => (boolean) $c->get('config')->get('debug'),
                'cache'            => $c->get('config')->get('cache_dir') ? $c->get('config')->get('cache_dir') . '/twig' : false,
            ));

            $twig->addExtension(new TwigExtension($c));

            return $twig;
        });

        $this['twig'] = $this->share(function () use ($c) {
            $twig = clone $c['twig_string'];
            $twig->setLoader(new \Twig_Loader_Filesystem(array('/')));
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
