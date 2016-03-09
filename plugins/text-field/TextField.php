<?php


use BitSurv\Plugins\BasePlugin;
use BitSurv\Engine\PluginSystem;

class TextField extends BasePlugin{

    public function initialize(PluginSystem $pluginSystem)
    {
        require_once('TextFieldQuestion.php');
        $pluginSystem->registerQuestionType('text-field', function($question){
            return new TextFieldQuestion($question);
        });
        dbg('text field init');
    }
}