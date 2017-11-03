;(function($){
    "use strict";

    if ( !$().wilokeEditPortfolio )
    {
        $.fn.wilokeEditPortfolio = function()
        {
            $(this).magnificPopup({
                type: 'inline',
                preloader: false,
                focus: '#wiloke-portfolio-settings',
                modal: true,
                callbacks: {
                    elementParse: function(item) {
                        var $el = $(item.el),
                            oInfo   = $(item.el).attr('data-info');

                        if ( oInfo != '' )
                        {
                            if ( !oInfo.wiloke_portfolio_size )
                            {
                                oInfo = $.parseJSON(oInfo);
                            }

                            if ( oInfo.wiloke_portfolio_size )
                            {
                                $("#wiloke-portfolio-size option").each(function()
                                {
                                    if ( $(this).attr("value") == oInfo.wiloke_portfolio_size )
                                    {
                                        $(this).prop("selected", true);
                                    }
                                })
                            }

                            if ( oInfo.wiloke_portfolio_customsize )
                            {
                                $("#wiloke-portfolio-customsize").val(oInfo.wiloke_portfolio_customsize);
                            }
                        }else{
                            $("#wiloke-portfolio-size option").first().prop("selected", true);
                            $("#wiloke-portfolio-customsize").val('');
                        }

                        $("#wiloke-save-portfolio-settings").unbind('click').on("click", function (event)
                        {
                            event.preventDefault();

                            var portfolio_size        = $("#wiloke-portfolio-size").val(),
                                portfolio_customsize  = $("#wiloke-portfolio-customsize").val(),
                                jsonData              = {
                                    wiloke_portfolio_size: portfolio_size,
                                    wiloke_portfolio_customsize: portfolio_customsize,
                                    id: $(item.el).data('portfolio_id'),
                                    page_id: WILOKE_GLOBAL.postID
                                },
                                $this                = $(this);

                            jsonData = JSON.stringify(jsonData);

                            $this.prop('disabled', true);
                            $this.html('Progressing');

                            $.ajax({
                                url: WILOKE_GLOBAL.ajaxurl,
                                type: "POST",
                                data: {action: 'wiloke_save_project_size', data: jsonData},
                                success: function (res) {
                                    var parseJson = $.parseJSON(res);

                                    if ( parseJson.status != '400'  )
                                    {
                                        $this.html('Saved');
                                        $el.attr("data-info", jsonData);

                                        if ( parseJson.style != 'project-3' )
                                        {
                                            $el.closest('.grid-item').find('.project-item .img a').html(parseJson.content);
                                        }else{
                                            $el.parent().removeClass('wide large');
                                            $el.parent().addClass(parseJson.content);
                                        }

                                        $(".popup-modal-dismiss").trigger("click");

                                        var $project = $el.closest('.project-isotope'),
                                            top      = $el.offset().top + 100;


                                        WILOKE_GLOBAL.isotope[$project.attr('id')].isotope('destroy');
                                        $project.isotope({
                                            layoutMode: 'packery',
                                            packery: {
                                                columnWidth: '.grid-size'
                                            },
                                            itemSelector: '.grid-item',
                                            percentPosition: true,
                                            stamp: '.stamp'
                                        });

                                        //$project.isotope( 'reloadItems' ).isotope({ sortBy: 'original-order' });

                                        window.scrollTo(0, top);

                                    }else{
                                        $("#wiloke-portfolio-settings .wiloke-alert").removeClass('hidden');
                                    }

                                    $this.html('Save');
                                    $this.prop('disabled', false);
                                }
                            })

                        });

                        $("#wiloke-portfolio-size").change(function(){
                            if ( $(this).val() == 'custom' )
                            {
                                $('#wiloke-portfolio-custom-style-wrapper').removeClass('hidden');
                            }else{
                                $('#wiloke-portfolio-custom-style-wrapper').addClass('hidden');
                            }
                        }).trigger('change');
                    }
                }
            });
        }
    }

    $('.wiloke-portfolio-edit').each(function(){
        $(this).wilokeEditPortfolio();
    });

    $(document).on('click', '.popup-modal-dismiss', function (e) {
        $("#wiloke-portfolio-settings .wiloke-alert").addClass("hidden");
        e.preventDefault();
        $.magnificPopup.close();
    });

    $(document).ready(function () {
        if ( typeof pagenow != 'undefined' && pagenow == 'portfolio' ){
            $('.postbox').sortable('disable');
        }
    })

})(jQuery);