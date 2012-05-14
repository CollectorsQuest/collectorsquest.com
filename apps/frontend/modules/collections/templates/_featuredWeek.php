<?php
/**
 * @var $collectibles Collectible[]
 * @var $collection Collection
 * @var $featured_week Featured
 */
?>

<div id="weeks-promo-box">
  <div class="row-fluid">
    <div class="span12">
      <span class="weeks-promo-title Chivo webfont"><?= $wp_post->getPostTitle() ?></span>
    </div>
  </div>

  <div class="row imageset">
    <div class="span-12">
      <ul class="thumbnails">
        <li class="span6">
          <a href="#">
            <?= image_tag_wp_post($wp_post, '308x301'); ?>
          </a>
          <span class="white-block">
            <?= $wp_post->getPostContent(); ?>
          </span>
        </li>
        <?php foreach ($collectibles as $i => $collectible): ?>
        <li class="span3 <?= ($i >= 4) ? 'dn' : null; ?>">
          <?php
            include_partial(
              'collection/collectible_grid_view_square_small',
              array('collectible' => $collectible, 'i' => (int) $i)
            );
          ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <button class="btn btn-small gray-button see-more-full"
          id="seemore-featured-week"
          data-target="#weeks-promo-box div.imageset">
    See more
  </button>
</div>

<script>
$(document).ready(function()
{
  $('#seemore-featured-week').click(function()
  {
    var $url = '<?= url_for('@ajax_collections?section=component&page=featuredWeekCollectibles&id='. $wp_post->getId()) ?>';
    var $button = $(this);
    $button.html('loading...');

    $.get($url, function(data)
    {
      $($button.data('target')).append(data);

      $('.fade-white').mosaic();
      $("a.target").bigTarget({
        hoverClass: 'over',
        clickZone : 'div.link'
      });

      $button.hide();
    },'html');
  });
});
</script>
