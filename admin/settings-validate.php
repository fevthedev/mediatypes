<?php
	// exit if file is called directly
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	// callback: validate options
	function mediatypes_callback_validate_options( $input ): array
	{
		$check_type = isset( $_GET[ 'type' ] ) ? sanitize_text_field( $_GET[ 'type' ] ) : NULL;
		$current_selection_options = get_option( 'mediatypes_options_sel', [] );

		$filtered_selection_options = $check_type ? array_filter( $current_selection_options, function ( $item ) use (
			$check_type
		)
		{
			return str_contains( $item, $check_type );
		} ) : [];

		$diff_user_selection = array_diff_key( $filtered_selection_options, $input );

		if ( ! empty( $diff_user_selection ) )
		{
			foreach ( $diff_user_selection as $key => $val )
			{
				unset( $current_selection_options[ $key ] );
			}
		}

		return array_merge( $input, $current_selection_options );
	}