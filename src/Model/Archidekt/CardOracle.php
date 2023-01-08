<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * Represents the oracle version of a card.
 * 
 * @method int id() Card id.
 * @method string name() Card name.
 * @method int cmc() Card cmc.
 * @method string[] colorIdentity()
 * @method string[] colors()
 * @method array faces() Card faces.
 * @method string layout() Card layout.
 * @method string manaCode() Card mana cost.
 * @method array manaProduction() Keyed array of the mana this card can produce.
 * @method string power() Creature power.
 * @method float salt() Saltiness of card.
 * @method string[] subTypes() List of card's subtypes.
 * @method string[] superTypes() List of card's supertypes.
 * @method string text() Card text.
 * @method array tokens() List of token ids this card produces.
 * @method string toughness() Creature toughness.
 * @method string[] types() Card types.
 * @method int loyalty() Number of loyalty a planeswalker has.
 */
class CardOracle extends ApiObjectBase {

  /**
   * Alias for cmc().
   * 
   * @return int
   */
  public function mv() {
    return $this->cmc();
  }
  
}