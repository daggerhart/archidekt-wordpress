<?php

namespace Archidekt\Service;

use Archidekt\Model\Archidekt\Deck;

/**
 * Fetch remote Archidekt & Scryfall data.
 */
class ApiClient {

	/**
	 * Cache service.
	 *
	 * @var Cache
	 */
	protected Cache $cache;

	/**
	 * @param Cache $cache
	 */
	public function __construct(Cache $cache) {
		$this->cache = $cache;
	}

	/**
	 * Fetch an archidekt deck. If the deck isn't found, returns an empty Deck object.
	 *
	 * @param int $deck_id
	 *
	 * @return \Archidekt\Model\Archidekt\Deck
	 */
	public function getDeck(int $deck_id): ?Deck {
		$cache_id = "deck/{$deck_id}";
		if ($cache = $this->cache->getCache($cache_id)) {
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
			$this->cache->setCache($cache_id, $data);
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
				if ($card_deck_meta_data['card']['uid'] == $scryfall_card['id']) {
					$archidekt_deck_data['cards'][$index]['card']['scryfall_image_uris'] = $scryfall_card['image_uris'] ?? [];
					$archidekt_deck_data['cards'][$index]['card']['scryfall_image_status'] = $scryfall_card['image_status'];
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

}
