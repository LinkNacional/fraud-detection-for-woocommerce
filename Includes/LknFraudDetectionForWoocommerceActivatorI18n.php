<?php
namespace Lkn\FraudDetectionForWoocommerce\Includes;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://linknacional.com.br
 * @since      1.0.0
 *
 * @package    LknFraudDetectionForWoocommerceActivator
 * @subpackage LknFraudDetectionForWoocommerceActivator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    LknFraudDetectionForWoocommerceActivator
 * @subpackage LknFraudDetectionForWoocommerceActivator/includes
 * @author     Link Nacional <contato@linknacional.com>
 */
class LknFraudDetectionForWoocommerceActivatorI18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fraud-detection-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
