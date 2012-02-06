<?php

/** @var $form FeaturedWeekForm */

use_stylesheet('backend/collections.css');

if ($featured_week = $form->getObject())
{
  /** @var $featured_week Featured */

  $ids = $featured_week->getCollectionIds();
  $items = array();

  $collections = CollectionQuery::create()->filterById($ids, Criteria::IN)->find();
  foreach ($collections as $collection)
  {
    $items[] = get_partial('collections/list_view', array('collection' => $collection));
  }
}

?>

<?php if (!empty($items)): ?>
<div class="clearfix sf_admin_form_row sf_admin_text">
  <label>Collection Names:</label>
  <div class="input" style="padding-top: 9px;">
    <?= implode(', ', $items); ?>
  </div>
</div>
<?php endif; ?>
