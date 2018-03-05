<?php

namespace TabModifierCompanion\Model;

use TabModifierCompanion\Model\Variation\Variation;

class Config {

  /**
   * @var string
   */
  public static $icons_generation_dir;

  /**
   * @var  array
   */
  public static $environments;

  /**
   * @var array
   */
  public static $appIcons;

  /**
   * @var array
   */
  public static $namedVariations;


  /**
   * @return mixed
   */
  public static function getIconsGenerationDir() {
    return static::$icons_generation_dir;
  }

  /**
   * @param mixed $icons_generation_dir
   */
  public static function setIconsGenerationDir($icons_generation_dir) {
    static::$icons_generation_dir = $icons_generation_dir;
  }

  /**
   * @return array
   */
  public static function getEnvironments() {
    return static::$environments;
  }


  /**
   * @param array $environments
   */
  public static function setEnvironments($environments) {
    static::$environments = $environments;
  }

  /**
   * @return array
   */
  public static function getNamedVariations() {
    return static::$namedVariations;
  }

  /**
   * @param array $namedVariations
   */
  public static function setNamedVariations($namedVariations) {
    static::$namedVariations = $namedVariations;
  }

  /**
   * @param string $variationName
   *
   * @return Variation|FALSE
   */
  public static function getNamedVariation($variationName) {
    return !empty(static::$namedVariations[$variationName]) ? static::$namedVariations[$variationName] : FALSE;
  }

  /**
   * @param $variationName
   * @param \TabModifierCompanion\Model\Variation\Variation $variation
   */
  public static function setNamedVariation($variationName, $variation) {
    static::$namedVariations[$variationName] = $variation;
  }

  /**
   * @return array
   */
  public static function getAppIcons() {
    return static::$appIcons;
  }

  /**
   * @param array $appIcons
   */
  public static function setAppIcons($appIcons) {
    static::$appIcons = $appIcons;
  }


  public static function load($json_path) {

    $json_raw = file_get_contents($json_path);
    $conf = json_decode($json_raw, TRUE);

    static::$icons_generation_dir = $conf['icons_generation_dir'];

    $environments = [];
    foreach ($conf['environments'] as $environment_label => $environment) {
      $environments[$environment_label] = new Environment($environment_label, $environment['color']);
    }
    self::setEnvironments($environments);

    foreach ($conf['apps'] as $app_label => $app) {
      static::$appIcons[$app_label] = new AppIcon($app_label, $app['original_icon'], $app['variations']);
    }

    foreach ($conf['variations'] as $variation_label => $variation) {
      static::$namedVariations[$variation_label] = Variation::build($variation);
    }
  }

  /**
   * Display the conf as html
   */
  public static function toHtml() {

    $html = '';

    $html .= '<h3> Generated icons target directory:</h3>';
    $html .= '<p><code>' . static::$icons_generation_dir . '</code></p>';

    $html .= '<br>';
    $html .= '<hr>';

    $html .= '<h3>Environments:</h3>';
    foreach (static::$environments as $environment_machine_name => $environment) {
      $html .= theme('panel', ['key' => $environment_machine_name, 'title' => $environment_machine_name, 'panel_body_suffix' => $environment->toHtml()]);
    }

    $html .= '<br>';
    $html .= '<hr>';

    $html .= '<h3>Apps:</h3>';
    foreach (static::$appIcons as $app_machine_name => $appIcon) {
      $html .= theme('panel', ['key' => $app_machine_name, 'title' => $app_machine_name, 'panel_body_suffix' => $appIcon->toHtml()]);
    }

    //TODO Find a readable way to display Named variations


    $html .= '<br>';
    $html .= '<hr>';

    $html .= '<h3>Named variations:</h3>';
    $html .= '<ul>';
    foreach (static::$namedVariations as $namedVariation_machine_name => $namedVariation) {
      $html .= '<li><code>' . $namedVariation_machine_name . '</code></li>';
    }
    $html .= '</ul>';

    return $html;
  }

  public static function toJson() {
    return json_encode(static::toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public static function toArray() {
    return [
      'icons_generation_dir' => static::$icons_generation_dir,
      'environments' => static::$environments,
      'apps' => static::$appIcons,
      'variations' => static::$namedVariations,
    ];
  }
}