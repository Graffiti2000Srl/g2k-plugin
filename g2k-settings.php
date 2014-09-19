<?php

abstract class G2K_Settings {
	protected $_settings;

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
		add_action('admin_init', array($this, 'register_settings_addendum'));
	}

	abstract public function register_settings_pages();
	abstract public function register_settings();

	public function register_settings_addendum() {
		register_setting($this->_plugin->prefix . '_settings', $this->_plugin->prefix . '_settings', array($this, 'validate_settings'));
	}

	/**
	 * @return array
	 */
	abstract protected function _get_default_settins();

	public function __set($name, $value) {
		if ($name === 'settings') {
			$this->_settings = $this->validate_settings($value);
			update_option($this->_plugin->prefix . '_settings', $this->_settings);
		} else {
			throw new InvalidArgumentException('Not valid field: "' . $name . '"');
		}
	}

	public function __get($name) {
		if ($name === 'settings') {
			if (!isset($this->_settings)) {
				$this->_settings = shortcode_atts($this->_get_default_settins(), get_option($this->_plugin->prefix . '_settings', array()));
			}

			return $this->_settings;
		}

		throw new InvalidArgumentException('Not valid field: "' . $name . '"');
	}

	public function validate_settings($new_settings) {
		return shortcode_atts( $this->settings, $new_settings );
	}
}