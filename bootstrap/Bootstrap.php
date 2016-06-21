<?php

namespace BitSurv\bootstrap;

use BitSurv\Engine\PluginSystem;
use BitSurv\Engine\SurveyEngine;
use BitSurv\Router;
use \ORM;

class Bootstrap
{

    private function initializeDatabase()
    {
        $name = config('db.name');
        $host = config('db.host');
        $un   = config('db.username');
        $pw   = config('db.password');

        $debug = config("debug");

        ORM::configure('mysql:host='.$host.';dbname='.$name);
        ORM::configure('username', $un);
        ORM::configure('password', $pw);

        if(!empty($debug)){
            ORM::configure('logging', true);
        }
    }

    public function main()
    {
        $this->initializeDatabase();

        //load router
        $router = new Router();

        require( __DIR__ . "/../routes.php" );

        //load plugin system
        $pluginSystem = new PluginSystem();

        //bootstrap plugins
        $pluginSystem->loadAll();
        $pluginSystem->bootstrapAll();

        //do routing
        $routed = $router->route();

        if ($routed === false) {

            $engine = new SurveyEngine();
            $url    = $router->getLastUrl();
            $engine->providePluginSystem( $pluginSystem );
            $engine->render( $url );
        }
    }
}