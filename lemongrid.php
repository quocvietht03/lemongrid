<?php
/**
*
* Plugin Name: Lemon Grid
* Plugin URI: http://themebears.com
* Description: This plugin is addon visual composer, which is developed by THEMEBEARS Team for Visual Comporser plugin.
* Version: 1.2
* Author: BEARS Theme
* Author URI: http://bearsthemes.com
* Copyright 2015 bearsthemes.com. All rights reserved.
*/

define( 'TBLG_NAME', 'bearsthemes' );
define( 'TBLG_DIR', plugin_dir_path(__FILE__) );
define( 'TBLG_URL', plugin_dir_url(__FILE__) );
define( 'TBLG_INCLUDES', TBLG_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'TBLG_SHORTCODES', TBLG_DIR . "shortcodes" . DIRECTORY_SEPARATOR );

define( 'TBLG_CSS', TBLG_URL . "assets/css/" );
define( 'TBLG_JS', TBLG_URL . "assets/js/" );
define( 'TBLG_IMAGES', TBLG_URL . "assets/images/" );

/**
 * Require functions on plugin
 */
require_once TBLG_INCLUDES . 'functions.php';

/**
 * Use LemonGrid class
 */
new LemonGrid;

/**
 * LemonGrid Class
 * 
 */
class LemonGrid
{
	/**
	 * Init function, which is run on site init and plugin loaded
	 */
	public function __construct()
	{
		/**
		 * Enqueue Scripts on plugin
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'register_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'include_script' ) );

		/**
		 * Visual Composer action
		 */
		add_action( 'vc_before_init', array( $this, 'shortcode' ) );

		/**
		 * Include widget
		 */
		$this->widget();

		/**
		 * admin_init_hook
		 */
		add_action( 'admin_init', array( $this, 'admin_init_hook' ) );
	}

	/**
	 * admin_init_hook
	 */
	function admin_init_hook()
	{
		/* includes script backend */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'tb-lemongrid-script-backend', TBLG_JS . 'lg_backend.js', array( 'jquery' ) );
		wp_enqueue_style( 'tb-lemongrid-script-backend', TBLG_CSS . 'lg_backend.css' );
		wp_localize_script( 'tb-lemongrid-script-backend', 'lgAdminObj', 
			array( 
				'ajaxurl' => admin_url('admin-ajax.php') 
				) );
		
	}

	/**
	 * Shortcode register
	 */
	function shortcode() 
	{
		require TBLG_INCLUDES . 'shortcode.php';
	}

	/**
	 * widget
	 */
	function widget()
	{
		require TBLG_INCLUDES . 'widget.php';
	}

	/**
	 * Register script on plugin
	 */
	function register_script()
	{	

		/**
		 * Lib JS Lodash
		 */
		wp_register_script( 'tb-lodash', TBLG_JS . 'lodash.min.js' );

		/**
		 * Lib JS Gridstack
		 */
		wp_register_script( 'gridstack', TBLG_JS . 'gridstack.js', array( 'jquery', 'tb-lodash' ) );
		wp_register_style( 'gridstack', TBLG_CSS . 'gridstack.css', array(), '1.0' );

		/**
		 * Lib JS Dynamics
		 */
		wp_register_script( 'dynamics', TBLG_JS . 'dynamics.min.js', array() );

		/**
		 * Lib ICON 
		 */
		wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '1.0' );
		wp_register_style( 'ionicon', TBLG_CSS . 'ionicons.min.css', array(), '1.0' );

		/**
		 * Script LemonGrid
		 */
		wp_register_script( 'tb-lemongrid', TBLG_JS . 'lemongrid.js', array( 'jquery', 'gridstack' ) );
		wp_register_style( 'tb-lemongrid', TBLG_CSS . 'lemongrid.css', array(), '1.0' );
	}

	/**
	 * include_script
	 */
	public static function include_script() 
	{
		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-resizable' );
		
		wp_enqueue_script( 'tb-lodash' );

		wp_enqueue_style( 'gridstack' );
		wp_enqueue_script( 'gridstack' );
		
		wp_enqueue_script( 'dynamics' );

		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'ionicon' );
		
		wp_enqueue_style( 'tb-lemongrid' );
		wp_enqueue_script( 'tb-lemongrid' );
		
		/**
		 * Variable
		 */
		$lemongridArr = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			);

		/**
		 * Check admin login
		 * On handle grid builder when login with account admin
		 */
		if( is_super_admin() )
			$lemongridArr['gridBuilder'] = true;

		wp_localize_script( 'tb-lemongrid', 'lemongridObj', $lemongridArr );
	}
}