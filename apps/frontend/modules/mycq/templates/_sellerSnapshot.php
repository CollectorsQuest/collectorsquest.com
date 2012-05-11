<?php
/**
 * @var $seller Collector
 * @var $profile CollectorProfile
 */
?>

<div class="row-fluid cp-collector-info">
  <div class="span12">
    <?php
      cq_sidebar_title(
        'My Market Place',
        link_to('Edit my Market Place &raquo;', '@mycq_marketplace', array('class' => 'text-v-middle link-align'))
      );
    ?>
    <div class="row-fluid">
      <div class="span5">
        <p>
          <strong>Name:&nbsp;</strong>
          <?= $collector->getDisplayName(); ?>
        </p>
        <p>
          <strong>From:&nbsp;</strong>
          <?= $profile->getCountry(); ?>
        </p>
        <?php if ($text = $profile->getProperty('about.what_you_collect')): ?>
        <p>
          <strong>I collect:</strong>
          <?= $text ?>
        </p>
        <?php endif; ?>
      </div>
      <div class="span5">
        <?php if ($text = $profile->getAboutMe()): ?>
        <p>
          <strong>About me:</strong>
          <?= cqStatic::truncateText($text, 140, '...', true); ?>
        </p>
        <?php endif; ?>
        <?php if ($text = $profile->getAboutCollections()): ?>
        <p>
          <strong>My collections are:&nbsp;</strong>
          <?= cqStatic::truncateText($text, 140, '...', true); ?>
        </p>
        <?php endif; ?>
      </div>
      <div class="span2">
        <strong>Linked accounts:</strong>
      </div>
    </div>
  </div>
</div>
