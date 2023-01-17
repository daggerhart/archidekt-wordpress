<?php

namespace Archidekt\Shortcode;

use Archidekt\Service\ApiClient;
use Archidekt\Service\Template;
use Archidekt\ServicesContainer;

/**
 * Shortcode for displaying a deck.
 */
class Deck extends AbstractShortcode {

	/**
	 * {@inheritDoc}
	 */
	public static function tag(): string {
		return 'deck';
	}

	/**
	 * {@inheritDoc}
	 */
	public function shortcode(array $attributes = [], string $content = ''): string {
		$attributes = wp_parse_args($attributes, [
			'id' => NULL,
			'mode' => 'summary',
		]);
		if (!$attributes['id']) {
			return $this->errorComment('Deck id is required for the deck shortcode.');
		}

		$deck = $this->client->getDeck($attributes['id']);
		if (!$deck) {
			return $this->errorComment("Deck not found: {$attributes['id']}");
		}

		// Render categories.
		$categories_with_linked_cards = [];
		foreach ($deck->getCategoriesWithCards() as $category) {
			$categories_with_linked_cards[] = $this->renderCategoryCards($category);
		}

		wp_enqueue_style('archidekt-shortcode-deck');
		$suggestions = [
			// Default to the summary if the given mode doesn't exist.
			'deck' . DIRECTORY_SEPARATOR . 'deck--summary',
			// More specific suggestions later in the array.
			'deck' . DIRECTORY_SEPARATOR . 'deck--' . $attributes['mode'],
		];

		return $this->template->render($suggestions, [
			'deck' => $deck,
			'deck_id' => $attributes['id'],
			'view_mode' => $attributes['mode'],
			'categories_with_linked_cards' => $categories_with_linked_cards,
		]);
	}

}
