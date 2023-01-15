<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * Represents an Archidekt user that owns the deck.
 *
 * @property int $id The user id.
 * @property string $username The user's name.
 * @property string $avatar URL to the user's avatar.
 * @property string $frame URL to the user's frame image.
 * @property string $ckAffiliate Card Kingdom affiliate... something. Id or url?
 * @property string $tcgAffiliate TCG Player affiliate... something. Id or url?
 */
class Owner extends ApiObjectBase {}
