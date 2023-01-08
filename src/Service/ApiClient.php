<?php

namespace Archidekt\Service;

use Archidekt\Model\Archidekt\Deck;

/**
 * Fetch remote Archidekt data.
 */
class ApiClient {

	/**
	 * Whether to cache.
	 *
	 * @var bool
	 */
	private bool $cacheEnabled;

	/**
	 * How long the cache should last in seconds.
	 *
	 * @var int|float
	 */
	private int $cacheLifetime;

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
	 * Fetch an archidekt deck.
	 *
	 * @param int $deck_id
	 *
	 * @return \Archidekt\Model\Archidekt\Deck|null
	 */
	public function getDeck(int $deck_id): ?Deck {
		$cache_id = "archidekt/{$deck_id}";
		if ($cache = $this->getCache($cache_id)) {
			return new Deck($cache);
		}

		$response = wp_remote_get("https://archidekt.com/api/decks/{$deck_id}/", [
			'headers' => [
				'content-type' => 'application/json',
			],
		]);

		if (isset($response['response']['code']) && $response['response']['code'] === 200) {
			// Attempt to decode and cache the response data.
			$data = \json_decode($response['body'], TRUE);
			if ($data) {
				$this->setCache($cache_id, $data);
			}

			return new Deck($data);
		}

		return NULL;
	}

	/**
	 * Look up data for cached version.
	 *
	 * @param string $cache_id
	 *
	 * @return false|array
	 */
	private function getCache(string $cache_id) {
		if (!$this->cacheEnabled) {
			return FALSE;
		}

		return \get_transient($cache_id);
	}

	/**
	 * Cache data as a transient.
	 *
	 * @param string $cache_id
	 * @param array $cache_data
	 *
	 * @return bool
	 */
	private function setCache(string $cache_id, array $cache_data): bool {
		if (!$this->cacheEnabled) {
			return FALSE;
		}

		return \set_transient($cache_id, $cache_data, $this->cacheLifetime);
	}

}
