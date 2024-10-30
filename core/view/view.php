<?php

	/**
	 * Anything to do with templates
	 * and outputting client code
	**/
	class ff_joinus_core_view extends ff_joinus {

		function load( $path = '', $data = array() ) {

			// Check for template in theme's path as a first thing
			$theme_dir = get_stylesheet_directory();
			$override_path = $theme_dir . '/ff-joinus-templates/' . $path . '.php';
			$default_path = $this->plugin_path . '/core/view/' . $path . '.php';

			// If user places a template in theme's directory, override default template
			if( file_exists( $override_path ) ) {

				require $override_path;

			// if not, load default template
			} elseif( file_exists( $default_path ) ) {

				require $default_path;

			// if template path doesn't exist
			} else {

				throw new Exception( 'The view path ' . $full_path . ' can not be found.' );

			}

		}

	}