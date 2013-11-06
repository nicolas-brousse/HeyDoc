<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Container extends \Pimple
{
    public function __construct()
    {
        if (file_exists($a = realpath(__DIR__.'/../../../../web'))) {
            $this['web_dir'] = $a;
        } else {
            $this['web_dir'] = realpath(__DIR__.'/../web');
        }
    }

    public function setRequest(Request $request)
    {
        $this['request'] = $request;
        $this['web_dir'] = realpath(dirname($this['request']->server->get('SCRIPT_FILENAME')));
    }

    public function setWebDir($dir) {
        $this['web_dir'] = realpath($dir);
    }

    public function load()
    {
        $c = $this;

        $this['root_dir'] = realpath($c['web_dir'] . '/../');
        $this['docs_dir'] = realpath($c['root_dir'] . '/docs/');

        $this['configs'] = $this->share(function () use ($c) {
            $settingsFilename = realpath($c['docs_dir'] . '/settings.yml');
            // TODO return exception if file does not exists
            return Yaml::parse(file_get_contents($settingsFilename));
        });

        $this['markdown_parser'] = $this->share(function () use ($c) {
            return new MarkdownExtraParser();
        });

        $this['parser'] = $this->share(function () use ($c) {
            return new Parsor($c);
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
            $templates = $c['template_dirs'];
            $templates[] = __DIR__ . '/Resources/themes';

            return new ThemeSet($templates);
        });

        $this['twig'] = $this->share(function () use ($c) {
            $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(array('/')), array(
                'strict_variables' => true,
                'debug'            => true,
                'auto_reload'      => true,
                'cache'            => false,
            ));
            $twig->addExtension(new TwigExtension());

            return $twig;
        });

        /**
         * Default configs
         */
        // $defaults = array(
        //     'theme'         => 'enhanced',
        //     'title'         => 'HeyDoc',
        //     'template_dirs' => array(),
        //     'cache_dir'     => getcwd() . '/cache',
        // );
        // $this['configs'] = array_replace($defaults, $this['configs']);
    }

    public function has($name)
    {
        return array_key_exists($name, $this);
    }

    public function get($name)
    {
        return $this[$name];
    }
}
