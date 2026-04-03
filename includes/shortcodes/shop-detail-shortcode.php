<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get current shop_id from query string.
 */
function wps_get_requested_shop_id()
{
    return isset($_GET['shop_id']) ? absint($_GET['shop_id']) : 0;
}

/**
 * Get requested shop object or null.
 */
function wps_get_requested_shop()
{
    $shop_id = wps_get_requested_shop_id();

    if ($shop_id <= 0 || !class_exists('WPS_DB')) {
        return null;
    }

    return WPS_DB::get_shop($shop_id);
}

/**
 * Detect if current page contains [shop_detail].
 */
function wps_is_shop_detail_page()
{
    if (is_admin() || !is_singular()) {
        return false;
    }

    global $post;
    if (!$post || !isset($post->post_content)) {
        return false;
    }

    return has_shortcode($post->post_content, 'shop_detail');
}

/**
 * Handle proper 404 when [shop_detail] is used with invalid/missing shop_id.
 */
function wps_handle_shop_detail_404()
{
    if (!wps_is_shop_detail_page()) {
        return;
    }

    $shop = wps_get_requested_shop();
    if ($shop) {
        return;
    }

    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    nocache_headers();

    $template_404 = get_query_template('404');
    if ($template_404) {
        include $template_404;
    } else {
        wp_die(esc_html__('Boutique introuvable.', 'wp-plugin-shops'), '', array('response' => 404));
    }

    exit;
}

function wps_display_shop_detail()
{
    $shop = wps_get_requested_shop();

    if (!$shop) {
        return '';
    }

    ob_start();
?>
    <div class="wps-shop-detail">
        <h1 class="wps-shop-detail-title"><?php echo esc_html($shop->name); ?></h1>

        <?php if (!empty($shop->image_url)): ?>
            <p class="wps-shop-detail-image">
                <img src="<?php echo esc_url($shop->image_url); ?>" alt="<?php echo esc_attr($shop->name); ?>">
            </p>
        <?php elseif (!empty($shop->logo_url)): ?>
            <p class="wps-shop-detail-image">
                <img src="<?php echo esc_url($shop->logo_url); ?>" alt="<?php echo esc_attr($shop->name); ?>">
            </p>
        <?php endif; ?>

        <ul class="wps-shop-detail-meta">
            <?php if (!empty($shop->number)): ?>
                <li><strong><?php esc_html_e('Numéro :', 'wp-plugin-shops'); ?></strong> <?php echo esc_html($shop->number); ?></li>
            <?php endif; ?>

            <?php if (!empty($shop->floor)): ?>
                <li><strong><?php esc_html_e('Étage :', 'wp-plugin-shops'); ?></strong> <?php echo esc_html($shop->floor); ?></li>
            <?php endif; ?>

            <?php if (!empty($shop->phone)): ?>
                <li><strong><?php esc_html_e('Téléphone :', 'wp-plugin-shops'); ?></strong> <?php echo esc_html($shop->phone); ?></li>
            <?php endif; ?>

            <?php if (!empty($shop->email)): ?>
                <li>
                    <strong><?php esc_html_e('Email :', 'wp-plugin-shops'); ?></strong>
                    <a href="mailto:<?php echo esc_attr(sanitize_email($shop->email)); ?>">
                        <?php echo esc_html(sanitize_email($shop->email)); ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (!empty($shop->whatsapp)): ?>
                <li><strong><?php esc_html_e('WhatsApp :', 'wp-plugin-shops'); ?></strong> <?php echo esc_html($shop->whatsapp); ?></li>
            <?php endif; ?>

            <?php if (!empty($shop->plan_url)): ?>
                <li>
                    <strong><?php esc_html_e('Plan :', 'wp-plugin-shops'); ?></strong>
                    <a href="<?php echo esc_url($shop->plan_url); ?>" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('Voir le plan', 'wp-plugin-shops'); ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <?php if (!empty($shop->description)): ?>
            <div class="wps-shop-detail-description">
                <?php echo wp_kses_post(wpautop($shop->description)); ?>
            </div>
        <?php endif; ?>
    </div>
<?php

    return ob_get_clean();
}

add_action('template_redirect', 'wps_handle_shop_detail_404');
add_shortcode('shop_detail', 'wps_display_shop_detail');
