<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * Represents the oracle version of a card.
 *
 * @property int $id Card id.
 * @property string $name Card name.
 * @property int $cmc Card cmc.
 * @property string[] $colorIdentity
 * @property string[] $colors
 * @property array $faces Card faces.
 * @property string $layout Card layout.
 * @property string $manaCost Card mana cost.
 * @property array $manaProduction Keyed array of the mana this card can produce.
 * @property string $power Creature power.
 * @property float $salt Saltiness of card.
 * @property string[] $subTypes List of card's subtypes.
 * @property string[] $superTypes List of card's supertypes.
 * @property string $text Card text.
 * @property array $tokens List of token ids this card produces.
 * @property string $toughness Creature toughness.
 * @property string[] $types Card types.
 * @property int $loyalty Number of loyalty a planeswalker has.
 */
class CardGameplay extends ApiObjectBase {

	/**
	 * @var CardPrinting|null
	 */
	protected ?CardPrinting $parentCardPrinting = null;

	/**
	 * Card face objects.
	 *
	 * @var CardFace[]
	 */
	protected array $cardFaces = [];

	/**
	 * @param CardPrinting $card_printing
	 *
	 * @return $this
	 */
	public function setParent(CardPrinting $card_printing): CardGameplay {
		$this->parentCardPrinting = $card_printing;
		return $this;
	}

	/**
	 * @return CardPrinting|null
	 */
	public function getParentCardPrinting(): ?CardPrinting {
		return $this->parentCardPrinting;
	}

	/**
	 * Get an array of all card faces.
	 *
	 * @return CardFace[]
	 */
	public function getCardFaces(): array {
		// Cached.
		if (!empty($this->cardFaces)) {
			return $this->cardFaces;
		}

		// Build from faces data arrays.
		if (!empty($this->faces)) {
			$this->cardFaces = [];
			foreach ($this->faces as $face_data) {
				$this->cardFaces[] = (new CardFace($face_data))->setParent($this);
			}
			return $this->cardFaces;
		}

		// Build from card printing if we have it.
		if ($this->parentCardPrinting) {
			$this->cardFaces = [
				CardFace::createFromCardPrinting($this->getParentCardPrinting())
			];
			return $this->cardFaces;
		}

		// Default to build from this gameplay card.
		$this->cardFaces = [
			CardFace::createFromCardGameplay($this),
		];

		return $this->cardFaces;
	}

}
