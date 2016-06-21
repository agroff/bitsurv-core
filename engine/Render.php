<?php


namespace BitSurv\Engine;


class Render {

    public function __construct($data){
        $this->d = $data;
    }

    public function id()
    {
        return $this->d["id"];
    }

    public function bool($name)
    {
        $bool = false;
        if(!empty($this->d[$name])){
            $bool = true;
        }

        return $bool;
    }

    public function attr($name, $default = false)
    {
        $attr = $default;
        if(isset($this->d[$name])){
            $attr = $this->d[$name];
        }

        return $attr;
    }

    public function getOption($name, $default = false)
    {
        $attr = $default;
        $options = $this->d["options"];
        if(isset($options[$name])){
            $attr = $options[$name];
        }

        return $attr;
    }

    public function type()
    {
        return $this->d["type"];
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