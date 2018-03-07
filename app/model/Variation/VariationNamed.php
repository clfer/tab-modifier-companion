<?php


namespace TabModifierCompanion\Model\Variation;


use TabModifierCompanion\Model\Config;

class VariationNamed extends Variation {

  public $type = 'named';

  function apply($image_path) {
    $variationName = $this->options['variation_name'];
    $namedVariation = Config::getNamedVariation($variationName);
    if (!empty($namedVariation)) {
      $namedVariation->setLabel($this->label);
      $variation_path = $namedVariation->apply($image_path);

      parent::apply($variation_path);

      return $variation_path;
    }
  }
}