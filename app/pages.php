<?php
use TabModifierCompanion\Model\Config;

/**
 * @return array
 */
function page_icon_generation(){
  $conf_path = './conf/example.conf.json';
  $icons = icon_generation($conf_path);
  $content['body'][] = _print_icons_table($icons, TRUE);

  return $content;
}

/**
 * @return array
 */
function page_conf(){
  $conf_path = './conf/example.conf.json';
  Config::load($conf_path);

  $content['body'][] = Config::toHtml();

  return $content;
}