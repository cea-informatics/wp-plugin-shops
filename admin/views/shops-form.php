<?php
if (!defined('ABSPATH')) exit;

$is_edit = !empty($shop);
$title = $is_edit ? __('Edit Shop', 'wp-plugin-shops') : __('Add New Shop', 'wp-plugin-shops');

$name = $is_edit ? esc_attr($shop->name) : '';
$description = $is_edit ? esc_textarea($shop->description) : '';
$floor = $is_edit ? esc_attr($shop->floor) : '';
$whatsapp = $is_edit ? esc_attr($shop->whatsapp) : '';
$email = $is_edit ? esc_attr($shop->email) : '';
$phone = $is_edit ? esc_attr($shop->phone) : '';
$image_url = $is_edit ? esc_url($shop->image_url) : '';
$plan_url = $is_edit ? esc_url($shop->plan_url) : '';
$active = $is_edit ? (bool)$shop->active : true;
?>
<div class="wrap">
    <h1><?php echo esc_html($title); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('wps_save_shop', 'wps_nonce'); ?>
        
        <?php if ($is_edit): ?>
            <input type="hidden" name="shop_id" value="<?php echo absint($shop->id); ?>">
        <?php endif; ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="name"><?php esc_html_e('Shop Name', 'wp-plugin-shops'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="<?php echo $name; ?>" 
                               class="regular-text" 
                               required>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="description"><?php esc_html_e('Description', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <textarea id="description" 
                                  name="description" 
                                  rows="5" 
                                  class="large-text"><?php echo $description; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="floor"><?php esc_html_e('Floor', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="floor" 
                               name="floor" 
                               value="<?php echo $floor; ?>" 
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="whatsapp"><?php esc_html_e('WhatsApp', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="whatsapp" 
                               name="whatsapp" 
                               value="<?php echo $whatsapp; ?>" 
                               class="regular-text"
                               placeholder="+33 6 12 34 56 78">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="email"><?php esc_html_e('Email', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo $email; ?>" 
                               class="regular-text"
                               placeholder="contact@shop.com">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="phone"><?php esc_html_e('Phone', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="<?php echo $phone; ?>" 
                               class="regular-text"
                               placeholder="+33 1 23 45 67 89">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="image_url"><?php esc_html_e('Shop Image', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <div class="wps-media-upload">
                            <div class="wps-media-upload-header">
                                <input type="url" 
                                    id="image_url" 
                                    name="image_url" 
                                    value="<?php echo $image_url; ?>" 
                                    class="regular-text wps-media-input">
                                <button type="button" class="button wps-media-upload-btn" data-target="image_url">
                                    <?php esc_html_e('Upload Image', 'wp-plugin-shops'); ?>
                                </button>
                            </div>
                            <?php if (!empty($image_url)): ?>
                                <div class="wps-image-preview" id="image_url_preview">
                                    <img src="<?php echo $image_url; ?>" style="max-width: 200px; margin-top: 10px; border-radius: 4px;">
                                </div>
                            <?php else: ?>
                                <div class="wps-image-preview" id="image_url_preview" style="display: none;"></div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="plan_url"><?php esc_html_e('Floor Plan Image', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <div class="wps-media-upload">
                            <div class="wps-media-upload-header">
                                <input type="url" 
                                    id="plan_url" 
                                    name="plan_url" 
                                    value="<?php echo $plan_url; ?>" 
                                    class="regular-text wps-media-input">
                                <button type="button" class="button wps-media-upload-btn" data-target="plan_url">
                                    <?php esc_html_e('Upload Plan', 'wp-plugin-shops'); ?>
                                </button>
                            </div>
                            <?php if (!empty($plan_url)): ?>
                                <div class="wps-image-preview" id="plan_url_preview">
                                    <img src="<?php echo $plan_url; ?>" style="max-width: 200px; margin-top: 10px; border-radius: 4px;">
                                </div>
                            <?php else: ?>
                                <div class="wps-image-preview" id="plan_url_preview" style="display: none;"></div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="active"><?php esc_html_e('Status', 'wp-plugin-shops'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   id="active" 
                                   name="active" 
                                   value="1" 
                                   <?php checked($active, true); ?>>
                            <?php esc_html_e('Active (visible on the website)', 'wp-plugin-shops'); ?>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" 
                   name="submit" 
                   class="button button-primary" 
                   value="<?php echo $is_edit ? esc_attr__('Update Shop', 'wp-plugin-shops') : esc_attr__('Create Shop', 'wp-plugin-shops'); ?>">
            <a href="<?php echo esc_url(admin_url('admin.php?page=wps-shops')); ?>" class="button">
                <?php esc_html_e('Cancel', 'wp-plugin-shops'); ?>
            </a>
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('.wps-media-upload-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetId = button.data('target');
        var input = $('#' + targetId);
        var preview = $('#' + targetId + '_preview');
        
        var mediaUploader = wp.media({
            title: '<?php esc_html_e('Select or Upload Image', 'wp-plugin-shops'); ?>',
            button: {
                text: '<?php esc_html_e('Use this image', 'wp-plugin-shops'); ?>'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
            preview.html('<img src="' + attachment.url + '" style="max-width: 200px; margin-top: 10px; border-radius: 4px;">').show();
        });
        
        mediaUploader.open();
    });
    
    // Clear preview if URL is manually cleared
    $('.wps-media-input').on('change', function() {
        var input = $(this);
        var preview = $('#' + input.attr('id') + '_preview');
        
        if (input.val() === '') {
            preview.hide();
        }
    });
});
</script>
