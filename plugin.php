<?php

/**
 * Plugin Name:     Custom Shops
 * Description:     The plugin is responsible of stores management.
 * Version:         1.1.1
 * Author:          CEA Informatics
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     wp-plugin-shops
 *
 * @package         wp-plugin-shops
 */

if (!defined('ABSPATH')) exit;

define('WPS_VERSION', '1.0.6');
define('WPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPS_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WPS_PLUGIN_DIR . 'includes/class-wps-db.php';
require_once WPS_PLUGIN_DIR . 'includes/class-wps-admin.php';

register_activation_hook(__FILE__, array('WPS_DB', 'install'));

// Initialize admin class if available
if (class_exists('WPS_Admin')) {
    WPS_Admin::init();
}

function wps_display_shops() {
    $shops = array();
    if (class_exists('WPS_DB')) {
        $shops = WPS_DB::get_shops(true);
    }

    // Collect distinct floors for filter
    $floors = array();
    foreach ($shops as $shop) {
        if (!empty($shop->floor)) {
            $floors[] = $shop->floor;
        }
    }
    $floors = array_values(array_unique($floors));
    sort($floors);

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
                <h5>Rechercher parmis les boutiques</h2>
                <div class="wps-filters-form">
                    <input type="text" id="wps-search" class="wps-filter-input" placeholder="Nom *">
                    <select id="wps-floor-filter" class="wps-filter-select" aria-label="Étage">
                        <option value=""><?php esc_html_e('Étage', 'wp-plugin-shops'); ?></option>
                        <?php foreach ($floors as $floor): ?>
                            <option value="<?php echo esc_attr($floor); ?>"><?php echo esc_html($floor); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="wp-block-stackable-button stk-block-button stk-block stk-1d86eb9">
                        <button type="button" id="wps-apply-filters" class="wps-filter-button stk-link stk-button stk--hover-effect-darken">
                            <span class="has-text-color has-palette-color-8-color stk-button__inner-text">Rechercher</span>
                            <svg class="wps-search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="none" style="margin-left:8px;vertical-align:middle">
                                <circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="2"/>
                                <line x1="14.1213" y1="14.1213" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="wps-shops-grid">
                <?php foreach ($shops as $shop): ?>
                    <article 
                        class="wps-shop-card" 
                        aria-labelledby="wps-shop-<?php echo esc_attr($shop->id); ?>"
                        data-name="<?php echo esc_attr($shop->name); ?>"
                        data-number="<?php echo esc_attr($shop->number); ?>"
                        data-floor="<?php echo esc_attr($shop->floor); ?>">
                        <?php if (!empty($shop->logo_url)): ?>
                            <img class="wps-shop-logo" src="<?php echo esc_url($shop->logo_url); ?>" 
                            alt="<?php echo esc_attr($shop->name); ?>">
                        <?php else: ?>
                            <h3 class="wps-shop-name"><?php echo esc_attr($shop->name); ?></h3>
                        <?php endif; ?>
                        <?php if (!empty($shop->number)): ?>
                            <span class="wps-shop-number"><?php echo esc_html($shop->number); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($shop->floor)): ?>
                            <div class="wps-shop-floor"><?php echo esc_html($shop->floor); ?></div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

function wps_enqueue_scripts() {
    wp_enqueue_style('wps-style', WPS_PLUGIN_URL . 'assets/shops.css', array(), WPS_VERSION);
    wp_enqueue_script('wps-script', WPS_PLUGIN_URL . 'assets/shops.js', array('jquery'), WPS_VERSION, true);
}

add_action('wp_enqueue_scripts', 'wps_enqueue_scripts');

add_shortcode('wp-shops', 'wps_display_shops');

