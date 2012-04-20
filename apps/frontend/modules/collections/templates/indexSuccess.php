<?php cq_page_title('Collections'); ?>

<br/>
<?php include_component('collections', 'featuredWeek'); ?>

<?php cq_section_title('Explore Collections') ?>
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
        <li><a href="javascript:" class="sortBy" data-name="Collections" data-sort="collections">Sort by <strong>Collections</strong></a></li>
        <li><a href="javascript:" class="sortBy" data-name="Collectibles" data-sort="collectibles">Sort by <strong>Collectibles</strong></a></li>
      </ul>
    </div>
    <input type="text" name="q" id="appendedPrependedInput" class="input-sort-by"><button type="submit" class="btn gray-button"><strong>Search</strong></button>
    <input type="hidden" name="s" id="sortByValue" value="most-relevant">
    </form>
  </div>
</div>

<div class="row" style="margin-left: -12px;">
  <div id="collections" class="row-content">
  <?php
    /** @var $collections Collection[] */
    foreach ($collections as $i => $collection)
    {
      include_partial(
        'collection/collection_grid_view_square_small',
        array('collection' => $collection, 'i' => $i)
      );
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
});
</script>
