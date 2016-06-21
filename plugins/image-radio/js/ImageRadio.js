

BTS.bindQuestion('image-radio', function($container){
    var $allLabels = $("label", $container);
    $allLabels.click(function(){
        var $thisLabel = $(this);

        $allLabels.removeClass("selected");
        $allLabels.addClass("unselected");

        $thisLabel.addClass("selected");
        $thisLabel.removeClass("unselected");
    });
});