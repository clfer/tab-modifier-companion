<?php

namespace TabModifierCompanion\Model;


class IconsSet {

  public $label;

  public $original_icon;

  public $variations = [];

  /**
   * IconsSet constructor.
   *
   * @param $label
   * @param $original_icon
   * @param $variations
   */
  public function __construct($label, $original_icon, $variations) {
    $this->label = $label;
    $this->original_icon = $original_icon;
    $this->variations = $variations;
  }


}