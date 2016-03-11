<?php


namespace BitSurv;


class Router {

    private $routes = [];

    private $baseUrl = '';

    private function ensureTrailingSlash($url){
        $final = $url[strlen($url) - 1];

        if($final !== '/'){
            $url = $url . '/';
        }

        return $url;
    }

    private function getUrlBase(){
        $uri = '';
        if(!empty($_SERVER["PATH_INFO"])){
            $uri = $_SERVER["PATH_INFO"];
        }
        $url = $_SERVER["HTTP_HOST"] . $uri;

        $url = $this->ensureTrailingSlash($url);

        $this->baseUrl = $url;

        return  $url;
    }

    private function matchesPath($path, $url){
        $path = $this->ensureTrailingSlash($path);
        $url = $this->ensureTrailingSlash($url);

        $path = preg_quote($path, '/');

        $path = str_replace("\\*", ".+", $path);

        if(preg_match('/'.$path.'/', $url)){
            return true;
        }

        return false;
    }

    public function getLastUrl(){
        return $this->baseUrl;
    }

    public function addRoute($path, $callback){
        $this->routes[$path] = $callback;
    }

    public function route(){
        $url = $this->getUrlBase();

        foreach($this->routes as $path => $callback){
            if($this->matchesPath($path, $url)){
                $callback();
                return true;
            }
        }

        return false;
    }
}