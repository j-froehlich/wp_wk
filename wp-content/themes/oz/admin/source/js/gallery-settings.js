/**
 * Gallery Settings
 */
(function($, window, document, undefined) {
    'use strict';

    var media = wp.media;
    
    $(document).ready(function () {
        
        _.extend(wp.media._galleryDefaults, {
            spacing: 'none',
            style: 'none'
        });

        wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
            template: function(view) {
                return wp.media.template('gallery-settings')(view)
                    + wp.media.template('wil-gallery-settings')(view);
            },

            update: function(key) {

                var value = this.model.get(key),
                    $setting = this.$('[data-setting="' + key + '"]'),
                    $buttons, $value;

                // Bail if we didn't find a matching setting.
                if (!$setting.length) {
                    return;
                }

                // Attempt to determine how the setting is rendered and update
                // the selected value.

                // Handle dropdowns.
                if ($setting.is('select')) {
                    $value = $setting.find('[value="' + value + '"]');

                    if ($value.length) {
                        $setting.find('option').prop('selected', false);
                        $value.prop('selected', true);
                    } else {
                        // If we can't find the desired value, record what *is* selected.
                        this.model.set(key, $setting.find(':selected').val());
                    }

                    // Handle button groups.
                } else if ($setting.hasClass('button-group')) {
                    $buttons = $setting.find('button').removeClass('active');
                    $buttons.filter('[value="' + value + '"]').addClass('active');

                    // Handle text inputs and textareas.
                } else if ($setting.is('input[type="text"], textarea')) {
                    if (!$setting.is(':focus')) {
                        $setting.val(value);
                    }
                    // Handle checkboxes.
                } else if ($setting.is('input[type="checkbox"]')) {
                    $setting.prop('checked', !!value && 'false' !== value);
                }

                // HERE the only modification I made
                else {
                    $setting.val(value); // treat any other input type same as text inputs
                }

                // end of that modification
            }
        });
    })
    
})(jQuery, window, document);
