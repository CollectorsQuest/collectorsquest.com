<?php
/**
 * @var $collectible  Collectible
 */
?>

<?php
cq_page_title(
  $collectible->getName(),
  link_to('Back to Collection &raquo;', '@content_categories')
);
?>


<!--
  Test with alternate images: http://www.collectorsquest.next/collectible/3515/rkw-teacup
  Test without alternate images: http://collectorsquest.next/collectible/70081/space-set
//-->


<div class="row-fluid" xmlns="http://www.w3.org/1999/html">
  <?php
    $span = 10;
    if (empty($additional_multimedia))
    {
      $span += 2;
    }
  ?>
  <div class="span<?= $span; ?>">
    <div class="thumbnail" style="text-align: center;">
      <?php
        echo link_to(
          image_tag_collectible(
            $collectible, '610x1000',
            array('max_width' => 610, 'class' => 'magnify')
          ),
          src_tag_collectible($collectible, 'original'),
          array('id' => 'collectible_multimedia_primary', 'target' => '_blank')
        );
      ?>
    </div>
  </div>

  <?php if (!empty($additional_multimedia)): ?>
  <div class="span2">
    <?php foreach ($additional_multimedia as $m): ?>
    <a class="zoom" href="<?php echo src_tag_multimedia($m, '1024x768'); ?>" title="<?php echo $m->getName(); ?>" onClick="return false;">
      <?php echo image_tag_multimedia($m, '100x100', array('max_width' => 85, 'title' => $m->getName())); ?>
      <br/><br/>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php if ($collectible->getDescription('stripped')): ?>
<br style="clear:both;"/>
<div class="row-fluid">
  <h3>
    <?php
    if ($sf_user->isOwnerOf($collectible))
    {
      echo __('This is what you said about this collectible:');
    }
    else
    {
      echo sprintf(__('What %s says about this collectible:'), link_to_collector($collectible, 'text'));
    }
    ?>
  </h3>
  <br style="clear:both;"/>
  <div>
    <dd id="collectible_<?= $collectible->getId(); ?>_description"
        style="border-left: 2px solid #eee; padding-left: 15px; font-size: 14px;"
      ><?= $collectible->getDescription('html'); ?></dd>
  </div>
</div>
<br style="clear:both;"/>
<?php endif; ?>


<div class="info-blue-box bottom-margin-double">
  <div class="row-fluid">
    <div class="span4">
      <ul>
        <li>
          <span>XXXX Views</span>
        </li>
        <li>
          <span>In XXX wanted lists</span>
        </li>
      </ul>
    </div>
    <div class="span8 text-right">
      <a href="#" class="btn btn-mini-share2 btn-lightblue">
        <i class="add-icon-middle"></i> Add to your want list
      </a>
      <a href="#" class="btn btn-mini-share btn-lightblue">
        <i class="mail-icon-mini"></i> Mail
      </a>
      <a class="btn-mini-social" href="http://facebook.com/Collectors.Quest" target="_blank" >
        <i class="s-16-icon-facebook social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="http://twitter.com/CollectorsQuest" target="_blank" >
        <i class="s-16-icon-twitter social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="#" target="_blank" >
        <i class="s-16-icon-google social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="http://pinterest.com/CollectorsQuest" target="_blank">
        <i class="s-16-icon-pinterest social-ico-padding"></i>
      </a>
    </div>
  </div>
</div>

<div class="about-item-info">
  <p><strong>Akkilioki Peecol</strong>, Limited edition Peecol by eboy for Kidrobot<p>
  <p>This arctic princess loves listening to Bjork and hitting the slopes on her snowboard when she isn't too busy studying for school. For kicks, visit her <a href="#">MySpace page! </a></p>
</div>

<div id="comments">
  <div class="add-comment">
    <div class="input-append post-comment">
      <form method="post" action="comment">
        <input type="text" id="c" data-provide="comment" autocomplete="off" name="c">
        <button type="submit" class="btn btn-large">Comment</button>
        <a class="upload-photo" title="Add a photo">&nbsp;</a>
      </form>
    </div>
  </div>
  <div class="user-comments">
    <div class="row-fluid user-comment">
      <div class="span2 text-right">
        <a href="#">
          <img src="http://placehold.it/65x65" alt="">
        </a>
      </div>
      <div class="span10">
        <p class="bubble left">
          <a href="#" class="username">RobotBacon Wow!</a>
          That gun is a real rarity.  I don't think the south produced much in the way of weaponry, so that is a good find!
          <span class="comment-time">2 hours ago</span>
        </p>
      </div>
    </div>
    <div class="row-fluid user-comment">
      <div class="span2 text-right">
        <a href="#">
          <img src="http://placehold.it/65x65" alt="">
        </a>
      </div>
      <div class="span10">
        <p class="bubble left">
          <a href="#" class="username">RobotBacon Wow!</a>
          That gun is a real rarity.  I don't think the south produced much in the way of weaponry, so that is a good find!
          <span class="comment-time">2 hours ago</span>
        </p>
      </div>
    </div>
  </div>
  <div class="see-more-under-image-set">
    <button class="btn btn-small gray-button see-more-full" id="see-more-comments">
      See all XX comments
    </button>
  </div>

</div>

Permalink: <span class="lightblue"><?= url_for_collectible($collectible, true) ?></span>

