<?php


namespace BitSurv\Engine;


class BaseQuestion extends Render{

    protected $d;

    public function __construct($question){
        parent::__construct($question);
        $this->d = $question;
    }



    protected function renderHeader(){
        $this->renderProperty("header", 'h3', 'question-header');
    }

    protected function renderSubHeader(){
        $this->renderProperty("sub-header", 'span', 'question-sub-header');
    }

    protected function renderFooter(){
        $this->renderProperty("footer", 'div', 'question-footer');
    }

    protected function renderBody(){
        echo "Whoops! We couldn't find a question with type: " . $this->d["type"];
    }

    public function requireField($fieldName)
    {
        if(empty($this->d[$fieldName]))
        {
            throw new \Exception("`$fieldName` is a required attribute for ".$this->type()." questions");
        }
    }

    public function label()
    {
        echo '<label class="'.$this->id().' '.$this->type().'" for="'.$this->id().'">'.$this->d["body"].'</label>';
    }

    public function render(){

        echo '<div class="question" id="question-' . $this->id() . '" data-question-type="' . $this->type() . '">';
        $this->renderHeader();
        $this->renderSubHeader();
        $this->renderBody();
        $this->renderFooter();
        echo '</div>';
    }

}