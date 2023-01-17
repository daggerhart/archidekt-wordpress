<?php

namespace Archidekt\Service;

/**
 * Cache service.
 */
class Cache {

	/**
	 * Whether to cache.
	 *
	 * @var bool
	 */
	private bool $cacheEnabled;

	/**
	 * How long the cache should last in seconds.
	 *
	 * @var int
	 */
	private int $cacheLifetime;

	/**
	 * Cache id prefix.
	 *
	 * @var string
	 */
	private string $prefix = 'archidekt/';

	/**
	 * Constructor.
	 *
	 * @param bool $cache_enabled
	 * @param int $cache_lifetime
	 */
	public function __construct(bool $cache_enabled = TRUE, int $cache_lifetime = DAY_IN_SECONDS) {
		$this->cacheEnabled = $cache_enabled;
		$this->cacheLifetime = $cache_lifetime;
	}

	/**
	 * Cache enabled.
	 *
	 * @return bool
	 */
	public function isEnabled(): bool {
		return $this->cacheEnabled;
	}

	/**
	 * Cache lifetime.
	 *
	 * @return int
	 */
	public function getLifetime(): int {
		return $this->cacheLifetime;
	}

	/**
	 * Look up data for cached version.
	 *
	 * @param string $cache_id
	 *
	 * @return false|array
	 */
	public function getCache(string $cache_id) {
		if (!$this->cacheEnabled) {
			return FALSE;
		}

		return \get_transient($this->prefix . $cache_id);
	}

	/**
	 * Cache data as a transient.
	 *
	 * @param string $cache_id
	 * @param array $cache_data
	 * @param int|null $lifetime
	 *
	 * @return bool
	 */
	public function setCache(string $cache_id, array $cache_data, int $lifetime = null): bool {
		if (!$this->isEnabled()) {
			return FALSE;
		}

		return \set_transient($this->prefix . $cache_id, $cache_data, $lifetime ?? $this->getLifetime());
	}

	/**
	 * Delete cache item by id.
	 *
	 * @param string $cache_id
	 *
	 * @return bool
	 */
	public function deleteCache(string $cache_id): bool {
		return \delete_transient($this->prefix . $cache_id);
	}

	/**
	 * Clear all archidekt cache.
	 *
	 * @return array
	 *   Array of transient names for items deleted.
	 */
	public function clearAll(): array {
		global $wpdb;

		$transients = $wpdb->get_col($wpdb->prepare("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_archidekt/%'"));
		foreach ($transients as $transient) {
			delete_transient($transient);
		}

		return $transients;
	}

}
