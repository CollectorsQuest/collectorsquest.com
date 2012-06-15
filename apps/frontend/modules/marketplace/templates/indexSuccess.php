<?php
/**
 * @var $wp_post wpPost
 * @var $collectibles_for_sale CollectibleForSale[]
 * @var $collectibles_for_sale_text array
 */
?>

<?php cq_page_title('Collectibles Marketplace'); ?>

<?php if (isset($wp_post) && $wp_post instanceof wpPost): ?>
<div class="row-fluid" id="marketplace-spotlight">
  <h2 class="spotlight-title Chivo webfont">
    <?= $wp_post->getPostTitle() ?>
  </h2>
  <?php foreach ($collectibles_for_sale as $i => $collectible_for_sale): ?>
  <div class="span4 link">
    <div class="thumbnail">
      <div class="spotlight-thumb">
        <?php
          echo image_tag_collectible(
            $collectible_for_sale->getCollectible(), '190x190',
            array('width' => 180, 'height' => 180)
          );
        ?>
        <span class="blue-label"><?= $collectibles_for_sale_text[$i]; ?></span>
      </div>
      <div class="spotlight-text">
        <h4><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target')); ?></h4>
        <p><?= $collectible_for_sale->getCollectible()->getDescription('stripped', 120); ?></p>
      </div>
      <div class="spotlight-price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>&nbsp;&nbsp;
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<br/>
<div class="banners-620">
  <?= link_to(image_tag('banners/040412_show_and_sell_red.gif'), '@collector_signup'); ?>
</div>

<?php cq_section_title('Discover more collectibles for sale'); ?>

<div class="sort-search-box full-lenght-blue">
  <div class="input-append">
    <form action="<?= url_for('@search_collectibles_for_sale'); ?>" method="post" id="form-discover-collectibles">
      <div class="btn-group">
        <div class="append-left-gray">Sort By <strong id="sortByName">Most Popular</strong></div>
        <a href="#" data-toggle="dropdown" class="btn gray-button dropdown-toggle">
          <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="javascript:" class="sortBy" data-name="Most Popular" data-sort="most-popular">Sort by <strong>Most Popular</strong></a></li>
          <li><a href="javascript:" class="sortBy" data-name="Most Recent" data-sort="most-recent">Sort by <strong>Most Recent</strong></a></li>
          <li><a href="javascript:" class="sortBy" data-name="Under $100" data-sort="under-100">Sort by <strong>Under $100</strong></a></li>
          <li><a href="javascript:" class="sortBy" data-name="$100 - $250" data-sort="100-200">Sort by <strong>$100 - $250</strong></a></li>
          <li><a href="javascript:" class="sortBy" data-name="Over $250" data-sort="over-250">Sort by <strong>Over $250</strong></a></li>
        </ul>
      </div>
      <input name="q" type="text" size="16" id="appendedPrependedInput" class="input-sort-by"><button type="submit" class="btn gray-button"><strong>Search</strong></button>
      <input type="hidden" name="s" id="sortByValue" value="most-popular">
      </form>
  </div>
</div>

<div id="items-for-sale">
  <div id="collectibles" class="row thumbnails">
    <?php include_component('marketplace', 'discoverCollectiblesForSale'); ?>
  </div>
</div>

<script>
$(document).ready(function()
{
  var $url = '<?= url_for('@ajax_marketplace?section=component&page=discoverCollectiblesForSale') ?>';
  var $form = $('#form-discover-collectibles');

  $('.dropdown-toggle').dropdown();
  $('.dropdown-menu a.sortBy').click(function()
  {
    $('#sortByName').html($(this).data('name'));
    $('#sortByValue').val($(this).data('sort'));
    $form.submit();
  });

  $form.submit(function()
  {
    $('#collectibles').fadeOut();

    $.post($url +'?p=1', $form.serialize(), function(data)
    {
      $('#collectibles').html(data).fadeIn();
    },'html');

    return false;
  });

  if ($form.find('input').val() !== '')
  {
    $form.submit();
  }
});
</script>
