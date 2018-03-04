<?php

namespace TabModifierCompanion\Model\Variation;


/**
 * Class Variation
 *
 * @package TabModifierCompanion
 */
class Variation implements \JsonSerializable {

  /**
   * @var string
   */
  public $label;

  public $options = [];

  /**
   * @var array
   */
  public $subvariations = [];

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
    $this->subvariations = $subvariations;
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
    elseif (!empty($variation_info['type'])) {
      $type = $variation_info['type'];
    }
    elseif (is_array($variation_info)) {
      $type = '';
      $variation_info = [
        'label' => '',
        'options' => [],
        'variations' => $variation_info,
      ];

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
        $subvariation = Variation::build($subvariation_info);
        $variation->setSubvariation($subvariation_label, $subvariation);
      }
    }

    return $variation;
  }

  /**
   * @return array
   */
  public function getSubvariations() {
    return $this->subvariations;
  }

  /**
   * @param \TabModifierCompanion\Model\Variation\Variation ...$subvariations
   */
  public function setSubvariations(Variation ...$subvariations) {
    $this->subvariations = $subvariations;
  }

  /**
   * @param $variation_name
   *
   * @return void
   */
  public function getSubvariation($variation_name) {
    !empty($this->subvariations[$variation_name]) ? $this->subvariations[$variation_name] : FALSE;
  }

  /**
   * @param $variation_name
   * @param \TabModifierCompanion\Model\Variation\Variation|string $subvariation
   */
  public function setSubvariation($variation_name, $subvariation) {
    $this->subvariations{$variation_name} = $subvariation;
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
    $new_path = $image_path;

    $this->applySubvariations($new_path);

    return $new_path;
  }

  /**
   * Apply variation on an image
   *
   * @param string $image_path
   */
  function applySubvariations($image_path) {
    if (!empty($this->subvariations)) {
      foreach ($this->subvariations as $subvariation) {
        $subvariation->apply($image_path);
      }
    }
  }


  public function jsonSerialize() {
    return array_filter(array_merge(get_class_vars(get_class($this)), get_object_vars($this)), function ($v) {
      return isset($v) && (!is_array($v) || !empty($v));
    });
  }
}
