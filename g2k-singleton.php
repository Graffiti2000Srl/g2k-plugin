<?php

abstract class G2K_Singleton {
	/**
	 * @var G2K_Singleton
	 */
	protected static $_instance = null;

	protected function __construct(){}
	protected function __clone(){}

	/**
	 * @return G2K_Singleton
	 */
	public static function getInstance() {
		if (!isset(static::$_instance)) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}
}