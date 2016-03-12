<?php


use BitSurv\Plugins\BasePlugin;
use BitSurv\Engine\PluginSystem;

class Radio extends BasePlugin{

    public function initialize(PluginSystem $pluginSystem)
    {
        require_once('RadioQuestion.php');
        $pluginSystem->registerQuestionType('radio', function($question){
            return new RadioQuestion($question);
        });
        //dbg('text field init');
    }
}