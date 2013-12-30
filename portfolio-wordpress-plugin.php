<?php
/*
Plugin Name: Portfolio WordPress Plugin
Plugin URI: https://github.com/vilmosioo/Portfolio-WordPress-Plugin
Description: A WordPress plugin that creates a portfolio and testimonial custom posts to handle and display a work portfolio 
Version: 0.0.1
Author: Vilmos Ioo
Author URI: http://vilmosioo.co.uk
License: GPL2
Requires at least: 3.3

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
		add_action('wp_enqueue_scripts', array( &$this, 'add_scripts_and_styles') );
		add_shortcode('portfolio-slider', array( &$this, 'display_portfolio_slider' ));
	} 

	public function display_portfolio_slider($atts){
		extract(shortcode_atts(array('class' => 'flexslider', 'image_size' => 'medium', 'animation' => 'fade', 'controlsContainer' => ".$class-container", 'directionNav' => false, 'animationDuration' => 1200), $atts));

		wp_enqueue_script( 'vi-portfolio-flex' ); 
		wp_enqueue_script( 'vi-portfolio-script' ); 
		wp_enqueue_style( 'vi-portfolio-flex' );

		$s = "<div class='$class-container clearfix'>";
		$s .=	"<div class='$class'>";
		$s .=	"<ul class='slides'>";
		
		$the_query = new WP_Query( array( 'post_type' => 'portfolio-item', 'posts_per_page' => -1 ) );
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$img = wp_get_attachment_image_src( get_post_thumbnail_id(), $image_size );
			$img = $img[0];
			if(!empty($img)) $s .= "<li><img src='$img' /></li>";
		endwhile;
		wp_reset_postdata();

		wp_localize_script('vi-portfolio-script', 'PORTFOLIO_SLIDER_DATA', array(
			'sliderClass' => $class,
			'options' => array(
				'animation' => $animation,
				'controlsContainer' => ".$class-container",
				'directionNav' => $directionNav,
				'animationDuration' => $animationDuration
			)
		));

		$s .= "</ul></div></div>";
		return $s;
	}

	public function register_posts(){
		VI_Portfolio_Custom_Post::create(array('name' => 'Portfolio item'));
		VI_Portfolio_Custom_Post::create(array('name' => 'Testimonial'));
	}

	// add flexi slider scripts and styles
	public function add_scripts_and_styles(){
		wp_register_script( 'vi-portfolio-flex', plugins_url('js/jquery.flexslider-min.js', __FILE__), array( 'jquery' ), '1.8', true ); 
		wp_register_script( 'vi-portfolio-script', plugins_url('js/script.js', __FILE__), array( 'vi-portfolio-flex' ), '0.0.1', true ); 
		wp_register_style( 'vi-portfolio-flex', plugins_url('css/flexslider.css', __FILE__), array(), '1.8' );
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