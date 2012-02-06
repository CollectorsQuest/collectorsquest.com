<?php if (!empty($buttons)): ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => $buttons)); ?>
  </ul>
<?php endif; ?>

<?php include_component('_sidebar', 'widgetRelatedCollections', array('collections' => $collections)); ?>

<hr>
<div class="span-6 last" style="margin-top: 10px;">
  <?php cq_ad_slot('collectorsquest_com_-_Sidebar_160x600', '160', '600'); ?>
</div>
