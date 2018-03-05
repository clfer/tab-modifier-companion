<?php

if (is_array($content['head'])) {
  $content['head'] = implode('', $content['head']);
}

if (is_array($content['body'])) {
  $content['body'] = implode('', $content['body']);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/theme/css/style.css">


  <?php // Include page header  ?>

  <?php print $content['head']; ?>

    <link href="/theme/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container<?php print !empty($content['class']) ? ' ' . $content['class'] : ''; ?>">

  <?php print $content['body']; ?>

</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/theme/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/theme/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>