<?php
	// exit if uninstall constant is not defined aka called by WordPress
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit;
	}

	// delete the plugin options
	delete_option( 'mediatypes_options_sel' );
	delete_option( 'mediatypes_options_acc' );