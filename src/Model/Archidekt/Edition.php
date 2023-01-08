<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;

/**
 * @method string editioncode() Set code.
 * @method string editionname() Set name.
 * @method string editiondate() Set release date.
 * @method string editiontype() Set type.
 * @method int|string mtgoCode() Card code on mtgo.
 */
class Edition extends ApiObjectBase {

  /**
   * @return string|null
   */
  public function code(): ?string {
    return $this->editioncode();
  }

  /**
   * @return string|null
   */
  public function name(): ?string {
    return $this->editionname();
  }

  /**
   * @return string|null
   */
  public function date(): ?string {
    return $this->editiondate();
  }

  /**
   * @return string|null
   */
  public function type(): ?string {
    return $this->editiontype();
  }
  
}