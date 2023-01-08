<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @method int id() Category id.
 * @method string name() Category name.
 * @method bool includedInDeck() Whether the category is included in the deck.
 * @method bool includedInPrice() Whether the category is included in the price.
 * @method bool isPremier() Whether the category is special to the format.
 */
class Category extends ApiObjectBase {}