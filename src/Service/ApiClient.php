<?php

namespace Archidekt\Service;

use Archidekt\Model\Archidekt\CardDeckMeta;
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
	 * Fetch an archidekt deck. If the deck isn't found, returns an empty Deck object.
	 *
	 * @param int $deck_id
	 *
	 * @return \Archidekt\Model\Archidekt\Deck
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

		if (!isset($response['response']['code']) || $response['response']['code'] !== 200) {
			return new Deck([
				'id' => 0,
				'name' => "Deck {$deck_id} Not Found",
				'categories' => [],
				'cards' => [],
			]);
		}

		// Attempt to decode and cache the response data.
		$data = \json_decode($response['body'], true);
		if ($data) {
			$data = $this->setCardPrintingScryfallImages($data);
			$this->setCache($cache_id, $data);
		}

		return new Deck($data);
	}

	/**
	 * Get card image uris from scryfall for the given archidekt data array.
	 *
	 * @param array $archidekt_deck_data
	 *
	 * @return array
	 */
	protected function setCardPrintingScryfallImages(array $archidekt_deck_data): array {
		$identifiers = array_map(function(array $card_deck_meta_data) {
			return [
				'id' => $card_deck_meta_data['card']['uid'],
			];
		}, $archidekt_deck_data['cards'] ?? []);

		foreach ($this->getScryfallCards($identifiers) as $scryfall_card) {
			foreach ($archidekt_deck_data['cards'] ?? [] as $index => $card_deck_meta_data) {
				if ($card_deck_meta_data['uid'] == $scryfall_card['id']) {
					$archidekt_deck_data['cards'][$index]['scryfall_image_uris'] = $scryfall_card['image_uris'];
				}
			}
		}

		return $archidekt_deck_data;
	}

	/**
	 * @param array $identifiers
	 *
	 * @return array
	 */
	public function getScryfallCards(array $identifiers): array {
		$response = wp_remote_post('https://api.scryfall.com/cards/collection', [
			'headers' => [
				'content-type' => 'application/json',
			],
			'body' => json_encode([
				'identifiers' => $identifiers,
			]),
		]);

		if (isset($response['response']['code']) && $response['response']['code'] === 200) {
			$list = \json_decode($response['body'], true);
			return $list['data'] ?? [];
		}

		return [];
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
