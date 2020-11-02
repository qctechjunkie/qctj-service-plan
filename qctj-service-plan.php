<?php
/**
 * Plugin Name: QCTechJunkie - Service Support Plan
 * Description: Adds tracking for service plans provided by QCTechJunkie
 * Version: 1.0.0
 * Author: TechJunkie, LLC
 * Author URI: https://qctechjunkie.com
 * Text Domain: qctj-ssp
 */

namespace QCTJ\Ssp;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define constants.
 */
define( 'QCTJ_SSP_VERSION', '1.0.0' );
define( 'QCTJ_SSP_PATH', plugin_dir_path( __FILE__ ) );
define( 'QCTJ_SSP_URL', plugin_dir_url( __FILE__ ) );
define( 'QCTJ_SSP_STORE_URL', 'https://qctechjunkie.com' );
define( 'QCTJ_SSP_ITEM_NAME', 'QCTechJunkie - Service Support' );
define( 'QCTJ_SSP_ITEM_ID', 1702 );
define( 'QCTJ_SSP_PLUGIN_LICENSE_PAGE', 'qctj-service' );

/**
 * Check for requirements and load the plugin.
 *
 * @since 2.0.0
 * @return void
 */
function load() {

	// PHP 5.6+ is required.
	if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
		add_action( 'admin_notices', __NAMESPACE__ . '\unsupported_php_version_notice' );

		return;
	}

	if ( is_admin() ) {
		require_once QCTJ_SSP_PATH . 'includes/admin/license/qctj-service-sl-license.php';
		require_once QCTJ_SSP_PATH . 'includes/admin/settings.php';
	}

}

add_action( 'plugins_loaded', __NAMESPACE__ . '\load' );

function plugin_updater() {

	if( !class_exists( 'QCTJ_SL_Plugin_Updater' ) ) {
		// load our custom updater
		require_once QCTJ_SSP_PATH . 'includes/admin/license/QCTJ_SL_Plugin_Updater.php';
	}

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'qctj_service_license_key' ) );

	// setup the updater
	$edd_updater = new \QCTJ_SL_Plugin_Updater( QCTJ_SSP_STORE_URL, __FILE__ ,
		array(
			'version' 	=> QCTJ_SSP_VERSION, // current version number
			'license' 	=> $license_key, // license key (used get_option above to retrieve from DB)
			'item_id' 	=> QCTJ_SSP_ITEM_ID, // ID of the product
			'author'  	=> 'QCTechJunkie', // author of this plugin
			'url' 		=> home_url(),
			'beta'    	=> false,
		)
	);

}
add_action( 'admin_init',  __NAMESPACE__ . '\plugin_updater', 0 );

/**
 * Displays an admin notice if using an unsupported PHP version.
 *
 * @since 2.0.0
 * @return void
 */
function unsupported_php_version_notice() {
	echo '<div class="error"><p>' . __( 'QCTechJunkie Service Support add-on requires PHP version 5.6 or later. Please contact your web host and request your site be updated to a modern PHP version, preferably 7.0 and later.', 'qctj-ssp' ) . '</p></div>';
}
