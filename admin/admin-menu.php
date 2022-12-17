<?php
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	add_action( 'admin_menu', 'mediatypes_sub_level_menu' );
	function mediatypes_sub_level_menu(): void
	{
		add_submenu_page( 'options-general.php',
		                  'MediaTypes Settings',
		                  'MediaTypes',
		                  'manage_options',
		                  'mediatypes',
		                  'mediatypes_settings_page_html' );
	}

	function mediatypes_add_toplevel_menu()
	{
		add_menu_page( 'MediaTypes Settings',
		               'MediaTypes',
		               'manage_options',
		               'mediatypes',
		               'mediatypes_settings_page_html',
		               'dashicons-admin-generic',
		               NULL );
	}

	// add_action( 'admin_menu', 'mediatypes_add_toplevel_menu' );