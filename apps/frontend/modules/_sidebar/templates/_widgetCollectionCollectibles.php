<?php
/*
 * @var $collectibles Collectible[]
 */

$_height = 0;
?>


<div class="carousel-sidebar sidebar-title" id="collectionCollectiblesWidget" data-page="<?=$page ?>"  data-lastpage="<?=$lastPage ?>" data-url="<?= url_for('ajax_collection', array('section' => 'collectibles', 'page' => 'collectionsWidget')); ?>">
  <h3 class="Chivo webfont spacer-bottom-5">Other items in this collection:</h3>
  <div class="thumbnails-inner well">
      <?php if ($lastPage!=1)
  { ?>
    <a href="javascript:void(0)" id="ui-carousel-prev" title="previous collectible" class="left-arrow">
      <i class="icon-chevron-left white"></i>
    </a>
    <a href="javascript:void(0)" id="ui-carousel-next" title="next collectible" class="right-arrow">
      <i class="icon-chevron-right white"></i>
    </a>
<?php } ?>
    <div id="carousel"
         class="thumbnails">
      <?php foreach ($collectibles as $key=>$c): ?>
        <?php if ($c->getId()== $curCollectible )
        {
          slot('lastItem');
          echo link_to($count.'<br />Items', 'collection_by_slug', $collection, array('class'=>'moreItems'));
          end_slot();
        }
        else
        {
          include_partial('_sidebar/widgetCollectionCollectiblesItem', array('item'=> $c));
        } ?>
      <?php endforeach ?>
      <?php echo get_slot('lastItem') ?>
    </div>
  </div>
</div>
<?php $_height -= 165; ?>

<?php
if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>


<script type="text/javascript">

  $(document).ready(function()
  {
    (function($){

    $.fn.extend({

      collectionCollectiblesWidget: function() {

        var defaults = {
          nextControl : '.right-arrow',
          prevControl : '.left-arrow',
          itemsHolder : '.thumbnails'
        }

        var options =  $.extend(defaults, options);

        return this.each(function() {

          var o = options;
          var widget = $(this);
          var curPage = widget.data('page');
          var lastPage = widget.data('lastpage');
          var url = widget.data('url');
          var cache = {};
          var holder = $(o.itemsHolder, widget);

          $(o.nextControl, widget).click(function(){

              loadPage(lastPage == curPage ? 1 : curPage+1);
          });
          $(o.prevControl, widget).click(function(){

            loadPage(curPage ==1 ? lastPage : curPage-1);
          });

          function loadPage(page)
          {
            curPage = page;
            if ( page in cache )
            {
              update();
            }
            else
            {
              holder.animate({'opacity':0.4},200);

              $.getJSON(url +'?widgetPage='+ page,
                {
                  id: <?= $collection->getId(); ?>,
                  collectible_id: <?= $collectible->getId(); ?>
                },
                function(data)
                {
                  cache[ page ] = data;
                  update();
                }
              );
            }
          }

          function update(){
            var data = cache[curPage];
            if(curPage != widget.data('page')&&data.html)
            {
              holder.animate({'opacity':0},200,function(){
                holder.html('');
                $(data.html).appendTo(holder);
                holder.animate({'opacity':1},300);
              });
            }
            widget.data('page',curPage);
          }
        });
      }
    });

  })(jQuery);


  $('#collectionCollectiblesWidget').collectionCollectiblesWidget();

});
</script>
