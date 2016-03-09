<?php


namespace BitSurv\Engine;


class Survey
{

    private $d;
    private $currentPage = 0;

    /** @var  PluginSystem */
    private $pluginSystem;

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

        $questionInstance = $this->pluginSystem->getNewQuestion($type, $question);

        if($questionInstance !== false){
            return $questionInstance;
        }

        // TODO: Remove this and port text to a plugin
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

    public function providePluginSystem(PluginSystem $pluginSystem)
    {
        $this->pluginSystem = $pluginSystem;
    }

    public function render($currentPage = 0)
    {

        $this->currentPage = $currentPage;

        $page = $this->d["pages"][$this->currentPage];
        $questions = $this->d["questions"];


        foreach($page["questions"] as $question){
            $questions[$question]["id"] = $question;
            $question = $this->questionFactory($questions[$question]);

            $question->render();
        }

    }

}