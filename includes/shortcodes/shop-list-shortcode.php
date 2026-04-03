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
        <div class="wps-filters">
            <h5><?php esc_html_e('Rechercher parmi les boutiques', 'wp-plugin-shops'); ?></h5>
            <div class="wps-filters-form">
                <input type="text" id="wps-search" class="wps-filter-input" placeholder="<?= esc_attr__('Nom *', 'wp-plugin-shops'); ?>">
                <select id="wps-floor-filter" class="wps-filter-select" aria-label="<?= esc_attr__('Étage', 'wp-plugin-shops'); ?>">
                    <option value=""><?php esc_html_e('Étage', 'wp-plugin-shops'); ?></option>
                    <?php foreach (WPS_Floor::options() as $value => $floor): ?>
                        <option value="<?= esc_attr($value); ?>"><?= esc_html($floor); ?></option>
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
                <?php $shop_id = $shop->id ?? 0; ?>
                <article
                    class="wps-shop-card" id="wps-shop-<?= esc_attr($shop_id); ?>"
                    data-name="<?= esc_attr($shop->name); ?>"
                    data-number="<?= esc_attr($shop->number); ?>"
                    data-floor="<?= esc_attr($shop->floor); ?>">
                    <a class="wps-shop-detail-link" href="<?= esc_url(add_query_arg('shop_id', $shop_id, $detail_base_url)); ?>">
                        <?php if (empty($shop->logo_url)): ?>
                            <h3 class="wps-shop-name"><?= esc_html($shop->name); ?></h3>
                        <?php else: ?>
                            <img class="wps-shop-logo"
                                src="<?= esc_url($shop->logo_url); ?>"
                                alt="<?= esc_attr($shop->name); ?>">
                        <?php endif; ?>
                        <span class="wps-shop-number">
                            <?= esc_html($shop->number); ?>
                        </span>
                        <div class="wps-shop-floor">
                            <?= WPS_Floor::tryFrom($shop->floor)?->label(); ?>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="wps-empty-state" style="display: <?= empty($shops) ? 'block' : 'none' ?>;">
            <p><?php esc_html_e('Aucune boutique trouvée.', 'wp-plugin-shops'); ?></p>
        </div>
    </div>
<?php

    return ob_get_clean();
}

add_shortcode('wp-shops', 'wps_display_shops');
