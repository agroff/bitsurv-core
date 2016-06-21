<?php


use BitSurv\Plugins\BasePlugin;
use BitSurv\Engine\PluginSystem;

class ImageRadio extends BasePlugin{

    public function initialize(PluginSystem $pluginSystem)
    {
        require_once('ImageRadioQuestion.php');
        $pluginSystem->registerQuestionType('image-radio', function($question){
            return new ImageRadioQuestion($question);
        });
        //dbg('text field init');
    }
}