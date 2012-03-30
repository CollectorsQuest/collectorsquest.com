<div style="margin-top: 10px">&nbsp;</div>
<div class="row-fluid" style="background: #0982C2;">
  <div class="span9">
    <h2 class="FugazOne" style="font-size: 18px; color: white;">&nbsp;&nbsp;Market Spotlight</h2>
  </div>
  <div class="span3"><a href="#">Link is here>></a></div>
</div>

<br/>
<div class="row-fluid" style="background: #FEF8E0; margin-left: 0; overflow: hidden;">
  <h2 class="FugazOne" style="font-size: 20px; color: #125276; line-height: 46px;">&nbsp;&nbsp;Spotlight on items from the Civil War</h2>
  <div class="span4" style="width: 31%; margin-left: 10px;">
    <div class="thumbnail" style="background: white; border: 1px solid #C8BEB2;">
      <?= ice_image_tag_flickholdr('260x260', array(), 1) ?>
      <br/>
      <h4>some text here</h4>
      <br/>
      Placerat augue nunc enim nisi auctor? Penatibus, cum sagittis proin ac lacus.
      Odio scelerisque nunc dis tristique adipiscing tincidunt placerat mus, sit integer in parturient,
      phasellus proin nec elementum montes mus elit.
      <br/>
      <strong>$32.99</strong>
    </div>
  </div>
  <div class="span4" style="width: 31%">
    <div class="thumbnail" style="background: white; border: 1px solid #C8BEB2;">
      <?= ice_image_tag_flickholdr('260x260', array(), 2) ?>
      some text here
    </div>
  </div>
  <div class="span4" style="width: 31%">
    <div class="thumbnail" style="background: white; border: 1px solid #C8BEB2;">
      <?= ice_image_tag_flickholdr('260x260', array(), 3) ?>
      some text here
    </div>
  </div>
  <div class="span12">&nbsp;</div>
</div>


<? cq_section_title('Discover more items for sale'); ?>
<div class="row">
  <div id="collectibles" class="row-content">
    <?php
    /** @var $collectible Collectible */
    foreach ($collectibles as $i => $collectible)
    {
      echo '<div class="span4">';
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_for_sale_grid_view',
        array(
          'collectible' => $collectible,
          'culture' => (string) $sf_user->getCulture(),
          'i' => (int) $i
        )
      );
      echo '</div>';
    }
    ?>
  </div>
</div>
