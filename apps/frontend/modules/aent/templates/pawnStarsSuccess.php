<div class="promo-space">
  <img src="/images/banners/040412_promo_space_banner.jpg" alt="">
</div>
<?php cq_page_title('As seen on Pawn Stars'); ?>

<div id="show-items">
  <div class="row">
    <ul class="thumbnails">
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
      <li class="span4">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/190x190" alt="">
        </a>
      </li>
    </ul>
  </div>
  <div class="top-margin-double">
    <button class="btn btn-small gray-button see-more-full" id="see-more-collections">
      See more
    </button>
  </div>
</div>

<div class="row-fluid sidebar-title">
  <div class="span7">
    <h3 class="Chivo webfont">
      Featured Items For Sale
    </h3>
  </div>
  <div class="span5 text-right">
    <a href="/collectors" class="text-v-middle link-align">
      See all items for sale »
    </a>&nbsp;
  </div>
</div>

<div id="items-for-sale">
  <div class="row thumbnails">
    <?php
    /** @var $collectibles_for_sale CollectibleForSale[] */
    foreach ($collectibles_for_sale as $i => $collectible_for_sale)
    {
      include_partial(
        'marketplace/collectible_for_sale_grid_view_square_small',
        array('collectible_for_sale' => $collectible_for_sale, 'i' => $i)
      );
    }
    ?>
  </div>
</div>
