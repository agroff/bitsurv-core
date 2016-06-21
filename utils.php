<?php

/**
 * @param      $item - The config key to search for. Separated by dots for nested arrays.
 *                       eg "path.one.two" returns $findIn["path"]["one"]["two"]
 * @param bool $findIn - The array to search for the key, defaults to config
 *
 * @return bool
 */
function config( $item, $findIn = false )
{
    //make static so we don't keep hitting file system to include
    static $config = false;

    //load config if its not loaded
    if ( ! $config) {
        $config = require( __DIR__ . '/../config.php' );
    }

    //we'll search config if another array to search wasn't provided
    if ($findIn === false) {
        $findIn = $config;
    }

    //turn the items string into an array
    $items = explode( '.', $item );

    //get the first item
    $item = array_shift( $items );

    if ( ! empty( $findIn[$item] )) {

        $found = $findIn[$item];

        //if there are no items left to find, return the one we found
        if (count( $items ) === 0) {
            return $found;
        }

        //gotta go deeper. recurse searching the item we found
        return config( implode( '.', $items ), $found );
    }

    return false;
}

function tbl($name)
{
    switch($name){
        case "surveys";
            return "bts_surveys";
        case "answers";
            return "bts_answers";
        case "responses";
            return "bts_responses";
    }

    return "unknown_table";
}

function o( $thing )
{
    echo $thing;
}

function forceArray( $item )
{
    if (is_array( $item )) {
        return $item;
    }

    return [ $item ];
}

function joinPaths()
{
    $args  = func_get_args();
    $paths = array();
    foreach ($args as $arg) {
        $paths = array_merge( $paths, (array) $arg );
    }

    $paths = array_map( create_function( '$p', 'return trim($p, "/");' ), $paths );
    $paths = array_filter( $paths );

    return join( '/', $paths );
}

/**
 * Replaces the last occurance of $search with $replace
 *
 * @param $search
 * @param $replace
 * @param $subject
 *
 * @return mixed
 */
function str_lreplace( $search, $replace, $subject )
{
    $pos = strrpos( $subject, $search );

    if ($pos !== false) {
        $subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
    }

    return $subject;
}

/**
 * Convert php time phase to a standard SQL format
 *
 * @param string $timePhrase
 * @param string $format
 * @return bool|string
 */
function sqlDate($timePhrase = '', $format = 'Y-m-d H:i:s')
{
    return (!!$timePhrase) ? date($format, strtotime($timePhrase)) : date($format);
}

function post( $key = false )
{
    if ($key === false) {
        return $_POST;
    }
    if (empty( $_POST[$key] )) {
        return false;
    }

    return $_POST[$key];
}

/**
 * Outputs variables for debugging
 */
function dbg()
{
    array_map( function ( $x ) {
        echo "<pre>";
        var_dump( $x );
        echo "</pre>";
    }, func_get_args() );
}

/**
 * Outputs variables for debugging and exits
 */
function dd()
{
    call_user_func_array( 'dbg', func_get_args() );
    die();
}

function dashesToCamelCase( $string, $capitalizeFirstCharacter = true )
{

    $str = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $string ) ) );

    if ( ! $capitalizeFirstCharacter) {
        $str[0] = strtolower( $str[0] );
    }

    return $str;
}

function surveyDbg( $data )
{
    echo json_encode( $data, JSON_PRETTY_PRINT );
}