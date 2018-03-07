<?php

namespace TabModifierCompanion\Model\Variation;

use TabModifierCompanion\Model\Config;

class VariationMerge extends Variation {

  public $type = 'merge';

  function apply($image_path) {

    if (empty($this->options['path'])) {
      throw new \Exception(__CLASS__ . '->' . __FUNCTION__ . ' : Missing \'path\' options.');
    }

    $base_layer = imagecreatefrompng($image_path);
    _imagetransparency($base_layer);

    $this->options += array(
      'position' => 'top-left',
      'margin' => 0,
    );

    $base_layer_width = imagesx($base_layer);
    $base_layer_height = imagesy($base_layer);

    //--- Merge with new layer ---
    // Load and merge layer
    $new_layer = imagecreatefrompng($this->options['path']);
    _imagetransparency($new_layer);

    list($x, $y) = _get_position($this->options['position'], array(
      'margin' => $this->options['margin'],
      'width' => imagesx($new_layer),
      'height' => imagesy($new_layer),
      'total_width' => $base_layer_width,
      'total_height' => $base_layer_height,
    ));

    imagecopy($base_layer, $new_layer, $x, $y, 0, 0, $base_layer_width, $base_layer_height);

    imagedestroy($new_layer);

    // ?
    $variation_filename = pathinfo($image_path, PATHINFO_FILENAME) . '-' . $this->label;

    $variation_path = imagepng_save($base_layer, Config::getIconsGenerationDir(), $variation_filename);

    // Apply subvariations
    parent::apply($variation_path);
  }

}