<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @method int id() The user id.
 * @method string username() The user's name.
 * @method string avatar() URL to the user's avatar.
 * @method string frame() URL to the user's frame image.
 * @method string ckAffiliate() Card Kingdom affiliate... something. Id or url?
 * @method string tcgAffiliate() TCG Player affiliate... something. Id or url?
 */
class Owner extends ApiObjectBase {}