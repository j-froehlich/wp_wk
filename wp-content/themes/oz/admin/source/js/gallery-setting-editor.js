/**
 * Gallery Settings Editor
 */
( function( window, wp, shortcode, $ ) {
    
    _.extend( wp.mce.View.prototype, {

        replaceMarkers: function() {

            this.getMarkers( function( editor, node ) {
                
                var $viewNode;

                if ( ! this.loader && $( node ).text() !== this.text ) {
                    editor.dom.setAttrib( node, 'data-wpview-marker', null );
                    return;
                }

                if(this.type == 'gallery') {
                    var spacing = this.shortcode.attrs.named.spacing ? this.shortcode.attrs.named.spacing : 'none',
                        style = this.shortcode.attrs.named.style ? this.shortcode.attrs.named.style : 'none';

                    $viewNode = editor.$(
                        '<div data-spacing="'+ spacing +'" data-style="'+ style +'" class="wpview wpview-wrap" data-wpview-text="' + this.encodedText + '" data-wpview-type="' + this.type + '" contenteditable="false"></div>'
                    );

                } else {
                    $viewNode = editor.$(
                        '<div class="wpview wpview-wrap" data-wpview-text="' + this.encodedText + '" data-wpview-type="' + this.type + '" contenteditable="false"></div>'
                    );
                }

                editor.$( node ).replaceWith( $viewNode );
                 
            } );
        },

        render: function( content, force ) {
            if ( content != null ) {
                this.content = content;
            }

            content = this.getContent();

            // If there's nothing to render an no loader needs to be shown, stop.
            if ( ! this.loader && ! content ) {
                return;
            }

            // We're about to rerender all views of this instance, so unbind rendered views.
            force && this.unbind();

            // Replace any left over markers.
            this.replaceMarkers();

            if ( content ) {

                this.setContent( content, function( editor, node ) {

                    $( node ).data( 'rendered', true );

                    if(this.type == 'gallery') {
                        var spacing = this.shortcode.attrs.named.spacing ? this.shortcode.attrs.named.spacing : 'none',
                            style = this.shortcode.attrs.named.style ? this.shortcode.attrs.named.style : 'none';
                        
                        $( node ).attr('data-spacing', spacing);
                        $( node ).attr('data-style', style);

                        if( style !== 'none' ) {
                            $('.gallery-icon', $( node )).each(function(index, el) {
                                var self = $(this),
                                    $img = self.find('img');
                                self.css({
                                    'background-image': 'url('+ $img.attr('src') +')'
                                });
                            });
                        }
                        
                    }

                    
                    this.bindNode.call( this, editor, node );
                }, force ? null : false );
            } else {
                this.setLoader();
            }
        },

    });

})( window, window.wp, window.wp.shortcode, window.jQuery );
