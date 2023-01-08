<?php

namespace Archidekt\Model;

/**
 * @property string $cacheEnabled Raw setting value.
 * @property string $cacheLifetime Raw setting value.
 */
class Settings {

	/**
	 * Raw settings values array.
	 *
	 * @var array
	 */
	protected array $values;

	/**
	 * @param array $settings
	 */
	private function __construct(array $settings = []) {
		$this->values = $settings;
	}

	/**
	 * Get a singleton instance for settings. Why not.
	 *
	 * @return static
	 */
	public static function instance(): Settings {
		static $instance = NULL;
		if (is_null($instance)) {
			$instance = new static(\get_option('archidekt_settings', [
				'cacheEnabled' => TRUE,
				'cacheLifetime' => DAY_IN_SECONDS,
			]));
		}

		return $instance;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed|string|array|null
	 */
	public function __get(string $name) {
		return $this->values[$name] ?? NULL;
	}

	/**
	 * @return bool
	 */
	public function cacheEnabled(): bool {
		return (bool) $this->values['cacheEnabled'];
	}

	/**
	 * @return int
	 */
	public function cacheLifetime(): int {
		return (int) $this->values['cacheLifetime'];
	}

}
