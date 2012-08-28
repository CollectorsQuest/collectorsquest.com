<?php
/*
 * @var $collectibles Collectible[]
 */

  use_javascript('jquery/rcarousel.js');

$_height = 0;
?>


<div class="carousel-sidebar sidebar-title">
  <h3 class="Chivo webfont spacer-bottom-5">Other items in this collection:</h3>
  <div class="thumbnails-inner well">
    <a href="javascript:void(0)" id="ui-carousel-prev" title="previous collectible" class="left-arrow">
      <i class="icon-chevron-left white"></i>
    </a>
    <a href="javascript:void(0)" id="ui-carousel-next" title="next collectible" class="right-arrow">
      <i class="icon-chevron-right white"></i>
    </a>
    <div id="carousel" data-loaded='<?= json_encode(array_fill(1, $carousel_page, true)); ?>'
         class="thumbnails">
      <?php foreach ($collectibles as $c): ?>
        <a href="<?= url_for_collectible($c) ?>"
           class="thumbnail <?= $c->getId() == $collectible->getId() ? 'active' : '' ?>">
          <?php
            echo image_tag_collectible(
              $c, '100x100', array('width' => 90, 'height' => 90)
            );
          ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php $_height -= 165; ?>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value += $_height;
  }
?>


<script type="text/javascript">
$(document).ready(function()
{
  var carousel_page = <?= $carousel_page; ?>;
  var carousel_page_offset = <?= $carousel_page_offset; ?>;

  var $carousel = $('#carousel');

  if ($carousel.children().length >= 3) {
    $carousel.rcarousel({
      visible: 3, step: 1,
      width: 90, height: 90,
      margin: 6,
      auto: { enabled: false, interval: 15000 },
      start: firstLoad,
      pageLoaded: loadPage
    });
  }

  function firstLoad(event)
  {
    $carousel.css('height', 'auto');

    if (carousel_page !== 1) {
      // execute carousel animation
      $carousel.rcarousel('goToPage', carousel_page);
    }

    // when current page is first page, manually fire event to load next page,
    // because goToPage will not fire the "pageLoaded" event
    if (1 == $carousel.rcarousel('getCurrentPage')) {
      loadPage(event, {});
    }
  }

  function loadPage()
  {
    var loaded = $carousel.data('loaded');

    var $link, $img, $jqElements = $();
    var url = '<?= url_for('ajax_collection', array('section' => 'collectibles', 'page' => 'carousel')); ?>';
    var target_page = $carousel.rcarousel('getCurrentPage') + 1;

    if (typeof(loaded) !== 'undefined' && loaded[target_page] === true)
    {
      return;
    }

    $.getJSON(url +'?p='+ (target_page + carousel_page_offset),
      {
        id: <?= $collection->getId(); ?>,
        collectible_id: <?= $collectible->getId(); ?>
      },
      function(data)
      {
        $.each(data.collectibles, function(i, collectible)
        {
          $link = $( "<a />" )
            .attr( "href", collectible.url )
            .attr( "class", 'thumbnail' );

          $img = $( "<img />" )
            .attr( "src", collectible.thumbnails.x75 )
            .attr( "width", 90 )
            .attr( "height", 90 );

          $link.html($img);
          $jqElements = $jqElements.add( $link );
        });

        if ($jqElements.length > 0)
        {
          $carousel.rcarousel("append", $jqElements);
        }

        loaded[target_page] = true;
      }
    );

    $carousel.data('loaded', loaded);
  }
});
</script>
