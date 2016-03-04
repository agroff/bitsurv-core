<?php

namespace BitSurv\Bootstrap;

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

        //bootstrap plugins

        //do routing
        $routed = $router->route();

        if($routed === false){

            $engine = new SurveyEngine();
            $url = $router->getLastUrl();
            $engine->render($url);
        }
    }
}