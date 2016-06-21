var BtsQuestion = function ($container) {

    this.container = $container;

    this.getValue = function(){

    };

    this.isComplete = function(){

    };
};

var BTS = (function () {
    var pub = {},
        bindings = {},
        questions = [],
        question;

    pub.bindQuestion = function (type, callback) {
        bindings[type] = callback;
    };

    $(function () {
        $(".question").each(function () {
            var $container = $(this),
                type = $container.attr("data-question-type");

            if (!_.isUndefined(bindings[type])) {
                bindings[type]($container);
            }
        });
    });

    return pub;
})();
