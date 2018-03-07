<?php

namespace TabModifierCompanion\Model\Variation;

use TabModifierCompanion\Model\Config;

/**
 * Class VariationText
 *
 * @package TabModifierCompanion
 */
class VariationText extends Variation {

  public $type = 'text';

  function apply($image_path) {


    if (empty($this->options['text'])) {
      throw new \Exception(__CLASS__ . '->' . __FUNCTION__ . ' : Missing \'text\' options.');
    }

    $variation_path = $image_path;

    $base_layer = imagecreatefrompng($image_path);
    _imagetransparency($base_layer);

    $this->options += array(
      'position' => 'top-left',
      'font_color' => '#FFFFFF',
      'bg_color' => '#000000',
      'bg_transparency' => 0.5,
      'font_path' => $_SERVER['DOCUMENT_ROOT'] . '/fonts/CONSOLAB.TTF',
    );

    $text = $this->options['text'];

    $font_size = 10;
    $bounds = imagettfbbox($font_size, 0, $this->options['font_path'], $text);
    if ($bounds !== FALSE) {
      $number_width = $bounds[2] - $bounds[0];
      $number_heigth = $bounds[1] - $bounds[7];

      list($bg_r, $bg_g, $bg_b) = hex2rgb($this->options['bg_color']);
      $bg_imagecolor = imagecolorallocatealpha($base_layer, $bg_r, $bg_g, $bg_b, $this->options['bg_transparency'] * 127);

      $padding = 1;

      $bg_width = $number_width + 2 * $padding;
      $bg_height = $number_heigth + 2 * $padding;

      $bg_width = evenize_down($bg_width);
      $bg_height = evenize_down($bg_height);

      list($bg_posx, $bg_posy) = _get_position($this->options['position'], array(
        'width' => $bg_width,
        'height' => $bg_height,
      ));

      imagefilledrectangle($base_layer, $bg_posx, $bg_posy, $bg_posx + $bg_width, $bg_posy + $bg_height, $bg_imagecolor);

      $number_posx = $bg_posx + $padding;
      $number_posy = $bg_posy + $number_heigth + $padding;

      list($font_r, $font_g, $font_b) = hex2rgb($this->options['font_color']);
      $font_imagecolor = imagecolorallocate($base_layer, $font_r, $font_g, $font_b);

      imagettftext($base_layer, $font_size, 0, $number_posx, $number_posy, $font_imagecolor, $this->options['font_path'], $text);

      $variation_filename = pathinfo($image_path, PATHINFO_FILENAME) . '-' . $this->label;

      $variation_path = imagepng_save($base_layer, Config::getIconsGenerationDir(), $variation_filename);

      parent::apply($variation_path);
    }

    return $variation_path;
  }
}