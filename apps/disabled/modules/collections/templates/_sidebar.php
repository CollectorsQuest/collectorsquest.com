<?php if (!empty($buttons)): ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => $buttons)); ?>
  </ul>
<?php endif; ?>

<h2>Collections Tag Cloud</h2>
<div id="sidebar-tag-cloud">
<?php
  if (isset($tags))
  foreach($tags as $tag => $count)
  {
    echo link_to(
      $tag,
      '@collections_by_tag?tag='. $tag .'&page=1',
      array('class'  => 'tag_popularity_'.($count+3))
    )." &nbsp; ";
  }
?>
</div>
