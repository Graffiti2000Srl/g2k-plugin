<?php

abstract class G2K_Settings {
	protected $_settings = array();

	/**
	 * @var G2K_Plugin
	 */
	protected $_plugin;

	public function __construct(G2K_Plugin $plugin) {
		$this->_plugin = $plugin;

		$this->_register_hooks();
	}

	protected function _register_hooks() {
		add_action('admin_menu', array($this, 'register_settings_pages'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	abstract public function register_settings_pages();
	abstract public function register_settings();

	/**
	 * @return array
	 */
	abstract protected function _get_default_settins();

	public function __set($name, $value) {
		if ($name === 'settings') {
			$this->_settings = $this->_validate_settings($value);
			update_option($this->_slug . '_settings', $this->_settings);
		} else {
			throw new InvalidArgumentException('Not valid field: "' . $name . '"');
		}
	}

	public function __get($name) {
		if ($name === 'settings' and !isset($this->_settings)) {
			$this->_settings = shortcode_atts($this->_get_default_settins(), get_option($this->_slug . '_settings', array()));

			return $this->_settings;
		}

		throw new InvalidArgumentException('Not valid field: "' . $name . '"');
	}

	protected function _validate_settings($new_settings) {
		return shortcode_atts( $this->_settings, $new_settings );
	}
}