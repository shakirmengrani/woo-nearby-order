<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/shakirmengrani
 * @since             1.0.0
 * @package           Woo_Nearby_Order
 *
 * @wordpress-plugin
 * Plugin Name:       WooNearbyOrder
 * Plugin URI:        https://github.com/shakirmengrani/woo-nearby-order
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Shakir Mengrani
 * Author URI:        https://github.com/shakirmengrani
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-nearby-order
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
define( 'WOO_NEARBY_ORDER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-nearby-order-activator.php
 */
function activate_woo_nearby_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-nearby-order-activator.php';
	Woo_Nearby_Order_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-nearby-order-deactivator.php
 */
function deactivate_woo_nearby_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-nearby-order-deactivator.php';
	Woo_Nearby_Order_Deactivator::deactivate();
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    register_activation_hook( __FILE__, 'activate_woo_nearby_order' );
	register_deactivation_hook( __FILE__, 'deactivate_woo_nearby_order' );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-nearby-order.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_nearby_order() {

	$plugin = new Woo_Nearby_Order();
	$plugin->run();

}
run_woo_nearby_order();
