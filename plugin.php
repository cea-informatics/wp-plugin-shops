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

define('WPS_VERSION', '1.0.5');
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
                <input type="text" id="wps-search" class="wps-filter-input" placeholder="<?php esc_attr_e('Rechercher par nom ou numéro', 'wp-plugin-shops'); ?>" aria-label="<?php esc_attr_e('Search shops', 'wp-plugin-shops'); ?>">
                <select id="wps-floor-filter" class="wps-filter-select" aria-label="<?php esc_attr_e('Filtrer par étage', 'wp-plugin-shops'); ?>">
                    <option value=""><?php esc_html_e('Tous les étages', 'wp-plugin-shops'); ?></option>
                    <?php foreach ($floors as $floor): ?>
                        <option value="<?php echo esc_attr($floor); ?>"><?php echo esc_html($floor); ?></option>
                    <?php endforeach; ?>
                </select>
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

