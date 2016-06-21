<?php


namespace BitSurv\Engine;

use \ORM;

class DbHelper {

    public static function executeSqlFile($file)
    {

        $contents = file_get_contents($file);

        try{
            ORM::raw_execute($contents);
        }
        catch(\Exception $e){
            return false;
        }

        return $contents;
    }
}