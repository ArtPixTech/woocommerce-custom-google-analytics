<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link
 * @since      1.0.0
 *
 * @package    Custom_Google_Analytics
 * @subpackage Custom_Google_Analytics/public
 */

use TheIconic\Tracking\GoogleAnalytics\Analytics;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_Google_Analytics
 * @subpackage Custom_Google_Analytics/public
 * @author     Scanerrr <scanerrr@gmail.com>
 */
class Custom_Google_Analytics_Public {

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
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-google-analytics-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-google-analytics-public.js', array( 'jquery' ), $this->version, false );

	}

	protected function get_parent_categories( $category_id ) {
		$parent_categories = [];
		// This will retrieve the IDs of all the parent categories
		// of a category, all the way to the top level
		$parent_categories_ids = get_ancestors( $category_id, 'product_cat' );

		foreach ( $parent_categories_ids as $category_id ) {
			// Now we retrieve the details of each category, using its
			// ID, and extract its name
			$category                             = get_term_by( 'id', $category_id, 'product_cat' );
			$parent_categories[ $category->slug ] = $category->name;
		}

		return $parent_categories;
	}

	protected function get_product_categories( $product, $return_raw_categories = false ) {
		$categories = wp_get_post_terms( $product->id, 'product_cat' );

		if ( is_array( $categories ) && ! $return_raw_categories ) {
			$parent_categories = [];
			// Retrieve the parent categories of each category to which
			// the product is assigned directly
			foreach ( $categories as $category ) {
				// Using array_merge(), we keep a list of parent categories
				// that doesn't include duplicates
				$parent_categories = array_merge( $parent_categories, $this->get_parent_categories( $category->term_id ) );
			}
			// When we have the full list of parent categories, we can merge it with
			// the list of the product's direct categories, producing a single list
			$categories = array_merge( $parent_categories, wp_list_pluck( $categories, 'name', 'slug' ) );
		}

		return $categories;
	}

	protected function gen_uuid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	public function purchase_tracking_with_variation( $orderId, $posted_data, $order ) {
		$meta_key = '_order_key_analytic_success';

		if ( ! $orderId || ! $order ) {
			return;
		}

		if ( get_post_meta( $orderId, $meta_key, true ) == 1 ) {
			return;
		}

		$analytics = new Analytics();

		// Build the order data programmatically, including each order product in the payload
		// Take notice, if you want GA reports to tie this event with previous user actions
		// you must get and set the same ClientId from the GA Cookie
		// First, general and required hit data
		$analytics->setProtocolVersion( '1' )
		          ->setCurrencyCode( $order->get_currency() )
		          ->setTrackingId( esc_attr( get_option( 'cga_tracking_id' ) ) )
		          ->setClientId( $this->get_ga_client_id() );

		if ( version_compare( WC_VERSION, '3.7', '<' ) ) {
			$coupons = $order->get_used_coupons();
		} else {
			$coupons = $order->get_coupon_codes();
		}

		// Then, include the transaction data
		$analytics->setTransactionId( $orderId )
		          ->setAffiliation( 'ArtPix 3D Shop' )
		          ->setRevenue( number_format( $order->get_total(), 2, '.', '' ) )
		          ->setTax( number_format( $order->get_total_tax(), 2, '.', '' ) )
		          ->setShipping( number_format( $order->get_shipping_total(), 2, '.', '' ) )
		          ->setCouponCode( implode( ',', $coupons ) );

		$itemPosition = 0;

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();

			$sku = trim( $product->get_sku() );

			if ( ! $sku ) {
				$sku = $product->get_id();
			}

			$productType = $product->get_type();
			$size        = $dimension = '';

			if ( 'variation' === $productType || 'variable' === $productType ) {
				// if product name not contains 3D in title
				if ( strpos( strtolower( $product->get_name() ), '3d' ) === false ) {
					$imageConversion = wc_get_order_item_meta( $item->get_id(), 'Convert Image from 2D to 3D? (&#36;25)', true );
					$dimension       = $imageConversion === 'Yes' ? '' : '2D';
				}

				// check if product is simple product
				// for example Square Keychain
				$parentProduct = wc_get_product( $product->get_parent_id() );
				if ( count( $parentProduct->get_children() ) > 1 ) {
					$size = $product->get_attribute( 'size' );
				}

			}

			$name = trim( $size . ' ' . $product->get_name() . ' ' . $dimension );

			$categories = $this->get_product_categories( $product ) ?? [];

			$categories = join( '/', $categories );

			$productData = [
				'sku'         => $sku,
				'name'        => $name,
				'brand'       => 'ArtPix 3D',
				'coupon_code' => $coupons,
				'category'    => $categories,
				'variant'     => $size,
				'price'       => number_format( $item->get_subtotal(), 2, '.', '' ),
				'quantity'    => $item->get_quantity(),
				'position'    => ++ $itemPosition
			];

			$analytics->addProduct( $productData );
		}

		// Don't forget to set the product action, in this case to PURCHASE
		$analytics->setProductActionToPurchase();

		// Finally, you must send a hit, in this case we send an Event
		$analytics->setEventCategory( 'Enhanced Ecommerce' )
		          ->setEventAction( 'Purchase' )
		          ->setNonInteractionHit( true )
		          ->sendEvent();

		//add to database and continue
		update_post_meta( $orderId, $meta_key, 1 );
	}


	// Handle the parsing of the _ga cookie or setting it to a unique identifier
	protected function get_ga_client_id() {
		if ( isset( $_COOKIE['_ga'] ) ) {
			list( $version, $domainDepth, $cid1, $cid2 ) = explode( '[\.]', $_COOKIE['_ga'], 4 );
			$contents = [ 'version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1 . '.' . $cid2 ];
			$cid      = $contents['cid'];
		} else {
			$cid = $this->gen_uuid();
		}

		return $cid;
	}

}
