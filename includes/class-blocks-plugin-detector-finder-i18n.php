<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://elementinvader.com
 * @since      1.0.0
 *
 * @package    Blocks_Plugin_Detector_Finder
 * @subpackage Blocks_Plugin_Detector_Finder/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Blocks_Plugin_Detector_Finder
 * @subpackage Blocks_Plugin_Detector_Finder/includes
 * @author     ElementInvader <support@elementinvader.com>
 */
class Blocks_Plugin_Detector_Finder_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'blocks-plugin-detector-finder',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
