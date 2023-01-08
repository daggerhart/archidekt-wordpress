<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @property string $editioncode Set code.
 * @property string $editionname Set name.
 * @property string $editiondate Set release date.
 * @property string $editiontype Set type.
 * @property int|string $mtgoCode Card code on mtgo.
 */
class Edition extends ApiObjectBase {

	/**
	 * @return string|null
	 */
	public function code(): ?string {
		return $this->editioncode;
	}

	/**
	 * @return string|null
	 */
	public function name(): ?string {
		return $this->editionname;
	}

	/**
	 * @return string|null
	 */
	public function date(): ?string {
		return $this->editiondate;
	}

	/**
	 * @return string|null
	 */
	public function type(): ?string {
		return $this->editiontype;
	}

}
