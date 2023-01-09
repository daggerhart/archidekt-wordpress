<?php

namespace Archidekt;

/**
 * Simple services container.
 */
class ServicesContainer {

	/**
	 * @var array
	 */
	protected array $registered = [];

	/**
	 * @var array
	 */
	protected array $resolved = [];

	/**
	 * Register a new service.
	 *
	 * @param string $name
	 * @param $service
	 *
	 * @return void
	 */
	public function add(string $name, $service) {
		$this->registered[$name] = $service;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function get(string $name) {
		if (isset($this->resolved[$name])) {
			return $this->resolved[$name];
		}

		return $this->resolveService($name);
	}

	/**
	 * @param string $name
	 *
	 * @return false|mixed
	 * @throws \Exception
	 */
	private function resolveService(string $name) {
		if (!isset($this->registered[$name])) {
			throw new \Exception("Service not registered.");
		}

		if (is_callable($this->registered[$name])) {
			$this->resolved[$name] = call_user_func($this->registered[$name], $this);
			return $this->resolved[$name];
		}

		if (is_string($this->registered[$name]) && class_exists($this->registered[$name])) {
			$this->registered[$name] = new $this->registered[$name]();
			return $this->resolved[$name];
		}

		throw new \Exception("Service unresolveable.");
	}
}
