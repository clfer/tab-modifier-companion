<?php

namespace TabModifierCompanion\Model\Variation;


/**
 * Class VariationText
 *
 * @package TabModifierCompanion
 */
class VariationText extends Variation {

  public $type = 'text';

  function apply($image_path) {
    $variation_path = $image_path;
    parent::apply($variation_path);
  }
}