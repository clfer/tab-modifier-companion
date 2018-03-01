<?php

class tabModifierRule {

  public $name;

  public $detection;

  public $url_fragment;

  public $tab = array();

  /**
   * tabModifierRule constructor.
   *
   * @param null $name
   * @param string $detection
   * @param null $url_fragment
   * @param array $tab
   */
  public function __construct($name, $detection = NULL, $url_fragment = NULL, $tab = array()) {
    $this->name = $name;
    $this->detection = isset($detection) ? $detection : 'CONTAINS';
    $this->url_fragment = $url_fragment;
    $tab += array(
      'title' => NULL,
      'icon' => NULL,
      'pinned' => FALSE,
      'protected' => FALSE,
      'unique' => FALSE,
      'muted' => FALSE,
      'title_matcher' => NULL,
      'url_matcher' => NULL,
    );
    $this->tab = $tab;
  }
}

$rules = array();
$server_url = _get_basepath();

$icon_list = _read_dir('icons_generated');

foreach ($icon_list as $rule_name => $icon_path) {
  $rules[] = new tabModifierRule($rule_name, NULL, '', array('icon' => $server_url . '/' . $icon_path));
}

_json_rules_deliver($rules);


//====== Helper functions ======
/**
 * @param $rules
 */
function _json_rules_deliver($rules) {
  header('Content-Type: application/json');
  header('Content-Disposition: inline; filename="tab-modifier-companion-rules.json"');
  echo _get_json_rules($rules);
}

/**
 * @param $rules
 * @return string
 */
function _get_json_rules($rules) {
  return json_encode(array('rules' => $rules), JSON_UNESCAPED_SLASHES);
}


/**
 * @return string
 */
function _get_basepath() {
  $server_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
  if (($_SERVER['REQUEST_SCHEME'] == 'http' && $_SERVER['SERVER_PORT'] != '80') || ($_SERVER['REQUEST_SCHEME'] == 'https' && $_SERVER['SERVER_PORT'] != '443')) {
    $server_url .= ':' . $_SERVER['SERVER_PORT'];
  }
  return $server_url;
}


function _read_dir($dir_path) {
  $icon_list = array();

  $it = new RecursiveDirectoryIterator($dir_path, RecursiveDirectoryIterator::SKIP_DOTS);
  $files = new RecursiveIteratorIterator($it,
    RecursiveIteratorIterator::CHILD_FIRST);
  foreach ($files as $file) {
    if (!$file->isDir()) {
      $rule_name = explode('/', $file->getPath());
      array_shift($rule_name);
      array_pop($rule_name);
      $basename = $file->getBasename('.' . $file->getExtension());
      $rule_name[] = $basename;
      $rule_name = implode(' - ', $rule_name);

      $icon_list[$rule_name] = $file->getPathname();
    }
  }
  return $icon_list;
}
