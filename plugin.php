<?php

/**
 * Plugin Name:     Custom Shops
 * Description:     The plugin is responsible of stores management.
 * Version:         1.0.4
 * Author:          CEA Informatics
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     wp-plugin-shops
 *
 * @package         wp-plugin-shops
 */

if (!defined('ABSPATH')) exit;

define('WPS_VERSION', '1.0.4');
define('WPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPS_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WPS_PLUGIN_DIR . 'includes/class-wps-db.php';
require_once WPS_PLUGIN_DIR . 'includes/class-wps-admin.php';

register_activation_hook(__FILE__, array('WPS_DB', 'install'));

WPS_Admin::init();

function wpw_display_shops() {
    ob_start(); ?>
    <div id="wp-shops">
    <button id="wp-shops">shops Info</button>
    <?php
    return ob_get_clean();
}

add_shortcode('wp-shops', 'wpw_display_shops');

