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
  <?php /* @var $video ContentEntry */ ?>
  <?php foreach ($videos as $video): ?>
  <div class="row-fluid bottom-margin">
      <div class="span5">
        <a href="<?php echo $video->getIframeUrl() ?>" title="<?php echo $video->getTitle() ?>">
          <img src="<?php echo $video->getThumbnail() ?>" alt="<?php echo $video->getTitle() ?>" width="120" style="flaot: left;" />
        </a>
      </div>
      <div class="span7 max-height-video-box-sidebar">
        <div id="sidebar-videos">
          <span class="title">
            <a href="<?php echo $video->getIframeUrl() ?>" title="<?php echo $video->getTitle() ?>">
              <?php echo $video->getTitle() ?>
            </a>
          </span>
          <span class="content">
            <?php echo $video->getContent(); ?>
          </span>
          <span class="updated-at">
          <?php echo $video->getUpdatedAt() ?>
          </span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
