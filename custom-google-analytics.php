<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link
 * @since             1.0.0
 * @package           Custom_Google_Analytics
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Google Analytics
 * Plugin URI:
 * Description:       Handles woocommerce purchase/transactions information
 * Version:           1.0.0
 * Author:            Scanerrr
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-google-analytics
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CUSTOM_GOOGLE_ANALYTICS_VERSION', '1.0.0' );

define( 'CUSTOM_GOOGLE_ANALYTICS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CUSTOM_GOOGLE_ANALYTICS_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Include composer autoloader.
 */
require_once 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-google-analytics-activator.php
 */
function activate_custom_google_analytics() {
	require_once CUSTOM_GOOGLE_ANALYTICS_PATH . 'includes/class-custom-google-analytics-activator.php';
	Custom_Google_Analytics_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-google-analytics-deactivator.php
 */
function deactivate_custom_google_analytics() {
	require_once CUSTOM_GOOGLE_ANALYTICS_PATH . 'includes/class-custom-google-analytics-deactivator.php';
	Custom_Google_Analytics_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_google_analytics' );
register_deactivation_hook( __FILE__, 'deactivate_custom_google_analytics' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require CUSTOM_GOOGLE_ANALYTICS_PATH . 'includes/class-custom-google-analytics.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_google_analytics() {

	$plugin = new Custom_Google_Analytics();
	$plugin->run();

}

run_custom_google_analytics();
