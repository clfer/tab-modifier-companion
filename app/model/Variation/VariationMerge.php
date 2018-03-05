<?php

namespace TabModifierCompanion\Model\Variation;


class VariationMerge extends Variation {

  public $type = 'merge';

  function apply($image_path) {
    $variation_path = $image_path;
    parent::apply($variation_path);
  }

}