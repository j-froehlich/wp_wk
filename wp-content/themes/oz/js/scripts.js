
(function($) {
    "use strict";
    /*==============================
     Is mobile
     ==============================*/
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    }
    var isIE = /(MSIE|Trident\/|Edge\/)/i.test(navigator.userAgent);

    /*==============================
     Image cover
     ==============================*/
    $.fn.imageCover = function(opts) {
        $(this).each(function() {
            var self = $(this),
                image = $('img', self),
                defaults = {
                    imageRatio: 100
                },
                data = self.data(),
                dataTemp = $.extend(defaults, opts),
                options = $.extend(dataTemp, data);

            if (!options.imageRatio) {
                return false;
            }
            $(window).on('resize', function(event) {
                cover();
            });

            function cover() {
                if ( !image.length ){
                    return;
                }

                var naturalHeight = image[0].naturalHeight,
                    naturalWidth = image[0].naturalWidth,
                    heightWrap = self.outerHeight(),
                    widthWrap = self.outerWidth();

                self.css({
                    'position': 'relative',
                    'overflow': 'hidden',
                    'transform': 'translateZ(0)',
                    'zIndex': '9',
                    'padding-top': options.imageRatio + '%'
                });
                image.css({
                    'position': 'absolute',
                    'width': '100%',
                    'vertical-align': 'middle',
                    'top': '50%',
                    'left': '50%',
                    'zIndex': '9',
                    'transform': 'translate(-50%, -50%)'
                });

                if ((widthWrap/ heightWrap) < (naturalWidth/naturalHeight)) {
                    image.css({
                        'height': '100%',
                        'width': 'auto',
                        'max-width' : 'none'
                    });
                } else {
                    image.css({
                        'height': '',
                        'max-width': ''
                    });
                }
            }
        });
    }

    /*==============================
     Overflow text
     ==============================*/
    $.fn.numberLine = function(opts) {
        $(this).each( function () {
            var el = $(this),
                defaults = {
                    numberLine: 0
                },
                data = el.data(),
                dataTemp = $.extend(defaults, opts),
                options = $.extend(dataTemp, data);

            if (!options.numberLine)
                return false;

            $(window).on('resize', function(event) {
                reInit();
            });

            function reInit() {
                var fontSize = parseInt(el.css('font-size')),
                    lineHeight = parseInt(el.css('line-height')),
                    overflow = fontSize * (lineHeight / fontSize) * options.numberLine;

                el.css({
                    'display': 'block',
                    'max-height': overflow,
                    'overflow': 'hidden'
                });
            }
        })
    }

    $('[data-number-line]').numberLine();

    /**
     * Wiloke Masonry
     * Based on jQuery Masonry
     */
    function WilokeMasonry(el, options) {
        this.oSettings = $.extend({}, WilokeMasonry.oDefaults, options);
        this.$el = $(el);

        this._oOwnData = {};

        this._windowInnerWidth = 0;

        this._windowOuterWidth = 0;

        // Navigation element
        this.$filter = this.$el.parent().find(this.oSettings.navFilterClass);
        this._filterClicked = false;

        // Current Nav Filter
        this.$currentNavFilterItem = this.$filter.find(this.oSettings.currentNavFilterItemClass);

        // Navigation markup storage
        this._navigationMarkupStorage = {};

        // Masonry Wrapper
        this.$masonry = this.$el.find(this.oSettings.masonryClass);

        // Isotope Caching
        this.$masonryCaching = null;

        // Grid Items
        this.$gridItem = this.$masonry.find(this.oSettings.gridItemClass);
        this.$workItem = this.$masonry.find(this.oSettings.workItemClass);

        // Special Filter
        this.$specialFilter = this.$filter.parent().find('.wil-work-filter--1');
        this.$filterAnimation = this.$specialFilter.find('.wil-work-filter__list');

        // work Icon
        this.$filterWorkIcon = this.$filter.find(this.oSettings.filterWorkIconClass);

        this.initialize();
    }

    /* Remove empty padding */
    function removeEmptyPadding(){
        if ( !$('.breadcrumb').length ){
            $('.wil-section').removeClass('pt-0');
        }
    }

    WilokeMasonry.oDefaults = {
        duration: 0.5,
        navFilterClass: '.wil-work-filter',
        newItemClass: '.new-item',
        masonryClass: '.wil_masonry',
        gridItemClass: '.grid-item',
        workItemClass: '.wil-work-item',
        animationClass: '.wil-animation',
        currentNavFilterItemClass: '.current a',
        filterWorkIconClass: '.wil-work-filter__icon'
    };

    // workSetColMdForGridItems
    WilokeMasonry.prototype = {
        initialize: function () {
            this.windowWidth();
            this.setup();
            this.observer(this.$gridItem, this.setColMdForGridItems);
            this.masonry();
            this.navFilter();
            this.animationMakeup();
            this.filterAnimation();
            this.filterEvent();
            this.observer(this.$workItem, this.hoverEvent);
            this.observer(this.$masonry.find('.wil-work-item__quickview'), this.popupEvent);
            this.windowResize();
            this.addActionToNewItems();
        },
        windowWidth: function () {
            this._windowInnerWidth = $(window).innerWidth();
            this._windowOuterWidth = $(window).outerWidth();
        },
        setup: function () {
            this._oOwnData = this.$el.data();
            this._navigationMarkupStorage = this.$filter.data();
            if ( this._oOwnData.buttoncolor != '' ){
                this.$masonry.find('.wil-svg .wil-svg__circle').css({stroke: this._oOwnData.buttoncolor});
                this.$masonry.find('.flaticon').css({'color': this._oOwnData.buttoncolor});
            }
        },
        masonry: function () {
            var self = this;
            self.$masonry.imagesLoaded(function () {
                self.observer(self.$gridItem, self.gridItemCalculation);
                self.observer(self.$workItem, self.workItemCalculation);

                if (!self.$masonry.data('isotope')) {
                    if ( !self.$masonry.children().length ){
                        return;
                    }

                    self.$masonryCaching = self.$masonry.isotope({
                        // percentPosition: true,
                        layoutMode: 'masonry',
                        itemSelector: '.grid-item',
                        transitionDuration: self.oSettings.duration * 2 + 's',
                        masonry: {
                            columnWidth: '.grid-sizer'
                        },
                        hiddenStyle: {
                            opacity: 0,
                            transform: 'rotateY(180deg)'
                        },
                        visibleStyle: {
                            opacity: 1,
                            transform: 'rotateY(0)'
                        },
                        resize: true
                    });

                    self.$masonry.addClass('wiloke-isotope-initilized');

                    self.$masonryCaching.one('layoutComplete', function () {
                        $(document.body).trigger('wiloke_isotope_initialized');
                        self.observer(self.$workItem, self.gridItemMarkup);
                    });

                    if ( self.$el.parent().find(self.oSettings.navFilterClass).length && !self._filterClicked ){
                        self.$currentNavFilterItem.trigger('click');
                    }else{
                        self.observer(self.$workItem, self.gridItemMarkup);
                    }

                    self.$masonry.next().find('.is_now_allow').removeClass('is_now_allow');
                } else {
                    $(window).trigger('resize');
                }
            });
        },
        navFilter: function () {
            if ( typeof this._navigationMarkupStorage !== 'undefined' ){
                this.$currentNavFilterItem.css({color: this._navigationMarkupStorage['activatedcolor']});
                this.$currentNavFilterItem.find('span').css({'border-top-color': this._navigationMarkupStorage['activatedcolor']});
            }

            this.$filter.on('click', 'a', $.proxy(function(event){
                event.preventDefault();
                var $el = $(event.currentTarget),
                    target = $el.attr('data-filter');

                if ( typeof this._navigationMarkupStorage != 'undefined' ){
                    $el.css({color: this._navigationMarkupStorage['activatedcolor']});
                    $el.find('span').css({'border-top-color': this._navigationMarkupStorage['activatedcolor']});
                    this.$filter.find('.current a').css({color: this._navigationMarkupStorage['currentcolor']});
                }

                this.$filter.find('.current span').css({'border-top-color': 'none'});
                this.$filter.find('.current').removeClass('current');
                $el.parent().addClass('current');

                this.$masonryCaching.isotope({
                    filter: target
                });

                if ( this.$el.data('wiloke.masonry.triggerresizewhenall') && (target === '*') ){
                    this.$el.data('wiloke.masonry.triggerresizewhenall', false);
                }

            },this));
        },
        // This feature only effect wil-work-filter--1
        animationMakeup: function(){
            this.$filterAnimation.find('a').each(function () {
                var $el = $(this);

                if ( $el.hasClass('added-animation') ) {
                    return;
                }

                if ( $el.parent().hasClass('start') ){
                    $el.parent().removeClass('start');
                }
                $el.append('<span></span>');
                $el.parent().width($el.outerWidth());
                $el.parent().height($el.outerHeight());
                $el.addClass('added-animation');
            });
        },
        filterAnimation: function () {
            var self = this;
            this.$filterAnimation.find('a').each(function () {
                var $el = $(this),
                    thisParentOffset = $el.parent().offset(),
                    thisParentPosition = $el.parent().position(),
                    filtersOffset = self.$filter.offset();

                $el.css({
                    'position': 'absolute',
                    'left': (thisParentOffset.left - filtersOffset.left) + 'px',
                    'bottom': thisParentPosition.top + 'px',
                    'transition': 'all ' + self.oSettings.duration + 's ease'
                });
            });
        },
        filterEvent: function () {
            var self = this, $liItem = this.$filterAnimation.find('li');
            this.$filterAnimation.on('click', 'a', function() {
                self._filterClicked = true;
                $(this).parent().css('position', 'static');
                self.$filterAnimation.find('.effect').removeClass('effect');
                self.$filterAnimation.find('.start').removeClass('start');
                $(this).parent().addClass('start');
            });

            this.$filterWorkIcon.on('click', function() {
                $liItem.addClass('effect');
                $liItem.css('position', 'static');
                self.$filterAnimation.find('.start').removeClass('start');
            });
        },
        gridItemCalculation: function ($items) { // reCalWidth
            var calculated = false;
            $items.each(function () {
                $(this).css('width', '');
                var width = Math.floor($(this).outerWidth());

                $(this).css('width', width + 'px');

                if ( !calculated ){
                    var $wideItem = $(this).parent().children('.wide'),
                        height = $wideItem.outerWidth()/2;
                    $wideItem.css('height', Math.floor(height) + 'px');
                    calculated = true;
                }
            });
        },
        workItemCalculation: function ($items) { // animationForWork
            $items.each(function() {
                var $item = $('.wil-work-item__inner', $(this));
                    $item.width($(this).outerWidth());
                    $item.data('calculated', true);
            });
        },
        setColMdForGridItems: function ($items) {
            var eh, ev;
            if (this._windowInnerWidth >= 768 && this._windowInnerWidth < 992) {
                eh = this._oOwnData['smHorizontal'];
                ev = this._oOwnData['smVertical'];
            } else if (this._windowInnerWidth >= 992 && this._windowInnerWidth < 1200) {
                eh = this._oOwnData['mdHorizontal'];
                ev = this._oOwnData['mdVertical'];
            } else if (this._windowInnerWidth >= 1200) {
                eh = this._oOwnData['lgHorizontal'];
                ev = this._oOwnData['lgVertical'];
            } else {
                eh = this._oOwnData['xsHorizontal'];
                ev = this._oOwnData['xsVertical'];
            }

            this.$el.css({
                'margin-top': -ev/2 + 'px',
                'margin-bottom': -ev/2 + 'px',
                'margin-left': -eh/2 + 'px',
                'margin-right': -eh/2 + 'px'
            });

            $items.find('.grid-item__content-wrapper').each(function(){
                $(this).css({
                    'margin': ev/2 + 'px ' + eh/2 + 'px',
                    'top': ev/2 + 'px',
                    'bottom': ev/2 + 'px',
                    'left': eh/2 + 'px',
                    'right': eh/2 + 'px'
                });
            })
        },
        gridItemMarkup: function ($items) { // ->workItemResize
            if ($items.hasClass('wil-work-item--over')) {
                $items.each(function () {
                    var elOuterWidth = $(this).outerWidth(),
                        elWidthContent = $('.wil-work-item__content', $(this)).width(),
                        elTbCellWidth = $('.wil-tb__cell', $(this)).outerWidth();
                    if (elOuterWidth < elTbCellWidth) {
                        $('.wil-work-item__title', $(this)).css('font-size', '');
                        var fontTitle = $('.wil-work-item__title', $(this)).css('font-size').split(/(\d+)/)[1],
                            setFontTitle = window.innerWidth > 1250 ? (fontTitle * (elOuterWidth/elTbCellWidth - 0.15)) : (fontTitle * elOuterWidth/elTbCellWidth);
                        $('.wil-work-item__title', $(this)).css({
                            'font-size': setFontTitle + 'px',
                            'width': elWidthContent + 'px'
                        });
                        $('.wil-work-item__cat', $(this)).css({
                            'width': elWidthContent + 'px'
                        });
                        $('.wil-work-item__quickview', $(this)).css({
                            'transform': 'scale(' + elOuterWidth/elTbCellWidth + ')'
                        });
                    } else {
                        $('.wil-work-item__title', $(this)).css({
                            'font-size': '',
                            'width': ''
                        });
                        $('.wil-work-item__cat', $(this)).css({
                            'width': ''
                        });
                        $('.wil-work-item__quickview', $(this)).css({
                            'transform': ''
                        });
                    }
                })
            }
            if ($items.hasClass('wil-work-item--static') || $items.hasClass('wil-work-item--grayscale')) {
                var isSecondMasonry = this.$el.hasClass('wil_masonry--2') ? true : false;
                $items.each(function () {
                    var elHeight = $(this).outerHeight(),
                        workcontentHeight = $('.wil-work-item__content', $(this)).outerHeight();
                    $('.wil-work-item__inner', $(this)).height(elHeight-workcontentHeight);

                    if (isSecondMasonry) {
                        $('.wil-work-item__quickview', $(this)).css('bottom', workcontentHeight);
                    }
                });
            }
        },
        hoverEvent: function ($items) {
            var timingFunction = 300, quickview, setTimingFunction, timeQuickview = 8000;
            $items.each(function () {
                var $el = $(this);

                if ($el.hasClass('wil-work-item--over'))
                    $el.hoverdir({
                        speed: timingFunction,
                        easing: 'ease',
                        hoverDelay: 50,
                        inverse: false,
                        hoverElem: '.wil-work-item__content'
                    });

                if ( !isMobile.any() ){
                    $el.hover(function() {
                        setTimingFunction = setTimeout(function() {
                            $('.wil-svg__circle', $el)
                                .stop()
                                .animate({
                                    'stroke-dashoffset': 0
                                }, timeQuickview, 'linear');
                        }, timingFunction);

                        quickview = setTimeout(function() {
                            $('.wil-work-item__quickview', $el).trigger('click');
                        }, timeQuickview + 100);
                    }, function() {
                        $('.wil-svg__circle', $el)
                            .stop()
                            .animate({
                                'stroke-dashoffset': 165
                            }, 0);
                        clearTimeout(quickview);
                        clearTimeout(setTimingFunction);
                    });

                    $el.on('click', function () {
                        clearTimeout(quickview);
                        clearTimeout(setTimingFunction);
                    })
                }
            });

            if ( $items.hasClass('wil-work-item--static') || $items.hasClass('wil-work-item--grayscale') ){
                $items.find('.wil-work-item__inner .wil-work-item__media a').hover(function () {
                    $(this).css({'background-color': $(this).data('hovercolor')});
                }, function () {
                    $(this).css({'background-color': ''});
                });

                $items.find('.wil-work-item__inner a.wil-work-item__quickview').hover(function () {
                    $(this).closest('.wil-work-item__inner').find('.wil-work-item__media a').css({'background-color': $(this).data('hovercolor')});
                }, function () {
                    $(this).closest('.wil-work-item__inner').find('.wil-work-item__media a').css({'background-color': ''});
                })

                $items.find('.wil-work-item__content').hover(function () {
                    $(this).closest('.wil-work-item__inner').find('.wil-work-item__media a').css({'background-color': $(this).data('hovercolor')});
                }, function () {
                    $(this).closest('.wil-work-item__inner').find('.wil-work-item__media a').css({'background-color': ''});
                })
            }

            if ( $items.hasClass('wil-work-item--over') ){
                var $workItemContent = $items.find('.wil-work-item__content');
                $workItemContent.css({'background-color': $workItemContent.data('hovercolor')});
            }
        },
        replaceWorkPattern: function ($this) {
            var template = WILOKE_GLOBAL.popup_template;
            template = $this.hasClass('iframe') ? template.replace('<div class="wil-work-popup__image">{{content}}</div>', '') : template;
            template = template.replace(/{{link}}/g, $this.data('link'));
            template = template.replace(/{{thumbnail}}/g, $this.data('thumbnail'));
            template = template.replace(/{{title}}/g, $this.data('title'));
            template = template.replace(/{{category}}/g, $this.prev().find('.wil-work-item__cat').html());

            return template;
        },
        popupEvent: function ($items) {
            var self = this, items = $items.data('imgs');
            $items.magnificPopup({
                mainClass: 'wil-work-popup-wrap',
                removalDelay: 600,
                items: {
                    src: $items.hasClass('iframe') ? $items.attr('href') : '<div id="wiloke-portfolio-popup" class="wil-work-popup hidden"></div>',
                    type: $items.hasClass('iframe') ? 'iframe' : 'inline',
                },
                iframe: {
                    markup: '<div id="wiloke-portfolio-popup" class="wil-work-popup wiloke-has-iframe"><div class="mfp-iframe-scaler"><div class="wil-work-popup__image">'+
                    '<div class="mfp-close"></div>'+
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                    '</div>'+self.replaceWorkPattern($items)+'</div></div>',
                },
                tLoading: '<div class="wil-loader"></div>',
                callbacks: {
                    change: function() {
                        if ( $(this.st.el).hasClass('iframe') ) {
                            return;
                        }
                        var $el = $(this.st.el), $html = WILOKE_GLOBAL.popup_template, imgTemplate = '', number=1;
                        if ( number = $el.find('.wiloke-magnific-performance').length ){
                            imgTemplate = $el.find('.wiloke-magnific-performance').html();
                        }else{
                            imgTemplate += '<div class="swiper-slide"><a class="wiloke-link-inside-magnific" href="'+$el.data('link')+'"><img src="' + items + '" alt="' + $items.attr('title') + '"></a></div>';
                        }

                        $html = self.replaceWorkPattern($el);

                        if (number > 2) {
                            $html = $html.replace(/{{content}}/g, '<div data-swiper-pagination="true" data-swiper-navigation="true" data-swiper-pagination-style="2" data-swiper-navigation-style="2" class="wil-swiper"><div class="swiper-wrapper">' + imgTemplate + '</div><div class="wil-swiper__pagination"></div><div class="wil-swiper__button"><div class="wil-swiper__button-item wil-swiper__button-prev"><i class="fa fa-angle-left"></i></div><div class="wil-swiper__button-item wil-swiper__button-next"><i class="fa fa-angle-right"></i></div></div><div class="wil-loader wil-loader--color"></div></div>');
                        } else {
                            $html = $html.replace(/{{content}}/g, imgTemplate);
                        }

                        $(this.content).html($html).removeClass('hidden');
                        // $(this.content).find('.wil-work-item__title a').css({color: $items.data('titlecolor')});
                    },
                    open: function() {
                        // Will fire when this exact popup is opened
                        // this - is Magnific Popup object
                        var $container = $(this.content).find('.wil-swiper');
                        $container.imagesLoaded(function () {
                            $container.swiper({
                                pagination: '.wil-swiper__pagination',
                                paginationClickable: true,
                                nextButton: '.wil-swiper__button-next',
                                prevButton: '.wil-swiper__button-prev',
                                spaceBetween: 0,
                                autoHeight: true,
                                loop: true,
                                speed: 400
                            });
                        });

                        $container.find('.wil-swiper__button-next, .wil-swiper__button-prev').hover(function () {
                            $(this).css({'background-color': $items.data('buttoncolor')});
                        }, function(){
                            $(this).css({'background-color': '#fff'});
                        });
                    }
                }
            });
        },
        // $items: Our target machine: function that will handle our target
        observer: function ($items, machine) {
            machine.call(this, $items);
        },
        addActionToNewItems: function () {
            this.$el.on('wiloke.masonry.hasNewItems', $.proxy(function(event){
                var $gridItems = this.$masonry.find(this.oSettings.newItemClass+this.oSettings.gridItemClass);
                this.observer($gridItems.find('.wil-work-item__quickview'), this.popupEvent);
                this.observer($gridItems.find(this.oSettings.workItemClass), this.hoverEvent);
                this.observer($gridItems, this.setColMdForGridItems);
                this.observer($gridItems, this.gridItemCalculation);
                this.observer($gridItems.find(this.oSettings.workItemClass), this.gridItemMarkup);
                this.observer($gridItems.find(this.oSettings.workItemClass), this.workItemCalculation);
            }, this));
        },
        windowResize: function () {
            var self = this;

            if ( isMobile.any() ){
                return;
            }

            $(window).on('resize', function () {
                self.$gridItem = self.$masonry.find(self.oSettings.gridItemClass);
                self.$workItem = self.$masonry.find(self.oSettings.workItemClass);
                self.windowWidth();

                self.observer(self.$workItem, self.gridItemMarkup);
                self.observer(self.$gridItem, self.gridItemCalculation);
                self.observer(self.$workItem, self.workItemCalculation);
                self.observer(self.$gridItem, self.setColMdForGridItems);
                self.filterAnimation();
            });
        }
    };

    $.fn.WilokeOZMasonry = function (options) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('wiloke.masonry');
            if ( !data ){
                data =  new WilokeMasonry(this, options);
                $this.data('wiloke.masonry', data);
            }
        })
    };

    $.fn.WilokeOZMasonry.Constructor = WilokeMasonry;

    // Loadmore post
    function wilokeLoadmorePost() {
        $('.wiloke-loadmore').each(function () {
            var $this = $(this);

            var oWilokeInfiniteScroll = $this.WilokeInfiniteScroll({
                oAjaxOptions      : {
                    post_type  : 'portfolio',
                    action     : 'wiloke_loadmore_posts',
                    additional : $(this).data('additional')
                },
                itemCssClass      : '.grid-item',
                progressingClass  : '.work__loadmore-wrapper',
                // is_debug          : true,
                isInfiniteScroll  : $(this).hasClass('is_infinite_scroll') ? true : false,
                navFiltersCssClass: '.wil-work-filter__list li',
                navFilterWrapperCssClass: '.wil-work-filter__list',
                currentFilterCssClass: '.current'
            });

            oWilokeInfiniteScroll.data('wiloke.infinite_scroll').beforeAppend = function($renderItems) {
                $renderItems = $($renderItems.join(''));
                $renderItems.addClass('new-item');

                addAnimation($renderItems.find('.wil-work-item__anim'), this.$container.find('.wil-masonry-wrapper').data());

                $renderItems.find('.wil-svg .wil-svg__circle').css({stroke: this.$appendTo.parent().data('buttoncolor')});
                $renderItems.find('.flaticon').css({'color': this.$appendTo.parent().data('buttoncolor')});
                return $renderItems;
            };

            oWilokeInfiniteScroll.data('wiloke.infinite_scroll').beforeAppendToIsotope = function () {
                var self = this,
                    $masonryWrapper = this.$container.find('.wil-masonry-wrapper');
                this.$appendTo.find('.new-item.grid-item');

                this.$appendTo.one('layoutComplete', function () {
                    $masonryWrapper.trigger('wiloke.masonry.hasNewItems');
                    setTimeout(function () {
                        runAnimation(self.$container.find('.new-item .wil-work-item__anim'), true);
                        $masonryWrapper.find('.new-item').removeClass('new-item');
                    }, 550);
                });
            };

            oWilokeInfiniteScroll.data('wiloke.infinite_scroll').afterAppended = function () {
                if ( oWilokeInfiniteScroll.data('wiloke.infinite_scroll').oSettings.isInfiniteScroll ){
                     oWilokeInfiniteScroll.data('wiloke.infinite_scroll').$appendTo.one('layoutComplete', function ()
                     {
                         $this.trigger('click');
                     });
                }
            };

            if ( oWilokeInfiniteScroll.data('wiloke.infinite_scroll').oSettings.isInfiniteScroll ){
                oWilokeInfiniteScroll.data('wiloke.infinite_scroll').$container.find('.wil_masonry').one('layoutComplete', function () {
                    oWilokeInfiniteScroll.data('wiloke.infinite_scroll').triggerInfiniteScroll();
                });
            }
        });
    }

    // Blog creative
    $.fn.wilokePaginateAjaxLoading = function (options, callback) {
        var $container  = $(this),
            nonce       = $container.data('nonce'),
            timeCaching = 3600,
            currentPage = '',
            pattern = new RegExp(/(.*)((?=\/page\/)|(?=\/\?paged=))((\/\?paged=)|\/page\/)/g),
            clicked     = false,
            oOptions    = {type: 'switch_page', callback: function () {}, getBlock: '.wiloke-js-blog-wrapper', ajax_type: 'post', ajax_action: 'wiloke_pagination_with_fajax', identifier: '.wil-section', isScrollTop: true, addMethod: 'replace'};
        oOptions = $.extend(oOptions, options);

        var localCache = {
            data: {},
            remove: function (url) {
                delete localCache.data[url];
            },
            exist: function (url) {
                return localCache.data[url] !== null && typeof localCache.data[url] !== 'undefined';
            },
            get: function (url) {
                return localCache.data[url];
            },
            set: function (url, cachedData, callback) {
                localCache.remove(url);
                localCache.data[url] = cachedData;
                if ($.isFunction(callback)) callback(cachedData, oOptions);
            },
            rightURL: function (url) {
                if ( currentPage == '' ){
                    currentPage = pattern.exec(url);
                    currentPage = currentPage[0];
                }

                if ( url.search('admin-ajax.php') !== -1 ) {
                    return url;
                }

                url = url.replace(/(.*)(?=page|paged)(page|paged)(=|\/)/g, function (match) {
                    return '';
                });
                url = currentPage + url.replace('\/', '');

                return url;
            }
        };

        $container.on('click', '.page-numbers:not(.current)', function (event) {
            event.preventDefault();
            var $this = $(this), aimtoURL = $(this).attr('href'), cachingURL = localCache.rightURL(aimtoURL);
            $(this).attr('data-type', 'ajax');

            if ( $container.data('ajax_loading') || typeof  aimtoURL === 'undefined' || aimtoURL == '' )
            {
                return false;
            }

            if ( !clicked ) {
                var currentNumber = $container.find('.page-numbers.current').text(),
                    neighbor = currentPage + currentNumber;

                var currentHTML = $container.html();

                localCache.set(localCache.rightURL(neighbor), currentHTML, '');
                clicked = true;
            }

            if ( !$container.hasClass('woocommerce') ){
                $container.addClass('loading');
            }

            $container.data('ajax_loading', true);

            // The settings was created in visual composer. it's very import to re-build layout looks like first render
            var oSettings = typeof $container.data('settings') != 'undefined' ? $container.data('settings') : $(this).closest('.wil-pagination').data('settings'),
                otherData = $(this).closest('.wil-pagination').data();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {action: oOptions.ajax_action, url: aimtoURL, _nonce: WILOKE_GLOBAL.wiloke_nonce, _blog_nonce: $this.closest('.wil-pagination').data('nonce'), settings: oSettings, rest: $container.data('rest'), other_data: otherData, real_prefix_url: currentPage},
                url: WILOKE_GLOBAL.ajaxurl,
                cache: false,
                beforeSend: function () {
                    if (localCache.exist(cachingURL)) {
                        appendContent(localCache.get(cachingURL), oOptions);
                        return false;
                    }
                    return true;
                },
                complete: function (response, textStatus) {
                    var parseResponse = $.parseJSON(response.responseText);

                    if ( parseResponse.success )
                    {
                        if ( typeof parseResponse.data.pagination != 'undefined' ) {
                            var $content = $(parseResponse.data.html).append($(parseResponse.data.pagination));
                            parseResponse.data.html = '<div>'+$content.html()+'</div>';
                        }

                        var result = '';

                        if ( oOptions.getBlock === 'self' ) {
                            result = parseResponse.data.html;
                        }else if( oOptions.getBlock === 'children' ) {
                            result = $(parseResponse.data.html).html();
                        }else{
                            result = $(parseResponse.data.html).find(oOptions.getBlock).html(); // get what we need
                        }

                        localCache.set(cachingURL, result, appendContent);
                    }
                }
            });

        });

        function appendContent(response, oOptions){
            $container.data('ajax_loading', false);

            if ( oOptions.ajax_action !== 'wiloke_woocommerce_loadmore' ){
                var getHeight;
                getHeight = $container.outerHeight();
                $container.children().remove();
                $container.css({height: getHeight});
                $container.html($(response));

                var currentHeight = $container.outerHeight();
                if ( currentHeight > $(window).height() ) {
                    $('html, body').animate({
                        scrollTop: $container.offset().top - 100
                    }, 1000, function () {
                        $container.fadeIn('slow');
                    });
                }else{
                    $container.fadeIn('slow');
                }
                $container.css({height: 'auto'});
            }else{
                var $navigation = $(response).find('.wil-pagination').html(),
                    $editResponse = $(response).find('.wil_masonry'),
                    $masonry = $container.find('.wil_masonry');

                $editResponse.find('.grid-sizer').remove();
                $editResponse = $($editResponse.html());
                $editResponse.addClass('new-item');

                addAnimation($editResponse.find('.product__anim'), $('.wil-masonry-wrapper').data());

                $masonry.one('layoutComplete', function () {
                    $masonry.parent().trigger('wiloke.masonry.hasNewItems');

                    setTimeout(function () {
                        runAnimation($editResponse.find('.product__anim'));
                    }, 550);

                    if ( !$(response).find('.wil-pagination .prev.page-numbers').next().next().length ) {
                        $container.find('#wil-shop-loadmore').remove();
                    }else{
                        $container.find('#wil-shop-loadmore').addClass('loaded');
                    }
                });

                $masonry.append($editResponse).isotope( 'appended', $editResponse ).isotope('layout');
                $container.find('.wil-pagination').html($navigation);
            }

            if ( !$container.hasClass('woocommerce') ){
                $container.removeClass('loading');
            }

            oOptions.callback($container);

            if ( typeof callback !== 'undefined' ) {
                callback();
            }

            if ($container.find('.lazy').length){
                $container.find('.lazy').lazyload({
                    effect : 'fadeIn',
                    threshold : 200
                });
            }
        }
    };

    // Progress bar
    function wilProgress() {
        var offset = $('.wil-progress').offset(),
            windowHeight = $(window).height(),
            windowScroll = $(window).scrollTop();

        $('.wil-progress').each(function() {
            var progress = $(this),
                progressVal = progress.attr('aria-value'),
                progressValMin = progress.attr('aria-value-min'),
                progressValMax = progress.attr('aria-value-max');

            if ((windowHeight + windowScroll) > offset.top) {
                if (progress.hasClass('done') == false) {
                    progress.addClass('done');
                    $('.wil-progress__bar-count', progress)
                        .css({
                            'width': progressValMin + '%'
                        });

                    setTimeout(function() {
                        $('.wil-progress__bar-count', progress)
                            .css({
                                'width': (progressVal * 100 / progressValMax) + '%'
                            });
                    }, 200);
                }
            }
        });
    }

    // Add to cart fly effect
    function addToCart() {
        $('.product__add-to-cart a').each(function() {
            if ( $(this).data('run-addtocart') ){
                return;
            }
            var addCart = $(this),
                addCartWidth = addCart.outerWidth(),
                addCartHeight = addCart.outerHeight();
            addCart.parent().siblings('.product__add-to-cart-bg')
                .css({
                    'width': addCartWidth,
                    'height': addCartHeight,
                });
            $(this).data('run-addtocart', true);
        });
    }

    function cartFlyWidthWp() {
        $(document.body).on('oz_remove_cart_loading', function(event, $thisbutton){
            $thisbutton.removeClass('loading');
        });

        $(document.body).on('adding_to_cart', function (event, $thisbutton, data) {
            $thisbutton.parent().addClass( 'wrap-loading' );
            $thisbutton.parent().removeClass( 'wrap-added' );
        });

        $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $thisbutton) {
            var elOffset = $thisbutton.offset(),
                addCartWidth = $thisbutton.outerWidth(),
                addCartHeight = $thisbutton.outerHeight(),
                wScrollTop = $(window).scrollTop(),
                goToCart = $('.wil-toggle-minicart__inner').offset();

            $thisbutton.parent().addClass('wrap-added');
            $thisbutton.parent().removeClass('wrap-loading');
            $thisbutton.addClass('added');

            $('#cart-mini-content').html(fragments['div.widget_shopping_cart_content']);

            setTimeout(function() {
                $thisbutton
                    .parent().siblings('.product__add-to-cart-bg')
                    .css({
                        'position': 'fixed',
                        'top': elOffset.top - wScrollTop + 50 + 'px',
                        'left': elOffset.left + 'px',
                        'right': 'auto',
                        'bottom': 'auto',
                        'z-index': 99999
                    })
                    .animate({
                        'top': goToCart.top - wScrollTop + 8 + 'px',
                        'left': goToCart.left + 8 + 'px',
                        'width': 43 + 'px'
                    }, 1000, 'easeInOutExpo',function() {
                        $thisbutton
                            .parent()
                            .siblings('.product__add-to-cart-bg')
                            .children('.bg')
                            .css('transition', 'none');
                        setTimeout(function() {
                            $thisbutton
                                .parent()
                                .siblings('.product__add-to-cart-bg')
                                .removeAttr('style')
                                .css({
                                    'width': addCartWidth,
                                    'height': addCartHeight
                                });
                            $thisbutton
                                .parent()
                                .siblings('.product__add-to-cart-bg')
                                .children('.bg')
                                .css('transition', '');
                        }, 100);

                        $thisbutton.removeClass('added');
                        $thisbutton.parent().removeClass('wrap-added');
                        $thisbutton.removeClass('loading');
                    });

                setTimeout(function() {
                    $('.wil-toggle-minicart i')
                        .css({
                            'width': 60 + 'px',
                            'height': 60 + 'px',
                            'line-height': 60 + 'px',
                            'transition': 'none'
                        })
                        .animate({
                            'width': 50 + 'px',
                            'height': 50 + 'px',
                            'line-height': 50 + 'px',
                        }, 600, 'easeOutElastic', function() {
                            $('.wil-toggle-minicart').removeAttr('style');
                        });
                        $("#wil-minicart-container #cart-mini-content").trigger('change');
                }, 700);
            }, 300);
        });
    }

    function addToCartShow() {
        $('.type-product').each(function() {
            var el = $(this),
                elHeader = $('.product__header', el);

            if ( el.data('run-addtocartshow') ){
                return;
            };

            if (isMobile.any()) {
                elHeader.on('click', '> a', function(e) {
                    e.preventDefault();
                    el.toggleClass('active');
                });

                if (window.innerWidth <= 768) {
                    $(window).on('scroll', function() {
                        var windowHeight = $(window).height(),
                            windowScrollTop = $(window).scrollTop(),
                            getOffset = elHeader.offset();
                        if ( typeof getOffset === 'undefined'  ){
                            return;
                        }

                        if ((windowHeight/2 + windowScrollTop) > getOffset.top && windowScrollTop < (getOffset.top + 20)) {
                            el.addClass('active');
                        } else {
                            el.removeClass('active');
                        }
                    });
                }

            } else {
                el.hover(function() {
                    el.addClass('active');
                }, function() {
                    el.removeClass('active');
                });
            }

            el.data('run-addtocartshow', true);
        });
    }

    // Open cart fixed
    function minicartFixed() {
        if ($('.wil-section-top .wil-minicart').length) {
            var windowScrollTop = $(window).scrollTop(),
                windowWidth = $(window).innerWidth(),
                offsettop = $('.wil-section-top .wil-minicart').offset().top,
                offsetLeft = $('.wil-section-top .wil-minicart').offset().left,
                setOffset = 10;

            if( $('body').hasClass('admin-bar') && windowWidth > 600 ) {
               setOffset =  $('#wpadminbar').innerHeight() + 10;
            }

            if (windowScrollTop >= (offsettop - setOffset)) {
                $('.wil-section-top .wil-minicart__inner')
                    .css({
                        'position': 'fixed',
                        'top': setOffset + 'px',
                        'left': offsetLeft + 'px'
                    });
            } else {
                $('.wil-section-top .wil-minicart__inner')
                    .removeAttr('style');
            }
        }
    }

    function boxToggle() {
        $('.wil-box-toggle').each(function() {
            var el = $(this),
                elId = el.attr('id');
            $('[href="#' + elId + '"]').on('click', function(e) {
                e.preventDefault();
                el.toggleClass('active');
                $(this).toggleClass('active');
                if (el.hasClass('body-overflow-hidden')) {
                    $('html, body').toggleClass('overflow-hidden');
                }
            });
            $('html, .wil-box-toggle__close').on('click', function() {
                el.removeClass('active');
                $('[href="#' + elId + '"]').removeClass('active');
                $('html, body').removeClass('overflow-hidden');
            });
            $('.wil-box-toggle__close').on('click', function(e) {
                e.preventDefault();
            });
            $('.wil-box-toggle, [href="#' + elId + '"]').on('click', function(e) {
                e.stopPropagation();
            });
        });
    }

    // Work detail
    function workDetail() {
        if ($('.wil-work-detail').length) {
            $(window).on('resize', function () {
                if (!$('.wil-work-detail__media').length)
                    $('.wil-work-detail-bg-content').before('<div class="wil-work-detail__media"></div>');
                var mediaHeight = $('.wil-work-detail__media').height();

                var headerHeight = $('.wil-header').outerHeight();
                $('.wil-work-detail__header').height(mediaHeight - headerHeight);

                $('.wil-work-nav-page__item').hover(function(){
                    var el = $(this),
                        elWidth = el.outerWidth(),
                        offsetLeft = $('.wil-work-nav-page__prev').offset().left;
                    $('.wil-work-nav-page__line', el).width(elWidth+offsetLeft);
                }, function() {
                    $('.wil-work-nav-page__line').width(0);
                });
            }).trigger('resize');
        }
    }

    function firstLetterWork() {
        if ( $('.wil-work-detail__title').length && !$('.wil-work-detail__sup').length ) {

            var text = $('.wil-work-detail__title h1').text().slice(0,1);
            $('.wil-work-detail__header .wil-tb__cell').prepend('<span class="wil-work-detail__sup">' + text + '</span>')

            if ( typeof $('.wil-work-detail__header').data('gradientfc') !== 'undefined' ){

                if ( isIE ) {
                    $('.wil-work-detail__header .wil-tb__cell .wil-work-detail__sup').css({'color': $('.wil-work-detail__header').data('gradientfc'), '-webkit-text-fill-color':$('.wil-work-detail__header').data('gradientfc')});
                }else{
                    $('.wil-work-detail__header .wil-tb__cell .wil-work-detail__sup').css({'background-image': 'linear-gradient(45deg,'+$('.wil-work-detail__header').data('gradientfc')+' 40%, '+$('.wil-work-detail__header').data('gradientsc')+' 80%)'});
                }

            }

            if (!isIE) {
                $('.wil-work-detail__sup').addClass('bg-transparent');
            }
        }
    }

    // Image cover
    function imgCover() {
        $('.widget-list .img').imageCover({
            imageRatio: 100
        });
        $('.wil-related-post__media a').imageCover({
            imageRatio: 56.25
        });

        $('[data-image-ratio]').imageCover();
    }

    // Button shadow
    function btnShadow() {
        $('a.wil-btn, button.wil-btn, a.button, button.button')
            .append('<div class="wil-btn--shadow"></div>');
    }

    // Quantity
    function numberIncrementer() {
        $('#wiloke-body-area').off('click', '.btn-number').on('click', '.btn-number', function() {
            var $button = $(this),
                oldValue = $button.parent().find('input').val(),
                newVal = 0;

            if ($button.hasClass('qty-up')) {
                newVal = parseFloat(oldValue) + 1;
            } else {
                if (oldValue > 1) {
                    newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }
            $button.parent().find('input').val(newVal).trigger('change');
        });

        $('#wiloke-body-area').off('click', 'input[name="update_cart"]').on('click', 'input[name="update_cart"]', function(){
            var totalItems = 0;
            $('.shop_table_responsive').find('.input-text.qty').each(function () {
                totalItems +=  parseInt($(this).val(), 10);
            });

            $('.wil-toggle-minicart__number-item').html(totalItems);
        })

        $('#wiloke-body-area').off('click', '.product-remove .remove').on('click', '.product-remove .remove', function(){
            var deduct = $(this).closest('.cart_item').find('.input-text.qty').attr('value');

            wilokeUpdateCard('deduct', deduct);
        })
    }

    // Google Map
    function wilokeMap(){
        if ( $("#wiloke-googlemap").length ) {

            if ( typeof google == 'undefined' ) {
                return;
            }

            if ( $('#wiloke-googlemap').data('loaded') ) {
                return;
            }

            var oConfigs = $('#wiloke-googlemap').data(),
                mapStyle = {
                    grayscale : [{featureType: 'all',  stylers: [{saturation: -100},{gamma: 0.50}]}],
                    blue : [{featureType: 'all',  stylers: [{hue: '#0000b0'},{invert_lightness: 'true'},{saturation: -30}]}],
                    dark : [{featureType: 'all',  stylers: [{ hue: '#ff1a00' },{ invert_lightness: true },{ saturation: -100  },{ lightness: 33 },{ gamma: 0.5 }]}],
                    pink : [{"stylers": [{ "hue": "#ff61a6" },{ "visibility": "on" },{ "invert_lightness": true },{ "saturation": 40 },{ "lightness": 10 }]}],
                    cobalt : [{featureType: "all",elementType: "all",stylers: [{invert_lightness: true},{saturation: 10},{lightness: 30},{gamma: 0.5},{hue: "#435158"}]}],
                    brownie : [{"stylers": [{ "hue": "#ff8800" },{ "gamma": 0.4 }]}]
                },
                mapOptions = {
                    zoom: oConfigs.zoom,
                    center: new google.maps.LatLng(oConfigs.lat, oConfigs.long),
                    styles: mapStyle[oConfigs.style],
                    scrollwheel: false,
                    draggable: $(document).width() > 480 ? true : false
                },
                mapInit = new google.maps.Map(document.getElementById('wiloke-googlemap'), mapOptions),
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(oConfigs.lat, oConfigs.long),
                    map: mapInit,
                    icon: oConfigs.marker
                });

            if ( oConfigs.info != '' ) {
                var infowindow = new google.maps.InfoWindow({
                    content: oConfigs.info
                });
                infowindow.open(mapInit,marker);
            }

            $('#wiloke-googlemap').data('loaded', true);
        }
    }

    // Post Counter
    function wilokePostCounter() {
        if ( ($('.single.single-portfolio').length > 0) || ($('.single-post').length > 0) || ($('.single-product').length > 0) ){
            if ( WILOKE_GLOBAL.postID != -1 && WILOKE_GLOBAL.post_type != '-1' ) {
                var $xhr = null;

                $xhr = $.ajax({
                    type: 'POST',
                    url: WILOKE_GLOBAL.ajaxurl,
                    dataType: 'json',
                    data: {action: 'wiloke_post_counter', post_type: WILOKE_GLOBAL.post_type, post_id: WILOKE_GLOBAL.postID, security: WILOKE_GLOBAL.wiloke_nonce},
                    beforeSend : function()    {
                        if($xhr != null) {
                            $xhr.abort();
                        }
                    },
                    success: function (data) {
                    }
                });
            }
        }
    }

    function blogCreative() {
        $('.wil-blog-creative').append('<div class="wil-blog-creative__background-wrap"></div>');

        $('.wil-blog-creative__effect').mousemove(function(e) {
            var $el = $(this),
                y = e.pageY - $el.offset().top,
                height = $el.outerHeight(),
                res = (y / (height)) * 100;
            if ($('.wil-blog-creative__effect-inner', $el).outerHeight() > height) {
                $('.wil-blog-creative__effect-inner').css({
                    'top': res + '%',
                    'transform': 'translateY(-' + res + '%)'
                });
            }
        });

        $('.wil-blog-creative .post').each(function() {
            var $el = $(this),
                imageSrc = $('.post__media img', $el).attr('src');
            $('.wil-blog-creative')
                .find('.wil-blog-creative__background-wrap')
                .append('<div class="wil-blog-creative__background" style="background-image: url(' + imageSrc + ')"></div>');

            $('.wil-blog-creative .post:first-child, .wil-blog-creative__background:first-child').addClass('active');
            $el.mousemove(function() {
                var index = $(this).index();
                $('.wil-blog-creative .post').removeClass('active');
                $('.wil-blog-creative__background').removeClass('active');
                $(this).addClass('active');
                $('.wil-blog-creative__background:eq(' + index + ')').addClass('active');
            });
        });
    }

    function teamSocialHover() {
        $('.wil-social').append('<span class="wil-social__bg"></span>');
        $('.wil-social a').hover(function() {
            var item = $(this),
                bg = item.siblings('.wil-social__bg'),
                itemPosition = item.position();
            bg.css({
                'top': itemPosition.top + 'px',
                'left': itemPosition.left + 'px'
            });

        });
    }

    function swiper() {
        var anim;
        if (isMobile.any()) {
            anim = 'slide';
        } else {
            anim = 'fade';
        }
        if ($('.wil-swiper').length) {
            $('.wil-swiper').each(function () {
                if ( $(this).data('initialized') ) {
                    return;
                }
                var $this = $(this),
                    params = {
                        pagination: '.wil-swiper__pagination',
                        paginationClickable: true,
                        nextButton: '.wil-swiper__button-next',
                        prevButton: '.wil-swiper__button-prev',
                        spaceBetween: 0,
                        autoHeight: $this.data('swiper-height')==false,
                        loop: true,
                        speed: 400,
                        slidesPerView: $this.data('swiper-slidesperview'),
                        effect: $this.data('swiper-slidesperview') > 1 ? 'slide' : anim
                    };

                if ( $this.data('swiper-width') ) {
                    params.width = $this.data('swiper-width');
                }

                if ( $this.data('swiper-height') ) {
                    params.height = $this.data('swiper-height');
                }

                $this.swiper(params);
                $(this).data('initialized', true);
            });
        }
    }

    function menuHover() {
        $('.wil-header').nextAll().addClass('wil-ef');
        $('.wil-header__divider, .wil-work-detail__media').addClass('wil-ef');
        $('.wil-box-toggle').removeClass('wil-ef');
        $('.wil-toggle-search, .wil-minicart, .menu-item', '.wil-navigation').hover(function() {
            $('.wil-header').nextAll().addClass('wil-opacity');
            $('.wil-header__divider, .wil-work-detail__media').addClass('wil-opacity');
            $('.wil-box-toggle').removeClass('wil-opacity');
        }, function() {
            $('.wil-header').nextAll().removeClass('wil-opacity');
            $('.wil-header__divider, .wil-work-detail__media').removeClass('wil-opacity');
        });

        $('#wil-menu-mobile .menu-item-has-children > a').on('click', function(e) {
            var el = $(this);
            e.preventDefault();
            el.toggleClass('active');
            el.next().slideToggle(300);
        });
    }

    function menuResponsive() {
        var breakpoint = $('.wil-header').data('breakpoint'),
            windowWidth = window.innerWidth,
            menuMobileHeight = $('#wil-menu-mobile').height(),
            textBoxHeight = $('#wil-menu-mobile .wil-text-box-wrapper').height();
        if (windowWidth <= breakpoint || isMobile.any()) {
            $('.wil-header').addClass('wil-header--responsive');
            $('.wil-header').nextAll().removeClass('wil-ef');
            $('.wil-header__divider').removeClass('wil-ef');
        } else {
            $('.wil-header').removeClass('wil-header--responsive');
            $('.wil-header').nextAll().addClass('wil-ef');
            $('.wil-header__divider').addClass('wil-ef');
        }
        $('#wil-menu-mobile .wil-tb').height(menuMobileHeight - textBoxHeight - 30);
    }

    function addAnimation($items, oAnimationData){
        $items.each(function() {
            if ( $(this).hasClass('wil-animation--disable-mobile') && isMobile.any() ) {
                $(this).removeClass('wil-animation visible');
            }

            var child, animType;

            if ( typeof oAnimationData === 'undefined' ){
                child    = $(this).data('animation-children');
                animType = $(this).data('animation-type');
            }else{
                child = oAnimationData['animationChildren'];
                animType = oAnimationData['animationType'];
            }

            var classAnim = animType ? 'wil-animation__item ' + animType : 'wil-animation__item';
            $(this).addClass('visible');

            if ($(this).hasClass('wil-animation--children')) {
                $(this).removeClass('.wil-animation');
                $(child, $(this)).addClass(classAnim);
            } else {
                $(this).addClass(classAnim);
            }
        });
    }

    function runAnimation($el, newItem) {
        var order = 0, lastOffsetTop = 0;
        $.each($el, function(index, value) {
            var el = $(this);
            el.imagesLoaded(function() {
                var elOffset = el.offset(),
                    windowHeight = $(window).outerHeight(),
                    delay = 0,
                    offset = 20;
                if ( elOffset.top > (windowHeight + offset) ) {
                    if ( order == 0 ) {
                        lastOffsetTop = elOffset.top;
                    } else {
                        if ( lastOffsetTop != elOffset.top ) {
                            order = 0;
                            lastOffsetTop = elOffset.top;
                        }
                    }
                    order++;
                    index = order;
                }
                delay = index * 0.12;

                el.css({
                    'transition-delay': delay + 's'
                });

                el.attr('data-delay', delay);

                el.attr('data-delay', delay);
            });
        });

        $el.appear(function () {
            var el = $(this),
                windowScrollTop = $(window).scrollTop();

            if ( newItem ) {
                el.addClass('loaded');
            } else {
                var addLoaded = setTimeout(function() {
                    el.addClass('loaded');
                }, 300);

                if (windowScrollTop > 100) {
                    clearTimeout(addLoaded);
                    el.addClass('loaded');
                }
            }

            var elDur = el.css('transition-duration'),
                elDelay = el.css('transition-delay'),
                timeRemove = elDur.split('s')[0] * 1000 + elDelay.split('s')[0] * 1000 + 4000,
                notRemove = '.wil-progress';

            el.not(notRemove).delay(timeRemove).queue(function(){
                el
                    .removeClass('loaded wil-animation__item wil-animation')
                    .dequeue();
            });
            el.delay(timeRemove).queue(function(){
                el.css('transition-delay', '');
            });

            wilProgress();

            if ($('.wil-odometer', el).length) {
                var od,
                    odometer = $('.wil-odometer', el),
                    from = odometer.data('from'),
                    to = odometer.data('to');
                od = new Odometer({
                    el: odometer[0],
                    value: from,
                    format: '',
                    duration: 300
                });
                od.update(to);
            }

            if ( el.parent().hasClass('wil-work-item') && isMobile.any() && !el.data('trigger-hovered') ){
                el.trigger('hover');
                el.data('trigger-hovered', true);
            }
        }, {accX: 0,accY: 30});
    }

    function device() {
        if (isMobile.any()) {
            $('.wil-wrapper, .wil-work-detail__media, .wil-work-detail-bg-content').addClass('device-small');
            $('.wil-work-nav-page__hover, .wil-work-nav-page__line').remove();
        } else {
            $('.wil-wrapper, .wil-work-detail__media, .wil-work-detail-bg-content').addClass('device-large');
        }
        if (isIE) {
            $('.wil-wrapper').addClass('ie');
        }
    }

    function scrollTop() {
        $('.wil-scroll-top').on('click', function() {
            $('html, body').stop().animate({
                scrollTop: 0
            }, 800, 'easeInOutExpo');
        });
    }

    function teamHover() {
        if (isMobile.any()) {
            $('.wil-team--1').on('click', function() {
                $('.wil-team--1')
                    .removeClass('active');
                $(this)
                    .addClass('active');
            });
        } else {
            $('.wil-team--1').hover(function() {
                $(this).addClass('active');
            }, function() {
                $(this).removeClass('active');
            });
        }
    }

    function pullRefesh() {
        if ( !$('.wil-refesh ~ .wil-wrapper').length ){
            $('.wil-wrapper').before('<span class="wil-refesh"></span>');
        }

        $(window).on('load scroll', function() {
            var scrollTop = $(window).scrollTop(),
                pullOffset = -110,
                rotate = (pullOffset - scrollTop) * 5,
                timeloader = $('.wil-preloader').length ? $('.wil-preloader').css('transition-duration').split('s')[0] * 1000 + 500 : 0;

            setTimeout(function() {
                $('.wil-refesh').css({
                    'left': '0',
                    'top': -pullOffset/2 + 'px'
                });
                $('body').css('background-color', '#f1f1f1');
            }, timeloader);

            $('.wil-refesh').css({
                'transform': 'rotate(' + rotate + 'deg)'
            });

            if (scrollTop < pullOffset) {
                $('.wil-wrapper').css('margin-top', -pullOffset);
                $('.wil-refesh').addClass('spin');
                setTimeout(function() {
                    location.reload();
                }, 100);
            }
        });
    }

    function customTabLogin() {
        var loginWrap = $('#customer_login'),
            login = $('.login', loginWrap),
            reg = $('.register', loginWrap),
            loginText = login.prev('h2').text(),
            regText = reg.prev('h2').text();
        loginWrap.prepend('<ul class="custom-tab"><li data-click="login" class="custom-tab__item custom-tab__login active">' + loginText + '</li><li data-click="register" class="custom-tab__item custom-tab__reg">' + regText + '</li></ul>');
        login.addClass('active');
        $('.custom-tab__item').on('click', function() {
            var el = $(this),
                tagActive = el.data('click');
            $('.custom-tab__item').removeClass('active');
            el.addClass('active');
            $('form', loginWrap).removeClass('active');
            $('form.' + tagActive).addClass('active');
        });
    }

    // Give the shit away
    function wilokeRemoveShitaway() {
        if ( !$("#comments").children().length ){
            $("#comments").remove();
        }

        if ( $('.page-description .wpb_wrapper .woocommerce').children().length == '' ){
            $('.page-description').remove();
        }
    }

    function wilokeUpdateCard(status, amount) {
        var $cartContent = $("#wil-minicart-container #cart-mini-content"),
            $total = $cartContent.closest('.wil-minicart__inner').find('.wil-toggle-minicart__number-item:first'),
            countItems = parseInt($total.html(), 10);

        if ( status === 'plus' ){
            countItems = countItems + 1;
        }else{
            countItems = countItems - amount;
        }

        countItems = parseInt(countItems, 10);

        $total.html(countItems);
        $total.next().html(countItems);

        $total.parent().addClass('wil-toggle-minicart__number--anim').delay(1000).queue(function(){
            $total.parent()
                .removeClass('wil-toggle-minicart__number--anim')
                .dequeue();
        });
    }

    // Smart search
    function wilokeSearchSuggesstion() {
        if ( $("#wiloke-search-suggesstion").length && !$('#wiloke-search-suggesstion').children().length ){
            var $xhr = null;
            $xhr = $.ajax({
                type: 'POST',
                url: WILOKE_GLOBAL.ajaxurl,
                data: {action: 'wiloke_render_search_suggestion', security: WILOKE_GLOBAL.wiloke_nonce, expired: $("#wiloke-search-suggesstion").data('expired')},
                beforeSend : function()    {
                    if($xhr != null) {
                        $xhr.abort();
                    }
                },
                success: function (data) {
                    if ( !data.success ){
                        $("#wiloke-search-suggesstion").remove();
                    }

                    for ( var i in data.data ) {
                        $("#wiloke-search-suggesstion").append('<li><a href="'+data.data[i].link+'">'+data.data[i].title+'</a></li>');
                    }
                }
            });
        }
    }

    // Subscribe with Mailchimp
    function wilokeMailChimp(){
        $("#wiloke-ps-mailchimp").on('submit', function (event) {
            event.preventDefault();
            var $this   = $(this),
                $btnCtl = $("#wiloke-ps-btn"),
                btnName = $btnCtl.attr('value'),
                sending = $btnCtl.data('sending');
            $btnCtl.prop('disabled', true);

            if ( $this.data('is_ajax') ){
                return;
            }

            $btnCtl.data('is_ajax', true);
            $btnCtl.attr('value', sending);
            $this.find('#wiloke-ps-email').removeClass('wiloke-input-invalid');

            $.ajax({
                type: 'POST',
                url: WILOKE_GLOBAL.ajaxurl,
                data: {action: 'wiloke_subscribe', security: $btnCtl.data('security'), email: $("#wiloke-ps-email"
                ).val()},
                success: function (response) {
                    response = $.parseJSON(response);
                    if ( response.type == 'success' ) {
                        $this.find('.message-success-also-here').html('<p class="alert alert-success">'+response.msg+'</p>');
                        $this.find('.form-submit').remove();
                        $this.find('.error-response-here').addClass('hidden');
                    }else{
                        $this.find('#wiloke-ps-email').addClass('wiloke-input-invalid');
                        $this.find('.error-response-here').html(response.msg);
                        $btnCtl.data('is_ajax', false);
                        $btnCtl.attr('value', btnName);
                        $btnCtl.prop('disabled', false);
                    }
                }
            })
        })
    }

    // Responsive embed
    function responsiveEmbed() {
        var selectors = [
            'iframe[src*="player.vimeo.com"]',
            'iframe[src*="youtube.com"]',
            'iframe[src*="youtube-nocookie.com"]',
            'iframe[src*="kickstarter.com"][src*="video.html"]',
            'object',
            'embed'
        ];
        var $allVideos = $('body').find(selectors.join(','));
        $allVideos.each(function() {
            var vid = $(this),
                vidWidth = vid.outerWidth(),
                vidHeight = vid.outerHeight(),
                ratio = (vidHeight / vidWidth) * 100;
            $allVideos
                .addClass('embed-responsive-item');
            if (ratio == 75) {
                $allVideos
                    .wrap('<div class="embed-responsive embed-responsive-4by3"></div>');
            } else {
                $allVideos
                    .wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
            }
        });
    }

    function improvingPerformance(){
        if ( $('.wil-work-item__quickview').length ) {
            $('.wil-work-item__quickview').each(function () {
                var $this = $(this);
                if ( !$this.data('loaded') ){
                    $this.data('loaded', true);
                    $this.append('<div class="wiloke-magnific-performance hidden"><div class="swiper-slide"><a class="wiloke-link-inside-magnific" href="'+$this.data('link')+'"><img class="added" src="'+$this.attr('data-thumbnail')+'" alt="'+$this.data('title')+'" /></a></div></div>');
                }
            })
        }
    }

    function renderTotalCart() {
        var $total = $("#wil-minicart-container #cart-mini-content").closest('.wil-minicart__inner').find('.wil-toggle-minicart__number-item');
        if ( $total.length ){
            $.ajax({
                type: 'POST',
                url: WILOKE_GLOBAL.ajaxurl,
                data: {security: WILOKE_GLOBAL.wiloke_nonce, action: 'render_total_cart'},
                success: function (response) {
                    if ( response.success ){
                        $total.html(response.data.total);
                    }
                }
            })
        }
    }

    function styleGalleryInPost() {
        var wrap = '[data-style="circle"], [data-style="square"], [data-style="high"]';
        $('.gallery-icon', wrap).each(function() {
            var el = $(this), src = $('img', el).attr('src');
            el.css('background-image', 'url("' + src + '")');
        });
    }

    function smartDisplayImageOnSinglePage(reChecked) {
        var $target = $('.wil-work-detail__content');

        if ( $target.length ){
            var $imgs = typeof reChecked === 'undefined' ? $target.find('img') : $target.find('img.still-waiting-for-you:not(.ignore-this-image)');
            var oDateInstance = new Date(), needRecheck=false, ratio=null, parseImgW = 0;

            if ( $imgs.length ){
                $imgs.each(function () {
                    var addClass = 'image-at-'+oDateInstance.getTime(), $this = $(this);
                    $this.on('error', function () {
                        $this.addClass('ignore-this-image');
                    });

                    if ( !$this.prop('complete') ){
                        $this.attr('data-childclass', addClass);
                        if ( typeof $this.attr('srcset') !== 'undefined' ){
                            ratio = $this.attr('srcset').match(/-(20x[0-9]*\.jpg|png|jpeg|gif)/g);
                            if ( ratio !== null  ){
                                if ( !$this.prev().hasClass('wiloke-image__placeholder') )
                                {
                                    $this.before('<img src="'+$this.attr('src').replace(/\.jpg|\.png|\.jpeg|\.gif$/g, ratio[0])+'" class="wiloke-image__placeholder '+addClass+'" width="'+$(this).attr('width')+'" height="'+$this.attr('height')+'">');
                                }

                                needRecheck = true;
                            }else{
                                var img = $(this).attr('srcset').match(/(https?([^\s])*)\s?(?=20w)/g);
                                if ( img !== null ){
                                    img = img[0].trim();
                                    parseImgW = $(this).attr('sizes');
                                    parseImgW = parseImgW.match(/[0-9]*px$/);
                                    if ( !$this.prev().hasClass('wiloke-image__placeholder') )
                                    {
                                        $(this).before('<img src="'+img+'" class="'+$(this).attr('class')+' wiloke-image__placeholder '+addClass+'" width="'+parseImgW[0]+'">');
                                    }
                                    needRecheck = true;
                                }
                            }
                            $this.addClass('still-waiting-for-you hidden');
                        }
                    }else{
                        if ( $this.prev('.wiloke-image__placeholder').length ){
                            $this.prev().fadeOut(200, function(){
                                $this.removeClass('still-waiting-for-you hidden');
                            });
                        }else{
                            $this.removeClass('still-waiting-for-you hidden');
                        }
                    }
                });

                if ( needRecheck ){
                    setTimeout(function(){
                        smartDisplayImageOnSinglePage.call(this, true);
                    }, 1000);
                }
            }
        }
    }


    function main() {
        $('.wiloke-js-blog-wrapper.ajax, .wil-pagination.ajax').find('.page-numbers').attr('data-type', 'ajax');

        if ( $('.wil-masonry-wrapper').length ){
            $('.wil-masonry-wrapper').WilokeOZMasonry();
        }

        addAnimation($('.wil-animation'));
        addToCart();
        cartFlyWidthWp();
        numberIncrementer();
        blogCreative();
        btnShadow();
        firstLetterWork();
        boxToggle();
        teamSocialHover();
        addToCartShow();
        device();
        scrollTop();
        swiper();
        menuHover();
        teamHover();
        pullRefesh();
        customTabLogin();
        wilokeRemoveShitaway();
        wilokeMailChimp();
        wilokeLoadmorePost();
        responsiveEmbed();
        wilokeMap();
        styleGalleryInPost();
        smartDisplayImageOnSinglePage();

        if (isMobile.iOS()) {
            var h = window.innerHeight + 70;
            $('.wil-work-detail-bg-content').css('height', h + 'px');

            if ($('.wil-work-detail').hasClass('wil-work-detail--2col')) {
                var hh = $('.wil-work-detail__media-inner').parent().height() + 70;
                $('.wil-work-detail__media-inner').css('height', hh + 'px');
            } else {
                $('.wil-work-detail__media-inner').css('height', h + 'px');
            }
        }

        $('#wil-minicart-container #cart-mini-content').change(function() {
            wilokeUpdateCard('plus');
        });

        $('#wil-minicart-container #cart-mini-content').on('deductcart', function (event, amount) {
            wilokeUpdateCard('deduct', amount);
        });

        $('.wiloke-js-blog-wrapper').wilokePaginateAjaxLoading({}, blogCreative);

        var $wooCommerce = $('.wil-woocommerce-nav').parent();
        $wooCommerce.addClass('wiloke-products-wrapper');

        $wooCommerce.wilokePaginateAjaxLoading({
            callback: function($container){
                $container.find('.wil-masonry-wrapper').imagesLoaded(function(){
                    addToCart();
                    addToCartShow();
                    $(this).trigger('wiloke.masonry.hasNewItems');
                });
            },
            ajax_type: 'get',
            ajax_action: 'wiloke_woocommerce_loadmore',
            getBlock: 'children',
            identifier: '.wpb_wrapper',
            addMethod: 'replace'
        });

        $('.wil-work-detail__content p').each(function() {
            var p = $(this);
            if (p.html() == '&nbsp;')
                p.addClass('wil-nbsp')
        });

        $(window).on('resize', function() {
            menuResponsive();
            if ( $(window).outerWidth() > 1200 ){
                $('.wil-masonry-wrapper').each(function () {
                    var $this = $(this);
                    if ( !$this.hasClass('fixed-gap') ) {
                        $this.attr({
                            'data-horizontal': $this.attr('data-large-horizontal'),
                            'data-vertical': $this.attr('data-large-vertical')
                        });
                        $this.addClass('fixed-gap');
                    }
                })
            }
        });

        $(window).on('scroll', function() {
            minicartFixed();
        });

        if ( !$('.wil-work-detail__description').children().length ) {
            $('.wil-work-detail__description').remove();
        }

        $('.widget_shopping_cart').off('click', '.remove').on('click', '.remove', function (event) {
            event.preventDefault();
            var pageLink = $(event.currentTarget).attr('href');
            $.get(pageLink, function (data, textStatus, jqXHR) {});

            var productId = $(this).attr('data-product_id'),
                $allCartId = $('.widget_shopping_cart').find('.mini_cart_item a[data-product_id="'+productId+'"]:first').closest('.mini_cart_item'),
                $miniCart = $('#cart-mini-content').find('.mini_cart_item a[data-product_id="'+productId+'"]:first').closest('.mini_cart_item'),
                qtity = $miniCart.find('.quantity').text(),
                parseQtity = qtity.split('×'),
                amount = parseInt(parseQtity[0], 10),
                price = parseQtity[1].replace(/([^0-9.]*)/gi, ''),
                total = price*amount,
                currentPrice = $('#cart-mini-content .total .woocommerce-Price-amount').text(),
                symbol = $('#cart-mini-content .woocommerce-Price-currencySymbol').html();
            currentPrice = currentPrice.replace(/([^0-9.]*)/gi, '');
            currentPrice = currentPrice - total;

            $allCartId.slideUp('slow', function () {
                $allCartId.remove();
                $("#wil-minicart-container #cart-mini-content").trigger('deductcart', [amount]);
                if ( currentPrice == 0 ){
                    $('.widget_shopping_cart_content').empty().html('<ul class="cart_list product_list_widget "><li>'+WILOKE_GLOBAL.woocommerce.cartEmpty+'</li></ul>');
                }else{
                    $('.widget_shopping_cart .total .woocommerce-Price-amount').html('<span class="woocommerce-Price-currencySymbol">'+symbol+'</span>'+currentPrice);
                }
            });

            if ( $('.shop_table_responsive.woocommerce-cart-form__contents').length ){
                $('.shop_table_responsive.woocommerce-cart-form__contents').find('a[data-product_id="'+productId+'"]').closest('.cart_item.woocommerce-cart-form__cart-item').slideUp('slow', function(){
                    $(this).remove();
                });
            }
        });

        $('#wil-shop-loadmore .wiloke-loadmore').off('click').on('click', function (event) {
            event.preventDefault();

            var $target = $(this).closest('#wil-shop-loadmore');
            $target.addClass('loading');
            $target.next().find('.page-numbers.current').next().trigger('click');
        });

        $('body').find('.woocommerce-tabs').trigger('init');

        renderTotalCart();
        menuResponsive();
    }

    function preloader() {
        $('.wil-preloader').addClass('done');
        if (!isMobile.any()) {
            if ($('.wil-preloader').length) {
                $('.wil-preloader').attr('data-transition', 'fadeOut');
                $('#wil-wrapper').attr('data-transition', 'fadeIn');

                setTimeout(function() {
                    $('#wil-wrapper').attr('data-transition', '');
                }, 1000);

                var notHrefDefault = '[target="_blank"], [href^="#"], [href^="javascript"], .comment-edit-link, .comment-reply-link, .product-remove .remove, #cart-mini-content .remove, #cancel-comment-reply-link, #wiloke-portfolio-popup .wil-btn',
                    timeload = $('.wil-preloader').length ? $('.wil-preloader').css('transition-duration').split('s')[0] * 1000 - 400 : 0,
                    notHref = '[href="work-ajax-popup.html"], [data-type="ajax"], .ajax_add_to_cart, [class*="jg-entry"], [class*="magnific"], [class*="pretty-photo"], [class*="popup"]';

                $(document).off('click', '#wil-wrapper a:not(' + notHrefDefault + ', ' + notHref + ')').on('click', '#wil-wrapper a:not(' + notHrefDefault + ', ' + notHref + ')', function(e) {
                    e.preventDefault();
                    var link = $(this);
                    $('#wil-wrapper').attr('data-transition', 'fadeOut');

                    $('.wil-preloader').attr('data-transition', 'fadeIn');

                    $('body').css('background-color', 'transparent');
                    $('.wil-refesh').hide();
                    setTimeout(function() {
                        window.location.href = link.attr("href");
                    }, timeload);
                });
            }
        }
    }

    function lazyLoad(){
        $('.lazy').lazyload({
            effect : 'fadeIn',
            threshold : 200
        });
    }

    function callAfterWindowLoaded(){
        preloader();
        workDetail();
        imgCover();
        removeEmptyPadding();
        runAnimation($('.wil-animation__item'), false);
        wilokePostCounter();
        improvingPerformance();
        wilokeSearchSuggesstion();
        ozLoadPage.init();
        lazyLoad();
    }


    var ajaxHandling = false;
    window.ozLoadPage = {
        init: function () {
            var self = this;
            if ( WILOKE_GLOBAL.toggleAjaxFeature === 'disable' ){
                return;
            }

            var currentPage = window.location.href;
            if ( (typeof WILOKE_GLOBAL.siteCache[currentPage] === 'undefined') && !self.notCachingThesePages(currentPage) ) {
                WILOKE_GLOBAL.siteCache[window.location.href] = '<html>'+$('html').html()+'</html>';
            }

            this.isAjaxLoading = false;
            this.ajaxMachine = '';
            this.isIsertPage = false;
            this.isImportantLoading = false;
            this.focusLink = '';
            this.currentLink = '';
            this.$body = $('body');
            this.forLandingPage();
            this.listOfTargets = '#wiloke-body-area a:not(.comment-reply-link, .comment-edit-link, .add_to_cart_button.product_type_variable)';
            this.mouseEvents();
            this.comment();
            this.searchForm();
            this.scanLink();
            this.aCurrentScript = typeof this.aCurrentScript != 'undefined' ? this.aCurrentScript : this.getScripts();

            self.listOfAutomaticLoading();

            $(document.body).off('wiloke.renderedpage').on('wiloke.renderedpage', function(){
                self.processEnqueueLoading();

                $('#wil-wrapper').find('[data-ride="vc_carousel"]').each(function() {
                   var $carousel = $(this);
                   $carousel.removeData('vc.carousel');
                   // console.log($carousel.data(''));
                   $carousel.carousel($carousel.data()).Constructor;
                });


                $(document).trigger("ready.vc.accordion");
            })
        },
        enqueueLoading: function(pageLink){
            if ( !WILOKE_GLOBAL.siteHandling.length || (WILOKE_GLOBAL.siteHandling.indexOf(pageLink) === -1) )
            {
                WILOKE_GLOBAL.siteHandling.push(pageLink);
            }
        },
        forLandingPage: function(){
            var self = this;
            if ( $('.page-id-1466.page-template-pagebuilder').length > 0 ){
                $('#menu-ozmenu').find('#menu-item-1001 a').each(function(){
                    self.loadPage($(this).attr('href'), $(this));
                })
            }
        },
        getScripts: function () {
            var self = this,
                aCurrentScript = [];

            $.each(document.scripts, function (index, script) {
                if ( typeof $(script).attr('src') != 'undefined' ){
                    aCurrentScript.push(encodeURI(self.replaceAllProtocal($(script).attr('src'))));
                }else{
                    aCurrentScript.push(encodeURI(script.innerHTML.replace(/"/g, "'")));
                }
            });

            return aCurrentScript;
        },
        listOfAutomaticLoading: function(){
            var self = this;
            if ( $('.wil-masonry-wrapper').length ){
                $('.wil-masonry-wrapper').find('.wil-work-item__link').each(function () {
                    var pageLink = $(this).attr('href');
                    if ( pageLink != WILOKE_GLOBAL.homeurl && self.passConditional($(this), pageLink) && self.verifyLink(pageLink) ){
                        self.enqueueLoading(pageLink);
                    }
                })
            }
        },
        processEnqueueLoading: function(){
            if ( WILOKE_GLOBAL.siteHandling.length ){
                this.loadPage(WILOKE_GLOBAL.siteHandling[0], $('a[href="'+WILOKE_GLOBAL.siteHandling[0]+'"]'));
            }
        },
        mouseEvents: function () {
            var t = this, pageLink = '';
            if ( !isMobile.any() ){
                $(document).off('mouseenter', t.listOfTargets).on('mouseenter', t.listOfTargets, function (event) {
                    var pageLink = $(event.currentTarget).attr('href');
                    if ( !t.verifyLink(pageLink) || !t.passConditional($(event.currentTarget), pageLink) || t.notCachingThesePages(pageLink) )
                    {
                        return;
                    }

                    $(this).attr('data-type', 'ajax');

                    if ( $(this).data('ajax') ) {
                        return;
                    }

                    t.loadPage(pageLink, $(this));
                });

                //  $('.grid-item').off('mouseenter').on('mouseenter', function (event) { 
                //     if ( $('body').hasClass('wiloke-ajax-has-yet-finished') || $(document).data('wiloke-handling-click-event') ) {
                //         return;
                //     }
                //
                //     pageLink = $(this).find('.wil-work-item__link').attr('href');
                //
                //     if ( typeof pageLink == 'undefined' ){
                //         pageLink = $(this).find('.wiloke-product-link').attr('href');
                //     }
                //
                //     if ( typeof pageLink === 'undefined' || $(this).data('justhovered') || !t.passConditional($(event.currentTarget), pageLink) || !t.verifyLink(pageLink) ) {
                //         return;
                //     }
                //
                //     t.loadPage(pageLink, $(this));
                // }, function () {});
            }

            $(document).off('click', t.listOfTargets).on('click', t.listOfTargets, function (event) {
                var pageLink = $(event.currentTarget).attr('href');

                if ( !t.verifyLink(pageLink) || !t.passConditional($(event.currentTarget), pageLink) ){
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                if (pageLink == window.location.href){
                    return;
                }

                if ( $(event.currentTarget).hasClass('wiloke-link-inside-magnific') ) {
                    $.magnificPopup.close();
                }

                t.insertPage(pageLink, $(this));
            });

            $('#wil-minicart-container').on('click', '.button.wc-forward', function (event) {
                var pageLink = $(event.currentTarget).attr('href');

                if ( !t.verifyLink(pageLink) || !t.passConditional($(event.currentTarget), pageLink) ){
                    return;
                }

                event.preventDefault();

                if (pageLink == window.location.href){
                    return;
                }

                t.insertPage(pageLink, $(this));
            });

            $('#wiloke-search-suggesstion').on('click', 'a', function (event) {
                var pageLink = $(event.currentTarget).attr('href');
                if ( !t.verifyLink(pageLink) || !t.passConditional($(event.currentTarget), pageLink) ){
                    return;
                }

                $('html, body').removeClass('overflow-hidden');
                event.preventDefault();
                event.stopPropagation();

                if (pageLink == window.location.href){
                    return;
                }

                if ( $(event.currentTarget).hasClass('wiloke-link-inside-magnific') ) {
                    $.magnificPopup.close();
                }

                t.insertPage(pageLink, $(this));
            });

            window.onpopstate = function(event) {
                pageLink = document.location.href;

                if ( pageLink.search('#tab-') !== -1 || pageLink.search('#reviews') !== -1 || t.notCachingThesePages(pageLink) ){
                    return;
                }

                t.insertPage(pageLink, $('a[href="'+pageLink+'"]'));
            };
        },
        passConditional: function (t, pageLink) {
            if (
                ( t.hasClass('add_to_cart_button') && !t.hasClass('product_type_variable') )
                || t.hasClass('wil-work-item__quickview')
                || ( t.closest('.wil-work-item__quickview').length )
                ||  (t.closest('.add_to_cart_button').length && !t.closest('.product_type_variable'))
                || ( t.hasClass('page-numbers') && ( (pageLink.search('/shop/') === false) || ( pageLink.search('/shop/') !== false && t.closest('.wil-woocommerce-nav.ajax').length ) || t.parent().hasClass('not-reloadpage') ))
                || t.hasClass('nav') || (t.attr('href') === '_blank')
                || t.hasClass('checkout-button')
                || t.hasClass('woocommerce-MyAccount-navigation-link--customer-logout')
                || pageLink.search('logout') !== -1
                || t.parent().attr('id') === 'wpadminbar'
                || t.hasClass('ab-item')
                || t.hasClass('remove')
                || t.hasClass('checkout-button')
                || (t.attr('href') === '_blank')
            ) {
                return false;
            }

            return true;
        },
        verifyLink: function(pageLink){
            if ( typeof pageLink === 'undefined' ){
                return false;
            }

            var patt = new RegExp(/\.jpg|add-to-cart=|\.png|\.gif|\.jpeg|\.mp4|\.mp3|\.ogg|\.svg|(\.[0-9A-Za-z])$/gi),
                result = patt.exec(pageLink);
            if(result === null &&  (pageLink.search(WILOKE_GLOBAL.homeurl) !== -1) && (pageLink.search('wp-login.php') === -1) && (pageLink.search('#') !== 0)){
                return true;
            }

            return false;
        },
        notCachingThesePages: function (pageLink, data) {

            if( (pageLink.search('\/cart\/$') !== -1) || (pageLink.search('\/checkout\/$') !== -1) ){
                return true;
            }else{
                return false;
            }
        },
        insertPage: function (pageLink, t) {
            if ( typeof WILOKE_GLOBAL.siteCache[pageLink] === 'undefined' ){
                if ( !this.passConditional(t, pageLink) || !this.verifyLink(pageLink) )
                {
                    return false;
                }
            }

            this.isImportantLoading = true;
            this.preloader(pageLink);

            $(document).data('wiloke-handling-click-event', true);

            this.pageLink = pageLink;

            if ( typeof WILOKE_GLOBAL.siteCache[pageLink] !== 'undefined' && !this.notCachingThesePages(pageLink) ) {
                this.pageData = WILOKE_GLOBAL.siteCache[pageLink];
                this.renderPage();
                $(document).data('wiloke-handling-click-event', false);
            }else{
                this.$body.addClass('wiloke-ajax-has-yet-finished');
                this.isIsertPage = pageLink;
                this.loadPage(pageLink, t, true);
            }
        },
        loadPage: function (pageLink, t, isFocus) {
            var self = this;

            if ( (typeof WILOKE_GLOBAL.siteCache[pageLink] !== 'undefined') || !self.verifyLink(pageLink) ) {
                return false;
            }

            if ( self.isImportantLoading ){
                if ( self.ajaxMachine !== '' ){
                    self.ajaxMachine.abort();
                }

                if ( self.pageLink !== pageLink ){
                    self.enqueueLoading(pageLink);
                    return false;
                }else{
                    self.isAjaxLoading = false;
                }
            }

            if ( self.isAjaxLoading ) {
                self.enqueueLoading(pageLink);
                return false;
            }

            self.currentLink = pageLink;
            self.isAjaxLoading = true;

            self.ajaxMachine = $.ajax({
                method: 'GET',
                url: pageLink,
                dataType: 'text',
                cache: true,
                global: !1,
                success: function (data, status) {
                    if ( !self.notCachingThesePages(pageLink, data) ){
                        WILOKE_GLOBAL.siteCache[pageLink] = data;
                    }

                    $(document).data('wiloke-handling-click-event', false);
                    if ( self.isImportantLoading ){
                        self.pageData = data;
                        self.renderPage();
                        self.$body.removeClass('wiloke-ajax-has-yet-finished');
                    }else{
                        self.isAjaxLoading = false;
                        if ( !isMobile.any() ){
                            setTimeout(function () {
                                self.imagePreloading(data);
                            }, 100);
                        }

                        var checkPassed = WILOKE_GLOBAL.siteHandling.indexOf(pageLink);
                        if ( checkPassed >  -1 ){
                            WILOKE_GLOBAL.siteHandling.splice(checkPassed, 1);
                        }
                    }
                },
                error: function () {

                }
            })
        },
        replaceAllProtocal: function (url) {
            if ( url == '' ){
                return '';
            }
            url = url.replace(/https?:/,'');
            var getStringBeforeQuestion = url.match(/[^?]*/);

            if ( getStringBeforeQuestion === null ){
                return url;
            }else{
                return getStringBeforeQuestion[0];
            }
        },
        renderPage: function () {
            var $oldMasonry = this.$body.find('.wil_masonry');
            if ( $oldMasonry.length && $oldMasonry.data('isotope') ){
                $oldMasonry.isotope('destroy');
            }
            var innerScript = '',
                self = this,
                oPageData = this.pageData,
                head = oPageData.match(/<head[^>]*>([^<]*(?:(?!<\/?head)<[^<]*)*)<\/head\s*>/i);
            $('head').find('meta, title, link:not([id]), style, script').remove();
            $(head[0]).each(function () {
                if ( 'LINK' !== $(this)[0].nodeName || ( 'LINK' === $(this)[0].nodeName && ( ('undefined' === typeof $(this).attr("id")) || ( ('undefined' !== typeof $(this).attr("id")) && typeof $(this)[0].href != 'undefined' && !$('#wiloke-head').find('link[href="'+$(this)[0].href+'"]').length))) ) {
                    if ( 'SCRIPT' === $(this)[0].nodeName ){
                        if ( $(this)[0].src.search('googleapis.com') === false ){
                            var withoutProtocalSrc = self.replaceAllProtocal($(this)[0].src);
                            if ($(this)[0].src != ''){
                                if (
                                    !$('#wiloke-head').find('script[src="'+$(this)[0].src+'"]').length
                                    && !$('#wiloke-head').find('script[src="'+withoutProtocalSrc+'"]').length
                                    && ($.inArray(withoutProtocalSrc, self.aCurrentScript) == -1)
                                ){
                                    $('#wiloke-head').prepend($(this));
                                }
                            }else if(
                                ($.inArray(encodeURI($(this)[0].innerHTML), self.aCurrentScript) == -1)
                            ){
                                $('#wiloke-head').prepend($(this));
                            }
                        }
                    }else if ('LINK' === $(this)[0].nodeName){
                        $('#wiloke-head').prepend($(this));
                    }else{
                        $('#wiloke-head').append($(this));
                    }
                }
            });

            var body = oPageData.match(/<body[^>]*>([^<]*(?:(?!<\/?body)<[^<]*)*)<\/body\s*>/i),
                bodyCssClass = oPageData.match(/<body(?:(?!class).*)class=(?:"|')([^\"]*)/i),
                aScripts = body[0].match(/<script[^>]*>([^<]*(?:(?!<\/?script)<[^<]*)*)<\/script\s*>?/gmi),
                bodyStyle = oPageData.match(/<body(?:(?!script).*)script=(?:"|')(?:(?!style).*)style=(?:"|')([^\"]*)/i);

            bodyCssClass = bodyCssClass !== null && typeof bodyCssClass[1] != 'undefined' ? bodyCssClass[1] : '';
            bodyStyle = bodyStyle != null && typeof bodyStyle[1] != 'undefined' ? bodyStyle[1] : '';

            var $rawBody = $(body[1]), wilokeBodyAreaStyle, $adminBar, $body, maxLength = $rawBody.length;

            for ( var nodeIndex = 0; nodeIndex <= maxLength; nodeIndex++ ){
                if ( $rawBody[nodeIndex]['id'] === 'wiloke-body-area' ){
                    wilokeBodyAreaStyle = $($rawBody[nodeIndex]).attr('style');

                    $body = typeof $rawBody[nodeIndex].childNodes[1] !== 'undefined' ? $rawBody[nodeIndex].childNodes[1].innerHTML : $rawBody[nodeIndex].childNodes[0].innerHTML;
                    break;
                }
            }

            if ( this.$body.find('#wpadminbar').length ){
                if ( maxLength == 1 ){
                    $adminBar = $($rawBody[0]).find('#wpadminbar').html();
                }else{
                    for ( nodeIndex = maxLength - 1; nodeIndex >= 2; nodeIndex-- ){
                        if ( (typeof $rawBody[nodeIndex]['id'] != 'undefined') && ($rawBody[nodeIndex]['id'] === 'wpadminbar') ){
                            $adminBar = $rawBody[nodeIndex].innerHTML;
                            break;
                        }
                    }
                }
                if ( typeof $adminBar !== 'undefined' ){
                    document.getElementById('wpadminbar').innerHTML = $adminBar;
                }
            }

            var sp1 = document.createElement('div');
            sp1.id = 'wiloke-where-replace';
            sp1.innerHTML = $body;

            var sp2 = document.getElementById('wiloke-where-replace'),
                parentDiv = sp2.parentNode;
            parentDiv.replaceChild(sp1, sp2);
            $(window).scrollTop(0);

            $('.wiloke-loadmore').removeData();
            $('.wiloke-infinite-scroll-wrapper').removeData();
            $('.wil_masonry').removeData();
            $(document.body).removeData();
            $('#wiloke-body-area').attr('style', wilokeBodyAreaStyle);
            var maxScripts = aScripts.length, currentScript = '', currentSrc = null, withoutProtocalSrc = '';
            if ( maxScripts > 0 ){
                for ( var indexOfScript = 0; indexOfScript < maxScripts; indexOfScript++  ){
                    var isCartFlag=false;
                    if ( typeof $(aScripts[indexOfScript]).attr('src') !== 'undefined' ){
                        currentSrc = $(aScripts[indexOfScript]).attr('src');
                        withoutProtocalSrc = self.replaceAllProtocal(currentSrc);
                        currentScript = encodeURI(withoutProtocalSrc);
                        if ( currentSrc.search('cart-fragments') !== -1 ){
                            isCartFlag = true;
                        }
                    }else{
                        currentScript = typeof $(aScripts[indexOfScript])[0].innerHTML != 'undefined' ? encodeURI($(aScripts[indexOfScript])[0].innerHTML) : encodeURI(aScripts[indexOfScript]);
                        currentSrc = null;
                    }

                    if ( isCartFlag ){
                        this.$body.find('script[src="'+currentSrc+'"]').remove();
                        this.$body.append(aScripts[indexOfScript]);
                    }else {
                        if (
                            ($.inArray(currentScript, self.aCurrentScript) == -1)
                            && (currentScript.match(/\/\*WilokeCustomJS\*\//g) === null)
                            && ((currentSrc == null) || (currentSrc != null && !$('body').find('script[src="' + currentSrc + '"]').length && !this.$body.find('script[src="' + withoutProtocalSrc + '"]').length))
                            // && (currentScript.search('revolution') !== -1)
                        ) {
                            this.$body.append(aScripts[indexOfScript]);
                            self.aCurrentScript.push(currentScript);
                        }
                    }
                }
            }
            $(document.body).off('added_to_cart');
            $(document.body).off('adding_to_cart');
            this.$body.attr({
                'class': bodyCssClass,
                'style': bodyStyle
            });

            this.pageLink !== window.location.href && (
                window.blockhashchange = !0,
                    history.pushState(null, '', this.pageLink)
            );

            $('.wpb_animate_when_almost_visible').addClass('wpb_start_animation animated');
            $('.wil-animation__item').removeClass('done');
            $(document).data('wiloke-handling-click-event', false);

            if ( self.isIsertPage ){
                $(document.body).data('wiloke.singlepage.notloadfromcache', true);
                self.isIsertPage = false;
            }

            $('.woocommerce-message').remove();

            main();

            var $masonry = this.$body.find('.wil_masonry');
            if ( $masonry.length ){
                $(window).trigger('resize');
                $masonry.one('layoutComplete', function () {
                    callAfterWindowLoaded();
                })
            }else{
                callAfterWindowLoaded();
                $(window).trigger('resize');
            }

            self.isImportantLoading = false;
            self.isAjaxLoading = false;
            $(document.body).trigger('wiloke.renderedpage');
        },
        comment: function () {
            $('#commentform').find('.ajax-response').html();
            $('#commentform').on('submit', function (event) {
                event.preventDefault();
                var $this = $(this),
                    postTo = $this.attr('action'),
                    $submit = $this.find('#submit'),
                    $commentList = $('#comments').find('.commentlist'),
                    currentBtnName = $submit.val(),
                    $ajaxResponse = $this.find('.form-submit .ajax-response'),
                    oData = $this.serializeArray();
                $ajaxResponse.empty();

                $this.find('#submit').val(WILOKE_GLOBAL.comment.status.submit);
                $this.find('.required-field').each(function(){
                    if ( $(this).val() === '' && $(this).text() === ''  ){
                        $ajaxResponse.html('<p class="alert alert-error">'+WILOKE_GLOBAL.comment.error.empty+'</p>');
                        return;
                    }
                });

                $.post(postTo, oData, function (data, status, xhr) {
                    if ( data.search('id="error-page"') !== -1 ){
                        $ajaxResponse.html('<p class="alert alert-error">'+WILOKE_GLOBAL.comment.error.empty+'</p>');
                    }else{
                        data = $('<div>'+data+'</div>');
                        data.children().addClass('wiloke-comment-blink');
                        data = data.html();

                        if ( $('#commentform .comment-form-rating').length ){
                            var rated = $('#commentform .comment-form-rating .stars.selected .active').html();
                            rated = (parseInt(rated)/5)*100;
                            var markupRating = '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating"><span style="width:'+rated+'%"><strong itemprop="ratingValue"></strong></span></div>';
                            data = $(data);
                            data.find('.comment-body').prepend(markupRating);
                        }

                        if ( $('#comment_parent').val() == 0 ){
                            $commentList.append(data);
                        }else{
                            var $appendTo = $this.closest('#respond').next();
                            if ( $appendTo.hasClass('children') ){
                                $appendTo.append(data);
                            }else{
                                $this.closest('.comment').append('<ul class="children">'+data+'</ul>');
                            }
                            $('#cancel-comment-reply-link').trigger('click');
                        }
                    }
                    $this.find('#comment').val('');
                }, 'html').fail(function(data) {
                    if ( data.statusText == 'Conflict' ){
                        $ajaxResponse.html('<p class="alert alert-error">'+WILOKE_GLOBAL.comment.error.conflict+'</p>');
                    }
                }).complete(function(){
                    $submit.val(currentBtnName);
                });
            });
        },
        preloader: function (pageLink) {
            $('.wil-preloader').removeClass('done');
            $('.wil-preloader').attr('data-transition', 'fadeIn');
            $('#wil-wrapper').attr('data-transition', 'fadeOut');
        },
        imagePreloading: function (pageData) {
            var pattern = new RegExp(/(?:<img(?:[^>]*src="|'))([^"']*(?:jpg|png))|(?:background-image:\s?url\()([^\)]*(?:jpg|png))/g), match;
            while (match = pattern.exec(pageData)) {
                if ( typeof match[1] == 'undefined' ){
                    (new Image()).src = match[2];
                }else{
                    (new Image()).src = match[1];
                }

            }
        },
        searchForm: function () {
            var self = this;
            $('.wiloke-search-form').off('submit').on('submit', function (event) {
                event.preventDefault();
                var $keywordForm = $(this).find('[type="search"]'),
                    keyword = $keywordForm.val();
                if ( keyword == '' ){
                    $keywordForm.addClass('wiloke-input-invalid');
                }else{
                    var url = $(this).attr('action') + '?s='+keyword;
                    url = encodeURI(url);
                    self.insertPage(url, $(this));
                    $('html, body').removeClass('overflow-hidden');
                }
            })
        },
        scanLink: function () {
            var self = this;
            setTimeout(function () {
                var $nextPage = $('.wil-work-nav-page__next'),
                    $prevPage = $('.wil-work-nav-page__prev');
                if ( $nextPage.length ){
                    self.loadPage($nextPage.attr('href'), $nextPage);
                    self.loadPage($prevPage.attr('href'), $prevPage);
                }
            }, 5000);
        }
    };

    $(document).ready(function () {
        main();
    });

    $(window).on('load', function() {
        callAfterWindowLoaded();
    });

    menuSticky();
    function menuSticky() {

      if( $('.wil-header').length && $('.wil-header').hasClass('wil-header--sticky') ) {

          var height = $('.wil-header').innerHeight();

          $('.wil-header').on('menusticky', function(event) {
              var self = $(this),
                  windowWidth = $(window).innerWidth(),
                  scrollTop = $(window).scrollTop();

              if( !self.hasClass('wil-header--sticked') ) {
                  height = self.innerHeight();
              }

              if(scrollTop > height) {

                  if( !self.hasClass('wil-header--absolute') ) {

                    if( $('body').find('#wil-header-fix-sticky').length ) {
                        $('body').find('#wil-header-fix-sticky').height(height);
                    } else {
                        var sticky = $('<div id="wil-header-fix-sticky"></div>').height(height);
                        self.after(sticky);
                    }
                  }

                  self.addClass('wil-header--sticked');

                  if( $('.wil-section-top .wil-minicart').length ) {

                    if( self.hasClass('wil-header--responsive') ) {

                        if(windowWidth > 1540) {
                            self.find('.wil-navigation').css('padding-right', '85px');
                        } else {
                            self.find('.wil-navigation').css('padding-right', '70px');
                        }

                    } else {
                        self.find('.wil-navigation').css('padding-right', '80px');
                    }

                    $('.wil-section-top .wil-minicart').css('z-index', '9999999');
                  }

                  setTimeout(function() {
                    self.addClass('sticky');
                  }, 300);

              } else {
                  $('body').find('#wil-header-fix-sticky').remove();

                  self.removeClass('wil-header--sticked sticky');

                  if( $('.wil-section-top .wil-minicart').length ) {
                    self.find('.wil-navigation').css('padding-right', '');
                    $('.wil-section-top .wil-minicart').css('z-index', '');
                  }
              }

          });

          $(window).on('scroll', function(event) {
              $('.wil-header').trigger('menusticky');
          });
        }
    }

    headerHover();
    function headerHover() {

        $('.wil-header .menu-item, .wil-header .wil-toggle-search, .wil-header .wil-minicart').hover(function() {

            var self = $(this),
                $parent = self.closest('.wil-header');

                if($parent.hasClass('wil-theme-dark')) {

                    $('#wiloke-body-area').css({
                        'background-color': '#000',
                        'transition': 'background-color 0.3s linear'
                    });
                }

                if($parent.hasClass('wil-theme-light')) {
                    $('#wiloke-body-area').css({
                        'background-color': '#fff',
                        'transition': 'background-color 0.3s linear'
                    });
                }

                if($parent.hasClass('wil-header--sticked') && $('.wil-section-top').length) {
                    $('.wil-section-top').css('opacity', 1);
                    $('.wil-section-top').find('.wil-breadcrumb').addClass('wil-opacity');
                }

        }, function() {
            var self = $(this),
                $parent = self.closest('.wil-header');

            $('#wiloke-body-area').css('background-color', '');

            if($parent.hasClass('wil-header--sticked') && $('.wil-section-top').length) {
                $('.wil-section-top').css('opacity', '');
                $('.wil-section-top').find('.wil-breadcrumb').removeClass('wil-opacity');
            }
        });
    }


})(jQuery);
