<?php


namespace BitSurv;


use BitSurv\Engine\Render;

class Page extends Render{

    protected $d;
    private $page;
    private $pageNumber = 0;
    private $title = "Default Survey";
    private $subtitle = "";
    private $templatePath = '';

    public function __construct($page = false, $pageNumber = 0){
        //dd($page);
        parent::__construct($page);
        $this->page = $page;
        $this->pageNumber = $pageNumber;
        $this->d = $page;
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

    public function buttons($totalPages){
        echo "<div class=\"button-container\">";
        if($this->pageNumber > 0){
            echo "<button type=\"submit\" name=\"action\" value=\"back\" class=\"btn btn-default\">Back</button>";
        }
        if($this->pageNumber < ($totalPages - 1)){
            echo "<button type=\"submit\" name=\"action\" value=\"submit\" class=\"btn btn-default\">Next</button>";
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