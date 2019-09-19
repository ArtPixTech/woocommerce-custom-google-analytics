<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link
 * @since      1.0.0
 *
 * @package    Custom_Google_Analytics
 * @subpackage Custom_Google_Analytics/admin/partials
 */

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'display_options';
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h1>Custom Google Analytics Ecommerce Options</h1>
    <?php settings_errors(); ?>

    <?php
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=custom-google-analytics&tab=settings"
           class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
        <a href="?page=custom-google-analytics&tab=logs"
           class="nav-tab <?php echo $active_tab == 'logs' ? 'nav-tab-active' : ''; ?>">Logs</a>
    </h2>

    <?php if ($active_tab == 'settings'): ?>
        <h2>Main Options</h2>

        <form method="post" action="options.php">
            <?php settings_fields('cga_group'); ?>

            <div class="field">
                <label>
                    <span>Google Analytics Tracking ID</span>
                    <input type="text" name="cga_tracking_id"
                           value="<?php echo esc_attr(get_option('cga_tracking_id')); ?>" placeholder="UA-XXXXX-XX"
                           data-lpignore="true">
                </label>
            </div>

            <div class="form-controls">
                <input type="submit" class="button-primary" value="Save Changes">
            </div>
        </form>
    <?php elseif ($active_tab == 'logs'): ?>
        <h2>Logs</h2>
        <pre><?= @file_get_contents(CUSTOM_GOOGLE_ANALYTICS_PATH . 'debug.log'); ?></pre>
    <?php endif; ?>
</div>