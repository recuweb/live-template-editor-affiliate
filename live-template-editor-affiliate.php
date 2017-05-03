<?php
/*
 * Plugin Name: Live Template Editor Affiliate
 * Version: 1.0
 * Plugin URI: https://github.com/rafasashi
 * Description: Another Live Template Editor affiliate.
 * Author: Rafasashi
 * Author URI: https://github.com/rafasashi
 * Requires at least: 4.6
 * Tested up to: 4.7
 *
 * Text Domain: ltple
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Rafasashi
 * @since 1.0.0
 */
	
	/**
	* Add documentation link
	*
	*/
	
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	if(!function_exists('is_dev_env')){
		
		function is_dev_env( $dev_ip = '176.132.10.223' ){
			
			if( $_SERVER['REMOTE_ADDR'] == $dev_ip || ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == $dev_ip ) ){
				
				return true;
			}

			return false;		
		}
	}
	
	if(!function_exists('ltple_row_meta')){
	
		function ltple_row_meta( $links, $file ){
			
			if ( strpos( $file, basename( __FILE__ ) ) !== false ) {
				
				$new_links = array( '<a href="https://github.com/rafasashi" target="_blank">' . __( 'Documentation', 'cleanlogin' ) . '</a>' );
				$links = array_merge( $links, $new_links );
			}
			
			return $links;
		}
	}
	
	/**
	 * Returns the main instance of LTPLE_Affiliate to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object LTPLE_Affiliate
	 */
	function LTPLE_Affiliate ( $version = '1.0.0' ) {
		
		$instance = LTPLE_Client::instance( __FILE__, $version );
		
		if ( is_null( $instance->affiliate ) ) {
		
			$instance = LTPLE_Affiliate::instance( $instance, $version );
		}

		return $instance;
	}	
	
	add_filter('plugin_row_meta', 'ltple_row_meta', 10, 2);
	
	add_filter( 'plugins_loaded', function(){

		$mode = ( is_dev_env() ? '-dev' : '');
		
		if( $mode == '-dev' ){
			
			ini_set('display_errors', 1);
		}

		// Load plugin functions
		require_once( 'includes'.$mode.'/functions.php' );	
		
		// Load plugin class files

		require_once( 'includes'.$mode.'/class-ltple.php' );
		require_once( 'includes'.$mode.'/class-ltple-settings.php' );

		// Autoload plugin libraries
		
		$lib = glob( __DIR__ . '/includes'.$mode.'/lib/class-ltple-*.php');
		
		foreach($lib as $file){
			
			require_once( $file );
		}
	
		if( $mode == '-dev' ){
			
			LTPLE_Affiliate('1.1.1');
		}
		else{
			
			LTPLE_Affiliate('1.1.0');
		}		
	});
	