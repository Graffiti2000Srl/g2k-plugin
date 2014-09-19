<?php

abstract class G2K_Module {
	/**
	 * @var G2K_Plugin
	 */
	protected $_plugin;

	public function __construct(G2K_Plugin $plugin) {
		$this->_plugin = $plugin;

		$this->_register_hooks();
	}

	abstract protected function _register_hooks();
}