<?php
	$pro_active = in_array( 'joinus-pro/joinus-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	$plugin_configured = (
		(get_option('ff_joinus_facebook_app_api_key') <> '' && get_option('ff_joinus_facebook_app_secret') <> '')
		|| ( $pro_active && (get_option('ff_joinus_linkedin_api_key') <> '' && get_option('ff_joinus_linkedin_api_secret') <> '') )
		|| ( $pro_active && (get_option('ff_joinus_instagram_client_id') <> '' && get_option('ff_joinus_instagram_client_secret') <> '') )
		|| ( $pro_active && (get_option('ff_joinus_google_client_id') <> '' && get_option('ff_joinus_google_client_secret') <> '') )
	);
?>

<?php if( !$plugin_configured ): ?>

	<h4><?php echo wp_kses_post( sprintf( __( 'Please <a href="%s">configure</a> at least one social network to make this shortcode work.', 'joinus' ), admin_url('options-general.php?page=joinus/core/controller/backend-controller.php') ) ); ?></h4>

<?php else: ?>

	<div class="ff-joinus-caption">
		<div class="ff-joinus-title">
			<<?php echo esc_attr( $data['join_text_tag'] ); ?>>

			<?php echo wp_kses_post( $data['join_text'] ); ?>

			</<?php echo esc_attr( $data['join_text_tag'] ); ?>>
		</div>

		<div class="ff-joinus-action">
			<div class="ff-joinus-join-social-links">

				<?php if( get_option('ff_joinus_facebook_app_api_key') <> '' && get_option('ff_joinus_facebook_app_secret') <> '' ): ?>

				<a href="<?php echo esc_attr( add_query_arg( array(
					'ff-joinus' => 'facebook',
					'ff-joinus-return-url' => add_query_arg( 'ff-joinus-callback', 'facebook', get_permalink( get_the_ID()) )
				), site_url('/') ) ); ?>" class="facebook"><i class="fa fa-facebook-official"></i></a> 

				<?php endif; ?>

				<?php do_action( 'ff_joinus_join_social_links', $data ); ?>

			</div>
			
			<a href="javascript:;" class="ff-joinus-button" data-text="<?php echo esc_attr( $data['join_button_text'] ); ?>" data-active-text="<?php echo esc_attr( $data['join_button_active_text'] ); ?>">
				<?php echo wp_kses_post( $data['join_button_text'] ); ?>
			</a>

		</div>

	</div>

<?php endif; 