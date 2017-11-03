<div id="comments">
    <?php if (post_password_required()) : ?>
    <p><?php esc_html_e( 'Post is password protected. Enter the password to view any comments.', 'oz' ); ?></p>
</div>
<?php return; endif; ?>
<?php if ( have_comments() ) : ?>
    <div class="comments-inner-wrap">
        <?php
        $numberOfComments = apply_filters('wiloke_comments_nummber', get_comments_number());

        if( $numberOfComments == 0 )
        {
            $numberOfComments = esc_html__('No Comment', 'oz');
        }elseif( $numberOfComments == 1 )
        {
            $numberOfComments = esc_html__('01 Comment', 'oz');
        }elseif($numberOfComments <= 10){
            $numberOfComments = '0' .$numberOfComments . esc_html__(' Comments', 'oz');
        }else{
            $numberOfComments = $numberOfComments . esc_html__(' Comments', 'oz');
        }
        ?>
        <h3 id="comments-title"><?php echo esc_html($numberOfComments); ?></h3>

        <ul class="commentlist">
            <?php
            wp_list_comments(
                array(
                    'callback' => array('WilokePublic', 'comment_template'),
                    'max_depth'=>3
                )
            );
            ?>
        </ul>

        <div class="comment-navigation">
            <div class="alignleft"><?php previous_comments_link() ?></div>
            <div class="alignright"><?php next_comments_link() ?></div>
        </div>
    </div>
<?php endif; ?>
<?php if ( !comments_open() ||  !post_type_supports( get_post_type(), 'comments' ) ) : ?>
    </div>
<?php else : ?>
    </div>
    <!-- LEAVER YOUR COMMENT -->
    <?php
    $commenter = wp_get_current_commenter();
    $commenter['comment_author'] = $commenter['comment_author'] == '' ? '': $commenter['comment_author'];
    $commenter['comment_author_email'] = $commenter['comment_author_email'] == '' ? '': $commenter['comment_author_email'];
    $commenter['comment_author_url'] = $commenter['comment_author_url'] == '' ? '': $commenter['comment_author_url'];

    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $sao      = ( $req ? " *" : '' );

    $fields = array(
        'author' => '<div class="form-item"><label for="author">'.esc_html__('Your name', 'oz').$sao.'</label><input type="text" id="author" class="required-field" name="author" tabindex="1" value="'.esc_attr($commenter['comment_author']).'" ' . $aria_req . ' /></div>',
        'email'  => '<div class="form-item"><label for="email">'.esc_html__('Your Email', 'oz').$sao.'</label><input type="text" id="email" class="required-field" name="email" value="'.esc_attr($commenter['comment_author_email']).'" ' . $aria_req . ' /></div>'
    );

    $comment_field = '<div class="form-item"><label for="message">'.esc_html__('Comment', 'oz').$sao.'</label><textarea id="comment" class="required-field" name="comment" rows="5" required></textarea></div>';

    $comment_args = array(
        'fields'                => $fields,
        'title_reply'           => esc_html__( 'LEAVE A COMMENT', 'oz' ),
        'comment_field'         => $comment_field,
        'comment_notes_after'   => '',
        'comment_notes_before'  => '',
        'class_submit'          => 'btn btn--1',
        'submit_field'          => '<div class="form-submit">%1$s %2$s <div class="ajax-response"></div><div id="temporary-render-text"></div></div>',
        'logged_in_as'          => '<div class="form-item form-login-logout">' . Wiloke::wiloke_kses_simple_html(sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'oz' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', esc_url(get_permalink( $post->ID ) ) ) ) ), true) . '</div>'
    );

    comment_form($comment_args);

    ?>

<?php endif; ?>
