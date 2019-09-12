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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1>Custom Google Analytics Ecommerce Options</h1>

    <form method="post" action="options.php">
		<?php settings_fields( 'cga_group' ); ?>

        <div class="field">
            <label>
                <span>Google Analytics Tracking ID</span>
                <input type="text" name="cga_tracking_id"
                       value="<?php echo esc_attr( get_option( 'cga_tracking_id' ) ); ?>" placeholder="UA-XXXXX-XX"
                       data-lpignore="true">
            </label>
        </div>

        <div class="form-controls">
            <input type="submit" class="button-primary" value="Save Changes">
        </div>
    </form>
</div>