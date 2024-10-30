<div class="ff-joinus-settings-wrap wrap">

	<h2><?php esc_html_e( 'Join Us Plugin Settings', 'joinus' ); ?></h2>

	<form method="post" action="options.php">

		<?php settings_fields( 'ff_joinus_settings' ); ?>

		<div class="ff-joinus-settings-form">
			<h2><?php esc_html_e( 'Facebook API', 'joinus' ); ?></h2>

			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Facebook App API Key', 'joinus' ); ?></th>
					<td>

						<input type="text" class="widefat" name="ff_joinus_facebook_app_api_key" value="<?php echo esc_attr( get_option('ff_joinus_facebook_app_api_key') ); ?>" />

					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Facebook App Secret', 'joinus' ); ?></th>
					<td>

						<input type="text" class="widefat" name="ff_joinus_facebook_app_secret" value="<?php echo esc_attr( get_option('ff_joinus_facebook_app_secret') ); ?>" />

					</td>
				</tr>

			</table>
		</div>

		<?php do_action( 'ff_joinus_display_settings_page'); ?>

		<?php submit_button(); ?>

	</form>

</div>