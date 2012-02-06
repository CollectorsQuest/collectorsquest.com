<?php if ($sf_user->isOwnerOf($collection)): ?>
	<span style="margin: 0 0 0 20px; color: #6699CC; font-weight: bold; font-size: 15px;">
    Click on an image below to edit the collectible information <?php echo $sf_user->hasCredential('seller') ? ' and add pricing.' : ''; ?>
  </span>
<?php endif;?>
<div class="clear" style="height: 20px;">&nbsp;</div>
<div id="update_view_collectible">
<?php
  foreach ($pager->getResults() as $i => $collectible)
  {
    // Show the collectible (in grid, list or hybrid view)
    include_partial(
      'collection/'. $display .'_view_collectible',
      array('collectible' => $collectible, 'editable' => $editable, 'culture' => $sf_user->getCulture(), 'i' => $i)
    );
  }
?>
</div>
<br class="clear"><br>
<div class="span-19 last" style="margin-bottom: 25px">
  <?php
    include_partial(
      'global/pager',
      array('pager' => $pager, 'options' => array('url' => $sf_request->getPathInfo(),'update' => 'update_view_collectible'))
    );
  ?>
</div>

<?php if (!$sf_user->isAuthenticated()): ?>
<!--
<div class="span-19 append-bottom last">
  <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
</div>
//-->
<?php endif; ?>

<?php
  if ($collection->getId() > 0)
  {
    include_component('comments', 'commentList', array('object' => $collection));
    include_component('comments', 'commentForm', array('object' => $collection));
  }
?>
