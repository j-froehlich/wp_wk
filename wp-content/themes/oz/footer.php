<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WilokeThemes
 * @subpackage OZ
 * @since 1.0
 */
?>
                        <footer class="wil-footer">
                        <?php
                        /**
                         * Print Footer sections here
                         * @since 1.0
                         *
                         * @hooked render_before_footer_widget
                         * @hooked render_rest_of_footer
                         * @hooked render_popup_subscribe
                         */
                        do_action('wiloke/oz/footer_section');
                        ?>
                        </footer>
                </div>
            </div>
        </div>
</div><!-- END / wiloke-body-area-->
<?php wp_footer(); ?>
</body>
</html>
