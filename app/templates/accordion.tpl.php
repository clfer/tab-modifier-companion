<?php
if (empty($id)) {
  $id = 'accordion';
}
?>

<div class="panel-group" id="<?php print $id; ?>">
  <?php foreach ($panels as $panel_key => $panel): ?>

    <?php
    $class = 'panel';
    $class .= ' panel-default';

    $panel_id = $id . '-panel-' . $panel_key;
    ?>
      <div class="<? print $class ?>" id="<?php print $panel_id; ?>">
          <div class="panel-heading">
              <h4 class="panel-title">
                  <a class="collapsed" data-toggle="collapse" data-target="#collapse-<?php print $panel_id; ?>" href="#collapse-<?php print $panel_id; ?>">
                    <?php print $panel['title']; ?>
                  </a>
              </h4>

          </div>
          <div id="collapse-<?php print $panel_id; ?>" class="panel-collapse collapse">
              <div class="panel-body">
                <?php print $panel['content']; ?>
              </div>
          </div>
      </div>
  <?php endforeach; ?>
</div>