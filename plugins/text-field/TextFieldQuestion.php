<?php

use BitSurv\Engine\BaseQuestion;

class TextFieldQuestion extends BaseQuestion{

    protected function renderBody(){

        $placeholder = "";
        if(!empty($this->d["placeholder"]))
        {
            $placeholder = " placeholder=\"".$this->d["placeholder"]."\"  ";
        }

        //dbg($this->d);
        $this->label();
        echo " <input $placeholder type=\"text\" id=\"".$this->id()."\" name=\"".$this->id()."\">";

    }

}