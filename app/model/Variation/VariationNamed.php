<?php


namespace TabModifierCompanion\Model\Variation;


use TabModifierCompanion\Model\Config;

class VariationNamed extends Variation {

  public $type = 'named';

  function apply($image_path) {
    $variation_path = $image_path;

    $namedVariation = $this->loadNamedVariation();

    if (!empty($namedVariation)) {
      $variation_path = $namedVariation->apply($image_path);

      parent::apply($variation_path);

    }
    return $variation_path;
  }

  function getOptionsHtml(): string {
    $namedVariation = $this->loadNamedVariation();

    if (!empty($namedVariation)) {
      $optionsHtml = $namedVariation->toHtml($this->label);
    } else {
      $optionsHtml = '<p>Invalid Name:</p>' . parent::getOptionsHtml();
    }
    return $optionsHtml;
  }

  /**
   * @return FALSE|\TabModifierCompanion\Model\Variation\Variation
   */
  public function loadNamedVariation() {
    $variationName = $this->options['variation_name'];
    $namedVariation = Config::getNamedVariation($variationName);
    if (!empty($namedVariation)) {
      $namedVariation->setLabel($this->label);
    }
    return $namedVariation;
  }
}