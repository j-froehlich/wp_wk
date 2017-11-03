;(function($, window, document, undefined){
    'use strict';

    var WilokeService = {};
    
    WilokeService =  {
        uploadMedia: function() {
            var frame,
                metaBox = $('.wiloke-media-uploader'), // Your meta box id here
                addImgLink = metaBox.find('.js_upload'),
                delImgLink = metaBox.find( '.delete-custom-img'),
                imgContainer = metaBox.find( '.custom-img-container'),
                imgIdInput = metaBox.find( '.pi_insert_val' );

            // ADD IMAGE LINK
            addImgLink.on( 'click', function( event ){

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( frame ) {
                    frame.open();
                    return;
                }

                // Create a new media frame
                frame = wp.media({
                    title: 'Select or Upload Media Of Your Chosen Persuasion',
                    button: {
                        text: 'Use this media'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });


                // When an image is selected in the media frame...
                frame.on( 'select', function() {

                    // Get media attachment details from the frame state
                    var attachment = frame.state().get('selection').first().toJSON();

                    // Send the attachment URL to our custom image input field.
                    // imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

                    // Send the attachment id to our hidden input
                    imgIdInput.val( attachment.url );

                    // Hide the add image link
                    // addImgLink.addClass( 'hidden' );

                    // Unhide the remove image link
                    // delImgLink.removeClass( 'hidden' );
                });

                // Finally, open the modal on click
                frame.open();
            });
        },
        importDemo: function(){
            $('.wiloke-import-panel').on('click', '.wil-btn_options', function(event) {

                event.preventDefault();
                var $this = $(this),
                    $popup = $('#wiloke-import-demo-optiops');
                $popup.find('.message').html('');

                if($popup.length) 
                {
                    var cPosWithWindow = $(this).offset().top;
                    $popup.css({top: cPosWithWindow - 150});
                    $popup.find('.wil-library__cancel').html('Cancel');
                    $popup.find('.message').html();
                    $popup.addClass('active');
                    $popup.find('#demo-url').val($this.attr('href'));
                    $popup.find('#demo-type').val($this.data('type'));
                    $('#wiloke-import-demos').addClass('overlay');
                }

            });

            $('#wiloke-import-demo-optiops').on('click', '.wil-library__cancel', function(event) {
                event.preventDefault();
                $('#wiloke-import-demo-optiops').removeClass('active');
                $('#wiloke-import-demos').removeClass('overlay');
            });

            $('#wiloke-import-start').on('click', function(event){
                event.preventDefault();
                var $this = $(this),
                    $popup = $('#wiloke-import-demo-optiops'),
                    $importing = $('#wiloke-service-importing'),
                    $form = $('#wiloke-import-options');
                $form.slideUp();
                $this.prop('disabled', true);

                if ( $this.data('importing') ){
                    return;
                }
                
                $this.data('importing', true);

                if ( !$this.data('keep_alive') ) {
                    $popup.find('.message').append('<p>This process may take a while, so please be patient.</p>'); 
                }

                $importing.removeClass('hidden');

                var xhr = $.ajax({
                    type: "POST",
                    data: {action: 'wiloke_import', security: WILOKE_SERIVCE_NONCE, settings: $this.closest('form').serialize(), keep_alive: $this.data('keep_alive')},
                    url: ajaxurl,
                    success: function(response){
                        $this.prop('disabled', false);
                        if ( response.success ){
                            $this.data('importing', false);

                            if ( typeof response.data !== 'undefined' ) {

                                if ( typeof response.data.message !== 'undefined' ) {
                                    $popup.find('.message').append('<p>'+response.data.message + '</p>'); 
                                }

                                if ( typeof response.data.keep_alive !== 'undefined' && response.data.keep_alive ) {
                                    $this.data('keep_alive', true);
                                    $this.trigger('click');
                                }else{
                                    $popup.find('.message p:last').css({'color': 'green'});
                                    $form.find('.wil-library__cancel').html('Back');
                                    $form.slideDown();
	                                $this.removeData('keep_alive');
                                    $importing.addClass('hidden');
                                }
                            }
                        }else{
	                        $this.data('keep_alive', false);
                            $popup.find('.message').html('<p style="color: red;">Import Failure</p>');
                            $importing.addClass('hidden');
                        }                        
                    },
                    error: function(){

                    }
                })

                //kill the request
                // xhr.abort();
            })
        },
        requestDemo: function(){
            $('#wiloke-service-refresh').on('click', function(event){
                event.preventDefault();
                var $this = $(this);

                if ( $this.data('is-importing') ) {
                    return;
                }

                $this.html('Progressing...');
                $this.addClass('loading');
                $this.data('is-importing', true);

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {action: 'wiloke_service_request_demo_data', type: $this.data('type'), security: WILOKE_SERIVCE_NONCE},
                    success: function(items){
                        $($this.data('printto')).html(items);
                        $this.data('is-importing', false);
                        $this.html('Refresh');
                        $this.removeClass('loading');
                    }
                })
            })
        },
        setWilokeImportPanel: function(){
            $(window).resize(function(){
                var adminMenuWidth = $('#adminmenuback').outerWidth();
                $("#wiloke-template-importer").css({
                    'width': $(window).outerWidth() - adminMenuWidth,
                    'margin-left': $('#adminmenuback').outerWidth()
                });
            }).trigger('resize');
        }, 
        piPage: function(){
            var $control = {
                _btnFire: "#wiloke-load-sample-template",
                $btnTriggerOpenPopup: $("#wiloke-template-importer"),
                $btnInstall: $("#wiloke-template-importer .wiloke-start-import-template"),
                $btnOpen: $("#wiloke-trigger-magnific-popup"),
                $btnClose: $("#wiloke-close-popup"),
                $ctrlPopup: $("#wiloke-show-sample-template"),
                addButton: function(){
                    $("#vc_not-empty-add-element").after('<a id="wiloke-load-sample-template" class="vc_add-element-not-empty-button" title="Insert Sample Template" href="#wiloke-show-sample-template"></a>');
                    $('.vc_welcome-visible-ne').css({display: 'block'});
                },
                openModel: function(){
                    var _this = this;
                    $(document).on('click', _this._btnFire, function () {
                        _this.$btnTriggerOpenPopup.addClass('active');
                    })
                },
                installing: function() {
                    $('#wiloke-import-templates').on('click', '.wiloke-start-import-template', function(event){
                        event.preventDefault();
                        var $this = $(this),
                            saveText = $this.html();
                        $this.html('<i class="dashicons dashicons-update"></i> Importing');
                        $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: {security: WILOKE_SERIVCE_NONCE, action: 'wiloke_import_template', file: $this.attr('href')},
                            success: function (response) {
                                if ( response.success ) {
                                    response = Base64.decode(response.data.shortcode);
                                    vc.storage.setContent( vc.storage.getContent() + response );
                                    vc.storage.checksum = false;
                                    vc.app.show();

                                    $this.html('<i class="dashicons dashicons-yes"></i> Imported');
                                    
                                    setTimeout(function () {
                                        $this.html(saveText);
                                        $('#wiloke-template-importer .wil-library__close').trigger('click');
                                    }, 200);
                                }else{
                                    $this.html('<i class="dashicons dashicons-dismiss"></i> Failure!');
                                }
                            },
                            error: function () {
                                console.log("Error: Couldn't connect to server");
                                // $(this).html("Error");
                            }
                        })
                    })
                },
                disabledBtn: function ($target) {
                    $target.attr("disabled", "disabled");
                },
                enabledBtn: function ($target) {
                    $target.attr("disabled", false);
                },
                closePopup: function() {
                    var _this = this;
                    _this.$btnClose.on('click', function (event) {
                        event.preventDefault();
                        _this.$btnTriggerOpenPopup.removeClass('active');
                    })

                    $('#wiloke-template-importer .wil-library__close').on('click', function(event){
                        event.preventDefault();
                        _this.$btnTriggerOpenPopup.removeClass('active');
                    })
                },
                init: function (){
                    this.addButton();
                    this.openModel();
                    this.installing();
                    this.closePopup();
                }
            }

            $control.init();
        },
        requestVCPortfolio: function () {

            $(document).on('click', '#wiloke-design-portfolio-request', function(event){
                event.preventDefault();
                var $this = $(this), $printTo = $('#wiloke-print-wiloke-design-layout');

                if ( $this.data('is-importing') ) {
                    return;
                }

                $this.html('Progressing...');
                $this.addClass('loading');
                $this.data('is-importing', true);

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data: {action: 'wiloke_service_request_vc_portfolio', type: 'wiloke_portfolio', security: WILOKE_SERIVCE_NONCE},
                    success: function(status){
                        $printTo.find('.wiloke-service').remove();
                        if ( status.success ) {
                            $printTo.append(status.data);
                        }
                        $this.data('is-importing', false);
                        $this.html('Refresh');
                        $this.removeClass('loading');
                    }
                })
            })
        },
        importVCPortfolio: function () {
            var _this = this;
            $(document).on('click', '.vc_ui-tabs-line-trigger', function (event) {
               var $currentDevice = $('#wiloke-print-wiloke-design-layout .wo_wpb_checked');
                if ( ($(event.currentTarget).text() === 'Design Layout') && ( !$currentDevice.length || $currentDevice.find('.wo_portfolio_layout_value').attr('value') == 'creative' ) ) {
                    $(document).data('wiloke-portfolio-allow-backup', true);
                }
            });

            $(document).on('click', '#wiloke-print-wiloke-design-layout .item', function (event) {
                event.preventDefault();
                var $this = $(this), getVal = $this.find('.wo_portfolio_layout_settings').val(), name = $this.find('.wo_portfolio_layout_value').val(), $parent = $('#wiloke-print-wiloke-design-layout');

                if ( $parent.data('is-ajax') ){
                    return;
                }

                // Previous activated
                $(document).data('wiloke-portfolio-prev-activated', $parent.find('.wo_wpb_checked .wo_portfolio_layout_value').attr('value'));
                $(document).data('wiloke-portfolio-control-activated', $parent.find('.wo_wpb_checked .wo_portfolio_layout_value').prev());

                $('#wiloke-print-wiloke-design-layout').find('.item').removeClass('wo_wpb_checked');
                $this.addClass('wo_wpb_loading');
                $this.removeClass('wo_wpb_checked');
                $parent.data('is-ajax', true);
                $(document).data('wiloke-portfolio-current-activated', $this.find('.wo_portfolio_layout_value').attr('value'));

                if ( getVal.search('.zip') == -1 ) {
                    _this.addStyleToDesignLayout($this, getVal);
                    $this.removeClass('wo_wpb_loading');
                    $this.addClass('wo_wpb_checked');
                    $parent.data('is-ajax', false);
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data: {action: 'wiloke_service_import_vc_portfolio', url: getVal, security: WILOKE_SERIVCE_NONCE, name: name},
                    success: function(status){
                        $this.toggleClass('wo_wpb_loading');
                        if ( status.success ){
                            $this.find('.wo_portfolio_layout_settings').attr('value', status.data);
                            $this.addClass('wo_wpb_checked');
                            _this.addStyleToDesignLayout($this, status.data);
                        }else{
                            $this.addClass('error');
                        }
                        $parent.data('is-ajax', false);
                    }
                });
            })
        },
        addStyleToDesignLayout: function ($this, style) {
            if ( typeof style == 'undefined' || style == '' ) {
                return;
            }
            var $target = $this.closest('.vc_ui-panel-window-inner').find('.wo_portfolio_layout_settings[name="wiloke_portfolio_layout[creative][settings]"]');
            $(document).data('wiloke-portfolio-is-customize', $this.data('is-customize'));
            $target.attr('value', Base64.decode(style));
            $target.trigger('change');
        },
        migrateExistedFiles: function () {
            $("#wiloke-service-migrate-files-to-s3").on('click', function (event) {
                event.preventDefault();
                var $this = $(this), $message = $this.siblings('.wiloke-message'), $gif = $this.prev();
                $this.prop('disabled', true);

                if ( $this.data('is-progressing') ) {
                    return;
                }
                var $xhr = null;
                $gif.removeClass('hidden');

                $xhr = $.ajax({
                    type: 'POST',
                    data: {action: 'wiloke_service_migrate_files', nonce: WILOKE_SERIVCE_NONCE, post__not_in: $this.data('ignore-ids')},
                    url: ajaxurl,
                    beforeSend: function () {
                        if ( $xhr === null ) {
                            return true;
                        }
                        $gif.addClass('hidden');
                        $this.prop('disabled', false);
                        $xhr.abort();
                    },
                    success: function (response) {
                        $this.data('is-progressing', false);
                        $this.prop('disabled', false);

                        $gif.addClass('hidden');

                        if ( response.success ) {
                            if ( typeof response.data === 'string' ) {
                                $message.append('<p class="wiloke-notice notice notice-success">Your files have been migrated successfully</p>');
                            }else{
                                var ids = '';
                                for ( var id in response.data ) {
                                    ids += ',' + id;
                                    $message.append('<p><strong>'+response.data[id]+'</strong> has been migrated!</p>');
                                }

                                ids = $this.data('ignore-ids') + ids;
                                ids = ids.replace(/^,/gm, '');
                                $this.data('ignore-ids', ids);
                                $xhr = null;
                                
                                setTimeout(function () {
                                    $this.trigger('click');
                                }, 300);
                            }
                        }else{
                            $message.html('<p class="wiloke-notice notice notice-error">Something went wrong</p>');
                        }
                    }
                });

                $this.data('is-progressing', true);
            })
        },
        readUpdate: function(){
            $('.wil-noti-show-icon.animation').one('click', function(){
                var $this = $(this);
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {action: 'wiloke_service_read_notification', security: WILOKE_SERIVCE_NONCE},
                    success: function(){
                        $this.removeClass('animation');
                    }
                })
            })
        },
        compress: function () {
            $('#wiloke-compress-settings').submit(function (event) {
                event.preventDefault();
                var $form = $(this),
                    $success = $form.find('.success'),
                    $error = $form.find('.error'),
                    $btn = $(this).find('button.button-save'),
                    homeUrl = $(this).data('homeurl');
	            $btn.addClass('loading');
	            $error.removeClass('visible');
	            $success.removeClass('visible');
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {action: 'save_settings', security: $form.find('#wiloke_minify_nonce').val(), data: $form.serialize()},
                    success: function (response) {
                        if ( response.success ){
	                        $success.addClass('visible');
                        }else{
	                        $error.addClass('visible');
                        }
	                    $btn.removeClass('loading');
                    }
                });

            })
        }
    }
    
    $(document).ready(function () {
        WilokeService.uploadMedia();
        WilokeService.importDemo();
        WilokeService.requestDemo();
        WilokeService.requestVCPortfolio();
        WilokeService.importVCPortfolio();
        WilokeService.migrateExistedFiles();
        WilokeService.readUpdate();
        WilokeService.compress();

        if ( $('#wiloke-template-importer').length ) {
            WilokeService.piPage();
            WilokeService.setWilokeImportPanel();
        }
    });

})(jQuery, window, document);