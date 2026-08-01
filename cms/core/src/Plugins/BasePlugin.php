<?php

namespace Cms\Core\Plugins;

abstract class BasePlugin
{
    /**
     * Bootstrap the plugin. Run routes, register hooks, register views, etc.
     */
    abstract public function boot(): void;
}
