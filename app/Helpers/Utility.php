<?php 

namespace ThemePaste\ShippingManager\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Utility class with static helper functions for general use throughout the plugin.
 */
class Utility {

    /**
	 * Prints information about a variable in a more readable format.
	 *
	 * @param mixed $data The variable you want to display.
	 * @param bool  $admin_only Should it display in wp-admin area only
	 * @param bool  $hide_adminbar Should it hide the admin bar
	 */
	public static function pri( $data, $admin_only = true, $hide_adminbar = true ) {
		if ( $admin_only && ! current_user_can( 'manage_options' ) ) {
			return;
		}

		echo '<pre>';
		if ( is_object( $data ) || is_array( $data ) ) {
			print_r( $data );
		} else {
			var_dump( $data );
		}
		echo '</pre>';

		if ( is_admin() && $hide_adminbar ) {
			echo '<style>#adminmenumain{display:none;}</style>';
		}
	}

    /**
	 * Includes a template file from the 'view' directory.
	 *
	 * @param string $template The template file name.
	 * @param array  $args Optional. An associative array of variables to pass to the template file.
	 */
	public static function get_template( $template, $args = array() ) {
		// Never let a caller escape the views directory.
		$template = ltrim( str_replace( '\\', '/', $template ), '/' );

		if ( false !== strpos( $template, '..' ) ) {
			return '';
		}

		$path = TPSM_PLUGIN_DIR . 'views/' . $template;

		if ( file_exists( $path ) ) {
			if ( ! empty( $args ) && is_array( $args ) ) {
				// EXTR_SKIP so a settings key can never clobber $path/$template/$args.
				extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			}

			ob_start();
			include $path;
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Includes a template file from the 'shipping-manager-pro/views' directory.
	 *
	 * This method is used to load a view/template file specifically from the pro version
	 * of the plugin. It supports passing variables to the template via an associative array.
	 *
	 * @param string $template The relative path to the template file inside the 'shipping-manager-pro/views/' directory.
	 * @param array  $args     Optional. An associative array of variables to extract into the template's scope.
	 *
	 * @return string|null The output of the template file, or null if the file doesn't exist.
	 */
	public static function get_pro_template( $template, $args = array() ) {
		// is_plugin_active() lives in wp-admin and is not loaded on the frontend
		// or during AJAX, where calling it used to be a fatal error.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$template = ltrim( str_replace( '\\', '/', $template ), '/' );

		if ( false !== strpos( $template, '..' ) ) {
			return '';
		}

		if ( is_plugin_active( 'shipping-manager-pro/shipping-manager-pro.php' ) ) {
			$path = TPSM_REAL_PATH . '/shipping-manager-pro/views/' . $template;

			if ( file_exists( $path ) ) {
				if ( ! empty( $args ) && is_array( $args ) ) {
					extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
				}

				ob_start();
				include $path;
				return ob_get_clean();
			}
		}

		return '';
	}

	/**
	 * @param string $var the variable name 
	 * @return string
	 */
	public static function get_screen( $var = '' ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen routing.
		return isset( $_GET[ $var ] ) ? sanitize_key( wp_unslash( $_GET[ $var ] ) ) : null;
	}


}