<?php

	/**
	 * Front-end part of plugin
	**/

	class ff_joinus_core_controller_front extends ff_joinus {

		function __construct() {

			parent::__construct();
			$this->run();

		}

		/**
		 * Here we add actions / filters etc for a front-end side
		**/
		function run() {

			// add assets for a front-end side
			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

			// register a shortcode
			add_shortcode( 'ff_joinus', array( $this, 'add_shortcode') );

		}

		/**
		 * Add assets for a front-end side
		**/
		function load_assets() {

			wp_register_script( 'masonry', $this->plugin_url . 'assets/libs/masonry.pkgd.min.js', array( 'jquery' ), $this->cache_time, true );
			wp_register_script( 'waitforimages', $this->plugin_url . 'assets/libs/jquery.waitforimages.min.js', array( 'jquery' ), $this->cache_time, true );
			wp_register_script( 'ff-joinus-front', $this->plugin_url . 'assets/js/front.js', array( 'jquery', 'masonry', 'waitforimages' ), $this->cache_time, true );

			wp_enqueue_script( 'ff-joinus-front' );

			wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false );
			wp_enqueue_style( 'ff-joinus-front', $this->plugin_url . 'assets/css/front.css', false );

		}

		/**
		 * Add a shortcode
		**/
		function add_shortcode( $atts ) {

			$atts = shortcode_atts( array(
				'id' => 'ff-joinus-shortcode',
				/**
					Query params
				**/
				'order' => 'ASC',
				'orderby' => 'display_name',
				'number' => 12,
				/**
					Image / avatar params
				**/
				'photo_width' => 150,
				'photo_height' => 150,
				'display_name' => 'yes',
				/**
					Possible options (more effects soon):
					- (grayscale)
				**/
				'hover_effect' => 'grayscale',
				'col_width' => '25%',
				'col_width_medium_screen' => '33.333%',
				'col_width_small_screen' => '50%',
				'grid_margins' => '20px',
				/**
					Possible options:
					- (top) - before grid
					- (bottom) - after grid
				**/
				'join_text_position' => 'bottom',
				'join_text_tag' => 'h4',
				'join_text' => esc_html__( 'Want to go? Join us, we are waiting', 'joinus'),
				'join_button_text' => esc_html__( 'Join Us', 'joinus'),
				'join_button_active_text' => esc_html__( 'Choose network', 'joinus'),

				'button_color' => '',
				'button_hover_color' => '',
				'button_shadow_color' => '',
				'tooltip_color' => '',
				'tooltip_icon_color' => '',
				'tooltip_icon_hover_color' => '',
				'name_overlay_color' => '',
				'name_overlay_text_color' => '',
			), $atts );

			ob_start();
			$this->view->load( 'front/shortcode', $atts );
			return ob_get_clean();

		}

		/**
		 * Save user avatar to WP media library
		**/
		function save_avatar( $url ) {

			$filename = 'avatar' . uniqid() . '.jpg';

			$uploaddir = wp_upload_dir();
			$uploadfile = $uploaddir['path'] . '/' . $filename;

			$contents= file_get_contents( $url );

			$savefile = fopen( $uploadfile, 'w');
			fwrite( $savefile, $contents);
			fclose( $savefile);

			$wp_filetype = wp_check_filetype( basename( $filename), null );

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => $filename,
				'post_content' => '',
				'post_status' => 'inherit'
			);

			$attach_id = wp_insert_attachment( $attachment, $uploadfile );

			return $attach_id;

		}

	}