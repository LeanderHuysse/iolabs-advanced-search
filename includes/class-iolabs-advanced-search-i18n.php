<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.iolabs.nl
 * @since      1.0.0
 *
 * @package    Iolabs_Advanced_Search
 * @subpackage Iolabs_Advanced_Search/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Iolabs_Advanced_Search
 * @subpackage Iolabs_Advanced_Search/includes
 * @author     Leander Huysse <info@iomedia.nl>
 */
class Iolabs_Advanced_Search_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'iolabs-advanced-search',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
