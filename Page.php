<?php


namespace BitSurv;


use BitSurv\Engine\Render;

class Page extends Render{

    protected $d;
    private $page;
    private $pageNumber = 0;
    private $responseId = -1;
    private $title = "Default Survey";
    private $subtitle = "";
    private $templatePath = '';

    public function __construct($survey = false, $pageNumber = 0){
        //dd($page);
        parent::__construct($survey);
        $this->page = $survey["pages"][$pageNumber];
        $this->pageNumber = $pageNumber;
        $this->d = $survey;
        $this->templatePath = Path::get('core', 'templates/default');
    }

    public function title($newTitle = false){
        if($newTitle === false){
            echo $this->title;
            return;
        }

        $this->title = $newTitle;
    }

    public function subtitle($newTitle = false){
        if($newTitle === false){
            echo $this->subtitle;
            return;
        }

        $this->subtitle = $newTitle;

    }

    public function container(){

    }

    public function setResponseId($id){
        $this->responseId = $id;
    }

    public function pageAttr($attr, $default){
        if(empty($this->page[$attr])){
            return $default;
        }

        return $this->page[$attr];
    }

    public function buttons($totalPages){

        $disableBack = $this->getOption("disable-back", false);

        $nextText = $this->pageAttr("next-text", "Next");
        $hideNext = $this->pageAttr("hide-next", false);
        $isLast = $this->pageAttr("is-last", false);

        $style = '';

        if($hideNext){
            $style = 'style="display: none;"';
        }

        echo "<div class=\"button-container\">";
        if($this->pageNumber > 0 && !$disableBack){
            echo "<button id=\"btsBackButton\" type=\"submit\" name=\"action\" value=\"back\" class=\"btn btn-default\">Back</button>";
        }
        if($this->pageNumber < ($totalPages - 1) && !$isLast){
            echo "<button id=\"btsNextButton\" type=\"submit\" name=\"action\" value=\"submit\" $style class=\"btn btn-default\">$nextText</button>";
        }
        echo "</div>";
    }

    public function header(){
        require($this->templatePath . "/header.php");
        $this->renderProperty('header', 'div', 'page-header');
    }

    public function footer(){
        $this->renderProperty('footer', 'footer', 'page-footer');
        require($this->templatePath . "/footer.php");
    }
}