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

    public function findByListener( $url, $skipHost = false )
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

        if ($skipHost === false) {
            //strip the host and just match against the URI in a single instance of recursion
            $segments = explode( '/', $url );
            array_shift( $segments );
            $newUrl = implode( '/', $segments );

            return $this->findByListener( $newUrl, true );
        }

        //default case just returns the first survey it finds
        return $this->fetchRaw( array_shift( $surveys ) );
    }

    public function findByName( $name )
    {
        return $this->fetchRaw( $name . '.json' );
    }

    private function findQuestion( $questionSet, $questionName )
    {
        foreach($questionSet as $name => $question){
            if($questionName === $name){
                return $question;
            }
        }

        return false;
    }

    private function mergeQuestions( $base, $new )
    {
        //merge all the questions so we're dealing with a single base of questions
        $questions = array_merge( $base, $new );

        foreach($questions as $name => $question){
            if(isset($question["extends"])){
                $toExtend = $this->findQuestion($questions, $question["extends"]);
                if($toExtend !== false){
                    $questions[$name] = array_merge($toExtend, $question);
                }
            }
        }

        return $questions;
    }

    private function mergePage()
    {

    }

    private function mergeSurveys( $base, $new )
    {
        //provide some default values for questions
        $questions = [ ];
        if ( ! empty( $base["questions"] )) {
            $questions = $base["questions"];
            unset( $base["questions"] );
        }

        $newQuestions = [ ];
        if ( ! empty( $new["questions"] )) {
            $newQuestions = $new["questions"];
            unset( $new["questions"] );
        }

        $questions = $this->mergeQuestions( $questions, $newQuestions );

        foreach ($new as $key => $value) {
            if (is_array( $value )) {

                if (empty( $base[$key] )) {
                    $base[$key] = [ ];
                }
                $base[$key] = array_merge( $base[$key], $new[$key] );
            } else {
                $base[$key] = $new[$key];
            }
        }

        $base["questions"] = $questions;

        return $base;
    }

    public function compile( $data )
    {

        if ( ! empty( $data["extends"] )) {
            $baseData = $this->findByName( $data["extends"] );

            unset( $data["extends"] );
            $data = $this->mergeSurveys( $baseData, $data );
        }

        return $data;
    }

}