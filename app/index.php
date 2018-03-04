<?php
require_once 'vendor/autoload.php';
require_once 'icon_generation.php';
require_once 'templates/helpers.php';
require_once 'pages.php';
$pages = [
  'icon_generation' => 'Icon generation',
  'conf' => 'Conf display',
]
?>

<?php
$content = [
  'head' => [],
  'body' => [],
];

try {
  if (!empty($_GET['q']) && function_exists('page_' . $_GET['q'])) {
    if (isset($pages[$_GET['q']])) {
      $content['head'][] = '<title>' . $pages[$_GET['q']] . '</title>';
    }
    $back_link = '<a href="/" class="btn-default btn">< Back</a>';
    $page_title =  '<h1>' . $pages[$_GET['q']] . '</h1>';

    $content['body'][] = $back_link;
    $content['body'][] = $page_title;

    $page_content = call_user_func('page_' . $_GET['q']);

    $content = array_merge_recursive($content, $page_content);

  }
  else {
    $content['head'][] = '<title>Index</title>';


    $content['body'][] = '<h1>Index</h1> ';
    $content['body'][] = '<ul>';
    foreach ($pages as $page => $title) {
      $content['body'][] = '<li><a href="/?q=' . $page . '">' . $title . '</a>';
    }
    $content['body'][] = '</ul>';
  }


} catch (Exception $e) {
  $content['body'][] = '<h2>Fail...</h2>';
  $content['body'][] = '<p>Exception: ' . $e->getMessage() . '</p>';
  $content['body'][] = '<pre>' . $e->getTraceAsString() . '</pre>';

}

include 'templates/html.php';
?>


