<?php


namespace BitSurv\Engine;

use \ORM;

class Response
{

    private function getSurveyId( $name )
    {
        $survey = ORM::for_table( TBL_SURVEYS )->where( 'name', $name )->find_one();

        return $survey->id;
    }

    private function getResponse( $responseId, $surveyName )
    {
        if ($responseId > 0) {
            $response = ORM::for_table( TBL_RESPONSES )->where( 'id', $responseId )->find_one();
            if ($response === false) {
                throw new \Exception( "Could not find responseId: " . $responseId );
            }
        } else {
            $surveyId = $this->getSurveyId( $surveyName );

            $response             = ORM::for_table( TBL_RESPONSES )->create();
            $response->ip_address = $_SERVER['REMOTE_ADDR'];
            $response->start_time = sqlDate( 'now' );
            $response->survey_id  = $surveyId;
        }

        $response->end_time = sqlDate( 'now' );

        return $response;
    }

    private function saveAnswer( $questionId, $responseId, $answerText )
    {
        $search = [
            "question_id" => $questionId,
            "response_id" => $responseId
        ];
        $answer = ORM::for_table( TBL_ANSWERS )->where( $search )->find_one();

        if ($answer == false) {
            $answer = ORM::for_table( TBL_ANSWERS )->create();
        }

        $answer->question_id = $questionId;
        $answer->response_id = $responseId;
        $answer->answer      = $answerText;
        $answer->updated     = sqlDate( 'now' );

        $answer->save();

    }

    private function saveAnswers( $responseId, $surveyData, $postData )
    {
        $questions = $surveyData["questions"];

        foreach ($questions as $questionId => $data) {
            if (isset( $postData[$questionId] )) {
                //dbg($postData[$questionId]);
                $answer = $postData[$questionId];
                $this->saveAnswer( $questionId, $responseId, $answer );
            }
        }
    }

    public function fetchFullResponse( $responseId )
    {
        $response = ORM::for_table( TBL_RESPONSES )->where( 'id', $responseId )->find_array();

        $answers = array();

        $answerList = ORM::for_table( TBL_ANSWERS )->where( 'response_id', $responseId )->find_array();
        //dd($answerList);

        foreach($answerList as $answer){
            $answers[$answer["question_id"]] = $answer["answer"];
        }

        return [
            "response" => array_pop($response),
            "answers"  => $answers
        ];
    }

    public function save( $surveyData, $postData )
    {

        $responseId = (int) $postData['bts_response_id'];
        $surveyName = $surveyData["name"];

        $response = $this->getResponse( $responseId, $surveyName );

        $response->save();

        $responseId = $response->id();

        $this->saveAnswers( $responseId, $surveyData, $postData );

        return $responseId;
    }
}