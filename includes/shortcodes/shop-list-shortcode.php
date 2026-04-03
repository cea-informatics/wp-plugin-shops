<?php

if (!defined('ABSPATH')) {
    exit;
}

function wps_display_shops($atts = [])
{
    $atts = shortcode_atts(['detail_page_id' => 0], $atts, 'wp-shops');
    $shops = WPS_DB::get_shops(true) ?? [];
    $detail_base_url = $atts['detail_page_id']
        ? $detail_base_url = get_permalink($atts['detail_page_id'])
        : $detail_base_url = get_permalink();

    ob_start();
?>
    <div id="wp-shops-container">
        <?php if (empty($shops)): ?>
            <div class="wps-empty-state">
                <p><?php esc_html_e('Aucune boutique trouvée.', 'wp-plugin-shops'); ?></p>
            </div>
        <?php else: ?>
            <div class="wps-filters">
                <div class="wps-filters-form">
                    <input type="text" id="wps-search" class="wps-filter-input" placeholder="<?php echo esc_attr__('Nom *', 'wp-plugin-shops'); ?>">
                    <select id="wps-floor-filter" class="wps-filter-select" aria-label="<?php echo esc_attr__('Étage', 'wp-plugin-shops'); ?>">
                        <option value=""><?php esc_html_e('Étage', 'wp-plugin-shops'); ?></option>
                        <?php foreach (WPS_Floor::options() as $value => $floor): ?>
                            <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($floor); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="wp-block-stackable-button stk-block-button stk-block stk-1d86eb9">
                        <button type="button" id="wps-apply-filters" class="wps-filter-button stk-link stk-button stk--hover-effect-darken">
                            <span class="has-text-color has-palette-color-8-color stk-button__inner-text"><?php esc_html_e('Rechercher', 'wp-plugin-shops'); ?></span>
                            <svg class="wps-search-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <circle cx="11" cy="11" r="7" fill="none" stroke-width="2"></circle>
                                <line x1="16.65" y1="16.65" x2="21" y2="21" stroke-width="2" stroke-linecap="round"></line>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="wps-shops-grid">
                <?php foreach ($shops as $shop): ?>
                    <?php
                    $shop_id = isset($shop->id) ? absint($shop->id) : 0;
                    $shop_url = add_query_arg('shop_id', $shop_id, $detail_base_url);
                    ?>
                    <article
                        class="wps-shop-card"
                        aria-labelledby="wps-shop-<?php echo esc_attr($shop_id); ?>"
                        data-name="<?php echo esc_attr($shop->name); ?>"
                        data-number="<?php echo esc_attr($shop->number); ?>"
                        data-floor="<?php echo esc_attr($shop->floor); ?>">
                        <a class="wps-shop-detail-link" href="<?php echo esc_url($shop_url); ?>">
                            <?php if (!empty($shop->logo_url)): ?>
                                <img class="wps-shop-logo" src="<?php echo esc_url($shop->logo_url); ?>"
                                    alt="<?php echo esc_attr($shop->name); ?>">
                            <?php else: ?>
                                <h3 id="wps-shop-<?php echo esc_attr($shop_id); ?>" class="wps-shop-name"><?php echo esc_html($shop->name); ?></h3>
                            <?php endif; ?>
                            <span class="wps-shop-number">
                                <?php echo esc_html($shop->number); ?>
                            </span>
                            <div class="wps-shop-floor">
                                <?php echo WPS_Floor::tryFrom($shop->floor)?->label(); ?>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php

    return ob_get_clean();
}

add_shortcode('wp-shops', 'wps_display_shops');
