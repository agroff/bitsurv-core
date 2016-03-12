<?php

use BitSurv\Engine\BaseQuestion;

class RadioQuestion extends BaseQuestion{

    protected function renderBody(){

        if(empty($this->d["choices"]) || !is_array($this->d["choices"]))
        {
            throw new Exception("Choices is a required attribute for radio questions");
        }

        //dbg($this->d);
        //$this->label();
        echo '<span class="radio-body '.$this->id().'">';
        echo $this->d["body"];
        foreach($this->d["choices"] as $value => $text)
        {
            echo "<label class=\"radio-label\"> ";
            echo "<input type=\"radio\" name=\"".$this->id()."\"> ";
            echo $text;
            echo " </label>";
        }
        //echo " <input $placeholder type=\"text\" id=\"".$this->id()."\" name=\"".$this->id()."\">";

    }

}