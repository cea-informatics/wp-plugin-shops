<?php

if (!defined('ABSPATH')) exit;

class WPS_Admin {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menus'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
    }

    /**
     * Register admin menus.
     */
    public static function register_menus() {
        add_menu_page(
            __('Boutiques', 'wp-plugin-shops'),
            __('Boutiques', 'wp-plugin-shops'),
            'manage_options',
            'wps-shops',
            array(__CLASS__, 'page_shops'),
            'dashicons-store',
            30
        );

        add_submenu_page(
            'wps-shops',
            __('Toutes les boutiques', 'wp-plugin-shops'),
            __('Toutes les boutiques', 'wp-plugin-shops'),
            'manage_options',
            'wps-shops',
            array(__CLASS__, 'page_shops')
        );
    }

    /**
     * Enqueue admin assets.
     */
    public static function enqueue_assets($hook) {
        if (strpos($hook, 'wps-') === false) {
            return;
        }

        wp_enqueue_style('wps-admin', WPS_PLUGIN_URL . 'assets/admin.css', array(), WPS_VERSION);
        wp_enqueue_media();
    }

    /**
     * Shops page router.
     */
    public static function page_shops() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized access.', 'wp-plugin-shops'));
        }

        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';

        // Handle form submissions.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::handle_shop_save();
            return;
        }

        // Handle delete action.
        if ($action === 'delete' && isset($_GET['id'])) {
            self::handle_shop_delete();
            return;
        }

        if ($action === 'new' || $action === 'edit') {
            $shop = null;
            if ($action === 'edit' && isset($_GET['id'])) {
                $shop = WPS_DB::get_shop(absint($_GET['id']));
            }
            include WPS_PLUGIN_DIR . 'admin/views/shops-form.php';
        } else {
            $shops = WPS_DB::get_shops();
            include WPS_PLUGIN_DIR . 'admin/views/shops-list.php';
        }
    }

    /**
     * Handle shop save (insert/update).
     */
    private static function handle_shop_save() {
        if (!check_admin_referer('wps_save_shop', 'wps_nonce')) {
            wp_die(__('Invalid nonce.', 'wp-plugin-shops'));
        }

        $data = array(
            'number'      => isset($_POST['number']) ? $_POST['number'] : null,
            'name'        => isset($_POST['name']) ? $_POST['name'] : null,
            'description' => isset($_POST['description']) ? $_POST['description'] : null,
            'floor'       => isset($_POST['floor']) ? $_POST['floor'] : null,
            'whatsapp'    => isset($_POST['whatsapp']) ? $_POST['whatsapp'] : null,
            'email'       => isset($_POST['email']) ? $_POST['email'] : null,
            'phone'       => isset($_POST['phone']) ? $_POST['phone'] : null,
            'logo_url'    => self::sanitize_optional_url($_POST['logo_url'] ?? ''),
            'image_url'   => self::sanitize_optional_url($_POST['image_url'] ?? ''),
            'plan_url'    => self::sanitize_optional_url($_POST['plan_url'] ?? ''),
            'active'      => isset($_POST['active']) ? 1 : 0,
        );

        if (!empty($_POST['shop_id'])) {
            WPS_DB::update_shop(absint($_POST['shop_id']), $data);
            $message = 'updated';
        } else {
            WPS_DB::insert_shop($data);
            $message = 'created';
        }

        wp_redirect(admin_url('admin.php?page=wps-shops&message=' . $message));
        exit;
    }

    /**
     * Handle shop deletion.
     */
    private static function handle_shop_delete() {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'wps_delete_shop_' . absint($_GET['id']))) {
            wp_die(__('Invalid nonce.', 'wp-plugin-shops'));
        }

        WPS_DB::delete_shop(absint($_GET['id']));

        wp_redirect(admin_url('admin.php?page=wps-shops&message=deleted'));
        exit;
    }

    /**
     * Return a URL or an empty string if not valid/empty. Prevents "http://0" artifacts.
     */
    private static function sanitize_optional_url($url) {
        $url = trim($url ?? '');
        if ($url === '') {
            return '';
        }
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
    }
}
