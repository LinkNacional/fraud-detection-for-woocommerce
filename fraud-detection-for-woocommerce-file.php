<?php

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

use Lkn\FraudDetectionForWoocommerce\Includes\LknFraudDetectionForWoocommerce;
use Lkn\FraudDetectionForWoocommerce\Includes\LknFraudDetectionForWoocommerceActivator;
use Lkn\FraudDetectionForWoocommerce\Includes\LknFraudDetectionForWoocommerceDeactivator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( ! defined('FRAUD_DETECTION_FOR_WOOCOMMERCE_VERSION')) {
    define( 'FRAUD_DETECTION_FOR_WOOCOMMERCE_VERSION', '1.1.3' );
}

if ( ! defined('FRAUD_DETECTION_FOR_WOOCOMMERCE_FILE')) {
    define('FRAUD_DETECTION_FOR_WOOCOMMERCE_FILE', __DIR__ . '/fraud-detection-for-woocommerce.php');
}

if ( ! defined('FRAUD_DETECTION_FOR_WOOCOMMERCE_DIR')) {
    define('FRAUD_DETECTION_FOR_WOOCOMMERCE_DIR', plugin_dir_path(FRAUD_DETECTION_FOR_WOOCOMMERCE_FILE));
}

if ( ! defined('FRAUD_DETECTION_FOR_WOOCOMMERCE_DIR_URL')) {
    define('FRAUD_DETECTION_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url(FRAUD_DETECTION_FOR_WOOCOMMERCE_FILE));
}

if ( ! defined('FRAUD_DETECTION_FOR_WOOCOMMERCE_BASENAME')) {
    define('FRAUD_DETECTION_FOR_WOOCOMMERCE_BASENAME', plugin_basename(FRAUD_DETECTION_FOR_WOOCOMMERCE_FILE));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fraud-detection-for-woocommerce-activator.php
 */
function activate_fraud_detection_for_woocommerce() {
	LknFraudDetectionForWoocommerceActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fraud-detection-for-woocommerce-deactivator.php
 */
function deactivate_fraud_detection_for_woocommerce() {
	LknFraudDetectionForWoocommerceDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fraud_detection_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_fraud_detection_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fraud_detection_for_woocommerce() {

	$plugin = new LknFraudDetectionForWoocommerce();
	$plugin->run();

}
run_fraud_detection_for_woocommerce();
