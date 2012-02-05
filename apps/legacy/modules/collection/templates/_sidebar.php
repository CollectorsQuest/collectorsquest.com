<?php if (!empty($buttons)): ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => $buttons)); ?>
  </ul>
<?php endif; ?>

<div style="text-align: center;">
  <span class="st_twitter_large" displayText="Tweet"></span>
  <span class="st_facebook_large" displayText="Facebook"></span>
  <span class="st_gbuzz_large" displayText="Google Buzz"></span>
  <span class="st_email_large" displayText="Email"></span>
  <span class="st_sharethis_large" displayText="ShareThis"></span>
</div>
<div class="clear">&nbsp;</div>

<?php if (isset($collection) && $collection->getId() > 0): ?>
<h2><?php echo  __('Collection Information'); ?></h2>
<div style="margin-top: -8px; padding: 10px; padding-bottom: 15px; background: #C5E67E;">
  <table>
    <thead style="font-size: 12px;">
    <tr>
      <th style="text-align: center;"><?php echo  __('Collectibles'); ?></th>
      <th style="text-align: center;"><?php echo  __('Views'); ?></th>
      <th style="text-align: center;"><?php echo  __('Comments'); ?></th>
    </tr>
    <thead>
    <tr>
      <td style="text-align: center;"><?php echo  (int) $collection->countCollectibles(); ?></td>
      <td style="text-align: center;"><?php echo  (int) $collection->getNumViews(); ?></td>
      <td style="text-align: center;"><?php echo  (int) $collection->getNumComments(); ?></td>
    </tr>
  </table>

  <h4 style="color: #368AA2; margin-bottom: 5px;"><?php echo  __('Category:'); ?></h4>
  <div style="padding-left: 10px;"><?php echo  $collection->getCollectionCategory(); ?></div>

  <br>
  <h4 style="color: #368AA2; margin-bottom: 5px;"><?php echo  __('Description:'); ?></h4>
  <div style="padding-left: 10px;"><?php echo  cqStatic::linkify($collection->getDescription('html')); ?></div>

  <?php if ($collection->getTags()): ?>
    <br>
    <h4 style="color: #368AA2; margin-bottom: 5px;"><?php echo  __('Keywords/Tags:'); ?></h4>
    <div style="padding-left: 10px;"><?php echo  cq_tags_for_collection($collection); ?></div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php // include_component('_sidebar', 'widgetAmazonProducts', array('collection' => $collection)); ?>
<?php
  if ($collection->countCollectibles() > 4)
  {
    include_component('_sidebar', 'widgetRelatedCollections', array('collection' => $collection, 'limit' => 5));
  }
?>
