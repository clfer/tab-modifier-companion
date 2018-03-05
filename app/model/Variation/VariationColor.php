<?php


namespace TabModifierCompanion\Model\Variation;


class VariationColor extends Variation {

  public $type = 'color';

  function apply($image_path) {
    $variation_path = $image_path;
    parent::applySubvariations($variation_path);
  }
}