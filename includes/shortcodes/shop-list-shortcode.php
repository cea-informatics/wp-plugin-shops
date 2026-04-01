<?php

if (!defined('ABSPATH')) {
    exit;
}

function wps_display_shops($atts = []) {
    $atts = shortcode_atts(
        ['detail_page_id' => 0],
        $atts,
        'wp-shops'
    );

    $shops = [];
    if (class_exists('WPS_DB')) {
        $shops = WPS_DB::get_shops(true);
    }

    // Collect distinct floors for filter
    $floors = [];
    foreach ($shops as $shop) {
        if (!empty($shop->floor)) {
            $floors[] = $shop->floor;
        }
    }
    $floors = array_values(array_unique($floors));
    sort($floors);

    $detail_page_id = absint($atts['detail_page_id']);
    if ($detail_page_id > 0) {
        $detail_base_url = get_permalink($detail_page_id);
    } else {
        $detail_base_url = get_permalink();
    }

    ob_start();
    ?>
    <div id="wp-shops-container">
        <?php if (empty($shops)): ?>
            <div class="wps-empty-state">
                <div class="wps-empty-state-icon">🏬</div>
                <p><?php esc_html_e('Aucune boutique trouvée.', 'wp-plugin-shops'); ?></p>
            </div>
        <?php else: ?>
            <div class="wps-filters">
                <h5><?php esc_html_e('Rechercher parmi les boutiques', 'wp-plugin-shops'); ?></h5>
                <div class="wps-filters-form">
                    <input type="text" id="wps-search" class="wps-filter-input" placeholder="<?php echo esc_attr__('Nom *', 'wp-plugin-shops'); ?>">
                    <select id="wps-floor-filter" class="wps-filter-select" aria-label="<?php echo esc_attr__('Étage', 'wp-plugin-shops'); ?>">
                        <option value=""><?php esc_html_e('Étage', 'wp-plugin-shops'); ?></option>
                        <?php foreach ($floors as $floor): ?>
                            <option value="<?php echo esc_attr($floor); ?>"><?php echo esc_html($floor); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="wp-block-stackable-button stk-block-button stk-block stk-1d86eb9">
                        <button type="button" id="wps-apply-filters" class="wps-filter-button stk-link stk-button stk--hover-effect-darken">
                            <span class="has-text-color has-palette-color-8-color stk-button__inner-text"><?php esc_html_e('Rechercher', 'wp-plugin-shops'); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="wps-shops-grid">
                <?php foreach ($shops as $shop): ?>
                    <?php
                    $shop_id = isset($shop->id) ? absint($shop->id) : 0;
                    $shop_url = $detail_base_url
                        ? add_query_arg('shop_id', $shop_id, $detail_base_url)
                        : add_query_arg('shop_id', $shop_id, home_url('/'));
                    ?>
                    <a class="wps-shop-detail-link" href="<?php echo esc_url($shop_url); ?>">
                        <article
                            class="wps-shop-card"
                            aria-labelledby="wps-shop-<?php echo esc_attr($shop_id); ?>"
                            data-name="<?php echo esc_attr($shop->name); ?>"
                            data-number="<?php echo esc_attr($shop->number); ?>"
                            data-floor="<?php echo esc_attr($shop->floor); ?>">
                            <?php if (!empty($shop->logo_url)): ?>
                                <img class="wps-shop-logo" src="<?php echo esc_url($shop->logo_url); ?>"
                                alt="<?php echo esc_attr($shop->name); ?>">
                            <?php else: ?>
                                <h3 id="wps-shop-<?php echo esc_attr($shop_id); ?>" class="wps-shop-name"><?php echo esc_html($shop->name); ?></h3>
                            <?php endif; ?>
                            <?php if (!empty($shop->number)): ?>
                                <span class="wps-shop-number"><?php echo esc_html($shop->number); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($shop->floor)): ?>
                                <div class="wps-shop-floor"><?php echo esc_html($shop->floor); ?></div>
                            <?php endif; ?>
                        </article>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

add_shortcode('wp-shops', 'wps_display_shops');
