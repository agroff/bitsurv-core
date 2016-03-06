<?php


namespace BitSurv\Engine;


class TextQuestion extends BaseQuestion{

    protected function renderBody(){

        if(empty($this->d["body"])){
            return;
        }

        $body = forceArray($this->d["body"]);

        foreach($body as $p){
            $this->wrapIfPlain('p', $p, 'question-text-body');
        }

    }

}