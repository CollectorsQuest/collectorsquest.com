<div id="weeks-promo-box">
  <div class="row-fluid">
    <div class="span8">
      <span class="weeks-promo-title Chivo webfont">Camera week: Strike a pose</span>
    </div>
    <div class="span4 text-right">
      &nbsp;
    </div>
  </div>

  <div class="row imageset">
    <div class="span-12">
      <ul class="thumbnails">
        <li class="span6">
          <a href="#">
            <?= ice_image_tag_flickholdr('308x301', array('i' => 14)) ?>
          </a>
          <span class="white-block">
            Say cheese! This week we're featuring collectors who love to point and shoot for interesting cameras. They're ready for their close-up!
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
          data-url="<?= url_for('@ajax_collections?section=component&page=featuredWeekCollectibles') ?>"
          data-target="#weeks-promo-box div.imageset">
    See more
  </button>
</div>

<script>
$(document).ready(function()
{
  $('#seemore-featured-week').click(function()
  {
    var $button = $(this);
    $button.html('loading...');

    $.get($button.data('url'), function(data)
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
