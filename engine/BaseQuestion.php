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

    public function id()
    {
        return $this->d["id"];
    }

    public function label()
    {
        echo '<label class="'.$this->id().'" for="'.$this->id().'">'.$this->d["body"].'</label>';
    }

    public function render(){

        echo "<div class=\"question\">";
        $this->renderHeader();
        $this->renderSubHeader();
        $this->renderBody();
        $this->renderFooter();
        echo "</div>";
    }

}