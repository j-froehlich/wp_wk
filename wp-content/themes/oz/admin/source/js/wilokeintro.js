;(function($){
    "use strict"

    var steps = [{
            element: '#page_template',
            intro: 'We offer 3 options for the page: 1. Default: The default mode. 2. Page Builder: Using this mode combine with Visual Composer to build Static Page. Virtual Portfolio: Choosing this mode to enable special portfolio style.',
            position: 'left'
        }
    ];

    function introTemplateMode(){
        var intro = introJs();
        intro.setOptions({
            steps: steps,
            showBullets: true,
            showButtons: true,
            showProgress: true,
            exitOnOverlayClick: true,
            showStepNumbers: true,
            keyboardNavigation: true,
            tooltipClass: 'wiloke-templatemode'
        });

        intro.start();
    }

    $(document).ready(function(){
        $(document).on('click', '.wiloke-templatemode .introjs-skipbutton', function(){
            localStorage.setItem("wilokeintrotemplatemode", true);
        });

        if ( !localStorage.getItem("wilokeintrotemplatemode") )
        {
            // introTemplateMode();
        }
    })
})(jQuery);