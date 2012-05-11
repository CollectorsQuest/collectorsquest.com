<?php
/**
 * @var $collector Collector
 * @var $profile CollectorProfile
 */
?>

<div class="row-fluid mycq-collector-info">
  <div class="span2">
    <div class="mycq-collector-avatar">
      <?= image_tag_collector($collector, '140x140') ?>
      <?php // image_tag_collector($collector, '235x315', array('max_width' => 138, 'max_height' => 185)) ?>
      <span><?= ucfirst($profile->getCollectorType()); ?> Collector</span>
    </div>
  </div>
  <div class="span10">
    <?php
      cq_sidebar_title(
        'My Public Profile',
        link_to('Edit my Profile &raquo;', '@mycq_profile', array('class' => 'text-v-middle link-align'))
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
