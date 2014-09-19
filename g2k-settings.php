<?php

abstract class G2K_Settings {
	protected $_settings = array();
	protected $_slug = '';

	public function __construct($slug) {
		$this->_slug = $slug;
	}

	/**
	 * @return array
	 */
	abstract protected function _get_default_settins();

	public function get_settings() {
		return shortcode_atts($this->_get_default_settins(), get_option($this->_slug . '_settings', array()));
	}

	public function __set($name, $value) {
		if ($name === 'settings') {
			$this->_settings = $this->_validate_settings($value);
			update_option($this->_slug . '_settings', $this->_settings);
		}
	}

	protected function _validate_settings($new_settings) {
		return shortcode_atts( $this->_settings, $new_settings );
	}
}