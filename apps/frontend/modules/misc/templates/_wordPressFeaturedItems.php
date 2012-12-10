<?php
/* @var $pager             cqPropelModelPager */
/* @var $infinite_scroll   boolean            */
/* @var $collectibles_2x2  array              */
/* @var $collectibles_1x2  array              */
/* @var $collectibles_2x1  array              */
/* @var $collectibles_1x3  array              */
/* @var $collectibles_2x3  array              */
/* @var $collectibles_3x3  array              */
/* @var $collectibles_3x2  array              */
/* @var $collectibles_3x1  array              */
/* @var $post_id           integer            */
/* @var $cq_layout         string             */
?>

<?php if ($cq_layout == 'grid'): ?>
  <div id="collectibles" class="row-content">
    <?php
      foreach ($pager->getResults() as $i => $collectible)
      {
        /* @var $collectible Collectible */
        $id = $collectible->getId();

        // which partial we want to show the Collectible with
        $partial = '';
        if (in_array($id, $collectibles_2x1))
        {
          $partial = 'wide';
        }
        else if (in_array($id, $collectibles_1x2))
        {
          $partial = 'tall';
        }
        else if (in_array($id, $collectibles_2x2))
        {
          $partial = 'square_big';
        }
        else if (in_array($id, $collectibles_1x3))
        {
          $partial = '1x3';
        }
        else if (in_array($id, $collectibles_2x3))
        {
          $partial = '2x3';
        }
        else if (in_array($id, $collectibles_3x3))
        {
          $partial = '3x3';
        }
        else if (in_array($id, $collectibles_3x2))
        {
          $partial = '3x2';
        }
        else if (in_array($id, $collectibles_3x1))
        {
          $partial = '3x1';
        }
        else
        {
          $partial = 'square_small';
        }

        include_partial(
          'collection/collectible_grid_view_' . $partial,
          array(
            'collectible' => $collectible, 'i' => (int) $i,
            'lazy_image' => false
          )
        );
      }
    ?>
  </div>

<?php elseif ($cq_layout == 'pinterest'): ?>
  <div id="collectibles" class="row thumbnails" style="margin-left: 0;">
    <?php
    /* @var $collectible Collectible */
    /* @var $pager       PropelModelPager */
    foreach ($pager->getResults() as $i => $collectible)
    {
      if ($collectible->isForSale())
      {
        include_partial(
          'marketplace/collectible_for_sale_masonry_view_big',
          array(
            'collectible_for_sale' => $collectible->getCollectibleForSale(),
            'url' => url_for_collectible($collectible),
            'link_parameters' => array('class' => 'target zoom-zone')
          )
        );
      }
      else
      {
        include_partial(
          'collection/collectible_masonry_view_big',
          array(
            'collectible' => $collectible,
            'url' => url_for_collectible($collectible),
            'link_parameters' => array('class' => 'target zoom-zone')
          )
        );
      }
    }
    ?>
  </div>
<?php endif; ?>

<?php
 /*
  *  this div closes a div defined in misc/wordPressFeaturedItemsNoSidebarSuccess
  *  and misc/wordPressFeaturedItemsSuccess
  */
?>
</div>


<?php if ($infinite_scroll == true && $pager->getPage() === 1): ?>
  <div class="row-fluid text-center hidden">
    <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'collectibles-pagination',
          'url' => url_for('@ajax_misc?section=component&page=wordPressFeaturedItems&id=' . $post_id),
          'show_all' => true,
          'page_param' => 'p',
        )
      )
    );
    ?>
  </div>

  <script>
    $(document).ready(function()
    {
      var $container = $('#collectibles');

      $container.imagesLoaded(function()
      {
        $container.masonry(
          {
            <?php if ($cq_layout == 'pinterest'): ?>
            itemSelector : '.brick, .span4',
            columnWidth : 220, gutterWidth: 18
            <?php else: ?>
            itemSelector : '.span3, .span6, .span9',
            columnWidth : 140, gutterWidth: 15
            <?php endif; ?>
          });
      });

      $container.infinitescroll(
      {
        navSelector:'#collectibles-pagination',
        nextSelector:'#collectibles-pagination li.next a',
        <?php if ($cq_layout == 'pinterest'): ?>
          itemSelector:'#collectibles .span4',
        <?php else: ?>
          itemSelector:'#collectibles .span3, #collectibles .span6, #collectibles .span9',
        <?php endif; ?>
        loading:{
          msgText:'',
          finishedMsg:'No more items to load.',
          img:'<?= image_path('frontend/progress.gif'); ?>'
        },
        state: {
          curPage: 2
        },
        bufferPx:150
      },
      // trigger Masonry as a callback
      function(selector) {
        // hide new bricks while they are loading
        var $bricks = $(selector).css({opacity: 0});

        // ensure that images load before adding to masonry layout
        $bricks.imagesLoaded(function() {
          // show bricks now that they're ready
          $bricks.animate({opacity: 1});
          $container.masonry('appended', $bricks, true);

          $('.fade-white').mosaic();
          $("a.target").bigTarget({
            hoverClass: 'over',
            clickZone : 'div.link'
          });
        });
      });
    });
  </script>
<?php endif; ?>

<?php if ($infinite_scroll !== true): ?>
<div class="row-fluid text-center clear">
  <?php
    include_component(
      'global', 'pagination', array('pager' => $pager, 'options' => array('page_param' => 'p'))
    );
  ?>
</div>
<?php endif; ?>
