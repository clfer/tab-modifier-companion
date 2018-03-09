<?php

namespace TabModifierCompanion\Model\Variation;


/**
 * Class Variation
 *
 * @package TabModifierCompanion
 */
class Variation implements \JsonSerializable {

  public $type = 'default';

  /**
   * @var string
   */
  public $label;

  public $options = [];

  /**
   * @var array
   */
  public $variations = [];

  /**
   * Variation constructor.
   *
   * @param string $label
   * @param array $options
   * @param array $subvariations
   */
  public function __construct($label = '', $options = [], $subvariations = []) {
    $this->label = $label;
    $this->options = $options;
    $this->variations = $subvariations;
  }

  /**
   * @param $variation_info
   *
   * @return bool
   * @throws \Exception
   */
  static function build($variation_info) {
    if (is_object($variation_info) && get_parent_class($variation_info)) {
      // Already a variation...
      return $variation_info;
    }

    if (is_string($variation_info)) {
      $type = 'named';
      $variation_info = [
        'label' => $variation_info,
        'options' => [
          'variation_name' => $variation_info,
        ],
      ];
    }
    elseif (isset($variation_info['type'])) {
      $type = $variation_info['type'];
    }
    elseif (is_array($variation_info)) {
      $type = '';
      if (!isset($variation_info['variations'])) {
        $variation_info = [
          'label' => '',
          'options' => [],
          'variations' => $variation_info,
        ];
      }

    }
    else {
      throw new \Exception('Tried to build with malformed variation_info. Missing \'type\':<pre>' . var_dump($variation_info) . '</pre>');
    }


    $tentative_class = '\TabModifierCompanion\Model\Variation\Variation' . ucfirst($type);
    if (!class_exists($tentative_class)) {
      throw new \Exception("Tried to build with malformed variation_info. Variation type '$tentative_class' is unknown.");
    }

    $variation = new $tentative_class($variation_info['label'], $variation_info['options']);


    if (!empty($variation_info['variations'])) {
      foreach ($variation_info['variations'] as $subvariation_label => $subvariation_info) {
        if (!isset($subvariation_info['label'])) {
          $subvariation_info['label'] = $subvariation_label;
        }

        $subvariation = Variation::build($subvariation_info);

        $variation->setSubvariation($subvariation_label, $subvariation);
      }
    }

    return $variation;
  }

  /**
   * @return array
   */
  public function getVariations() {
    return $this->variations;
  }

  /**
   * @param \TabModifierCompanion\Model\Variation\Variation ...$variations
   */
  public function setVariations(Variation ...$variations) {
    $this->variations = $variations;
  }

  /**
   * @param $variation_name
   *
   * @return void
   */
  public function getSubvariation($variation_name) {
    !empty($this->variations[$variation_name]) ? $this->variations[$variation_name] : FALSE;
  }

  /**
   * @param $variation_name
   * @param \TabModifierCompanion\Model\Variation\Variation|string $subvariation
   */
  public function setSubvariation($variation_name, $subvariation) {
    $this->variations{$variation_name} = $subvariation;
  }

  /**
   * @return mixed
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * @param string $label
   *
   * @return Variation
   */
  public function setLabel($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getOptions() {
    return $this->options;
  }

  /**
   * @param mixed $options
   *
   * @return variation
   */
  public function setOptions($options) {
    $this->options = $options;
    return $this;
  }

  /**
   * @param $image_path
   *
   * @return mixed
   */
  function apply($image_path) {

    $this->applySubvariations($image_path);

    return $image_path;
  }

  /**
   * @return string Html table rendered
   */
  public function getHtmlTable() {

    $table = '<table class="table table-bordered">';

    $table .= '<tr><td class="row-label">Label:</td><td>' . $this->label . '</td></tr>';
    $table .= '<tr><td class="row-label">Type:</td><td>' . $this->type . '</td></tr>';

    if (!empty($this->options)) {
      $options_html = $this->getOptionsHtml();
      $table .= '<tr><td class="row-label">Options:</td><td>' . $options_html . '</td></tr>';
    }
    if (!empty($this->variations)) {

      $table .= '<tr>';
      $table .= '<td class="row-label"">Variations:</td>';
      $table .= '<td class="option-label">';
      $table .= $this->getSubvariationsHtml();
      $table .= '</td>';
      $table .= '</tr>';
    }


    $table .= '</table>';

    return $table;
  }

  /**
   * @return string Html
   */
  public function toHtml($title = NULL) {
    $panel_options = [
      'key' => 'variation-' . $this->type . '-' . rand(),
      'title' => '<code>' . $title . '</code>',
      'panel_body_suffix' => $this->getHtmlTable(),
      'collapsible' => TRUE,
      'collapsed' => TRUE
    ];
    return theme('panel', $panel_options);
  }

  /**
   * @return null
   */
  public function getSubvariationsHtml() {
    $html = '';
    foreach ($this->variations as $variation_key => $variation) {
      $html .= $variation->toHtml($variation_key);
    }

    return $html;
  }

  /**
   * Apply variation on an image
   *
   * @param string $image_path
   */
  function applySubvariations($image_path) {
    if (!empty($this->variations)) {
      foreach ($this->variations as $subvariation) {
        $subvariation->apply($image_path);
      }
    }
  }


  public function jsonSerialize() {
    $fields = array_merge(get_class_vars(get_class($this)), get_object_vars($this));
    return array_filter($fields, function ($v) {
      return isset($v) && (!is_array($v) || !empty($v));
    });
  }

  /**
   * @return string
   */
  public function getOptionsHtml(): string {
    $options_html = '<pre>' . json_encode($this->options, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</pre>';
    return $options_html;
  }
}
