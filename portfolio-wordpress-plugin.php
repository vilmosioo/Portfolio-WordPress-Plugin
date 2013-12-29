<?php
/*
Plugin Name: Portfolio WordPress Plugin
Plugin URI: https://github.com/vilmosioo/Portfolio-WordPress-Plugin
Description: A WordPress plugin that creates a portfolio and testimonial custom posts to handle and display a work portfolio 
Version: 0.0.1
Author: Vilmos Ioo
Author URI: http://vilmosioo.co.uk
License: GPL2

	Copyright 2014 Vilmos Ioo

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/

// Define constants
define('VI_PORTFOLIO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VI_PORTFOLIO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VI_PORTFOLIO_PLUGIN_WORDPRESS_VERSION', get_bloginfo( 'version' ));

// Includes
require_once(VI_PORTFOLIO_PLUGIN_DIR.'inc/VI_Portfolio_Custom_Post.php');

class VI_Portfolio_Plugin {
	
	static function init(){
		return new VI_Portfolio_Plugin();
	}

	const ID = 'VI_PORTFOLIO';

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	private function __construct() {
		register_activation_hook(__FILE__, array( &$this, 'activate' ) );
		register_deactivation_hook(__FILE__, array( &$this, 'deactivate' ) );
		
		add_action('init', array(&$this, 'register_posts'));
	} 

	public function register_posts(){
		VI_Portfolio_Custom_Post::create(array('name' => 'Portfolio item'));
		VI_Portfolio_Custom_Post::create(array('name' => 'Testimonial'));
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {

	} 

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	function deactivate( $network_wide ) {

	} 

} // end class

$GLOBALS['VI_Portfolio_Plugin'] = VI_Portfolio_Plugin::init();
?>