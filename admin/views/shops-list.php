<?php
if (!defined('ABSPATH')) exit;
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Boutiques', 'wp-plugin-shops'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=wps-shops&action=new')); ?>" class="page-title-action">
        <?php esc_html_e('Ajouter une boutique', 'wp-plugin-shops'); ?>
    </a>
    <hr class="wp-heading-inline">

    <?php if (isset($_GET['message'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                switch ($_GET['message']) {
                    case 'created':
                        esc_html_e('Shop created successfully.', 'wp-plugin-shops');
                        break;
                    case 'updated':
                        esc_html_e('Shop updated successfully.', 'wp-plugin-shops');
                        break;
                    case 'deleted':
                        esc_html_e('Shop deleted successfully.', 'wp-plugin-shops');
                        break;
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if (empty($shops)): ?>
        <p><?php esc_html_e('No shops found. Click "Add New" to create your first shop.', 'wp-plugin-shops'); ?></p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 80px;"><?php esc_html_e('Logo', 'wp-plugin-shops'); ?></th>
                    <th><?php esc_html_e('Nom ou numéro', 'wp-plugin-shops'); ?></th>
                    <th><?php esc_html_e('Étage', 'wp-plugin-shops'); ?></th>
                    <th><?php esc_html_e('Email', 'wp-plugin-shops'); ?></th>
                    <th><?php esc_html_e('Téléphone', 'wp-plugin-shops'); ?></th>
                    <th style="width: 80px;"><?php esc_html_e('Status', 'wp-plugin-shops'); ?></th>
                    <th style="width: 150px;"><?php esc_html_e('Actions', 'wp-plugin-shops'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shops as $shop): ?>
                    <tr>
                        <td>
                            <?php if (!empty($shop->logo_url)): ?>
                                <img src="<?php echo esc_url($shop->logo_url); ?>" 
                                     alt="<?php echo esc_attr($shop->name); ?>" 
                                     style="width: 60px; height: 60px; object-fit: contain; background: #fff; border: 1px solid #eee; border-radius: 6px; padding: 6px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo esc_html($shop->name); ?></strong>
                            <?php if (!empty($shop->number)): ?>
                                <div style="color:#666;font-size:12px;">№ <?php echo esc_html($shop->number); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($shop->floor); ?></td>
                        <td><?php echo esc_html($shop->email); ?></td>
                        <td><?php echo esc_html($shop->phone); ?></td>
                        <td>
                            <?php if ($shop->active): ?>
                                <span class="wps-status-active">✓ <?php esc_html_e('Active', 'wp-plugin-shops'); ?></span>
                            <?php else: ?>
                                <span class="wps-status-inactive">✕ <?php esc_html_e('Inactive', 'wp-plugin-shops'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=wps-shops&action=edit&id=' . $shop->id)); ?>" class="button button-small">
                                <?php esc_html_e('Edit', 'wp-plugin-shops'); ?>
                            </a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wps-shops&action=delete&id=' . $shop->id), 'wps_delete_shop_' . $shop->id)); ?>" 
                               class="button button-small button-link-delete"
                               onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this shop?', 'wp-plugin-shops'); ?>');">
                                <?php esc_html_e('Delete', 'wp-plugin-shops'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
