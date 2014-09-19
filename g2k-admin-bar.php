<?php

require_once __DIR__ . '/g2k-module.php';

abstract class G2K_Admin_Bar extends G2K_Module {
	protected function _register_hooks() {
		add_action('admin_bar_menu', array($this, 'create_admin_bar'));
	}

	abstract public function create_admin_bar();
}