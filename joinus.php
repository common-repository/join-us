<?php
	/*
		Plugin Name: Join Us
		Plugin URI: https://joinus.fruitfulcode.com/
		Description: Join Us plugin gives an ability to create a gallery of users who are going to visit your event using Facebook login. Enable your guests to use also Linkedin, Google and Instagram with our PRO version.
		Version: 1.0
		Author: fruitfulcode
		Author URI: http://fruitfulcode.com
		License: GPL
*/
if ( !class_exists( 'ff_joinus')) {

	class ff_joinus {

		public $view;
		public $controller;

		public $plugin_path;
		public $plugin_url;
		public $cache_time;

		/**
			Constructor
		**/
		public function __construct() {

			$this->plugin_path = plugin_dir_path( __FILE__ );
			$this->plugin_url = plugin_dir_url( __FILE__ );
			$this->cache_time = '110320170225';

		}

		/**
			Run the core
		**/
		public function run() {
			// internationalization
			load_plugin_textdomain( 'joinus', false, $this->plugin_path . '/languages' );

			// load core classes
			$this->_dispatch();
		}

		/**
		 * Load and instantiate all application
		 * classes neccessary for this plugin
		**/
		private function _dispatch() {

			$this->view = new stdClass();
			$this->controller = new stdClass();

			// Manually instantiate dependency classes first, noticed loading problems on some hosts with autoload

			// View
			require_once $this->plugin_path . '/core/view/view.php';
			$this->view = new ff_joinus_core_view();

			// Controller
			$this->controller->base = $this;

			if( is_admin() ) {

				require_once $this->plugin_path . '/core/controller/backend-controller.php';
				$this->controller->backend = new ff_joinus_core_controller_backend();

			} else {

				require_once $this->plugin_path . '/core/controller/front-controller.php';
				$this->controller->front = new ff_joinus_core_controller_front();

				require_once $this->plugin_path . '/core/controller/front-oauth-controller.php';
				$this->controller->front_oauth = new ff_joinus_core_controller_front_oauth();

			}

			// Inject models, view and controllers from this base controller into all OTHER controllers & models
			foreach ( $this->controller as $controller ) {
				$controller->_inject_application_classes( $this->view, $this->controller );
			}

		}

		/**
		 * Inject models, view and controllers
		 * into all other controllers to make
		 * them callable from there
		**/
		private function _inject_application_classes( $view, $controller ) {

			$this->view = $view;
			$this->controller = $controller;

		}

	}

	global $ff_joinus;
	$ff_joinus = new ff_joinus;
	$ff_joinus->run();

}
