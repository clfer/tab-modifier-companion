<?php

use TabModifierCompanion\Model\Config;

/**
 * @return array
 */
function page_icon_generation() {
  $conf_path = './conf/example.conf.json';
  $icons = icon_generation($conf_path);
  $content['body'][] = _print_icons_table($icons, TRUE);

  return $content;
}

/**
 * @return array
 */
function page_conf() {
  $conf_path = './conf/example.conf.json';
  Config::load($conf_path);

  $accordion['id'] = 'config-accordion';
  $accordion['panels']['config'] = [
    'title' => 'Config',
    'content' => Config::toHtml(),
  ];
  $accordion['panels']['json'] = [
    'title' => 'Json',
    'content' => '<pre>' . Config::toJson() . '</pre>',
  ];

  $content['body'][] = theme('accordion', $accordion);

  return $content;
}
