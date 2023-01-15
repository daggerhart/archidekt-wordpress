<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @property array $colors
 * @property string $flavor Flavor text.
 * @property string $manaCost Encoded mana cost.
 * @property string $name Card face name.
 * @property string $power Card face power.
 * @property array $subTypes Card face subtypes.
 * @property array $superTypes Card face supertypes.
 * @property string $text Card face text.
 * @property string $toughness Card face toughness.
 * @property array $types Card face types.
 * @property int|null $loyalty Card face loyalty.
 */
class CardFace extends ApiObjectBase {

	/**
	 * @var CardGameplay|null
	 */
	protected ?CardGameplay $parentCardGameplay = null;

	/**
	 * Create a card face from a card printing object.
	 *
	 * @param CardPrinting $printing
	 *
	 * @return CardFace
	 */
	public static function createFromCardPrinting(CardPrinting $printing): CardFace {
		$data = static::getCardFaceDataArray($printing->getCardGameplay()) + [
			'flavor' => $printing->flavor,
		];
		$face = new CardFace($data);
		$face->setParent($printing->getCardGameplay());
		return $face;
	}

	/**
	 * Create a card face for a gameplay object.
	 *
	 * @param CardGameplay $gameplay
	 *
	 * @return CardFace
	 */
	public static function createFromCardGameplay(CardGameplay $gameplay): CardFace {
		$face = new static(static::getCardFaceDataArray($gameplay));
		$face->setParent($gameplay);
		return $face;
	}

	/**
	 * @param CardGameplay $gameplay
	 *
	 * @return array
	 */
	private static function getCardFaceDataArray(CardGameplay $gameplay): array {
		return [
			'colors' => $gameplay->colors,
			'flavor' => '',
			'manaCost' => $gameplay->manaCost,
			'name' => $gameplay->name,
			'power' => $gameplay->power,
			'subTypes' => $gameplay->subTypes,
			'superTypes' => $gameplay->superTypes,
			'text' => $gameplay->text,
			'toughness' => $gameplay->toughness,
			'types' => $gameplay->types,
			'loyalty' => $gameplay->loyalty,
		];
	}

	/**
	 * @param CardGameplay $card_gameplay
	 *
	 * @return $this
	 */
	public function setParent(CardGameplay $card_gameplay): CardFace {
		$this->parentCardGameplay = $card_gameplay;
		return $this;
	}

	/**
	 * Get parent gameplay card object.
	 *
	 * @return CardGameplay|null
	 */
	public function getParentCardGameplay(): ?CardGameplay {
		return $this->parentCardGameplay;
	}
}
