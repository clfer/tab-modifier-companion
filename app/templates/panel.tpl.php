<?php
$panel_id = ['panel', $key];
if (!empty($id)) {
  array_unshift($panel_id, $id);
}
$panel_id = implode('-', $panel_id);
?>
<div class="panel panel-default" id="<?php print $panel_id; ?>">
    <div class="panel-heading">
        <h4 class="panel-title">
          <?php if (!empty($collapsible)): ?>
              <a class="<?php print !empty($collapsed) ? 'collapsed' : ''; ?>" data-toggle="collapse" data-target="#collapse-<?php print $panel_id; ?>" href="#collapse-<?php print $panel_id; ?>">
                <?php print $title; ?>
              </a>
          <?php else: ?>
            <?php print $title; ?>
          <?php endif; ?>
        </h4>

    </div>
  <?php if (!empty($collapsible)): ?>
    <div id="collapse-<?php print $panel_id; ?>" class="panel-collapse collapse<?php print !empty($collapsed) ? '' : ' in'; ?>">
      <?php endif; ?>
      <?php if (!empty($content)): ?>
          <div class="panel-body">
            <?php print $content; ?>
          </div>
      <?php endif; ?>
      <?php if (!empty($panel_body_suffix)): ?>
        <?php print $panel_body_suffix; ?>
      <?php endif; ?>

      <?php if (!empty($collapsible)): ?>
    </div>
<?php endif; ?>
</div>