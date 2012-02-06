<script type="text/javascript">
  $(document).ready(function() { createPlayer('<?php echo url_for($playlist_xml); ?>'); });
</script>

<div class="span-24 append-bottom rounded-top" style="margin-top: -5px; background: #F5F8DD; padding: 20px;">
  <div id="mediaplayer">
    <div id='mediaspace'>&nbsp;</div>
    <div id="video-title">&nbsp;</div>
    <div id="video-description">&nbsp;</div>
  </div>

  <div id="playlist" style="float: left;">
    <?php include_partial('playlist', array('videos' => $videos)); ?>
  </div>

  <div id="video-ads" class="rounded">
    <?php if (!empty($amazon_products) && false): ?>
    <?php foreach ($amazon_products as $product):?>
      <div style="text-align: center; margin-bottom: 15px; margin-top: 10px;">
      <?php if (!empty($product['image'])): ?>
        <?php echo link_to(image_tag($product['medium_image'], array('style' => 'margin-bottom: 5px; height: 150px', 'align' => 'center')), $product['url'], array('target' => '_blank')) ?>
        <?php if (isset($product['price']) && $product['price'] > 0): ?>
          <br clear="all">
          <font style='font-size: 14px; color: red'><?php echo sprintf('%s %01.2f', @$product['currency'], @$product['price']) ?></font>
        <?php endif; ?>
      <?php endif ?>
      <br clear="all">
      <font style='font-size: 12px'>
        <?php echo link_to(truncate_text($product['title'], 45, '...', true), $product['url'], array('target' => '_blank')) ?>
      </font>
      </div>
    <?php endforeach;?>
    <?php else: echo cq_ad_slot('collectorsquest_com_-_Sidebar_160x600', 160, 500); ?>
    <?php endif; ?>
  </div>
  <br clear="all">
</div>

<br clear="all">

<div class="rounded" style="margin: 0 20px; border: 1px solid #ccc; padding: 20px; height: 95px;">
  <div style="float: left; width: 200px; color: #000;">
    <font style="color: #829EAD; font-size: 20px;"><?= __('EVENTS'); ?></font>
    <br><br>
    <?= __('CQ On the Scene'); ?>
  </div>
  <?php foreach($playlists['events'] as $playlist): ?>
  <div style="float: left; width: 150px; margin: 0 10px; text-align: center;">
    <?php echo link_to(image_tag($playlist->getThumbSmallSrc()), '@video_playlist?playlist_id='.$playlist->getId().'&slug='.$playlist->getSlug()); ?>
    <br>
    <?php echo link_to($playlist->getTitle(), '@video_playlist?playlist_id='.$playlist->getId().'&slug='.$playlist->getSlug()); ?>
  </div>
  <?php endforeach; ?>
  <br clear="all">
</div>

<br>
<div class="rounded" style="margin: 0 20px; border: 1px solid #ccc; padding: 20px; height: 105px;">
  <div style="float: left; width: 200px; color: #000;">
    <font style="color: #829EAD; font-size: 20px;"><?= __('SPOTLIGHT'); ?></font>
    <br><br>
    <?= __('Toys, Trends and Triumphs'); ?>
  </div>
  <div id="spotlight" style="float: left; width: 150px; margin: 0 10px; text-align: center;">
    <ul>
      <?php foreach($playlists['spotlight'] as $key => $playlist): ?>
      <li style="float: left; width: 150px; margin: 0pt 10px; text-align: center;">
        <?php echo link_to(image_tag($playlist->getThumbSmallSrc()), '@video_playlist?playlist_id='. $playlist->getId() .'&slug='. $playlist->getSlug()); ?>
        <br>
        <?php echo link_to($playlist->getTitle(), '@video_playlist?playlist_id='. $playlist->getId() .'&slug='. $playlist->getSlug()); ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $("#spotlight").jCarouselLite(
  {
    mouseWheel: true, circular: false, visible: 4, scroll: 1
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
