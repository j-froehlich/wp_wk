;(function ($) {
    "use strict";

    function portfolioStartIntro(){

        if ( typeof WILOKE_IGNORE_INTRO != 'undefined' && WILOKE_IGNORE_INTRO.portfolio != 'undefined' && $('#post_ID').length )
        {
            return;
        }

        var intro = introJs();

        var aIntro = [];

        $.each( WILOKE_TOURS.portfolio, function( key, value ) {
            aIntro.push({
                element: '#'+key,
                intro: value
            })
        });
        
        intro.setOptions({
            steps: aIntro
        });

        intro.start();
    }

    $(document).ready(function () {
        // portfolioStartIntro();

        $(document).on('click', '.introjs-skipbutton', function () {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {action: 'wiloke_ignore_intro', post_type: 'portfolio'},
                success: function (res) {

                }
            })
        });

        var pollingDisableSortable = setInterval(function () {
            if ( typeof pagenow != 'undefined' && pagenow == 'portfolio' ){
                if ( $('.meta-box-sortables.ui-sortable').length ){
                    $('.meta-box-sortables.ui-sortable').sortable('destroy');
                    clearInterval(pollingDisableSortable);
                }
            }
        }, 1000);

        
    });

})(jQuery);