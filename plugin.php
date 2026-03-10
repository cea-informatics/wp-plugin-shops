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

function wpw_display_shops() {
    ob_start(); ?>
    <div id="wp-shops">
    <button id="wp-shops">shops Info</button>
    <?php
    return ob_get_clean();
}

add_shortcode('wp-shops', 'wpw_display_shops');

