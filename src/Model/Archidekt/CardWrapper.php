<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * This object represents the Archideck data for a card in a deck. It contains
 * a Card object that has many of the card's details.
 * 
 * @method int id()
 * @method string[] categories() Card category names.
 * @method bool companion() ??? Whether the card is a companion?
 * @method string label()
 * @method string modifier()
 * @method int quantity() Number of the chosen card in the deck.
 * @method string createdAt() Created datetime stamp.
 * @method string|null updatedAt() Updated datetime stamp.
 * @method string|null deletedAt() Deleted datetime stamp.
 */
class CardWrapper extends ApiObjectBase {

  /**
   * Get a Card object.
   * 
   * @return \Archidekt\Model\Archidekt\Card
   */
  public function card(): Card {
    return new Card($this->data['card'] ?? []);
  }
  
}