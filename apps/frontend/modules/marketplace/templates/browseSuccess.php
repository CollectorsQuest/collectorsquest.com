<br/><br/>
<fieldset>
  <form action="<?php echo url_for('@marketplace') ?>" method="post">
    Price: <input type="text" name="price[min]" style="width: 80px;"/> - <input type="text" name="price[max]" style="width: 80px;">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Condition:
    <select name="condition" id="condition" style="width: 150px;">
      <option value="" selected="selected">Any</option>
      <option value="excellent">Excellent</option>
      <option value="very good">Very Good</option>
      <option value="good">Good</option>
      <option value="fair">Fair</option>
      <option value="poor">Poor</option>
    </select>
    <br/>
    Listings:
    <select name="addtional_listing" id="addtional_listing">
      <option value="" selected="selected">Active</option>
      <option value="Sold">Sold</option>
      <option value="All">All</option>
    </select>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="commit" value="Search" class="button">
  </form>
</fieldset>

<div class="row">
  <div id="collectibles" class="row-content">
    <?php
    /** @var $collectible_for_sale CollectibleForSale */
    foreach ($pager->getResults() as $i => $collectible_for_sale)
    {
      echo '<div class="span4">';
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_for_sale_grid_view',
        array(
          'collectible_for_sale' => $collectible_for_sale,
          'culture' => (string) $sf_user->getCulture(),
          'i' => (int) $i
        )
      );
      echo '</div>';
    }
    ?>
  </div>
</div>
