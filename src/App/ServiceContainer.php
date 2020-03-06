<?php

namespace Give\App;

class ServiceContainer {
	/**
	 * Aliases
	 *
	 * @since 2.6.1
	 * @var array
	 */
	private $aliases;

	/**
	 * Singletons
	 *
	 * @since 2.6.1
	 * @var array
	 */
	private $singletons;

	/**
	 * Singletons
	 *
	 * @since 2.6.1
	 * @var array
	 */
	private $classPath;


	public function __construct() {
		$this->aliases    = require GIVE_PLUGIN_DIR . 'src/APP/Config/aliases.php';
		$this->singletons = require GIVE_PLUGIN_DIR . 'src/APP/Config/singletons.php';
		$this->classPath  = require GIVE_PLUGIN_DIR . 'src/APP/Config/classPath.php';
	}
}
