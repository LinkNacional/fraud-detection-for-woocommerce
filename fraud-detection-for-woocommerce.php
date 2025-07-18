<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://linknacional.com.br
 * @since             1.0.0
 * @package           LknFraudDetectionForWoocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Fraud Detection for WooCommerce
 * Plugin URI:        https://https://www.linknacional.com.br/wordpress/givewp
 * Description:       Performs verification and prevention of malicious payments.
 * Version:           1.1.2
 * Author:            Link Nacional
 * Author URI:        https://linknacional.com.br/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fraud-detection-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once 'fraud-detection-for-woocommerce-file.php';