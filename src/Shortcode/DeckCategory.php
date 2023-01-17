<?php

namespace Archidekt\Shortcode;

/**
 * Shortcode for displaying a deck with annotations.
 */
class DeckCategory extends AbstractShortcode {

	/**
	 * {@inheritDoc}
	 */
	public static function tag(): string {
		return 'deck-category';
	}

	/**
	 * {@inheritDoc}
	 */
	public function shortcode(array $attributes = [], string $content = ''): string {
		$attributes = wp_parse_args($attributes, [
			'id' => NULL,
			'category' => NULL,
		]);

		if (!$attributes['id']) {
			return $this->errorComment('Deck id is required for the deck-category shortcode.');
		}

		if (!$attributes['category']) {
			return $this->errorComment('Category attribute required for deck-category shortcode.');
		}

		$deck = $this->client->getDeck($attributes['id']);
		if (!$deck) {
			return $this->errorComment("Deck not found: {$attributes['id']}");
		}

		$category = $deck->getCategoryWithCards($attributes['category']);
		if (!$category) {
			return $this->errorComment("Category not found in deck: {$attributes['category']}");
		}

		wp_enqueue_style('archidekt-shortcode-deck');
		$suggestions = [
			'deck' . DIRECTORY_SEPARATOR . 'deck-category',
		];

		return $this->template->render($suggestions, [
			'deck' => $deck,
			'deck_id' => $attributes['id'],
			'category' => $category,
			'content' => $this->cleanShortcodeContent($content),
		]);
	}

}
