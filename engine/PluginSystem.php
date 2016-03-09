<?php


namespace BitSurv\Engine;


use BitSurv\Path;
use BitSurv\Plugins\BasePlugin;

class PluginSystem
{

    private $plugins = [ ];
    private $questionTypes = [ ];

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

    public function registerQuestionType( $type, $callback )
    {
        $this->questionTypes[$type] = $callback;
    }

    public function getNewQuestion($type, $question)
    {
        //dd('test');
        //dd($this->questionTypes);
        if(!isset($this->questionTypes[$type]))
        {
            //dd("Couldn't find: $type");
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