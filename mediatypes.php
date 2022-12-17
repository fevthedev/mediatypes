<?php
	/**
	 * Plugin Name:       MediaTypes
	 * Plugin URI:        https://fevthedev.com/wordpress-plugins/mediatypes/
	 * Description:       Easily manage WordPress media library file types.
	 * Version:           1.0.0
	 * Requires at least: 5.2
	 * Requires PHP:      7.2
	 * Author:            FevTheDev
	 * Author URI:        https://fevthedev.com/
	 * License:           GPLv2 or later
	 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
	 * Text Domain:       mediatypes
	 * Domain Path:       /languages
	 */

	// exit if file is called directly
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	// load text domain
	function mediatypes_load_textdomain(): void
	{
		load_plugin_textdomain( 'mediatypes', FALSE, plugin_dir_path( __FILE__ ) . 'languages/' );
	}

	add_action( 'plugins_loaded', 'mediatypes_load_textdomain' );

	function mediatypes_activate(): void
	{
		if ( ! current_user_can( 'activate_plugins' ) )
		{
			return;
		}

		$init_account_options = [ 'premium' => FALSE, 'show_welcome_message' => TRUE ];
		add_option( 'mediatypes_options_acc', $init_account_options );
		add_option( 'mediatypes_options_sel', get_allowed_mime_types() );

		// Clear permalinks
		flush_rewrite_rules();
	}

	register_activation_hook( __FILE__, 'mediatypes_activate' );

	// Enqueue admin scripts
	add_action( 'admin_enqueue_scripts', 'mediatypes_enqueue_scripts_admin' );
	function mediatypes_enqueue_scripts_admin()
	{
		$src = plugin_dir_url( __FILE__ ) . 'admin/css/mediatypes_style.css';
		wp_enqueue_style( 'mediatypes', $src, array(), NULL, 'all' );

		$src = plugin_dir_url( __FILE__ ) . 'admin/js/mediatypes_scripts.js';
		wp_enqueue_script( 'mediatypes', $src, array(), NULL, 'all' );
	}

	// include admin menu
	if ( is_admin() )
	{
		// include dependencies
		require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callbacks.php';
	}

	// Register restricted upload mime types
	add_filter( 'upload_mimes', 'mediatypes_uploads_restricted' );
	function mediatypes_uploads_restricted( $mime_types )
	{
		if ( ! get_option( 'mediatypes_options_sel' ) )
		{
			return $mime_types;
		}

		$user_selection = get_option( 'mediatypes_options_sel', [] );
		$differences = array_diff_key( $mime_types, $user_selection );

		if ( ! empty( $differences ) )
		{
			foreach ( $differences as $key => $val )
			{
				unset( $mime_types[ $key ] );
			}
		}

		return $mime_types;
	}

	// Deactivation Hook
	function mediatypes_deactivate(): void
	{
		if ( ! current_user_can( 'activate_plugins' ) )
		{
			return;
		}

		// Clear permalinks
		flush_rewrite_rules();
	}

	register_deactivation_hook( __FILE__, 'mediatypes_deactivate' );

