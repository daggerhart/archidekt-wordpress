<?php

namespace Archidekt\Model;

/**
 * Base model for Archidekt response data.
 */
abstract class ApiObjectBase {

	/**
	 * Raw data for the object.
	 *
	 * @var array
	 */
	protected array $data = [];

	/**
	 * @param array $data
	 */
	public function __construct(array $data = []) {
		$this->data = $data;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed|null
	 */
	public function __get(string $name) {
		return $this->data[$name] ?? NULL;
	}

	/**
	 * @return array
	 */
	public function getRawData(): array {
		return $this->data;
	}
}
