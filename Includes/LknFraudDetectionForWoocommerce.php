<?php
namespace Lkn\FraudDetectionForWoocommerce\Includes;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://linknacional.com.br
 * @since      1.0.0
 *
 * @package    LknFraudDetectionForWoocommerce
 * @subpackage LknFraudDetectionForWoocommerce/includes
 */

use Lkn\FraudDetectionForWoocommerce\Admin\LknFraudDetectionForWoocommerceAdmin;
use Lkn\FraudDetectionForWoocommerce\PublicView\LknFraudDetectionForWoocommercePublic;
use Automattic\WooCommerce\StoreApi\Utilities\NoticeHandler;


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    LknFraudDetectionForWoocommerce
 * @subpackage LknFraudDetectionForWoocommerce/includes
 * @author     Link Nacional <contato@linknacional.com>
 */
class LknFraudDetectionForWoocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      LknFraudDetectionForWoocommerceLoader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FRAUD_DETECTION_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = FRAUD_DETECTION_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'fraud-detection-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public $LknFraudDetectionForWoocommerceHelperClass;
	private function load_dependencies() {
		$this->LknFraudDetectionForWoocommerceHelperClass = new LknFraudDetectionForWoocommerceHelper();
		$this->loader = new LknFraudDetectionForWoocommerceLoader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the LknFraudDetectionForWoocommerceActivatorI18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$pluginI18n = new LknFraudDetectionForWoocommerceActivatorI18n();

		$this->loader->add_action( 'plugins_loaded', $pluginI18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new LknFraudDetectionForWoocommerceAdmin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'woocommerce_settings_tabs_array', $this->LknFraudDetectionForWoocommerceHelperClass, 'addSettingTab', 50 );
		$this->loader->add_action( 'woocommerce_settings_tabs_anti_fraud', $this->LknFraudDetectionForWoocommerceHelperClass, 'showSettingTabContent' );
		$this->loader->add_action( 'woocommerce_update_options_anti_fraud', $this->LknFraudDetectionForWoocommerceHelperClass, 'saveSettings' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new LknFraudDetectionForWoocommercePublic( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		/* add_action(
			'woocommerce_rest_checkout_process_payment_with_context',
			function (  $context, $result ) {
				add_option('woocommerce_api_payment MEU' . uniqid());
				if ( $result->status ) {
					return;
				}

				// phpcs:ignore WordPress.Security.NonceVerification
				$post_data = $_POST;

				// Set constants.
				wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );

				// Add the payment data from the API to the POST global.
				$_POST = $context->payment_data;

				// Call the process payment method of the chosen gateway.
				$payment_method_object = $context->get_payment_method_instance();

				if ( ! $payment_method_object instanceof \WC_Payment_Gateway ) {
					return;
				}

				$payment_method_object->validate_fields();

				// If errors were thrown, we need to abort.
				NoticeHandler::convert_notices_to_exceptions( 'woocommerce_rest_payment_error' );

				// Process Payment.
				$gateway_result = $payment_method_object->process_payment( $context->order->get_id() );

				// Restore $_POST data.
				$_POST = $post_data;

				// If `process_payment` added notices, clear them. Notices are not displayed from the API -- payment should fail,
				// and a generic notice will be shown instead if payment failed.
				wc_clear_notices();

				// Handle result. If status was not returned we consider this invalid and return failure.
				$result_status = $gateway_result['result'] ?? 'failure';
				// These are the same statuses supported by the API and indicate processing status. This is not the same as order status.
				$valid_status = array( 'success', 'failure', 'pending', 'error' );
				$result->set_status( in_array( $result_status, $valid_status, true ) ? $result_status : 'failure' );

				// set payment_details from result.
				$result->set_payment_details( array_merge( $result->payment_details, $gateway_result ) );
				$result->set_redirect_url( $gateway_result['redirect'] );
			},
			999, // Prioridade alta para garantir que seu código execute antes do `process_payment`
			2
		); */
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    LknFraudDetectionForWoocommerceLoader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
