<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
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
 */
class Card extends ApiObjectBase {

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
	 * Get oracle card object.
	 *
	 * @return \Archidekt\Model\Archidekt\CardOracle
	 */
	public function getOracleCard(): CardOracle {
		return new CardOracle($this->oracleCard);
	}

}
