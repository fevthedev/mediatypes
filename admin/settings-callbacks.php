<?php

	// exit if file is called directly
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	const SUPPORT_EMAIL = 'help@fevthedev.com';

	function mediatypes_callback_display_section(): void
	{
		$type = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : 'settings';
		if ( $type === 'settings' )
		{
			mediatypes_callback_settings_section();
		}
		else
		{
			echo '<h4>Select the ' . esc_html( $type ) . ' file types that you would like to support on your website.</h4>';
			mediatypes_show_file_types( $type );
		}
	}

	function mediatypes_callback_settings_section(): void
	{
		$account_options = get_option( 'mediatypes_options_acc', FALSE );
		$show_welcome_message = is_array( $account_options ) ? $account_options[ 'show_welcome_message' ] : FALSE;
		$welcome_message = '<h2 class="welcome-message">' . esc_html__( 'Thank you for supporting this plugin. Lot\'s of exciting
		updates to come!',
		                                                                'mediatypes' ) . '</h2>';
		$rating_message = '<p class="rating-message">';
		$rating_message .= __( 'Loving MediaTypes so far? Leave a rating - it will only take a second', 'mediatypes' );
		$rating_message .= ' 😊</p>';
		$donations_message = '<h2>' . __( 'Buy me a coffee?', 'mediatypes' ) . '</h2>';
		$donation_links = [
			'$5'                        => 'https://buy.stripe.com/8wMbM811VaMGfCg004',
			'$10'                       => 'https://buy.stripe.com/cN26rO6mf7AugGk001',
			'$25'                       => 'https://buy.stripe.com/3cs2by7qjf2Weyc9AC',
			__( 'Other', 'mediatypes' ) => 'https://buy.stripe.com/9AQ2bydOHbQK0Hm28b'
		];
		$support_message = '<h2>Support Links</h2><p>' . __( 'Having issues? Email me at', 'mediatypes' ) . ' <a href="mailto:'
		                   . sanitize_email( SUPPORT_EMAIL ) . '" title="support link">' . sanitize_email( SUPPORT_EMAIL )
		                   . '</a></p>';

		// html output
		echo "<div class='mediatypes-settings-page'>";
		echo $show_welcome_message ? wp_kses_post( $welcome_message ) : wp_kses_post( $rating_message );
		echo wp_kses_post( $donations_message );
		echo "<div><ul class='donation-link-list'>";
		foreach ( $donation_links as $price => $link )
		{
			echo wp_kses_post( "<li><a href='" . esc_url( $link ) . "' target='_blank'>" . esc_html( $price ) . "</a></li>" );
		}
		echo "</ul></div>";
		echo wp_kses_post( $support_message );
		echo "</div>";

		// turn off show welcome message flag after initial visit
		if ( $show_welcome_message )
		{
			$account_options[ 'show_welcome_message' ] = FALSE;
			update_option( 'mediatypes_options_acc', $account_options );
		}
	}

	function mediatypes_filter_by_type( $inArray, $inType ): array
	{
		return array_filter( $inArray, function ( $item ) use ( $inType )
		{
			return str_contains( $item, $inType );
		} );
	}

	function mediatypes_show_file_types( $inType ): void
	{
		// Check for existing options - selected mediatypes
		$allowed_mime_types = wp_get_mime_types();
		$allowed_mime_types = mediatypes_filter_by_type( $allowed_mime_types, $inType );
		ksort( $allowed_mime_types );

		$user_selection = get_option( 'mediatypes_options_sel', $allowed_mime_types );
		$user_selection = mediatypes_filter_by_type( $user_selection, $inType );

		echo wp_kses_post( '<fieldset class="mediatypes_display_btn_wrapper">' );

		foreach ( $allowed_mime_types as $key => $value )
		{
			$label = sizeof( explode( '|', $key ) ) > 1 ? str_replace( '|', ' | ', $key ) : $key;
			$id = sizeof( explode( '|', $key ) ) > 1 ? str_replace( '|', '_', $key ) : $key;
			$checked = array_key_exists( $key, $user_selection ) ? 'checked="checked"' : '';
			$activeClass = empty( $checked ) ? '' : 'active';
			echo '<label class="mediatypes_btn ' . esc_attr( $activeClass ) . '" for="mediatypes_' . esc_attr( $id ) . '">';
			echo '<input id="mediatypes_' . esc_attr( $id ) . '" name="mediatypes_options_sel[' . esc_attr( $key )
			     . ']" type="checkbox" value="' . esc_html( $value ) . '"' . esc_attr( $checked ) . '>' . esc_html( $label )
			     . '</label>';
		}

		echo '</fieldset>';
	}
