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

  public function toHtml() {

    $html = '<table class="table table-bordered">';

    $html .= '<tr><td class="row-label">Label:</td><td>' . $this->label . '</td></tr>';
    $html .= '<tr><td class="row-label">Color:</td><td style="background: ' . $this->color . ';">' . $this->color . '</td></tr>';

    $html .= '</table>';


    return $html;
  }

}