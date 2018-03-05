<?php

namespace TabModifierCompanion\Model;


class AppIcon {

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

  /**
   *
   */
  function toHtml() {
    $html = '<table class="table table-bordered">';

    $html .= '<tr><td class="row-label">Label:</td><td>' . $this->label . '</td></tr>';
    $html .= '<tr><td class="row-label">Icon:</td><td>' . _print_icon($this->original_icon) . '</td></tr>';

    if (!empty($this->variations)) {
      $html .= '<tr><td class="row-label">Variations:</td><td><code>' . json_encode($this->variations) . '</code></td></tr>';
    }

    $html .= '</table>';


    return $html;

  }


}