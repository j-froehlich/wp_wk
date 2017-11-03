;(function ($) {
    "use strict";

    if ( $('.wiloke-image-field-id').length > 0 )
    {
        $('.wiloke-image-field-id').each(function() {
            $(this).wilokePostFormatMediaPopup({multiple: false});
        });
    }

})(jQuery);