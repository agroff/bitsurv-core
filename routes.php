<?php
namespace BitSurv;

use BitSurv\Engine\DbHelper;
use BitSurv\Engine\SurveyData;
use \ORM;

//bootstrap system
$router->addRoute('*/admin', function(){
    echo "Admin Page";
});




$router->addRoute('*/install', function(){

    function debugQuery($path){
        $r = DbHelper::executeSqlFile($path);
        if($r !== false){
            echo "Query Ran Succesfully: <br>";
            dbg($path);
            //dbg($r);
        }
        else {
            echo "Error with query : <br>";
            dbg($path);
        }
    }

    $surveysSchemaPath = Path::get('core', 'db/bts_surveys.sql');
    debugQuery($surveysSchemaPath);

    $surveysSchemaPath = Path::get('core', 'db/bts_responses.sql');
    debugQuery($surveysSchemaPath);

    $surveysSchemaPath = Path::get('core', 'db/bts_answers.sql');
    debugQuery($surveysSchemaPath);

    $data = new SurveyData();
    $surveys = $data->findAll();

    foreach($surveys as $surveyName => $path){
        $count = ORM::for_table(tbl('surveys'))->where_like('name', $surveyName)->count();

        if($count > 0){
            echo "Survey Exists Already: `$surveyName` <br>";
            continue;
        }

        $survey = ORM::for_table(tbl('surveys'))->create();
        $survey->name = $surveyName;
        $survey->updated = sqlDate('now');

        $survey->save();
        echo "Creating survey: `$surveyName` <br>";
    }

});