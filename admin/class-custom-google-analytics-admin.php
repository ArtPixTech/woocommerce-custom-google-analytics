<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link
 * @since      1.0.0
 *
 * @package    Custom_Google_Analytics
 * @subpackage Custom_Google_Analytics/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Google_Analytics
 * @subpackage Custom_Google_Analytics/admin
 * @author     Scanerrr <scanerrr@gmail.com>
 */
class Custom_Google_Analytics_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Google_Analytics_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Google_Analytics_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-google-analytics-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Google_Analytics_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Google_Analytics_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-google-analytics-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function cga_options() {
		include_once 'partials/custom-google-analytics-admin-display.php';
	}

	public function register_settings() { // whitelist options
		register_setting( 'cga_group', 'cga_tracking_id' ); // the ga tracking id  (only used if it doesn't already exist)
	}

	public function add_plugin_page() {
		add_options_page( 'Custom Google Analytics Options', 'Custom Google Analytics', 'manage_options', 'custom-google-analytics', [
			$this,
			'cga_options'
		] );
	}

}
