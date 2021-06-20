<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/shakirmengrani
 * @since      1.0.0
 *
 * @package    Woo_Nearby_Order
 * @subpackage Woo_Nearby_Order/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Nearby_Order
 * @subpackage Woo_Nearby_Order/includes
 * @author     Shakir Mengrani <shakirmengrani@gmail.com>
 */
class Woo_Nearby_Order_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-nearby-order',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
