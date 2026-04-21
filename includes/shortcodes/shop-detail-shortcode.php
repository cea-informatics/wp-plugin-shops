<?php

if (!defined('ABSPATH')) {
    exit;
}

function wps_display_shop_detail()
{
    $shop = WPS_DB::get_shop($_GET['shop_id'] ?? 0);

    if (!$shop) {
        return '<p>' . esc_html__('Boutique non trouvée.', 'wp-plugin-shops') . '</p>';
    }

    ob_start();
?>
    <div class="wps-shop-detail">
        <div class="ct-container main">
            <?php if (!empty($shop->image_url)): ?>
                <div class="wps-shop-detail-image">
                    <img src="<?php echo esc_url($shop->image_url); ?>" alt="<?php echo esc_attr($shop->name); ?>">
                </div>
            <?php elseif (!empty($shop->logo_url)): ?>
                <div class="wps-shop-detail-image">
                    <img src="<?php echo esc_url($shop->logo_url); ?>" alt="<?php echo esc_attr($shop->name); ?>">
                </div>
            <?php endif; ?>
            <div class="wps-shop-detail-infos">
                <h1 class="wps-shop-detail-title"><?php echo esc_html($shop->name); ?></h1>
                <?php if (!empty($shop->floor)): ?>
                    <strong><?php esc_html_e('Étage :', 'wp-plugin-shops'); ?></strong> <?php echo esc_html(WPS_Floor::labelFor($shop->floor)); ?>
                <?php endif; ?>
                <?php if (!empty($shop->description)): ?>
                    <div class="wps-shop-detail-description">
                        <?php echo wp_kses_post(wpautop($shop->description)); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($shop->number)): ?>
                    <span class="btn btn-xl btn-primary"><?php esc_html_e('Boutique', 'wp-plugin-shops'); ?> <?php echo esc_html($shop->number); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="wps-shop-detail-meta">
            <div class="ct-container">
                <div class="wps-shops-detail-meta-infos">
                    <div>
                        <?php if (!empty($shop->phone)): ?>
                            <div>
                                <a href="tel:<?= esc_attr($shop->phone) ?>"><?php esc_html_e('Téléphone', 'wp-plugin-shops'); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($shop->email)): ?>
                            <div>
                                <a href="mailto:<?php echo esc_attr(sanitize_email($shop->email)); ?>">
                                    <?php esc_html_e('Email', 'wp-plugin-shops'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($shop->whatsapp)): ?>
                            <div>
                                <a href="https://api.whatsapp.com/send?phone=<?php echo str_replace([' ', '+'], '', esc_attr($shop->whatsapp)); ?>"
                                    target="_blank" rel="noopener noreferrer">
                                    <?php esc_html_e('WhatsApp', 'wp-plugin-shops'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="wps-floor-container">
            <div class="ct-container">
                <?php if (!empty($shop->plan_url)): ?>
                    <div class="wps-shop-plan">
                        <img src="<?php echo esc_url($shop->plan_url); ?>" alt="<?php echo esc_attr($shop->name); ?>">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php

    return ob_get_clean();
}

add_shortcode('shop_detail', 'wps_display_shop_detail');
