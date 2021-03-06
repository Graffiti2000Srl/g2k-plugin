<?php

abstract class G2K_Plugin {
	public $name = '';
	public $slug = '';
	public $prefix = '';
	public $version = '';
	public $capability = '';

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
	 * @return string
	 */
	public function render_template( $default_template_path = null, $variables = array() ) {
		do_action( $this->prefix . '_render_template_pre', $default_template_path, $variables );

		$template_path = dirname( __DIR__ ) . '/view/' . $default_template_path;
		$template_path = apply_filters( $this->prefix . '_template_path', $template_path );

		if ( is_file( $template_path ) ) {
			extract( $variables );
			ob_start();

			require( $template_path );

			$template_content = apply_filters( $this->prefix . '_template_content', ob_get_clean(), $default_template_path, $template_path, $variables );
		} else {
			$template_content = '';
		}


		do_action( $this->prefix . '_render_template_post', $default_template_path, $variables, $template_path, $template_content );
		return $template_content;
	}
}