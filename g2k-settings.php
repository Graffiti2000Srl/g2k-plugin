<?php

abstract class G2K_Settings {
	protected $_settings = array();
	protected $_slug = '';

	public function __construct($slug) {
		$this->_slug = $slug;

		$this->_register_hooks();
	}

	protected function _register_hooks() {
		add_action('admin_menu', array($this, '_register_settings_pages'));
		add_action('admin_init', array($this, '_register_settings'));
	}

	abstract protected function _register_settings_pages();
	abstract protected function _register_settings();

	/**
	 * @return array
	 */
	abstract protected function _get_default_settins();

	public function __set($name, $value) {
		if ($name === 'settings') {
			$this->_settings = $this->_validate_settings($value);
			update_option($this->_slug . '_settings', $this->_settings);
		} else {
			throw new InvalidArgumentException('Not valid field');
		}
	}

	public function __get($name) {
		if ($name === 'settings' and !isset($this->_settings)) {
			$this->_settings = shortcode_atts($this->_get_default_settins(), get_option($this->_slug . '_settings', array()));

			return $this->_settings;
		}

		throw new InvalidArgumentException('Not valid field');
	}

	protected function _validate_settings($new_settings) {
		return shortcode_atts( $this->_settings, $new_settings );
	}
}