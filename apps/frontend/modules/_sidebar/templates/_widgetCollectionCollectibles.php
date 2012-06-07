<?php
  use_javascript('jquery/ui.js');
  use_javascript('jquery/rcarousel.js');
?>

<div class="other-items-sidebar spacer-top-20">
  <span>Other collectibles in this collection:</span>
  <div class="thumbnails-inner">
    <a href="#" id="ui-carousel-prev" title="previous collectible" class="left-arrow" style="left: -12px;">
      <i class="icon-chevron-left white"></i>
    </a>
    <a href="#" id="ui-carousel-next" title="next collectible" class="right-arrow" style="right: -12px;">
      <i class="icon-chevron-right white"></i>
    </a>
    <div id="carousel" data-loaded='<?= json_encode(array_fill(1, $page, true)); ?>'
         class="thumbnails" style="">
      <?php foreach ($collectibles as $c): ?>
        <a href="<?= url_for_collectible($c) ?>" class="thumbnail" style="">
        <?php
          echo image_tag_collectible(
            $c, '75x75', array('width' => 69, 'height' => 69)
          );
        ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
  $("#carousel").rcarousel({
    visible: 4, step: 4,
    width: 73, height: 69,
    auto: { enabled: true, interval: 15000 },
    start: firstLoad,
    pageLoaded: loadPage
  });

  $('.other-items-sidebar').hover(
    function() { $("#ui-carousel-prev, #ui-carousel-next").show(); },
    function() { $("#ui-carousel-prev, #ui-carousel-next").hide(); }
  );

  function firstLoad(event)
  {
    $("#carousel").rcarousel('goToPage', <?= $page - 1; ?>);

    // when current page is first page, manually fire event to load next page,
    // because goToPage will not fire the "pageLoaded" event
    if (1 == $("#carousel").rcarousel('getCurrentPage')) {
      loadPage(event, {});
    }
  }

  function loadPage(event, data)
  {
    var loaded = $("#carousel").data('loaded');

    var $link, $img, $jqElements = $();
    var url = '<?= url_for('ajax_collection', array('section' => 'collectibles', 'page' => 'carousel')); ?>';
    var target_page = $("#carousel").rcarousel('getCurrentPage') + 1;

    if (typeof(loaded) !== 'undefined' && loaded[target_page] === true)
    {
      return;
    }

    $.getJSON(url +'?p='+ target_page,
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
            .attr( "class", 'thumbnail' )
            .attr( "style", 'margin: 0;' );

          $img = $( "<img />" )
            .attr( "src", collectible.thumbnails.x75 )
            .attr( "width", 69 )
            .attr( "height", 69 );

          $link.html($img);
          $jqElements = $jqElements.add( $link );
        });

        if ($jqElements.length > 0)
        {
          $( "#carousel" ).rcarousel("append", $jqElements);
        }

        loaded[target_page] = true;
      }
    );

    $("#carousel").data('loaded', loaded);
  }
});
</script>
