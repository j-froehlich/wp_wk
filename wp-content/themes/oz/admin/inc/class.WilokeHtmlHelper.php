<?php
/**
 * WilokeHtmlHelper Class
 *
 * @category Helper
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

if ( !defined('ABSPATH') )
{
    exit;
}

class WilokeHtmlHelper
{

    /**
     * Media Upload Field
     * @param: The id of target
     */
    public static function wiloke_render_media_field($aInput)
    {
        if (  isset($aInput['is_multiple']) && $aInput['is_multiple'] )
        {
            $isMultiple = 'true';
        }else{
            $isMultiple = 'false';
        }

        if (  !isset($aInput['return'])  )
        {
            $aInput['return'] = 'id';
        }

        if ( $aInput['return'] == 'id' )
        {
            $inputType = 'hidden';
        }else{
            $inputType = 'text';
        }

        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html($aInput['name']); ?></strong></label>
            </th>

            <td>
                <div class="wiloke-image-field-id">
                    <div class="image-show">
                    <ul class="list-wiloke-image-media">
                        <?php
                        if ( isset($aInput['value']) && !empty($aInput['value']) )
                        {
                            $thumbnail = wp_get_attachment_image_src($aInput['value'], 'thumbnail');
                            echo sprintf('<li class="wiloke-image" data-id="%1s"><img src="%2s"/><div class="wiloke-control-wrap"><i class="wiloke-edit dashicons dashicons-edit"></i><i class="wiloke-close dashicons dashicons-no-alt"></i></div></li>', esc_attr($aInput['value']), esc_url($thumbnail[0]));
                        }
                        ?>
                    </ul>
                </div>
                    <div class="wiloke-button-media" data-parent=".wiloke-image-field-id > .image-show" data-multiple="<?php echo esc_attr($isMultiple); ?>" data-return="<?php echo esc_attr($aInput['return']); ?>">
                        <input class="wiloke-media-value" type="<?php echo esc_attr($inputType); ?>" name="<?php echo esc_attr($aInput['id']); ?>" value="<?php echo esc_attr($aInput['value']); ?>" />
                        <a href="#" class="button wiloke-button button-primary"><?php esc_html_e('Select Image', 'oz'); ?></a>
                        <?php if ( isset($aInput['description']) ) : ?>
                            <p class="description"><?php Wiloke::wiloke_kses_simple_html($aInput['description']); ?></p>
                        <?php endif; ?>
                    </div>
            </div>
            </td>
        </tr>
        <?php
    }

    public static function wiloke_render_text_field($aInput)
    {
        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html($aInput['name']); ?></strong></label>
            </th>

            <td>
                <input class="wiloke-text-field" type="text" name="<?php echo esc_attr($aInput['id']); ?>" value="<?php echo esc_attr($aInput['value']); ?>" />
                <?php if ( isset($aInput['description']) ) : ?>
                    <p class="description"><?php Wiloke::wiloke_kses_simple_html($aInput['description']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
    
    public static function wiloke_render_textarea_field($aInput)
    {

        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html($aInput['name']); ?></strong></label>
            </th>

            <td>
                <textarea name="<?php echo esc_attr($aInput['id']); ?>" cols="30" rows="10"><?php echo esc_textarea($aInput['value']); ?></textarea>
                <?php if ( isset($aInput['description']) ) : ?>
                    <p class="description"><?php Wiloke::wiloke_kses_simple_html($aInput['description']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    public static function wiloke_render_checkbox_field($aInput)
    {
        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <strong><?php echo esc_html($aInput['name']); ?></strong>
            </th>

            <td>
                <?php
                $i = 0;
                foreach (  $aInput['options'] as $key => $val ) :
                    $i++;
                    if ( isset($aInput['value']) && !empty($aInput['value']) )
                    {
                        $checked = in_array($key, $aInput['value']) ? 'checked' : '';
                    }else{
                        $checked = '';
                    }

                    if ( !isset($aInput['check_supports']) || ( isset($aInput['check_supports']) && post_type_supports($key, $aInput['check_supports']) ) ) :
                ?>
                <label>
                    <input class="wiloke-checkbox-field" type="checkbox" name="<?php echo esc_attr($aInput['id']); ?>[]" value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($checked); ?> />
                    <?php echo esc_html($val); ?>
                </label>
                <br />
                <?php endif; endforeach; ?>

                <?php if ( isset($aInput['description']) ) : ?>
                    <p class="description"><?php Wiloke::wiloke_kses_simple_html($aInput['description']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    public static function wiloke_render_select_field($aInput)
    {

        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html($aInput['name']); ?></strong></label>
            </th>

            <td>
                <select class="wiloke-select-field" name="<?php echo esc_attr($aInput['id']); ?>">
                    <?php
                        foreach ( $aInput['options'] as $option => $name ) :
                    ?>
                        <option value="<?php echo esc_attr($option) ?>" <?php selected($option, $aInput['value']) ?>><?php echo esc_html($name); ?></option>
                    <?php endforeach; ?>
                </select>

                <?php if ( isset($aInput['description']) ) : ?>
                    <p class="description"><?php Wiloke::wiloke_kses_simple_html($aInput['description']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    public static function wiloke_render_colorpicker_field($aInput)
    {
        if ( !isset($aInput['value']) || empty($aInput['value']) )
        {
            $addClassEmpty = 'wiloke-start-empty';
        }else{
            $addClassEmpty = '';
        }
        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html($aInput['name']); ?></strong></label>
            </th>

            <td>
                <input class="wiloke-text-field wiloke-colorpicker <?php echo esc_attr($addClassEmpty); ?>" type="text" name="<?php echo esc_attr($aInput['id']); ?>" value="<?php echo esc_attr($aInput['value']); ?>" />
                <?php if ( isset($aInput['description']) ) : ?>
                    <p class="description"><?php Wiloke::wiloke_kses_simple_html($aInput['description']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    public static function wiloke_render_description_field($aInput)
    {
        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html($aInput['name']); ?></strong></label>
            </th>

            <td>
                <?php if ( isset($aInput['description']) ) : ?>
                    <p class="description"><?php Wiloke::wiloke_kses_simple_html($aInput['description']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    public static function wiloke_render_heading_field($aInput)
    {
        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html($aInput['name']); ?></strong></label>
                <hr>
            </th>
        </tr>
        <?php
    }

    public static function wiloke_render_submit_field()
    {
        ?>
        <tr class="wiloke-format-field form-field">
            <th scope="row">
                <label><strong><?php echo esc_html('Save', 'oz'); ?></strong></label>
            </th>
            <td>
                <input type="submit" class="button button-primary" value="<?php esc_html_e('Submit', 'oz'); ?>" />
            </td>
        </tr>
        <?php
    }
}