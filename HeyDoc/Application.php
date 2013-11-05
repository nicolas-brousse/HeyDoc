<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Application extends \Pimple
{
    public function __construct($appBaseDir)
    {
        $c = $this;

        $this['web_dir']  = realpath($appBaseDir);
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

        $this['tree'] = $this->share(function () use ($c) {
            return new Tree($c['docs_dir']);
        });

        $this['themes'] = $this->share(function () use ($c) {
            $templates = $c['template_dirs'];
            $templates[] = __DIR__.'/Resources/themes';

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

    public function run()
    {
        // TODO
        $this->prepare();
    }

    protected function prepare()
    {

    }
}
