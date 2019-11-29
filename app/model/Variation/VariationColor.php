<?php


namespace TabModifierCompanion\Model\Variation;


use ColorThief\ColorThief;
use TabModifierCompanion\Model\Config;

class VariationColor extends Variation {

  public $type = 'color';

  function apply($image_path) {

    if (empty($this->options['color'])) {
      throw new \Exception(__CLASS__ . '->' . __FUNCTION__ . ' : Missing \'color\' options.');
    }

    if ($this->options['color'] == 'none') {
      return $image_path;
    }


    list($r, $g, $b) = ColorThief::getColor($image_path, 1);
    list($base_hue, $base_saturation, $base_value) = rgb2hsl($r, $g, $b);

    $base_layer = imagecreatefrompng($image_path);
    _imagetransparency($base_layer);
    list($colorize_hue, $colorize_saturation, $colorize_value) = hex2hsl($this->options['color']);
    $delta_hue = $colorize_hue - $base_hue;
    $delta_value = $colorize_value - $base_value;
    imagehue($base_layer, $delta_hue * 360, NULL, $delta_value);

    // ?
    $variation_filename = pathinfo($image_path, PATHINFO_FILENAME) . '-' . $this->label;

    $variation_path = imagepng_save($base_layer, Config::getIconsGenerationDir(), $variation_filename);

    // Apply subvariations
    parent::apply($variation_path);

    return $variation_path;
  }
}