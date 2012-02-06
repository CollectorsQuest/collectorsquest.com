<?php

/** @var $form FeaturedWeekForm */

use_stylesheet('backend/collections.css');

if ($featured_week = $form->getObject())
{
  /** @var $featured_week Featured */

  $ids = $featured_week->getCollectibleIds();
  $items = array();

  $collectibles = CollectibleQuery::create()->filterById($ids, Criteria::IN)->find();
  foreach ($collectibles as $collectible)
  {
    $items[] = get_partial('collectibles/list_view', array('collectible' => $collectible));
  }
}

?>

<?php if (!empty($items)): ?>
<div class="clearfix sf_admin_form_row sf_admin_text">
  <label>Collectible Names:</label>
  <div class="input" style="padding-top: 9px;">
    <?= implode(', ', $items); ?>
  </div>
</div>
<?php endif; ?>
