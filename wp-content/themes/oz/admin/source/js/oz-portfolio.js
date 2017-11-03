;(function ($) {
    "use strict";
    var previousPosition = 0, oThemeOptions, isHeightResize=false;

    if ( !$().WilokeFillColorForPortfolio ) {
        $.fn.WilokeFillColorForPortfolio = function (options, callback) {
            var _this = this;
            this.oOptions = $.extend({target: null, css: '', isReset: false}, options);

            if ( _this.oOptions.isReset ){
                $(_this.oOptions.target).css(_this.oOptions.css, '');
                return;
            }

            if ( !$('.wil-focus-on').length  ) {
                return;
            }

            $(this).next().find('.sp-preview-inner').change(function () {
                handle($(this), '', callback);
            });

            $(this).change(function () {
                handle($(this), 'change', callback);
            }).trigger('change');

            function handle($target, event, callback) {
                var val = _this.oOptions.target == '#wpwrap' ? $target.data('rgbcolor') : $target.data('color');
                val = typeof val === 'undefined' ? $target.val() : val;

                if ( typeof val != 'undefined' && val !== '' && event === 'change' ) {
                   if ( _this.oOptions.target == '#wpwrap' && ( val.search('rgba') !== -1 ) ){
                       var lIndex = val.lastIndexOf(',');
                       val = val.substr(0, lIndex) + ')';
                   }
                }

                if ( val !== '' ) {
                    if (_this.oOptions.target !== null) {
                        $(_this.oOptions.target).css(_this.oOptions.css, val);
                    }
                }else{
                    if (_this.oOptions.target !== null) {
                        $(_this.oOptions.target).css(_this.oOptions.css, '');
                    }
                }

                if (typeof callback !== 'undefined') {
                    callback($target, val);
                }
            }
        }
    }

    if ( !$().WilokePortfolioScrollToSettingSection ) {
        $.fn.WilokePortfolioScrollToSettingSection = function (options) {
            var body = $("html, body");
            $(this).on('click spectrum-show', function (event) {
                if ( $('body.wil-focus-off').length ) {
                    return;
                }
                var $this = $(event.currentTarget);
                for ( var cssClass in options ) {
                    var currentPos = $(options[cssClass]).offset().top,
                        isAllow = true;

                    if ( (previousPosition == currentPos) || ( Math.abs( currentPos - previousPosition ) < 100 ) ) {
                        isAllow = false;
                    }

                    if ( isAllow && $this.hasClass(cssClass) ){
                        previousPosition = currentPos;
                        body.stop().animate({
                            scrollTop: currentPos - 300
                        }, '500', 'swing');
                        return;
                    }
                }
            });
        }
    }

    function convertToRgbColor(color){
        if ( color == '' || typeof color === 'undefined' ){
            return '';
        }

        if ( color.search('rgba') !== -1 ){
            var cutTo = color.lastIndexOf(',');
            color = color.slice(0, cutTo) + ')';
            color = color.replace('rgba', 'rgb');
        }

        return color;
    }

    $(document).ready(function () {

        if ( !$('#post_ID').length ){
            return;
        }
        
        $('#wpadminbar').addClass('hide-bar');

        oThemeOptions = WILOKE_OZ_THEMEOPTIONS !== null ? $.parseJSON(WILOKE_OZ_THEMEOPTIONS) : {};

        $('#background_id_status').after('<div class="wil-bg-content"></div><div class="wil-bg-color"></div>');

        $('[name="project_header_settings[background]"], [name="project_general_settings[project_content_bg_img]"]').change(function () {
            var $target = $(this).siblings('.cmb_media_status'),
                bg = $(this).val();
            $target.css("background-image", "url('" + bg + "')");
        }).trigger('change');

        $('[name="project_general_settings[project_content_bg_img]"]').change(function () {
            var $target = $(this).siblings('.cmb_media_status'),
                bg = $(this).val();
            $('.wil-bg-content').css("background-image", "url('" + bg + "')");
        }).trigger('change');

        $('body').addClass('wil-focus-off');
        $('#content_ifr').contents().find('body').addClass('wil-focus-off');
        $(window).load(function() {
            $('#content_ifr').contents().find('body').addClass('wil-focus-off');
        });

        var pholder = $('#titlewrap #title-prompt-text').text();

        $('#project_intro').before('<input type="text" placeholder="' + pholder + '" class="wil-titlediv">');

        $('body #titlediv').prepend('<span class="wil-work-detail__sup"></span>');

        $('body #titlediv #title').keyup(function() {
            var text = $(this).val();
            var textslice = $(this).val().slice(0,1);
            $('.wil-titlediv').val(text);
            $('body #titlediv .wil-work-detail__sup').text(textslice);
        }).trigger('keyup');

        $('.wil-titlediv').keyup(function() {
            var text = $(this).val();
            var textslice = $(this).val().slice(0,1);
            $('body #titlediv #title').val(text);
            $('body #titlediv .wil-work-detail__sup').text(textslice);
        }).trigger('keyup');

        $(window).on('scroll', function() {

            previousPosition = 0;

        }).trigger('scroll');

        $('.wiloke-portfolio-settings-button').on('click', function(e) {
            e.preventDefault();
            $('#project_general_settings').toggleClass('active');
            $(this).toggleClass('close');
            $('.wiloke-portfolio-rest-settings-button').removeClass('close');
            $('#postbox-container-1').removeClass('active');
        });
        $('.wiloke-portfolio-rest-settings-button').on('click', function(e) {
            e.preventDefault();
            $('#postbox-container-1').toggleClass('active');
            $(this).toggleClass('close');
            $('.wiloke-portfolio-settings-button').removeClass('close');
            $('#project_general_settings').removeClass('active');
        });

        $('#project_general_settings tr.cmb_id_project_gcfirst_title_s label').attr('data-text', 'Gradient');
        $('#project_general_settings tr.cmb_id_project_bg_gradient_s label').attr('data-text', 'Background Gradient');

        var select = $('#project_skin'),
            currentSelected = select.find('option:selected').attr('value');

        select.after('<div class="wil-check"></div>');
        select.find('option').each(function() {
            var option = $(this),
                val = option.val(),
                text = option.text(),
                selected = option.attr('selected'),
                select = option.parent(),
                cts = select.siblings('.wil-check');

            if ( currentSelected == 'inherit' && val == oThemeOptions.project_skin ) {
                selected = 'selected';
            }

            cts.append('<div class="wil-check--item ' + selected + '" title="' + text + '" data-val="' + val + '">' + text + '</div>');
        });

        $('.wil-check--item').on('click', function() {
            var item = $(this),
                dataVal = item.data('val'),
                wilcheck = item.parent();
            wilcheck.find('.wil-check--item').removeClass('selected');
            item.addClass('selected');
            wilcheck.prev().find('option').removeAttr('selected');
            wilcheck.prev().find('[value="' + dataVal + '"]').attr('selected', 'selected');
            $('body').attr('data-skin', dataVal);
        });

        var labelText = $('.cmb_id_project_intro label').text();
        $('#project_intro')
            .attr('placeholder', labelText)
            .removeAttr('rows')
            .removeAttr('cols');
        $.fn.autoHeight = function(){
            function _autoHeight(item){
                item = $(item);
                function resize () {
                    item.css('height','auto');
                    item.css('height', item.prop('scrollHeight') + 'px');
                }

                /* 0-timeout to get the already changed text */
                function delayedResize () {
                    window.setTimeout(resize, 0);
                }
                item.bind('change',  resize);
                item.bind('cut',     delayedResize);
                item.bind('paste',   delayedResize);
                item.bind('drop',    delayedResize);
                item.bind('keydown', delayedResize);

                resize();
            }


            $.each($(this),function(index,value){
                var configured = $(value).data("autoHeight");
                if (!configured){
                    _autoHeight(value);
                    $(value).data("autoHeight", true);
                }
            });
        }

        $("#project_intro").autoHeight();


        $('.wiloke-portfolio-toggle-settings-button').on('click', function() {
            $(this).toggleClass('active');
            $('#project_general_settings,.wp-editor-expand,#project_header_settings,#single_portfolio_intro').toggleClass('wil-show');
            localStorage.setItem('wiloke-oz-portfolio-mode', 'default');
        });

        $('.wiloke-portfolio-toggle-live-builder-button').on('click', function() {
            $('.wiloke-portfolio-toggle-settings-button').removeClass('active');
            $('#project_general_settings,.wp-editor-expand,#project_header_settings,#single_portfolio_intro').addClass('wil-show');
            $('#postbox-container-2 .meta-box-sortables > *:not(#project_general_settings)').hide();
            $('html').addClass('pt0');
            $('body')
                .removeClass('wil-focus-off')
                .addClass('wil-focus-on');
            $('#content_ifr').contents().find('body')
                .removeClass('wil-focus-off')
                .addClass('wil-focus-on');
            $('html, body').animate({
                scrollTop: 0
            }, 100);

            $('#content-tmce').trigger('click');

            $('#wp-content-editor-tools, body.wil-focus-on .mce-menubar, body.wil-focus-on .mce-menubar + div.mce-toolbar-grp').hide();

            headerFull();
        });

        $('.wiloke-portfolio-cancel-button').on('click', function(e) {
            e.preventDefault();

            $('#project_general_settings,.wp-editor-expand,#project_header_settings,#single_portfolio_intro').removeClass('wil-show');
            $('#postbox-container-2 .meta-box-sortables > *:not(#project_general_settings)').show();
            $('html').removeClass('pt0');
            $('body')
                .removeClass('wil-focus-on')
                .addClass('wil-focus-off');

            $('#content_ifr').contents().find('body')
                .removeClass('wil-focus-on')
                .addClass('wil-focus-off');

            $('html, body').animate({
                scrollTop: 0
            }, 100);

            $('[name="project_general_settings[project_bg_color]"]').WilokeFillColorForPortfolio({
                target: '#wpwrap, .wil-bg-color',
                css: 'background-color',
                isReset: true
            });
            $('.wiloke-portfolio-toggle-settings-button').trigger('click');
        });

        // Button style
        $('#project_launch_btn_style').change(function() {
            var select = $(this),
                option = $('option', select),
                val = $('option:selected', select).val();

            val = typeof val == 'undefined' || val == 'inherit' ? oThemeOptions.project_launch_btn_style : val;

            $('#project_launch_btn')
                .addClass('wil-btn')
                .attr('data-btn-type', val);
        }).trigger('change');

        $('#project_launch_btn_bg').change(function() {
            var select = $(this),
                option = $('option', select),
                val = $('option:selected', select).val();
            val = typeof val == 'undefined' || val == 'inherit' ? oThemeOptions.project_launch_btn_bg : val;
            $('#project_launch_btn')
                .attr('data-btn-bg', val);


            $('#project_launch_btn.wil-btn[data-btn-bg="wil-btn--gray"]').hover(function() {
                $(this).attr('data-btn-bg', '');
            }, function() {
                $(this).attr('data-btn-bg', 'wil-btn--gray');
            });

            $('#project_launch_btn.wil-btn[data-btn-bg="wil-btn--dark"]').hover(function() {
                $(this).attr('data-btn-bg', '');
            }, function() {
                $(this).attr('data-btn-bg', 'wil-btn--dark');
            });

            $('#project_launch_btn.wil-btn[data-btn-bg="wil-btn--main"]').hover(function() {
                $(this).attr('data-btn-bg', '');
            }, function() {
                $(this).attr('data-btn-bg', 'wil-btn--main');
            });
        }).trigger('change');

        $('#project_launch_btn_size').change(function() {
            var select = $(this),
                option = $('option', select),
                val = $('option:selected', select).val();
            val = typeof val == 'undefined' || val == 'inherit' ? oThemeOptions.project_launch_btn_size : val;
            $('#project_launch_btn')
                .attr('data-btn-size', val);
        }).trigger('change');

        $('#project_launch_btn').on('keyup', function() {
            var launchbtn = $(this),
                str = launchbtn.val();
            for(var i = 0; i < str.length;i++) {
                var res = str.charAt(i);
            }
            var letterWidth = 8;
            letterWidth += 0.5;
            if (res == 'm'){
                letterWidth += 1.5;
            } else if (res == 'w') {
                letterWidth += 1;
            }
            var realWidth = (launchbtn.val().length + 1)*letterWidth + 60 + 'px';
            launchbtn.css('width',realWidth);
        });

        $(window).scroll(function() {

            var btnOffset = $('#project_launch_btn').offset();
            var wscrollTop = $(window).scrollTop();

            $('#project_link').css({
                'top': (btnOffset.top - wscrollTop - 80) + 'px',
                'left': btnOffset.left + 'px'
            });
            $('.cmb_id_project_launch_type').css({
                'top': (btnOffset.top - wscrollTop - 206) + 'px',
                'left': btnOffset.left + 'px'
            });
        }).trigger('scroll');

        $('#project_launch_btn').click(function() {
            $('#project_link, .cmb_id_project_launch_type').addClass('active');
        });
        $('html, body').click(function() {
            $('#project_link, .cmb_id_project_launch_type').removeClass('active');
        });
        $('#project_link, .cmb_id_project_launch_type, #project_launch_btn').click(function(e) {
            e.stopPropagation();
        });
        $('.wiloke-portfolio-entries-meta').before('<span class="wil-flag"></span>');

        // Theme setting
        $('#project_skin').change(function() {
            var select = $(this),
                option = $('option', select),
                val = $('option:selected', select).val();
            val = typeof val == 'undefined' || val == 'inherit' ? oThemeOptions.project_skin : val;
            $('body').attr('data-skin', val);
        }).trigger('change');


        // Theme type left right no sidebar
        $('#project_style').change(function() {
            var select = $(this),
                option = $('option', select),
                val = $('option:selected', select).val();
            val = typeof val == 'undefined' || val == 'inherit' ? oThemeOptions.project_style : val;

            $('body').attr('data-type', val);

            $("#project_intro").autoHeight();

            if ((val == 'style2') || (val == 'style3'))
                $('.wiloke-portfolio-entries-meta').insertBefore('#project_intro');
            else
                $('.wiloke-portfolio-entries-meta').insertAfter('.wil-flag');

        }).trigger('change');

        // Theme boxed
        $('#project_boxed').change(function() {
            var select = $(this),
                option = $('option', select),
                val = $('option:selected', select).val();
            val = typeof val == 'undefined' || val == 'inherit' ? oThemeOptions.project_boxed : val;

            $('body').attr('data-boxed', val);
            $('#content_ifr').contents().find('body').attr('data-boxed', val);

            headerFull();

            if (val == 'disable')
                $('#insert-media-button').addClass('fix-left');
            else
                $('#insert-media-button').removeClass('fix-left');
        }).trigger('change');

        function headerFull() {
            var bodyOffsetLeft = $('#post-body').offset().left + 15;
            $('#project_header_settings').css({
                'left': -bodyOffsetLeft + 'px',
                'right': -bodyOffsetLeft + 'px'
            });
        }

        $(window).resize(function() {
            headerFull();

            $('.wil-bg-color').css('height', $('body').outerHeight());
        }).trigger('resize');

        $('#after_title-sortables').before('<div class="wil-clear"></div>');

        $('.wiloke-portfolio-entries-meta').on('click', function(e) {
            e.preventDefault();
            $('#postbox-container-1').toggleClass('active');
            $('.wiloke-portfolio-rest-settings-button').toggleClass('close');
            $('#project_general_settings').removeClass('active');
            $('.wiloke-portfolio-settings-button').removeClass('close');
        });

        $(window).on('load resize', function() {
            $('#insert-media-button').css('left', $('#post-body').offset().left/2 + 'px');
        });

        $('.wiloke-portfolio-toggle-live-builder-button').on('click', function () {
            localStorage.setItem('wiloke-oz-portfolio-mode', 'visual');
            $('[name="project_general_settings[project_bg_color]"]').WilokeFillColorForPortfolio({
                target: '#wpwrap',
                css: 'background-color'
            });
            $('[name="project_general_settings[project_bg_color]"]').WilokeFillColorForPortfolio({
                target: '.wil-bg-color',
                css: 'background-color'
            });

            $('[name="project_general_settings[project_gcfirst_title_s]"]').WilokeFillColorForPortfolio({}, function ($this,val)
            {
                var firstColor = $('[name="project_general_settings[project_gcfirst_title_f]"]').val() == '' ? 'transparent' : $('[name="project_general_settings[project_gcfirst_title_f]"]').val();
                val = val != '' ? val : 'transparent';

                if ( firstColor === 'transparent' && val === 'transparent' ){
                    $('#titlediv .wil-work-detail__sup').css({'background-image': ''});
                }else{
                    $('#titlediv .wil-work-detail__sup').css({
                        'background-image': 'linear-gradient(45deg, '+firstColor+' 40%, '+val+' 80%)'
                    })
                }
            });

            $('[name="project_general_settings[project_gcfirst_title_f]"]').WilokeFillColorForPortfolio({}, function ($this, val)
            {
                var secondColor = $('[name="project_general_settings[project_gcfirst_title_s]"]').val() == '' ? 'transparent' : $('[name="project_general_settings[project_gcfirst_title_s]"]').val();
                val = val != '' ? val : 'transparent';

                if ( secondColor === 'transparent' && val === 'transparent' ){
                    secondColor = oThemeOptions.project_gcfirst_title_s.rgba != '' ? oThemeOptions.project_gcfirst_title_s.rgba : 'transparent';
                    val = oThemeOptions.project_gcfirst_title_f.rgba != '' ? oThemeOptions.project_gcfirst_title_f.rgba : 'transparent';

                    if ( secondColor != 'transparent' || val != 'transparent' ){
                        $('#titlediv .wil-work-detail__sup').css({
                            'background-image': 'linear-gradient(45deg, '+val+' 40%, '+secondColor+' 80%)'
                        })
                    }else{
                        $('#titlediv .wil-work-detail__sup').css({'background-image': ''});
                    }
                }else{
                    $('#titlediv .wil-work-detail__sup').css({
                        'background-image': 'linear-gradient(45deg, '+val+' 40%, '+secondColor+' 80%)'
                    })
                }
            });

            $('[name="project_general_settings[project_bg_gradient_s]"]').WilokeFillColorForPortfolio({}, function ($this,val)
            {
                var firstColor, firstRgbColor, mainRgbColor;

                if ( $('[name="project_general_settings[project_bg_gradient_f]"]').val() == '' ){
                    firstColor = firstRgbColor = 'transparent';
                }else{
                    firstColor    = $('[name="project_general_settings[project_bg_gradient_f]"]').val();
                    firstRgbColor = convertToRgbColor(firstColor);
                }

                if ( val == '' ){
                    val = mainRgbColor = 'transparent';
                }else{
                    mainRgbColor = convertToRgbColor(val);
                }

                if ( firstColor == 'transparent' && val == 'transparent' ){
                    $('#wpwrap, .wil-bg-color').css({
                        'background-image': '',
                        'background-color': ''
                    })
                }else{
                    $('.wil-bg-color').css({
                        'background-image': 'linear-gradient(45deg, '+firstColor+' 40%, '+val+' 80%)',
                        'background-color': firstColor
                    });

                    $('#wpwrap').css({
                        'background-image': 'linear-gradient(45deg, '+firstRgbColor+' 40%, '+mainRgbColor+' 80%)',
                        'background-color': firstRgbColor
                    });
                }
            });

            $('[name="project_general_settings[project_bg_gradient_f]"]').WilokeFillColorForPortfolio({}, function ($this, val)
            {
                var secondColor, secondRgbColor, mainRgbColor;
                if ( $('[name="project_general_settings[project_bg_gradient_s]"]').val() == '' ){
                    secondColor = secondRgbColor = 'transparent';
                }else{
                    secondColor    = $('[name="project_general_settings[project_bg_gradient_s]"]').val();
                    secondRgbColor = convertToRgbColor(secondColor);
                }

                if ( val == '' ){
                    val = mainRgbColor = 'transparent';
                }else{
                    mainRgbColor = convertToRgbColor(val);
                }

                if ( secondColor == 'transparent' && val == 'transparent' ){
                    $('#wpwrap, .wil-bg-color').css({
                        'background-image': '',
                        'background-color': ''
                    })
                }else{
                    $('.wil-bg-color').css({
                        'background-image': 'linear-gradient(45deg, '+val+' 40%, '+secondColor+' 80%)',
                        'background-color': val
                    })

                    $('#wpwrap').css({
                        'background-image': 'linear-gradient(45deg, '+mainRgbColor+' 40%, '+secondRgbColor+' 80%)',
                        'background-color': mainRgbColor
                    })
                }
            });

            $('[name="project_general_settings[project_title_color]"]').WilokeFillColorForPortfolio({
                target: '#titlediv #title, #single_portfolio_intro .wil-titlediv',
                css: 'color'
            });

            $('[name="project_general_settings[project_intro_label_color]"]').WilokeFillColorForPortfolio({
                target: '#post-body-content .wil-work-detail__meta__item',
                css: 'color'
            });

            $('[name="project_general_settings[project_intro_content_color]"]').WilokeFillColorForPortfolio({
                target: '#post-body-content .wil-work-detail__meta__item span, #post-body-content .wil-work-detail__categories a',
                css: 'color'
            });

            $('[name="project_general_settings[project_text_intro_color]"]').WilokeFillColorForPortfolio({
                target: '#project_intro',
                css: 'color'
            });

            $('[name="project_general_settings[theme_color]"]').WilokeFillColorForPortfolio({
                target: '.cmb_id_project_launch_btn .wil-btn',
                css: 'background-color'
            });

            $('[name="project_general_settings[project_bg_type]"]').change(function () {
                var val = $(this).find('option:selected').attr('value'), mainColor, fgColor, sgColor;

                if ( val === 'gradient' ) {
                    $('.wil-bg-color').css({
                        'background-color': ''
                    });
                    $('[name="project_general_settings[project_bg_gradient_f]"]').trigger('change');
                    $('[name="project_general_settings[project_bg_gradient_s]"]').trigger('change');
                }else if (val === 'color'){
                    $('#wpwrap, .wil-bg-color').css({'background-image': ''});
                    $('[name="project_general_settings[project_bg_color]"]').trigger('change');
                }else{
                    if ( oThemeOptions.project_bg_type == 'color' ){
                        mainColor = oThemeOptions.project_bg_color.color != '' ? oThemeOptions.project_bg_color.color : '';
                        $('#wpwrap, .wil-bg-color').css({'background-image': ''});
                        $('.wil-bg-color, #wpwrap').css({'background-color': mainColor})
                    }else{
                        fgColor = oThemeOptions.project_bg_gradient_f.rgba;
                        sgColor = oThemeOptions.project_bg_gradient_s.rgba;

                        $('#wpwrap, .wil-bg-color').css({
                            'background-image': 'linear-gradient(45deg,'+fgColor+' 40%, '+sgColor+' 80%)',
                            'background-color': fgColor
                        });
                    }

                }
            }).trigger('change');
        });

        $('.cmb-type-colorpicker, .cmb-type-select').WilokePortfolioScrollToSettingSection({
            'cmb_id_project_gcfirst_title_s': '.wil-work-detail__sup',
            'cmb_id_project_gcfirst_title_f': '.wil-work-detail__sup',
            'cmb_id_project_title_color': '#titlediv',
            'cmb_id_project_intro_label_color': '#titlediv',
            'cmb_id_project_intro_content_color': '#titlediv',
            'cmb_id_project_text_intro_color': '#project_intro',
            'cmb_id_project_launch_btn_style': '#single_portfolio_intro',
            'cmb_id_project_launch_btn_bg': '#single_portfolio_intro',
            'cmb_id_project_launch_btn_size': '#single_portfolio_intro'
        });

        $('#portfolio_categorychecklist .popular-category input').change(function () {
            if ( $(this).is(':checked') ) {
                var categoryName = $(this).parent().text();

                $('#post-body .wil-work-detail__categories ul').append('<li data-termid="'+$(this).attr('value')+'"><a href="#">'+categoryName+'</a></li>');
            }else{
                $('#post-body .wil-work-detail__categories ul').find('li[data-termid="'+$(this).attr('value')+'"]').remove();
            }
        });

        // Changing Time Published
        $('#timestampdiv .save-timestamp').on('click', function () {
            var val = $('#timestamp').find('b').html();
            val = val.slice(0, val.indexOf('@'));
            val = val.trim();
            $('#post-body-content').find('.wil-work-detail__date span').html(val);
        });

        $('#wiloke-portfolio-save-button').on('click', function() {
            $(window).unbind('beforeunload');
        });


        // Custom button remove bg header
        $('input[name="project_header_settings[background]"]').before('<span class="wil-header-bg-remove" title="Remove"></span>');
        $('[name="project_header_settings[background]"]').change(function() {
            var currentID = $(this).siblings('.cmb_upload_file_id ').val();
            if ($(this).val() == ''){
                $('.wil-header-bg-remove').hide();
            }else{
                $('.wil-header-bg-remove').show();
            }

            $(this).siblings('.cmb_upload_file_id ').val(currentID);
        }).trigger('change');

        $(document).find('.wil-header-bg-remove').on('click', function() {
            $(this).siblings('#background_id_status').find('.cmb_remove_file_button').trigger('click');
        });

    });

    $(window).load(function() {

        if ( localStorage.getItem('wiloke-oz-portfolio-mode') == 'default' || typeof localStorage.getItem('wiloke-oz-portfolio-mode') == 'undefined' ){
            $('.wiloke-portfolio-toggle-settings-button').trigger('click');
        }else{
            $('.wiloke-portfolio-toggle-live-builder-button').trigger('click');
        }

        $('#project_skin').change(function() {
            var select = $(this),
                option = $('option', select),
                val = $('option:selected', select).val();
            val = typeof val == 'undefined' || val == 'inherit' ? oThemeOptions.project_skin : val;
            $('#content_ifr').contents().find('body').attr('data-skin', val);
        }).trigger('change');

        $('.wil-check--item').on('click', function() {
            var item = $(this),
                dataVal = item.data('val');

            $('#content_ifr').contents().find('body').attr('data-skin', dataVal);
        });


        $('#content_ifr').before('<span class="wil-editor-placeholder">Enter content here</span>');


        if (($('#content_ifr').contents().find('body > p:first-child').html() === '') || ($('#content_ifr').contents().find('body > p:first-child').html() === '<br data-mce-bogus="1">')){
            $('.wil-editor-placeholder').removeClass('wil-hide');
        }else{
            $('.wil-editor-placeholder').addClass('wil-hide');
        }

        $('#content_ifr').contents().find('body').on({
            focus: function() {
                if ( localStorage.getItem('wiloke-oz-portfolio-mode') == 'visual' ) {
                    $('.wil-editor-placeholder').addClass('wil-hide');
                    $('#insert-media-button, .mce-menubar, #wp-content-editor-container .mce-container-body > .mce-toolbar-grp').addClass('active');
                }
            },
            blur: function() {
                if ( localStorage.getItem('wiloke-oz-portfolio-mode') == 'visual' ) {
                    if (($('#content_ifr').contents().find('body > p:first-child').html() === '') || ($('#content_ifr').contents().find('body > p:first-child').html() === '<br data-mce-bogus="1">')){
                        $('.wil-editor-placeholder').removeClass('wil-hide');
                    }else{
                        $('.wil-editor-placeholder').addClass('wil-hide');
                    }
                    $('#insert-media-button, .mce-menubar, #wp-content-editor-container .mce-container-body > .mce-toolbar-grp').removeClass('active');
                }
            }
        });

        $('.mce-widget.mce-btn').on('click', function () {
            if ( localStorage.getItem('wiloke-oz-portfolio-mode') == 'visual' ) {
                if ($(this).attr('aria-label') === 'Insert/edit link') {
                    wpLink.open('content');
                }
            }
        });

        $(window).scroll(function(){
            $('.wil-bg-color').css('height', $('body').outerHeight());
            isHeightResize = false;
        });
        
        $('.cmb_upload_file_id ').each(function () {
            if ( $(this).val() == '' && $(this).siblings('.cmb_upload_file ').val() != '' ){
                $(this).val($(this).data('mediaid'));
            }
        })

    });


})(jQuery);