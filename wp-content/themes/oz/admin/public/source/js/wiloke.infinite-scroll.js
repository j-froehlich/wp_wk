/**
 * This is a part of Wiloke's Themes
 *
 * @website http://wiloke.com
 * @copyright Wiloke
 * @since 1.0
 */
;(function($, window, document, undefined){
    "use strict";
    
    function Wiloke(element, options){
        this.$element = $(element);

        this.oSettings = $.extend(true, {}, Wiloke.oDefaults, options);
        // Ajax Options
        this.ajaxOptions = this.oSettings.oAjaxOptions;

        // Exclude these posts
        this._post__not_in = '';

        // We have 2 cases: Get single term or all terms
        this._isSingleTerm = false;

        // We are working on this term
        this._term = null;

        // We are working on this term
        this._currentPage = 1;

        // Ajax storage
        this._xhr = null;

        // In view
        this._inView = null;

        this.initialize();
    }

    Wiloke.oDefaults = {
        oAjaxOptions: {
            action              : 'wiloke_loadmore_portfolio',
            totalAjaxLoaded     : 0,
            security            : '',
            term_ids            : null,
            totalPostsOfTerm    : 0,
            post__not_in        : null,
            number_of_loaded    : 10,
            post_type           : 'post',
            max_posts           : 0,
            additional          : null
        },

        // Item class. Sometimes, Loadmore button doesn't contain the number of posts have been loaded at the first time, We need this class combine with length jquery function to detect that.
        itemCssClass: '.item',

        //Button Class. Using in infinite Scroll feature, this button will automatically fired when time come
        btnClass: '.wiloke-btn-infinite-scroll',

        // Preloader class. An preloader will be shown while loading
        progressingClass: '.wiloke-btn-infinite-scroll',

        // Using Infinite Scroll feature or just loadmore button
        isInfiniteScroll: true,

        // Container class
        containerClass:  '.wiloke-infinite-scroll-wrapper',

        // Toggle Debug
        is_debug: false,

        // Navigation Wrapper
        navFilterWrapperCssClass: '.wiloke-nav-filter',

        // Navigation Filter
        navFiltersCssClass: '.wiloke-nav-filter li',

        // Where do you want to append content that has been got from the server
        appendTo: '.wiloke-items-store',

        windowWidth: $(window).outerWidth(),

        // Mouse Direction
        direction_enter: 'down',
        direction_entered: '',
        direction_exit: '',
        direction_exited: ''
    };

    Wiloke.prototype.beforeAppend = function ($items) {
        return $($items);
    };

    // If you are not using Isotope and Masonry either, You can put your handle append here.
    Wiloke.prototype.handleAppendItems = function ($renderItems) {}

    Wiloke.prototype.afterAppended = function () {};
    Wiloke.prototype.beforeAppendToIsotope = function () {};
    Wiloke.prototype.afterAppendedIsotope = function () {};

    Wiloke.prototype.setup = function () {
        this.$container = this.$element.closest(this.oSettings.containerClass);
        this.$currentFilter = this.$container.find(this.oSettings.currentFilterCssClass);
        this.$items = this.$container.find(this.oSettings.itemCssClass);
        this.$processing = $(this.oSettings.progressingClass);
        this.$navFilter = $(this.oSettings.navFiltersCssClass);
        this.$appendTo = this.$container.find(this.oSettings.appendTo);
    };

    Wiloke.updateArgs = {
        ajaxOptions: function (event, data) {
            this.ajaxOptions = $.extend(this.ajaxOptions, data);
        }
    };

    Wiloke.prototype.parseNumberOfItemsLoaded = function () {
        var filter = this.$currentFilter.data('filter');

        if ( typeof filter === 'undefined' ){
            filter = this.$currentFilter.children().data('filter');
        }

        if ( filter != '*' )
        {
            return this.$container.find(filter).length;
        }else{
            return this.$items.length;
        }
    };
    
    Wiloke.prototype.toggleProcessing = function (cssClass) {
        this.$processing.toggleClass(cssClass);
    };

    Wiloke.prototype.parseAjaxData = function () {
        this.ajaxOptions.security = this.$element.data('nonce');
        this.ajaxOptions.max_posts = this.$element.data('max_posts');
        this.ajaxOptions.number_of_loaded = this.parseNumberOfItemsLoaded();
        this.ajaxOptions.windowWidth = $(window).width();
        if ( this.ajaxOptions.post__not_in === null ){
            this.ajaxOptions.post__not_in = this.$element.data('postids');
            this._post__not_in = this.ajaxOptions.post__not_in;
        }

        this.ajaxOptions.term_ids = this.ajaxOptions.term_ids === null ? this.$element.data('terms') : this.ajaxOptions.term_ids;
    };

    Wiloke.prototype.whileAppendItems = function ($items, response) {
        var $renderItems = this.beforeAppend($items);

        if ( $().isotope )
        {
            this.$appendTo.append($renderItems);
            this.beforeAppendToIsotope();
            
            $renderItems.imagesLoaded(function(){
                if ( this.$appendTo.data('isotope') ){
                    // this.$container.trigger('wiloke.masonry.hasNewItems');
                    if ( this._isSingleTerm ){
                        this.$appendTo.isotope('appended', $renderItems).isotope('layout');
                        this.$appendTo.trigger('layoutComplete');
                        this.$appendTo.parent().data('wiloke.masonry.triggerresizewhenall', true);
                    }else{
                        this.$appendTo.isotope('appended', $renderItems).isotope('layout').isotope({'sortBy': 'original-order'});
                    }
                }else{
                    this.handleAppendItems($renderItems);
                }

                this.afterAppendedIsotope();
            }.bind(this));
        }else{
            this.$appendTo.append($renderItems).masonry('appended', $renderItems);
            this.$appendTo.masonry('reloadItems').masonry({sortBy: 'original-order'});
        }

        // Callback function after our content have been appended
        this.afterAppended();

        this.$element.data('is-ajax', false);

        if ( response.data.finished == 'yes' ) {
            if ( this._isSingleTerm ){
                this.$processing.fadeOut('slow', function () {
                    $(this).remove();
                });
                return;
            }else{
                this.$element.addClass('hidden');
            }
        }

        if ( !this.$element.hasClass('mixed-loadmore-and-infinite-scroll') ){
            this.toggleProcessing('loading loaded');
            this.$element.removeClass('visibility-hidden');
        }else{
            this.$element.removeClass('loaded');
            this.toggleProcessing('loading');
        }

        this.$element.prop('disabled', false);
    };

    Wiloke.prototype.imageLoaded = function ($items, response) {
        var self = this, totalItem = $items.length;
        totalItem = parseInt(totalItem, 10) - 1;

        for ( var count = 0; count <= totalItem; count++ ){
            var tempImg = new Image();
            tempImg.src = $('img', $items[count]).attr('src');

            if ( count === totalItem ) {
                tempImg.onload = function () {
                    self.whileAppendItems($items, response);
                };

                tempImg.error = function () {
                    self.whileAppendItems($items, response);
                };
            }
        }
    };

    Wiloke.prototype.ajaxHandle = function () {
        var self = this;
        if ( this._xhr && this._xhr.readyState !== 4 ) {
            return;
        }

        this._xhr = $.ajax({
            method: 'POST',
            url: WILOKE_GLOBAL.ajaxurl,
            cache : true,
            data: self.ajaxOptions,
            success: function (response, status, xhr)
            {
                if ( self.oSettings.isInfiniteScroll ){
                    if (self._inView != null) {
                        self._inView.destroy();
                    }
                }

                var postIDs = xhr.getResponseHeader('Wiloke-PostsNotIn');

                // Updating Post Not In
                self.ajaxOptions.post__not_in = self._post__not_in + ',' + postIDs;

                if ( !response.success )
                {
                    self.toggleProcessing('loading');

                    if ( self._isSingleTerm ) {
                        self.$element.data('is-ajax-' + self._term, true);
                    }else{
                        self.$element.remove();
                        return;
                    }

                    self.$element.prop('disabled', false);
                }else{
                    if ( self._isSingleTerm )
                    {
                        self.$currentFilter.data('is_loaded', true);
                        self.$element.data('is-ajax-'+self._term, true);
                        self.toggleProcessing('loaded');
                    }

                    self.$element.data('is-ajax', false);

                    if ( response.data == '' || !response.data )
                    {
                        self.$element.remove();
                        self.toggleProcessing('loaded');
                        return;
                    }
                }

                self._currentPage = response.data.next_page;
                self.ajaxOptions.totalAjaxLoaded += length;
                self.ajaxOptions.number_of_loaded += response.data.data.item.length;

                self.imageLoaded.call(self, response.data.data.item, response);
            }
        })
    }

    Wiloke.prototype.loadMoreEvent = function () {
        this.$element.on('click', $.proxy( function(event){
            event.preventDefault();
            var $this = $(event.currentTarget);
            $this.addClass('visibility-hidden');
            
            if ( $this.hasClass('is_now_allow') ){
                return false;
            }

            if ( (this._xhr && this._xhr.readyState !== 4) || $this.data('is-ajax') === true) {
                this.$processing.removeClass('loading');
                this.$processing.addClass('loaded');
                return false;
            }

            this.$processing.addClass('loading');
            this.$processing.removeClass('loaded');

            if (!this.oSettings.is_debug) {
                $this.data('is-ajax', true);
                $this.prop('disabled', true);
            }

            if ( $this.hasClass('mixed-loadmore-and-infinite-scroll') ){
                this.oSettings.isInfiniteScroll = true;
            }

            $(document.body).trigger('wiloke.infinite_scroll.ajax_running');
        }, this));
    };

    Wiloke.prototype.navigationEvent = function () {
        this.$navFilter.on('click', $.proxy(function(event){
            var $this = $(event.currentTarget),
                filterClass = $this.data('filter');
            this.ajaxOptions.term_ids = $this.data('termid');
            this.ajaxOptions.totalPostsOfTerm = $this.data('total');

            if (  typeof filterClass == 'undefined' )
            {
                filterClass = $this.children().data('filter');
            }

            // Loaded all posts of this term
            if ( $this.data('is-loaded') )
            {
                this.$processing.removeClass('loading');
                this.$element.prop('disabled', true);
            }else{
                this.$element.prop('disabled', false);
            }
            // if there are no posts of the term, We will trigger load

            if ( this.$appendTo.find(filterClass).length < 1 )
            {
                this.$element.trigger('click');

                if ( this.$element.data('only-one-time') == 'yes' )
                {
                    $this.data('is-loaded', true);
                }
            }

            if ( filterClass == '*' )
            {
                this._isSingleTerm = false;
                if ( this.$items.length == this.max_posts )
                {
                    this.$element.remove();
                }else{
                    this.$element.removeClass('hidden');
                }
            }else{
                this._isSingleTerm = true;
                if ( this.$container.find(filterClass).length >= this.ajaxOptions.totalPostsOfTerm ){
                    this.$element.addClass('hidden');
                }else{
                    this.$element.removeClass('hidden');
                }
            }
        }, this));
    };

    Wiloke.prototype.triggerInfiniteScroll = function () {
        var self = this;

        self._inView = new Waypoint.Inview({
            element: self.$element[0],
            enter: function (direction) {
                if ( self.oSettings.direction_enter == direction )
                {
                    self.$element.trigger('click');
                }
            },
            entered: function (direction) {
            },
            exit: function (direction) {
                self.$element.trigger('click');
            },
            exited: function (direction) {
            }
        });
    };

    Wiloke.prototype.destroy = function () {
        $(document.body).off('wiloke.infinite_scroll.ajax_options');
        $(document.body).off('wiloke.infinite_scroll.ajax_running');
        this.$element.off('click');
        delete this.oSettings;
        delete this.ajaxOptions;
        delete this._post__not_in;
        delete this._isSingleTerm;
        delete this._term;
        delete this._currentPage;
        delete this._xhr;
        delete this._inView;
    };

    Wiloke.prototype.subscribe = function () {
        $(document.body).on('wiloke.infinite_scroll.ajax_options', $.proxy(function () {
            this.updateArgs.ajaxOptions;
        },this));

        $(document.body).on('wiloke.infinite_scroll.ajax_running', $.proxy(function () {
            this.ajaxHandle();
        }, this));
    };

    $.fn.WilokeInfiniteScroll = function (options) {
        // var args = Array.prototype.slice.call(arguments, 1);
        return this.each(function () {
            var $this = $(this),
                data = $this.data('wiloke.infinite_scroll');
            if ( !data ){
                data = new Wiloke(this, options);
                $this.data('wiloke.infinite_scroll', data);
            }
        });
    };

    $.fn.WilokeInfiniteScroll.Constructor = Wiloke;

    Wiloke.prototype.initialize = function () {
        this.setup();
        this.parseAjaxData();
        this.subscribe();
        this.loadMoreEvent();
        this.navigationEvent();
        this.$element.removeData();
    };

})(jQuery, window, document);