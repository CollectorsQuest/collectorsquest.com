<?php /* @var $videos ContentFeed */ ?>
<div class="row-fluid red-dashes-sidebar top-padding-double">
  <div class="span9">
    <span class="sidebar-title">Member Videos</span>
  </div>
  <div class="span3 text-right">
    <a href="<?php echo sfConfig::get('app_magnify_channel', 'collectors-quest.magnify.net') ?>">See all &raquo;</a>
  </div>
</div>
<br/>
<div class="row-fluid">
  <?php /* @var $video ContentEntry */ ?>
  <?php foreach ($videos as $video): ?>
  <div>
    <img src="<?php echo $video->getThumbnail() ?>" alt="<?php echo $video->getTitle() ?>" width="120" style="flaot: left;" />
    <?php echo $video->getTitle() ?>
    <?php echo $video->getContent(); ?>
    <?php echo $video->getUpdatedAt() ?>

    <!--
    <p><?php echo $video->getIframeUrl() ?></p>
    <p><?php echo $video->getTitle() ?></p>

    <p><?php echo $video->getContent(); ?></p>

    <p><?php echo $video->getUpdatedAt() ?></p>
    //-->
  </div>
  <?php endforeach; ?>
</div>
