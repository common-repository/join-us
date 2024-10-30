<?php

	/**
	 * Back-end part of plugin
	 **/

	class ff_joinus_core_controller_backend extends ff_joinus {

		function __construct() {

			parent::__construct();
			$this->run();

		}

		/**
		 * Here we add actions / filters etc for a backend side
		 **/
		function run() {

			// add admin styles
			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets') );

			// register plugin settings
			add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );

			// add settings menu
			add_action( 'admin_menu', array( $this, 'add_menu') );

		}

		/**
		 * Add scripts and styles
		 **/
		function load_assets() {

			wp_enqueue_style( 'ff-joinus-backend', $this->plugin_url . 'assets/css/admin.css' );

		}

		/**
		 * Register plugin settings
		**/
		function register_plugin_settings() {

			register_setting( 'ff_joinus_settings', 'ff_joinus_facebook_app_api_key' );
			register_setting( 'ff_joinus_settings', 'ff_joinus_facebook_app_secret' );

			register_setting( 'ff_joinus_settings', 'ff_joinus_linkedin_api_key' );
			register_setting( 'ff_joinus_settings', 'ff_joinus_linkedin_api_secret' );
			
			register_setting( 'ff_joinus_settings', 'ff_joinus_instagram_client_id' );
			register_setting( 'ff_joinus_settings', 'ff_joinus_instagram_client_secret' );

			register_setting( 'ff_joinus_settings', 'ff_joinus_google_client_id' );
			register_setting( 'ff_joinus_settings', 'ff_joinus_google_client_secret' );

		}

		/**
		 * Add settings menu
		 **/
		function add_menu() {
			add_submenu_page( 'options-general.php', esc_html__( 'Join Us Plugin Settings', 'joinus' ), esc_html__( 'Join Us Plugin Settings', 'joinus' ), 'administrator', __FILE__, array( $this, 'display_settings_page' ) );
		}

		/**
		 * Display plugin's settings page
		**/
		function display_settings_page() {

			$this->view->load( 'backend/settings' );

		}

	}