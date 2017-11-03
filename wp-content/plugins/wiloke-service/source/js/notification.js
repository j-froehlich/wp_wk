(function($) {
    $(document).ready(function () {

        $('.wil-noti-show-icon').on('click', function(event) {
            event.preventDefault();
            $('.wil-noti').toggleClass('wil-noti-show');
        });

        $('.wil-noti-header').on('click', '.dashicons-no', function () {
            $('.wil-noti').toggleClass('wil-noti-show');
        })
    })

})(jQuery);