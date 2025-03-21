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
use Exception;
use Lkn_Puc_Plugin_UpdateChecker;

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
		$this->updater_init();

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
		$this->loader->add_action( 'woocommerce_settings_tabs_lkn_anti_fraud', $this->LknFraudDetectionForWoocommerceHelperClass, 'showSettingTabContent' );
		$this->loader->add_action( 'woocommerce_update_options_lkn_anti_fraud', $this->LknFraudDetectionForWoocommerceHelperClass, 'saveSettings' );
		$this->loader->add_filter( 'woocommerce_register_shop_order_post_statuses', $this->LknFraudDetectionForWoocommerceHelperClass, 'createFraudStatus' );
		$this->loader->add_filter( 'wc_order_statuses', $this->LknFraudDetectionForWoocommerceHelperClass, 'registerFraudStatus' );
        $this->loader->add_filter( 'plugin_action_links_' . FRAUD_DETECTION_FOR_WOOCOMMERCE_BASENAME, $this, 'addSettings', 10, 2);
		
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
		$this->loader->add_action( 'enqueue_block_assets', $this->LknFraudDetectionForWoocommerceHelperClass, 'enqueueRecaptchaScripts');
		$this->loader->add_action( 'woocommerce_rest_checkout_process_payment_with_context', $this->LknFraudDetectionForWoocommerceHelperClass, 'processPayments', 1, 2 );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $this->LknFraudDetectionForWoocommerceHelperClass, 'verifyAjaxRequsets', 1, 3 );

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

	private function updater_init() {
        include_once __DIR__ . '/plugin-updater/plugin-update-checker.php';

        return new Lkn_Puc_Plugin_UpdateChecker(
            'https://api.linknacional.com/v2/u/?slug=fraud-detection-for-woocommerce',
            FRAUD_DETECTION_FOR_WOOCOMMERCE_FILE,
            'fraud-detection-for-woocommerce'
        );
    }

	public static function addSettings($plugin_meta, $plugin_file) {
        $new_meta_links['setting'] = sprintf(
            '<a href="%1$s">%2$s</a>',
            admin_url('admin.php?page=wc-settings&tab=lkn_anti_fraud'),
            __('Settings', 'woocommerce')
        );

        return array_merge($plugin_meta, $new_meta_links);
    }

}
