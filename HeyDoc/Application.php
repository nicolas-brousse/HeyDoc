<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use HeydDoc\Tree;

class Application extends \Pimple
{
    public function __construct($appBaseDir)
    {
        $sc = $this;

        $this['app_basedir'] = realpath($appBaseDir);

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
        $this['theme']         = 'enhanced';
        $this['title']         = 'API';
        $this['template_dirs'] = array();
        $this['build_dir']     = getcwd().'/build';
        $this['cache_dir']     = getcwd().'/cache';
    }

    public function run()
    {
    }
}
