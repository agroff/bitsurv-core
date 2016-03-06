<?php


namespace BitSurv\Engine;

use BitSurv\Path;

class SurveyData
{

    private function fetchRaw( $survey )
    {
        $path = Path::get( 'surveys', $survey );

        $json = file_get_contents( $path );

        $result = json_decode( $json, true );

        return $result;
    }

    private function listDir( $path )
    {
        //filters out directory entries beginning with `.`
        return preg_grep( '/^([^.])/', scandir( $path . '/' ) );
    }

    public function findByListener( $url )
    {

        $url = trim( $url, ' /' );

        $surveys = $this->listDir( Path::get( 'surveys' ) );

        foreach ($surveys as $survey) {
            $listening = "";
            $survey    = $this->fetchRaw( $survey );

            if ( ! empty( $survey["listen"] )) {
                $listening = trim( $survey["listen"], ' /' );
            }

            if ($url === $listening) {
                return $survey;
            }
        }

        return $this->fetchRaw( array_shift( $surveys ) );
    }

    public function findByName( $name )
    {
        return $this->fetchRaw($name . '.json');
    }

    public function compile( $data )
    {

        if ( ! empty( $data["extends"] )) {
            $baseData = $this->findByName( $data["extends"] );

            unset($data["extends"]);
            $data = array_merge_recursive($baseData, $data);
        }

        return $data;
    }

}