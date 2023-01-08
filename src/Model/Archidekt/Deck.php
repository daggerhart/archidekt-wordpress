<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @method int id() Deck Id.
 * @method string name() Deck name.
 * @method string createdAt() Created datetime stamp.
 * @method string updatedAt() Updated datetime stamp.
 * @method int deckFormat() Deck format id number.
 * @method string description() Description.
 * @method string featured() Featured image url.
 * @method string customFeatured() Customized featured image url.
 * @method null game() Game type Id.
 * @method bool private() Whether deck is private. 
 * @method int viewCount() Number of times deck has been viewed.
 * @method int points() Number of likes the deck has.
 * @method int userInput() ???
 * @method int commentRoot() Comment root id.
 * @method int[] editors() Array of user Ids that can edit the deck.
 * @method int parentFolder() Id of the folder the deck is in.
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