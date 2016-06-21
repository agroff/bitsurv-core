<?php
namespace BitSurv\Engine;

use BitSurv\Page;

class SurveyEngine {

    /** @var  PluginSystem */
    private $pluginSystem;

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

    public function providePluginSystem(PluginSystem $pluginSystem){
        $this->pluginSystem = $pluginSystem;
    }

    public function render($url){
        $response = new Response();

        $surveyData = new SurveyData();

        $data = $surveyData->findByListener($url);

        $compiled = $surveyData->compile($data);

        $survey = new Survey($compiled);
        $survey->providePluginSystem($this->pluginSystem);

        $currentPage = $survey->getCurrentPage();
        $responseId = -1;

        if(count(post()) > 0){

            $responseId = $response->save($compiled, post());

            $this->pluginSystem->setResponseId($responseId);

            $currentPage = $this->adjustPageNumber($currentPage);

            $currentPage = $this->pluginSystem->adjustPage($currentPage, $compiled);
        }

        $page = new Page($compiled, $currentPage);

        $page->setResponseId($responseId);

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