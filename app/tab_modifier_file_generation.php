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

print_r($_SERVER);
echo '<br><br>';
$rules = array();
$server_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
if (($_SERVER['REQUEST_SCHEME'] == 'http' && $_SERVER['SERVER_PORT'] != '80') || ($_SERVER['REQUEST_SCHEME'] == 'https' && $_SERVER['SERVER_PORT'] != '443')) {
  $server_url .= ':' . $_SERVER['SERVER_PORT'];
}
$rules[] = new tabModifierRule('Plop', NULL, 'plop', array('icon' => $server_url . '/app/icons_generated/dev/drupal/drupal.png'));
$rules[] = new tabModifierRule('Plip', NULL, 'plip', array('icon' => $server_url . '/app/icons_generated/dev/magento/magento.png'));
echo '<pre>' . json_encode(array('rules' => $rules), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</pre>';