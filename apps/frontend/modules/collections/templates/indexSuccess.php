<?php cq_page_title('Collections'); ?>

<br/>
<div id="weeks-promo-box">
  <div class="row-fluid">
    <div class="span8">
      <span class="weeks-promo-title Chivo webfont">Camera week: Strike a pose</span>
    </div>
    <div class="span4 text-right">
      <a href="#" class="link-align">See previous features &raquo;</a>
    </div>
  </div>
  <div class="row imageset">
    <div class="span-12">
      <ul class="thumbnails">
        <li class="span6">
          <a href="#">
            <?= ice_image_tag_flickholdr('370x301', array('i' => 4)) ?>
          </a>
          <span class="white-block">
            Say cheese! This week we're featuring collectors who love to point and shoot for interesting cameras. They're ready for their close-up!
          </span>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150', array('i' => 11)) ?>
          </a>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150', array('i' => 4)) ?>
          </a>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150', array('i' => 12)) ?>
          </a>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150', array('i' => 3)) ?>
          </a>
        </li>
        <li class="span3 dn">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150', array('i' => 21)) ?>
          </a>
        </li>
        <li class="span3 dn">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150', array('i' => 18)) ?>
          </a>
        </li>
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

<? cq_section_title('Explore Collections') ?>

<div id="sort-search-box">
  <div class="input-append">
    <form action="<?= url_for('@search_collections'); ?>" method="post">
    <div class="btn-group">
      <div class="append-left-gray">Sort by <strong id="sortByName">Most Relevant</strong></div>
      <a href="#" data-toggle="dropdown" class="btn gray-button dropdown-toggle">
        <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="javascript:" class="sortBy" data-name="Most Relevant" data-sort="most-relevant">Sort by <strong>Most Relevant</strong></a></li>
        <li><a href="javascript:" class="sortBy" data-name="Most Recent" data-sort="most-recent">Sort by <strong>Most Recent</strong></a></li>
        <li><a href="javascript:" class="sortBy" data-name="Most Popular" data-sort="most-popular">Sort by <strong>Most Popular</strong></a></li>
      </ul>
    </div>
    <input type="text" name="q" id="appendedPrependedInput" class="input-sort-by"><button type="submit" class="btn gray-button"><strong>Search</strong></button>
    <input type="hidden" name="s" id="sortByValue" value="most-relevant">
    </form>
  </div>
</div>

<div class="row">
  <div id="collections" class="row-content">
  <?php
    /** @var $collections Collection[] */
    foreach ($collections as $i => $collection)
    {
      echo '<div class="span4" style="margin-bottom: 15px">';
      include_partial(
        'collection/collection_grid_view',
        array('collection' => $collection, 'i' => $i)
      );
      echo '</div>';
    }
  ?>
  </div>
</div>
<div class="see-more-under-image-set">
  <button class="btn btn-small gray-button see-more-full" id="see-more-collections">
    See more
  </button>
</div>

<script>
  $(document).ready(function()
  {
    $('.dropdown-toggle').dropdown();

    $('.dropdown-menu a.sortBy').click(function()
    {
      $('#sortByName').html($(this).data('name'));
      $('#sortByValue').val($(this).data('sort'));
    });

    $('#seemore-featured-week').click(function()
    {
      var $button = $(this);

      $.get($button.data('url'), function(data)
      {
        $($button.data('target')).append(data);
      },'html');

      $(this).hide();
    });
  });
</script>
