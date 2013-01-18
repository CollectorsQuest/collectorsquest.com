<?php
/* @var $wp_post wpPost */
/* @var $collectibles Collectible[] */
/* @var $collectibles_bottom Collectible[] */
cq_page_title($wp_post->getPostTitle());

?>

<br/>
<div id="weeks-promo-box">
  <div class="row imageset">

    <div class="span-12">
      <ul class="thumbnails">
        <li class="span6">
          <?= image_tag_wp_post($wp_post, '308x301'); ?>
          <span class="white-block">
            <?= $wp_post->getPostContent(); ?>
          </span>
        </li>
        <?php foreach ($collectibles as $i => $collectible): ?>
        <li class="span3 <?= ($i >= 4) ? 'dn' : null; ?>">
          <?php
            include_partial(
              'collection/collectible_grid_view_square_small',
              array('collectible' => $collectible, 'i' => (int) $i)
            );
          ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="row">
      <?php
        /** @var $collectibles Collectible[] */
        foreach ($collectibles_bottom as $i => $collectible)
        {
          include_partial(
            'collection/collectible_grid_view_square_small',
            array('collectible' => $collectible, 'i' => (int) $i)
          );
        }
      ?>
    </div>

  </div>

</div>
