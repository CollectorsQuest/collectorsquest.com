<?php
/**
 * @var $collectibles Collectible[]
 */
?>
<div class="row">
  <div id="mwba-collectibles" class="row-content">
    <?php
    // set positions which will have the corresponding partials
    $wide  = array ( 1,  9, 20, 22 );
    $tall  = array ( 4,  8, 12, 15, 16 );
    $big   = array ( 5, 19, 18 );

    foreach ($collectibles as $i => $collectible)
    {
      // set the link to open modal dialog
      $link = link_to($collectible->getName(), 'ajax_aetn',
        array(
          'section' => 'mwba',
          'page' => 'collectible',
          'id' => $collectible->getId()
        ),
        array('class' => 'zoom-zone', 'onclick' => 'return false;')
      );

      // which partial we want to show the Collectible with
      $partial = '';
      if (in_array($i, $wide))
      {
        $partial = 'wide';
      }
      else if (in_array($i, $tall))
      {
        $partial = 'tall';
      }
      else if (in_array($i, $big))
      {
        $partial = 'square_big';
      }
      else
      {
        $partial = 'square_small';
      }

      include_partial(
        'collection/collectible_grid_view_' . $partial,
        array(
          'collectible' => $collectible, 'i' => $collectible->getId(),
          'link' => $link
        )
      );
    }
    ?>
  </div>
</div>

<script src="//s7.addthis.com/js/250/addthis_widget.js?#pubid=ra-4fa2c6240b775d05"></script>

<script>
  $(document).ready(function()
  {
    var $container = $('#mwba-collectibles');

    $container.imagesLoaded(function()
    {
      $container.masonry(
      {
        itemSelector : '.span3, .span6',
        columnWidth : 140, gutterWidth: 15
      });
    });

    $('a.zoom-zone').click(function(e)
    {
      e.preventDefault();

      var $a = $(this);
      var $div = $('<div></div>');

      $a.closest('.mosaic-overlay').showLoading();
      $div.appendTo('body').load($(this).attr('href'), function()
      {
          $a.closest('.mosaic-overlay').hideLoading();
          $('.modal', $div).modal('show');
      });

      return false;
    });
  });
</script>
