<?php


namespace BitSurv\Engine;


use BitSurv\Path;
use BitSurv\Plugins\BasePlugin;

class PluginSystem
{

    private $plugins = [ ];
    private $hooks = [ ];
    private $questionTypes = [ ];

    private $responseId = -1;

    private function getSystemPluginPath()
    {
        return Path::get( 'core' ) . '/plugins';
    }

    private function pluginPathToName( $plugin )
    {
        $pathFragments = explode( DIRECTORY_SEPARATOR, $plugin );

        return array_pop( $pathFragments );
    }

    private function getPluginClassPath( $plugin )
    {
        $name  = $this->pluginPathToName( $plugin );
        $class = dashesToCamelCase( $name );

        return $plugin . '/' . $class . '.php';
    }

    private function validatePlugin( $plugin )
    {
        $name = $this->pluginPathToName( $plugin );
        $file = $this->getPluginClassPath( $plugin );

        if ( ! file_exists( $file )) {
            throw new \ErrorException( "Could not load plugin `$name`. Failed to find file: `$file`" );
        }
    }

    private function loadFromDirectory( $path )
    {
        $plugins = array_filter( glob( $path . '/*' ), 'is_dir' );

        foreach ($plugins as $plugin) {
            $this->validatePlugin( $plugin );

            $name  = $this->pluginPathToName( $plugin );
            $class = dashesToCamelCase( $name );
            $file  = $this->getPluginClassPath( $plugin );

            require_once( $file );
            $this->plugins[$name] = new $class;
        }

    }

    private function getHooks($hookName)
    {
        if(isset($this->hooks[$hookName])){
            return $this->hooks[$hookName];
        }

        return [];
    }

    public function setResponseId($responseId)
    {
        $this->responseId = $responseId;
    }

    public function getResponseId()
    {
        return $this->responseId;
    }

    public function hook($hookName, $callback)
    {
        if(!isset($this->hooks[$hookName])){
            $this->hooks[$hookName] = array();
        }
        $this->hooks[$hookName][] = $callback;
    }

    public function adjustPage($nextPage, $compiledSurvey)
    {
        $pageHooks = $this->getHooks('adjust-page');

        $newNextPage = $nextPage;
        foreach($pageHooks as $hook){
            $newNextPage = $hook($newNextPage, $compiledSurvey);
        }

        return $newNextPage;
    }

    public function registerQuestionType( $type, $callback )
    {
        $this->questionTypes[$type] = $callback;
    }

    public function getNewQuestion($type, $question)
    {
        if(!isset($this->questionTypes[$type]))
        {
            return false;
        }
        return $this->questionTypes[$type]($question);
    }

    public function loadAll()
    {
        $path = $this->getSystemPluginPath();
        $this->loadFromDirectory( $path );
        $this->loadFromDirectory( Path::get( 'plugins' ) );
    }

    public function bootstrapAll()
    {
        /** @var BasePlugin $plugin */
        foreach ($this->plugins as $plugin) {
            $plugin->initialize( $this );
        }
    }
}