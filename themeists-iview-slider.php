<?php
/*
Plugin Name: Themeists iView Slider
Plugin URI: #
Description: Allows you to easily add an incredibly power content slider to your site
Version: 1.0
Author: Themeists
Author URI: #
License: GPL2
*/

	if( !class_exists( 'ThemeistsiViewSlider' ) ):

		
		class ThemeistsiViewSlider
		{


			/**
			 * We might not be using a themeists theme (which means we can't add anything to the options panel). By default,
			 * we'll say we are not. We check if the theme's author is Themeists to set this to true during instantiation.
			 *
			 * @author Richard Tape
			 * @package ThemeistsiViewSlider
			 * @since 1.0
			 */
			
			var $using_themeists_theme = false;


			/**
			 * Initialise ourselves and do a bit of setup
			 *
			 * @author Richard Tape
			 * @package ThemeistsiViewSlider
			 * @since 1.0
			 * @param None
			 * @return None
			 */

			function ThemeistsiViewSlider()
			{
				
				add_action( 'widgets_init', array( &$this, 'register_widgets' ), 1 );

				//we need to add a filter to plugins_url as we use symlinks in our dev setup
				add_filter( 'plugins_url', array( &$this, 'local_dev_symlink_plugins_url_fix' ), 10, 3 );

				$theme_data = wp_get_theme();
				$theme_author = $theme_data->display( 'Author', false );

				if( strtolower( trim( $theme_author ) ) == "themeists" )
					$this->using_themeists_theme = true;

			}/* ThemeistsiViewSlider() */


			/* =============================================================================== */


			/**
			 * Call the actual widget.
			 * 
			 *
			 * @author Richard Tape
			 * @package ThemeistsiViewSlider
			 * @since 1.0
			 * @param None
			 * @return None
			 */

			function register_widgets()
			{

				require_once( 'themeists-iview-slider-widget.php' );

			}/* register_widgets() */


			/* =============================================================================== */


			/**
			 * Edit the plugins_url() url to be appropriate for this widget (we use symlinks on local dev)
			 *
			 * @author Richard Tape
			 * @package Chemistry
			 * @since 0.7
			 */
			
			function local_dev_symlink_plugins_url_fix( $url, $path, $plugin )
			{

				// Do it only for this plugin
				if ( strstr( $plugin, basename( __FILE__ ) ) )
					return str_replace( dirname( __FILE__ ), '/' . basename( dirname( $plugin ) ), $url );

				return $url;

			}/* local_dev_symlink_plugins_url_fix() */


		}/* class ThemeistsiViewSlider */


	endif;

	$themeistsiviewslider = new ThemeistsiViewSlider;

?>