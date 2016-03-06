<?php


namespace BitSurv;


class Path
{

    public static function root()
    {
        $docRoot = $_SERVER["DOCUMENT_ROOT"];
        $rootPath = config( 'paths.root' );

        return str_lreplace($rootPath, '', $docRoot);
    }

    public static function get( $name, $suffix = false)
    {
        $paths = config( 'paths' );

        if($suffix !== false){
            return '/' . joinPaths( self::root(), $paths[$name], $suffix );
        }

        return '/' . joinPaths( self::root(), $paths[$name] );

    }
}