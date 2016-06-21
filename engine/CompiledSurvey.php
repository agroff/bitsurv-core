<?php


namespace BitSurv\Engine;


class CompiledSurvey {

    private $d;

    public function __construct($compiled){
        $this->d = $compiled;
    }

    public function name()
    {
        return $this->name();
    }

    public function get($property = false, $pickFrom = false){

        if($pickFrom === false){
            $pickFrom = $this->d;
        }


        if($property !== false){
            $propertyList = explode('.', $property);
            $property = array_shift($propertyList);

            if(!isset($pickFrom[$property])){
                return false;
            }

            if(count($propertyList) > 0){
                return $this->get(implode(".", $propertyList), $pickFrom[$property]);
            }

            return $pickFrom[$property];
        }

        return $pickFrom;
    }

    public function getPageId($index)
    {
        return $this->get("pages.$index.id");
    }

    public function pageIdToIndex($pageId)
    {
        $pages = $this->get("pages");
        foreach($pages as $index => $page){
            if($page["id"] === $pageId){
                return $index;
            }
        }

        return false;
    }
}