<?php

namespace BitSurv\Bootstrap;

use BitSurv\Engine\PluginSystem;
use BitSurv\Engine\SurveyEngine;
use BitSurv\Router;

class Bootstrap {

    public function main(){

        //load router
        $router = new Router();

        //bootstrap system
        $router->addRoute('*/admin', function(){
            echo "Admin Page";
        });

        //load plugin system
        $pluginSystem = new PluginSystem();

        //bootstrap plugins
        $pluginSystem->loadAll();
        $pluginSystem->bootstrapAll();

        //do routing
        $routed = $router->route();

        if($routed === false){

            $engine = new SurveyEngine();
            $url = $router->getLastUrl();
            $engine->providePluginSystem($pluginSystem);
            $engine->render($url);
        }
    }
}