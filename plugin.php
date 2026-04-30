<?php

/**
 * Plugin Name:     Custom Shops
 * Description:     The plugin is responsible of stores management.
 * Version:         1.1.9
 * Author:          CEA Informatics
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     wp-plugin-shops
 *
 * @package         wp-plugin-shops
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WPS_VERSION', '1.1.9');
define('WPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPS_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WPS_PLUGIN_DIR . 'includes/class-wps-db.php';
require_once WPS_PLUGIN_DIR . 'includes/class-wps-admin.php';
require_once WPS_PLUGIN_DIR . 'includes/class-wps-floor.php';
require_once WPS_PLUGIN_DIR . 'includes/shortcodes/shop-list-shortcode.php';
require_once WPS_PLUGIN_DIR . 'includes/shortcodes/shop-detail-shortcode.php';

register_activation_hook(__FILE__, array('WPS_DB', 'install'));

// Initialize admin class if available
if (class_exists('WPS_Admin')) {
    WPS_Admin::init();
}

function wps_enqueue_scripts()
{
    wp_enqueue_style('wps-style', WPS_PLUGIN_URL . 'assets/shops.css', array(), WPS_VERSION);
    wp_enqueue_script('wps-script', WPS_PLUGIN_URL . 'assets/shops.js', array('jquery'), WPS_VERSION, true);
}

add_action('wp_enqueue_scripts', 'wps_enqueue_scripts');
