<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * Represents a specific printing of a card.
 *
 * @property int $id Card id.
 * @property string $artist Artist name.
 * @property int $tcgProductId TCG Player product id.
 * @property int $ckFoilId Card Kingdom foil card id.
 * @property int $ckNormalId Card Kingdom non-foil card id.
 * @property string $cmEd ???
 * @property string $collectorNumber Card collector number.
 * @property int $multiverseid Gatherer's multiverse id.
 * @property int $mtgoFoilId
 * @property int $mtgoNormalId
 * @property string $uid ??? Looks like a uuid.
 * @property string $flavor Card's flavor text.
 * @property array $games
 * @property string[] $options Card's options.
 * @property array $edition Card set raw data.
 * @property array $oracleCard Raw oracle card data array.
 * @property int $owned
 * @property array $prices
 * @property string $rarity
 * @property array $scryfall_image_uris Image uris for card on scryfall.
 * @property string $scryfall_image_status Image status for card on scryfall.
 * @property string $scryfall_uri Uri to card on scryfall.
 */
class CardPrinting extends ApiObjectBase {

	// https://scryfall.com/docs/api/images
	const IMAGE_STYLES = [
		'png',
		'border_crop',
		'art_crop',
		'large',
		'normal',
		'small',
	];

	/**
	 * Parent deck meta object.
	 *
	 * @var CardDeckMeta|null
	 */
	protected ?CardDeckMeta $parentCardDeckMeta = null;

	/**
	 * Runtime cache of gameplay card object.
	 *
	 * @var CardGameplay|null
	 */
	protected ?CardGameplay $cardGameplay = null;

	/**
	 * @param CardDeckMeta $card_deck_meta
	 *
	 * @return $this
	 */
	public function setParent(CardDeckMeta $card_deck_meta): CardPrinting {
		$this->parentCardDeckMeta = $card_deck_meta;
		return $this;
	}

	/**
	 * Get the Archidekt meta object for this card printing.
	 *
	 * @return CardDeckMeta|null
	 */
	public function getParentCardDeckMeta(): ?CardDeckMeta {
		return $this->parentCardDeckMeta;
	}

	/**
	 * Get the card set details.
	 *
	 * @return \Archidekt\Model\Archidekt\Edition
	 */
	public function getEdition(): Edition {
		return new Edition($this->edition ?? []);
	}

	/**
	 * Get the card price for the given source.
	 *
	 * @param string $source
	 *   Options: ck, ckfoil, cm, cmfoil, mtgo, mtgofoil, tcg, tcgfoil.
	 *
	 * @return float
	 */
	public function getPrice(string $source = 'tcg'): float {
		return $this->prices[$source] ?? $this->prices['tcg'];
	}

	/**
	 * Get gameplay card object.
	 *
	 * @return \Archidekt\Model\Archidekt\CardGameplay|null
	 */
	public function getCardGameplay(): ?CardGameplay {
		if (!$this->cardGameplay) {
			$this->cardGameplay = new CardGameplay($this->oracleCard);
			$this->cardGameplay->setParent($this);
		}

		return $this->cardGameplay;
	}

	/**
	 * Get card faces.
	 *
	 * @return CardFace[]
	 */
	public function getCardFaces(): array {
		return $this->getCardGameplay()->getCardFaces();
	}

	/**
	 * @param string $style
	 *
	 * @return string
	 */
	public function getScryfallImageUri(string $style = 'normal'): string {
		if (!in_array($style, static::IMAGE_STYLES)) {
			$style = 'normal';
		}

		if (!$this->scryfall_image_uris) {
			return 'FINDME';
		}

		return $this->scryfall_image_uris[$style];
	}

}
