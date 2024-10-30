<?php

	/**
	 * Oauth controller
	**/

	class ff_joinus_core_controller_front_oauth extends ff_joinus {

		function __construct() {

			parent::__construct();
			$this->run();

		}

		/**
		 * Add necessairy actions
		**/
		function run() {

			add_action( 'template_redirect', array( $this, 'check_request' ));

		}

		/**
		 * Check $_GET['ff-joins'] parameter
		**/
		function check_request() {

			if( !session_id() ) {
				session_start();
			}

			$allowed_networks = array(
				'facebook' => array( 
					'auth' => array( $this, 'auth_facebook' ),
					'callback' => array( $this, 'callback_facebook' ),
				),
			);

			$allowed_networks = apply_filters( 'ff_joius_networks', $allowed_networks );

			if( isset( $_GET['ff-joinus'] ) && in_array( $_GET['ff-joinus'], array_keys( $allowed_networks ) ) ) {

				call_user_func( $allowed_networks[ $_GET['ff-joinus'] ]['auth'] );

			} elseif( isset( $_GET['ff-joinus-callback'] ) && in_array( $_GET['ff-joinus-callback'], array_keys( $allowed_networks ) ) ) {

				call_user_func( $allowed_networks[ $_GET['ff-joinus-callback'] ]['callback'] );

			}

		}

		/**
		 * Auth using Facebook
		**/
		function auth_facebook() {
			require_once $this->plugin_path . 'core/vendor/facebook/autoload.php';

			$fb = new \Facebook\Facebook([
				'app_id' => get_option('ff_joinus_facebook_app_api_key'),
				'app_secret' => get_option('ff_joinus_facebook_app_secret'),
			]);

			$helper = $fb->getRedirectLoginHelper();

			$permissions = ['email', 'picture', 'cover', 'name', 'first_name', 'last_name'];

			$redirect_url = $helper->getLoginUrl( add_query_arg( array(
				'ff-joinus-callback' => 'facebook'
			), add_query_arg( 'ff-joinus-callback', 'facebook', get_permalink( get_the_ID()) ) ) );

			wp_redirect( $redirect_url );

			die;


		}

		/**
		 * Oauth callback for Facebook
		**/
		function callback_facebook() {

			require_once $this->plugin_path . 'core/vendor/facebook/autoload.php';

			$fb = new \Facebook\Facebook([
				'app_id' => get_option('ff_joinus_facebook_app_api_key'),
				'app_secret' => get_option('ff_joinus_facebook_app_secret'),
			]);

			$helper = $fb->getRedirectLoginHelper();
			$_SESSION['FBRLH_state'] = $_GET['state'];

			try {
				$accessToken = $helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				//echo 'Graph returned an error: ' . $e->getMessage();
				//exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues
				//echo 'Facebook SDK returned an error: ' . $e->getMessage();
				//error_log('['.date("H:i:s").']'.'error:'.print_r($e->getMessage(), true)."\n", 3, get_stylesheet_directory().'/errors.log');
				//exit;
			}

			if( isset( $accessToken ) ) {

				$fb_profile = $fb->get('/me?fields=id,name,email,first_name,last_name', $accessToken );
				$fb_avatar = $fb->get('/me/picture?redirect=false&width=500&height=500', $accessToken);

				$facebook_user_data = $fb_profile->getGraphUser();
				$facebook_picture = $fb_avatar->getGraphUser();

				$user_login = 'fb_' . $facebook_user_data['id'];
				$user_email = '';

				if( isset( $facebook_user_data['email'] ) && trim( $facebook_user_data['email'] ) == '' ) {
					$user_email = $facebook_user_data['id'] . '@facebook.com';
				} else {
					$user_email = $facebook_user_data['email'];
				}

				if( !username_exists( $user_login ) && !email_exists( $user_email ) ) {

					$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
					$user_id = wp_create_user( $user_login, $random_password, $user_email );

				} else {
					
					$user = get_user_by( 'login', $user_login );
					$user_id = $user->ID;

				}

				wp_update_user( array(
					'ID' => $user_id,
					'first_name' => $facebook_user_data['first_name'],
					'last_name' => $facebook_user_data['last_name'],
					'user_nicename' => $facebook_user_data['name'],
					'display_name' => $facebook_user_data['name'],
				) );

				update_user_meta( $user_id, 'ff_joinus', 'yes' );
				update_user_meta( $user_id, 'ff_joinus_social_profile_url', 'https://www.facebook.com/profile.php?id=' . $facebook_user_data['id'] );

				if( isset( $facebook_picture['url'] ) ) {
					$avatar_img_id = $this->controller->front->save_avatar( $facebook_picture['url'] );
					update_user_meta( $user_id, 'ff_joinus_photo_id', $avatar_img_id );
				}

			}

		}


	}