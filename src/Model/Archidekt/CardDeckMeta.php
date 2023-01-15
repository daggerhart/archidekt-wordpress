<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * This object represents the Archideck data for a card in a deck. It contains
 * a Card object that has many of the card's details.
 *
 * @property int $id
 * @property string[] $categories Card category names.
 * @property bool $companion ??? Whether the card is a companion?
 * @property string $label
 * @property string $modifier
 * @property int $quantity Number of the chosen card in the deck.
 * @property string $createdAt Created datetime stamp.
 * @property string|null $updatedAt Updated datetime stamp.
 * @property string|null $deletedAt Deleted datetime stamp.
 */
class CardDeckMeta extends ApiObjectBase {

	/**
	 * Runtime cached printing object.
	 *
	 * @var CardPrinting|null
	 */
	protected ?CardPrinting $cardPrinting = null;

	/**
	 * Get a Card object.
	 *
	 * @return \Archidekt\Model\Archidekt\CardPrinting
	 */
	public function getCardPrinting(): CardPrinting {
		if (!$this->cardPrinting) {
			$this->cardPrinting = new CardPrinting($this->data['card'] ?? []);
			$this->cardPrinting->setParent($this);
		}

		return $this->cardPrinting;
	}

	/**
	 * @return CardGameplay
	 */
	public function getCardGameplay(): CardGameplay {
		return $this->getCardPrinting()->getCardGameplay();
	}

}
