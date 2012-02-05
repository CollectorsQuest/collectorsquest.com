<?php

/** @var $form FeaturedWeekForm */

if ($featured_week = $form->getObject())
{
  /** @var $featured_week Featured */

  $ids = $featured_week->getCollectorIds();
  $q = CollectorQuery::create()
     ->filterById($ids, Criteria::IN)
     ->select(array('DisplayName'))
     ->setFormatter(ModelCriteria::FORMAT_ARRAY);

  $collectors = $q->find();
}

?>

<?php if (!$collectors->isEmpty()): ?>
<div class="clearfix sf_admin_form_row sf_admin_text">
  <label>Collector Names:</label>
  <div class="input" style="padding-top: 9px;">
    <?= implode(', ', $collectors->toArray()); ?>
  </div>
</div>
<?php endif; ?>
