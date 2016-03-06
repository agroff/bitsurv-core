<?php


namespace BitSurv\Engine;


class Render {

    public function __construct($data){
        $this->d = $data;
    }

    protected function wrapIfPlain($element, $contents, $classList = ''){
        if($contents[0] === '<'){
            echo $contents;
            return;
        }

        echo "<" . $element;
        if($classList){
            echo " class=\"$classList\"";
        }
        echo " >";
        echo $contents;
        echo "</$element>";
    }

    protected function renderProperty($key, $element = 'div', $classList = ''){
        if(empty($this->d[$key])){
            return;
        }

        $contents = forceArray($this->d[$key]);

        foreach($contents as $c){
            $this->wrapIfPlain($element, $c, $classList);
        }

    }
}