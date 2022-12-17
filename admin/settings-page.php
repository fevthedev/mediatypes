<?php
	if ( ! defined( 'ABSPATH' ) )
	{
		exit;
	}

	function mediatypes_get_fields( $inType ): void
	{
		$setting_group = 'mediatypes_options';
		settings_fields( $setting_group );
		do_settings_sections( 'mediatypes' );
	}

	// display the plugin settings page
	function mediatypes_settings_page_html(): void
	{
		if ( ! current_user_can( 'manage_options' ) )
		{
			return;
		}

		// Get the active tab from $_GET
		$default_tab = 'settings';
		$tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) : $default_tab;

		?>
		<div class="wrap">
			<h1><?php esc_html_e( get_admin_page_title(), 'mediatypes' ) ?></h1>

			<!--			tabs-->
			<nav class='nav-tab-wrapper'>
				<a href='?page=mediatypes&tab=audio'
				   class='nav-tab <?php if ( $tab === 'audio' ): ?>nav-tab-active<?php endif; ?>'>Audio</a>
				<a href='?page=mediatypes&tab=image'
				   class='nav-tab <?php if ( $tab === 'image' ): ?>nav-tab-active<?php endif; ?>'>Image</a>
				<a href='?page=mediatypes&tab=video'
				   class='nav-tab <?php if ( $tab === 'video' ): ?>nav-tab-active<?php endif; ?>'>Video</a>
				<a href='?page=mediatypes&tab=text'
				   class='nav-tab <?php if ( $tab === 'text' ): ?>nav-tab-active<?php endif; ?>'>Text</a>
				<a href='?page=mediatypes&tab=application'
				   class='nav-tab <?php if ( $tab === 'application' ): ?>nav-tab-active<?php endif;
				   ?>'>Applications</a>
				<a href='?page=mediatypes&tab=settings'
				   class='nav-tab <?php if ( $tab === 'settings' ): ?>nav-tab-active<?php endif;
				   ?>'>Settings</a>
			</nav>


			<form action="options.php?type=<?php echo esc_html( $tab ); ?>"
				  method="post"
				  id='mediatypes-form'>

				<div class='tab-content'>
					<?php
						mediatypes_get_fields( $tab );
						if ( $tab !== 'settings' )
						{
							submit_button();
						}
					?>
				</div>

			</form>
		</div>

		<?php
	}
