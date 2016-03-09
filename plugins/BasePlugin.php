<?php


namespace BitSurv\Plugins;


use BitSurv\Engine\PluginSystem;

abstract class BasePlugin {

    abstract function initialize(PluginSystem $pluginSystem);

}