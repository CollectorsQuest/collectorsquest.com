<?php

/** @var $form FeaturedWeekForm */

if ($featured_week = $form->getObject())
{
  /** @var $featured_week Featured */

  $ids = $featured_week->getCategoryIds();
  $q = CollectionCategoryQuery::create()
     ->filterById($ids, Criteria::IN)
     ->select(array('Name'))
     ->setFormatter(ModelCriteria::FORMAT_ARRAY);

  $collection_categories = $q->find();
}

?>

<?php if (!$collection_categories->isEmpty()): ?>
<div class="clearfix sf_admin_form_row sf_admin_text">
  <label>Category Names:</label>
  <div class="input" style="padding-top: 9px;">
    <?= implode(', ', $collection_categories->toArray()); ?>
  </div>
</div>
<?php endif; ?>
