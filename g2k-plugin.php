<?php

require_once __DIR__ . '/g2k-singleton.php';

abstract class G2K_Plugin extends G2K_Singleton {
	const SLUG = '';
	const PREFIX = '';
	const VERSION = '';
	const REQUIRED_CAPABILITY = '';

	/**
	 * Activates the plugin.
	 *
	 * It's only a base method, so it only flushes the rewrite rules.
	 */
	public function activate() {
		flush_rewrite_rules();
	}

	/**
	 * Deactivates the plugin.
	 *
	 * It's only a base method, so it only flushes the rewrite rules.
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Render a template
	 *
	 * Allows parent/child themes to override the markup by placing the a file named basename( $default_template_path ) in their root folder,
	 * and also allows plugins or themes to override the markup by a filter. Themes might prefer that method if they place their templates
	 * in sub-directories to avoid cluttering the root folder. In both cases, the theme/plugin will have access to the variables so they can
	 * fully customize the output.
	 *
	 * @param  string $default_template_path The path to the template, relative to the plugin's `views` folder
	 * @param  array  $variables             An array of variables to pass into the template's scope, indexed with the variable name so that it can be extract()-ed
	 * @param  bool   $require_once          'once' to use require_once() | 'always' to use require()
	 * @return string
	 */
	protected static function _render_template( $default_template_path = null, $variables = array(), $require_once = true ) {
		do_action( static::PREFIX . '_render_template_pre', $default_template_path, $variables );

		$template_path = locate_template( basename( $default_template_path ) );
		if ( ! $template_path ) {
			$template_path = dirname( __DIR__ ) . '/view/' . $default_template_path;
		}
		$template_path = apply_filters( static::PREFIX . '_template_path', $template_path );

		if ( is_file( $template_path ) ) {
			extract( $variables );
			ob_start();

			if ( !$require_once ) {
				require( $template_path );
			} else {
				require_once( $template_path );
			}

			$template_content = apply_filters( static::PREFIX . '_template_content', ob_get_clean(), $default_template_path, $template_path, $variables );
		} else {
			$template_content = '';
		}

		do_action( static::PREFIX . '_render_template_post', $default_template_path, $variables, $template_path, $template_content );
		return $template_content;
	}
}