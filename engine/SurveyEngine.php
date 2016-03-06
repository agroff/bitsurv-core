<?php
namespace BitSurv\Engine;

use BitSurv\Page;

class SurveyEngine {

    private function debug($compiled){
        echo "<br><br>";
        echo "<pre>";
        surveyDbg($compiled);
        echo "</pre>";
    }

    private function validate(){
        return true;
    }

    private function adjustPageNumber($currentPage){
        $action = post("action");
        if($action === 'back'){
            $currentPage--;
            return $currentPage;
        }

        $isValid = $this->validate();

        if($isValid){
            $currentPage++;
        }

        return $currentPage;
    }

    public function render($url){

        $surveyData = new SurveyData();

        $data = $surveyData->findByListener($url);

        $compiled = $surveyData->compile($data);

        $survey = new Survey($compiled);

        $currentPage = $survey->getCurrentPage();

        if(count(post()) > 0){
            $currentPage = $this->adjustPageNumber($currentPage);
        }

        $page = new Page($compiled["pages"][$currentPage], $currentPage);
        dbg($currentPage);

        $page->title($compiled["title"]);
        $page->subtitle($compiled["sub-title"]);

        $page->header();
        $page->container("top");

        $survey->render($currentPage);

        $page->buttons(count($compiled["pages"]));
        $page->container("bottom");

        $this->debug($compiled);
        $page->footer();
        die();


        echo "pretend this is survey html";
    }
}