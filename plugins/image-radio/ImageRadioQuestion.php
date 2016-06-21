<?php

use BitSurv\Engine\BaseQuestion;

class ImageRadioQuestion extends BaseQuestion{

    protected function renderBody(){

        if(empty($this->d["choices"]) || !is_array($this->d["choices"]))
        {
            throw new Exception("Choices is a required attribute for image radio questions");
        }

        $image = $this->attr("image");
        $bodyIsAlt = $this->bool("body-is-alt");

        //dbg($this->d);
        //$this->label();
        echo '<div class="image-radio-body '.$this->id().'">';

        echo '<div class="radio-image '.$this->id().'" >';

        if($image){
            echo "<img class=\"radio-image-image\" src=\"$image\" alt=\"".$this->d["body"]."\">";
        }

        if(!$bodyIsAlt){
            $this->renderProperty("body", 'h3', 'image-radio-body');
        }

        echo '</div>';

        foreach($this->d["choices"] as $value => $text)
        {
            echo "<div class=\"image-radio-container\">";
            echo "<label class=\"radio-label\"> ";
            echo "<input type=\"radio\" class=\"\" value=\"".$value."\" name=\"".$this->id()."\"> ";
            echo $text;
            echo " </label>";
            echo " </div>";
        }
        echo " </div>";
        //echo " <input $placeholder type=\"text\" id=\"".$this->id()."\" name=\"".$this->id()."\">";

    }

}