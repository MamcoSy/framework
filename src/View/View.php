<?php

namespace LiteFramework\View;

use Jenssegers\Blade\Blade;
use LiteFramework\Globals\Session;
use LiteFramework\FileSystem\FileSystem;

class View
{
    /**
     * View Constructor
     */
    private function __construct() {}

    /**
     * Render a view
     *
     * @param  string   $view
     * @param  array    $data
     * @return string
     */
    public static function render(string $view, array $data = []): string
    {
        $data['errors'] = Session::flash('errors');
        $data['old']    = Session::flash('old');

        return static::bladeRenderer(str_replace(['\\', '/', '.'], DIRECTORY_SEPARATOR, $view), $data);
    }

    /**
     * Render a view with simple php
     *
     * @param  string   $view
     * @param  array    $data
     * @return string
     */
    public static function Simplerenderer(string $view, array $data = []): string
    {
        ob_start();
        extract($data);
        FileSystem::requireFile('views' . DIRECTORY_SEPARATOR . $view . '.php');
        $content = ob_get_clean();
        ob_clean();

        return $content;
    }

    /**
     * Render a view with blate templating engine
     *
     * @param  string   $view
     * @param  array    $data
     * @return string
     */
    public static function bladeRenderer(string $view, array $data = []): string
    {
        $blade = new Blade(FileSystem::getPath('views'), FileSystem::getPath('storage/cache'));

        return $blade->make($view, $data)->render();
    }
}
