<?php
	/**
	 * @param $data shortcode attributes
	**/

	if( ! class_exists( 'Aq_Resize' )) {
		require_once $this->plugin_path . 'core/vendor/aq_resizer/aq_resizer.php';
	}

	$users = new WP_User_Query( array(
		'order' => $data['order'],
		'orderby' => $data['orderby'],
		'number' => $data['number'],
		'meta_query' => array(
			array(
				'key' => 'ff_joinus',
				'value' => 'yes',
				'compare' => '='
			)
		)
	));

?>

<div id="<?php echo esc_attr( $data['id'] ); ?>" class="ff-joinus">

	<?php if( $data['join_text_position'] === 'top' ): ?>

		<?php
			// Title and button
			include 'caption.php';
		?>

	<?php endif; ?>

	<?php if( ! empty( $users->results ) ): ?>
	<div class="ff-joinus-grid ff-hover-effect-<?php echo esc_attr( $data['hover_effect'] ); ?>" data-margins="<?php echo esc_attr( $data['grid_margins'] ); ?>" data-col-width="<?php echo esc_attr( $data['col_width'] ); ?>" data-col-width-medium="<?php echo esc_attr( $data['col_width_medium_screen'] ); ?>" data-col-width-small="<?php echo esc_attr( $data['col_width_small_screen'] ); ?>">
		<?php foreach ( $users->results as $user ): ?>

			<div class="ff-joinus-grid-item">

				<div class="ff-joinus-grid-item-inside">

					<div class="ff-joinus-grid-item-overlay"></div>

					<div class="ff-joinus-grid-item-image">
						<?php

							$profile_url = get_user_meta( $user->ID, 'ff_joinus_social_profile_url', true );
							$profile_url = $profile_url <> '' ? $profile_url : 'javascript:;'
						
						?>

						<a target="_blank" rel="nofollow" href="<?php echo esc_attr( $profile_url ); ?>">
							<?php
								$social_avatar = get_user_meta( $user->ID, 'ff_joinus_photo_id', true );
								if( is_numeric( $social_avatar ) ) {

									$attachment_url = wp_get_attachment_url( $social_avatar );
									$resized = aq_resize( $attachment_url, $data['photo_width'], $data['photo_height'], true );
									$src = !$resized ? $attachment_url : $resized;
									echo '<img src="' . esc_attr( $src ) . '" alt="" />';

								} else {
									// fallback to default avatar
									echo get_avatar( $user->ID, $data['photo_width'] );
								}
							?>
						</a>
					</div>

					<?php if( filter_var( $data['display_name'], FILTER_VALIDATE_BOOLEAN ) ): ?>
					<div class="ff-joinus-grid-item-text">
						<?php
							$user_info = get_userdata( $user->ID );
							echo wp_kses_post( $user_info->display_name );
						?>
					</div>
					<?php endif; ?>

				</div>

			</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<?php if( $data['join_text_position'] === 'bottom' ): ?>

		<?php
			// Title and button
			include 'caption.php';
		?>

	<?php endif; ?>

</div>

<?php
	/**
	 * Custom styles
	**/
?>
<style type="text/css">

	<?php if( $data['button_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-button {
		background-color: <?php echo $data['button_color']; ?>;
	}
	<?php endif; ?>

	<?php if( $data['button_hover_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-button:hover,
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-button:active,
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-button:focus {
		background-color: <?php echo $data['button_hover_color']; ?>;
	}
	<?php endif; ?>

	<?php if( $data['button_shadow_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-button:hover,
	#<?php echo $data['id']; ?> .ff-joinus-action.open .ff-joinus-button {
		box-shadow: 0px 0px 0px 9px <?php echo $data['button_shadow_color']; ?>;
	}
	<?php endif; ?>

	<?php if( $data['tooltip_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-join-social-links {
		background-color: <?php echo $data['tooltip_color']; ?>;
	}
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-join-social-links > a:after {
		border-color: <?php echo $data['tooltip_color']; ?> transparent transparent transparent;
	}
	<?php endif; ?>

	<?php if( $data['tooltip_icon_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-join-social-links > a {
		color: <?php echo $data['tooltip_icon_color']; ?>;
	}
	<?php endif; ?>

	<?php if( $data['tooltip_icon_hover_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-join-social-links > a:hover,
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-join-social-links > a:active,
	#<?php echo $data['id']; ?> .ff-joinus-caption .ff-joinus-join-social-links > a:focus {
		color: <?php echo $data['tooltip_icon_hover_color']; ?>;
	}
	<?php endif; ?>

	<?php if( $data['name_overlay_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-grid-item-text {
		background-color: <?php echo $data['name_overlay_color']; ?>;
	}
	<?php endif; ?>

	<?php if( $data['name_overlay_text_color'] <> '' ): ?>
	#<?php echo $data['id']; ?> .ff-joinus-grid-item-text {
		color: <?php echo $data['name_overlay_text_color']; ?>;
	}
	<?php endif; ?>
	
</style>