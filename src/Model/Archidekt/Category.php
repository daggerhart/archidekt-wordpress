<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @property int $id Category id.
 * @property string $name Category name.
 * @property bool $includedInDeck Whether the category is included in the deck.
 * @property bool $includedInPrice Whether the category is included in the price.
 * @property bool $isPremier Whether the category is special to the format.
 */
class Category extends ApiObjectBase {

	/**
	 * @var CardDeckMeta[]
	 */
	protected array $cards = [];

	/**
	 * @return bool
	 */
	public function hasCards(): bool {
		return !empty($this->cards);
	}

	/**
	 * @param array $cards
	 *
	 * @return $this
	 */
	public function setCards(array $cards): Category {
		$this->cards = $cards;
		return $this;
	}

	/**
	 * @return CardDeckMeta[]
	 */
	public function getCards(): array {
		return $this->cards;
	}

}
