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
 * @property string $manaCode Card mana cost.
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
class CardOracle extends ApiObjectBase {}
