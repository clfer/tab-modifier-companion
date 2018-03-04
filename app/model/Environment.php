<?php

namespace TabModifierCompanion\Model;


class Environment {

  public $label;

  public $color;

  /**
   * Environment constructor.
   *
   * @param $label
   * @param $color
   */
  public function __construct($label, $color) {
    $this->label = $label;
    $this->color = $color;
  }


}