<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use HeydDoc\Tree;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Application extends \Pimple
{
    public function __construct($appBaseDir)
    {
        $sc = $this;

        $this['web_dir'] = realpath($appBaseDir);

        $this['configs'] = $this->share(function () use ($sc) {
            $settingsFilename = realpath($sc['web_dir'] . '/../docs/settings.yml');
            // TODO return exception if file does not exists
            return Yaml::parse(file_get_contents($settingsFilename));
        });

        $this['markdown_parser'] = $this->share(function () use ($sc) {
            return new MarkdownExtraParser();
        });

        $this['tree'] = $this->share(function () use ($sc) {
            return new Tree();
        });

        $this['themes'] = $this->share(function () use ($sc) {
            $templates = $sc['template_dirs'];
            $templates[] = __DIR__.'/Resources/themes';

            return new ThemeSet($templates);
        });

        $this['twig'] = $this->share(function () use ($sc) {
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
    }
}
