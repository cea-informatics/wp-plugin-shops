<?php

if (!defined('ABSPATH')) exit;

class WPS_DB {

    /**
     * Create database tables.
     */
    public static function install() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $shops_table = $wpdb->prefix . 'wps_shops';

        $sql = "CREATE TABLE $shops_table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            number VARCHAR(50),
            name VARCHAR(255) NOT NULL,
            description TEXT,
            floor SMALLINT,
            whatsapp VARCHAR(50),
            email VARCHAR(255),
            phone VARCHAR(50),
            logo_url VARCHAR(2083),
            image_url VARCHAR(2083),
            plan_url VARCHAR(2083),
            active BOOLEAN DEFAULT false,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    /* -------------------------------------------------------------------------
     * Shops CRUD
     * ---------------------------------------------------------------------- */

    /**
     * Get all shops.
     */
    public static function get_shops($active_only = false) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';

        if ($active_only) {
            return $wpdb->get_results("SELECT * FROM $table WHERE active = 1 ORDER BY name ASC");
        }

        return $wpdb->get_results("SELECT * FROM $table ORDER BY name ASC");
    }

    /**
     * Get a single shop by ID.
     */
    public static function get_shop($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';

        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    }

    /**
     * Insert a new shop.
     */
    public static function insert_shop($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';

        $wpdb->insert($table, array(
            'number'      => sanitize_text_field($data['number']),
            'name'        => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description']),
            'floor'       => sanitize_text_field($data['floor']),
            'whatsapp'    => sanitize_text_field($data['whatsapp']),
            'email'       => sanitize_email($data['email']),
            'phone'       => sanitize_text_field($data['phone']),
            'logo_url'    => esc_url_raw($data['logo_url']),
            'image_url'   => esc_url_raw($data['image_url']),
            'plan_url'    => esc_url_raw($data['plan_url']),
            'active'      => !empty($data['active']) ? 1 : 0,
        ), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'));

        return $wpdb->insert_id;
    }

    /**
     * Update an existing shop.
     */
    public static function update_shop($id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';

        return $wpdb->update($table, array(
            'number'      => sanitize_text_field($data['number']),
            'name'        => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description']),
            'floor'       => sanitize_text_field($data['floor']),
            'whatsapp'    => sanitize_text_field($data['whatsapp']),
            'email'       => sanitize_email($data['email']),
            'phone'       => sanitize_text_field($data['phone']),
            'logo_url'    => esc_url_raw($data['logo_url']),
            'image_url'   => esc_url_raw($data['image_url']),
            'plan_url'    => esc_url_raw($data['plan_url']),
            'active'      => !empty($data['active']) ? 1 : 0,
        ), array('id' => $id), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'), array('%d'));
    }

    /**
     * Delete a shop.
     */
    public static function delete_shop($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';

        return $wpdb->delete($table, array('id' => $id), array('%d'));
    }

    /**
     * Count total shops.
     */
    public static function count_shops($active_only = false) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';

        if ($active_only) {
            return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE active = 1");
        }

        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
    }

    /**
     * Search shops by name or description.
     */
    public static function search_shops($search_term, $active_only = false) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';
        
        $search_term = '%' . $wpdb->esc_like($search_term) . '%';
        
        $where = "WHERE (name LIKE %s OR description LIKE %s)";
        if ($active_only) {
            $where .= " AND active = 1";
        }
        
        $sql = $wpdb->prepare(
            "SELECT * FROM $table $where ORDER BY name ASC",
            $search_term,
            $search_term
        );
        
        return $wpdb->get_results($sql);
    }

    /**
     * Get shops by floor.
     */
    public static function get_shops_by_floor($floor, $active_only = false) {
        global $wpdb;
        $table = $wpdb->prefix . 'wps_shops';

        $where = "WHERE floor = %s";
        if ($active_only) {
            $where .= " AND active = 1";
        }

        $sql = $wpdb->prepare("SELECT * FROM $table $where ORDER BY name ASC", $floor);
        
        return $wpdb->get_results($sql);
    }
}
