<?php
	// Registers Plugin Settings and Data

	// exit if file is called directly
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	// register plugin settings
	add_action( 'admin_init', 'mediatypes_register_settings' );
	function mediatypes_register_settings(): void
	{
		$type = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : '';
		$section_title = $type !== 'settings' && $type !== '' ?
			ucfirst( $type ) . esc_html__( ' file-type selections', 'mediatypes' ) : '';

		// Settings Groups
		register_setting( 'mediatypes_options',
		                  'mediatypes_options_sel',
		                  'mediatypes_callback_validate_options' );

		// Sections
		add_settings_section( 'mediatypes_section_selection',
		                      $section_title,
		                      'mediatypes_callback_display_section',
		                      'mediatypes' );
	}
