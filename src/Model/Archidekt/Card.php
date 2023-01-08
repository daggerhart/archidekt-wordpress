<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @method int id() Card id.
 * @method string artist() Artist name.
 * @method int tcgProductId() TCG Player product id.
 * @method int ckFoilId() Card Kingdom foil card id.
 * @method int ckNormalId() Card Kingdom non-foil card id.
 * @method string cmEd() ???
 * @method string collectorNumber() Card collector number.
 * @method int multiverseid() Gatherer's multiverse id.
 * @method int mtgoFoilId()
 * @method int mtgoNormalId()
 * @method string uid() ??? Looks like a uuid.
 * @method string flavor() Card's flavor text. 
 * @method array games()
 * @method string[] options() Card's options.
 * @method int owned()
 * @method array prices()
 * @method string rarity()
 */
class Card extends ApiObjectBase {

  /**
   * Get the card set details.
   * 
   * @return \Archidekt\Model\Archidekt\Edition
   */
  public function edition(): Edition {
    return new Edition($this->data['edition'] ?? []);
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
    return $this->prices()[$source] ?? $this->prices()['tcg'];
  }

  /**
   * Get oracle card object.
   * 
   * @return \Archidekt\Model\Archidekt\CardOracle
   */
  public function oracleCard(): CardOracle {
    return new CardOracle($this->data['oracleCard']);
  }
  
}