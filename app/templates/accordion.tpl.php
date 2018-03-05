<?php
if (empty($id)) {
  $id = 'accordion';
}
$first = TRUE;
?>

<div class="panel-group" id="<?php print $id; ?>">
  <?php foreach ($panels as $panel_key => $panel): ?>
    <?php print theme('panel', $panel += ['id' => $id, 'key' => $panel_key, 'collapsible' => TRUE, 'collapsed' => !$first]); ?>
    <?php $first = FALSE; ?>
  <?php endforeach; ?>
</div>