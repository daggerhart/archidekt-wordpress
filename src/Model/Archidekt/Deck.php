<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @property int $id Deck Id.
 * @property string $name Deck name.
 * @property string $createdAt Created datetime stamp.
 * @property string $updatedAt Updated datetime stamp.
 * @property int $deckFormat Deck format id number.
 * @property string $description Description.
 * @property string $featured Featured image url.
 * @property string $customFeatured Customized featured image url.
 * @property null $game Game type Id.
 * @property bool $private Whether deck is private.
 * @property int $viewCount Number of times deck has been viewed.
 * @property int $points Number of likes the deck has.
 * @property int $userInput ???
 * @property int $commentRoot Comment root id.
 * @property int[] $editors Array of user Ids that can edit the deck.
 * @property int $parentFolder Id of the folder the deck is in.
 */
class Deck extends ApiObjectBase {

	/**
	 * Get the deck owner object.
	 *
	 * @return \Archidekt\Model\Archidekt\Owner
	 */
	public function owner(): Owner {
		return new Owner($this->data['owner'] ?? []);
	}

	/**
	 * Get collection of cards in the deck.
	 *
	 * @return \Archidekt\Model\Archidekt\CardWrapper[]
	 */
	public function cards(): array {
		return array_map(function($card) {
			return new CardWrapper($card);
		}, $this->data['cards'] ?? []);
	}

	/**
	 * Get category objects in this deck.
	 *
	 * @return \Archidekt\Model\Archidekt\Category[]
	 */
	public function categories(): array {
		return array_map(function($card) {
			return new Category($card);
		}, $this->data['categories'] ?? []);
	}

}
