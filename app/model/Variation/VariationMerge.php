<?php

namespace TabModifierCompanion\Model\Variation;


class VariationMerge extends Variation {

  static public $type = 'merge';

  const merge = 'merge';

  function apply($image_path) {
    $variation_path = $image_path;
    parent::apply($variation_path);
  }

}