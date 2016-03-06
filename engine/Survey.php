<?php


namespace BitSurv\Engine;


class Survey
{

    private $d;
    private $currentPage = 0;

    public function __construct( $data = false )
    {
        if($data !== false){
            $this->d = $data;
        }

        if(post('bts_page') !== false){
            $this->currentPage = post('bts_page');
        }
    }

    private function questionFactory($question){

        $type = $question["type"];

        switch ($type) {
            case "text":
                return new TextQuestion($question);
                break;
            default:
                return new BaseQuestion($question);
        }
    }

    public function getCurrentPage(){
        return $this->currentPage;
    }

    public function render($currentPage = 0)
    {

        $this->currentPage = $currentPage;

        $page = $this->d["pages"][$this->currentPage];
        $questions = $this->d["questions"];

        foreach($page["questions"] as $question){
            $question = $this->questionFactory($questions[$question]);

            $question->render();
        }

    }

}