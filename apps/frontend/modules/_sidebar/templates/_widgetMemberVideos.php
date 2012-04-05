<?php /* @var $videos ContentEntry[] */ ?>
<div class="row-fluid" style="border-bottom: 1px dotted red;">
  <div class="span9">
    <h3 style="color: #125375; font-family: 'Chivo', sans-serif;">Member Videos</h3>
  </div>
  <div class="span3" style="padding-top: 5px; text-align: right;">
    <a href="<?php echo sfConfig::get('app_magnify_channel', 'collectors-quest.magnify.net') ?>">See all >></a>
  </div>
</div>
<br/>
<div class="row-fluid">
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
