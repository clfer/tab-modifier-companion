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
  public static $iconsSets;

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
  public static function getIconsSets() {
    return static::$iconsSets;
  }

  /**
   * @param array $iconsSets
   */
  public static function setIconsSets($iconsSets) {
    static::$iconsSets = $iconsSets;
  }


  public static function load($json_path) {

    $json_raw = file_get_contents($json_path);
    $conf = json_decode($json_raw, TRUE);

    $conf = [
      'icons_dir' => './icons_original',
      'icons_generation_dir' => './icons_generated',
      'environments' =>
        [
          'local' =>
            [
              'color' => '#703293',
            ],
          'dev' =>
            [
              'color' => '#00ba44',
            ],
          'staging' =>
            [
              'color' => '#ebba3d',
            ],
          'prod' =>
            [
              'color' => '#ba0700',
            ],
        ],
      'apps' =>
        [
          'drupal' =>
            [
              'original_icon' => 'app/drupal-favicon.png',
              'variations' =>
                [
                  'US' => 'US',
                  'EMEA' => 'EMEA',
                ],
            ],
          'akeneo' =>
            [
              'original_icon' => 'app/favicon-akeneo-32x32.png',
            ],
          'magento' =>
            [
              'original_icon' => 'app/magento-favicon.png',
              'variations' =>
                [
                  'US-multi' => 'US-multi',
                  'EMEA' => 'EMEA-multi',
                ],
            ],
          'jenkins' =>
            [
              'original_icon' => 'app/favicon-jenkins-yellow.png',
            ],
        ],
      'variations' =>
        [
          'US' =>
            [
              'type' => 'merge',
              'options' =>
                [
                  'path' => './icons_original/flags/favicon-us-16.png',
                  'position' => 'top-left',
                  'margin' => 1,
                ],
            ],
          'US-multi' =>
            [
              'type' => 'merge',
              'options' =>
                [
                  'path' => './icons_original/flags/favicon-us-16.png',
                  'position' => 'top-left',
                  'margin' => 1,
                ],
              'variations' =>
                [
                  '' => 'multi',
                ],
            ],
          'EMEA' =>
            [
              'type' => 'merge',
              'options' =>
                [
                  'path' => './icons_original/flags/favicon-eu-16.png',
                  'position' => 'top-left',
                  'margin' => 1,
                ],
            ],
          'EMEA-multi' =>
            [
              'type' => 'merge',
              'options' =>
                [
                  'path' => './icons_original/flags/favicon-eu-16.png',
                  'position' => 'top-left',
                  'margin' => 1,
                ],
              'variations' =>
                [
                  '' => 'multi',
                ],
            ],
          'multi' =>
            [
              1 =>
                [
                  'type' => 'text',
                  'options' =>
                    [
                      'position' => 'top-right',
                      'text' => '1',
                    ],
                ],
              2 =>
                [
                  'type' => 'text',
                  'options' =>
                    [
                      'position' => 'top-right',
                      'text' => '2',
                    ],
                ],
              3 =>
                [
                  'type' => 'text',
                  'options' =>
                    [
                      'position' => 'top-right',
                      'text' => '3',
                    ],
                ],
              4 =>
                [
                  'type' => 'text',
                  'options' =>
                    [
                      'position' => 'top-right',
                      'text' => '4',
                    ],
                ],
              5 =>
                [
                  'type' => 'text',
                  'options' =>
                    [
                      'position' => 'top-right',
                      'text' => '5',
                    ],
                ],
            ],
        ],
    ];

    static::$icons_generation_dir = $conf['icons_generation_dir'];

    $environments = [];
    foreach ($conf['environments'] as $environment_label => $environment) {
      $environments[$environment_label] = new Environment($environment_label, $environment['color']);
    }
    self::setEnvironments($environments);

    foreach ($conf['apps'] as $app_label => $app) {
      static::$iconsSets[$app_label] = new IconsSet($app_label, $app['original_icon'], $app['variations']);
    }

    foreach ($conf['variations'] as $variation_label => $variation) {
      static::$namedVariations[$variation_label] = Variation::build($variation);
    }
  }

  /**
   * Display the conf as html
   */
  public static function toHtml() {

    return '<pre>' . self::toJson() . '</pre>';
  }

  public static function toJson() {
    return json_encode(static::toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  }

  public static function toArray() {
    return [
      'icons_generation_dir' => static::$icons_generation_dir,
      'environments' => static::$environments,
      'apps' => static::$iconsSets,
      'variations' => static::$namedVariations,
    ];
  }
}