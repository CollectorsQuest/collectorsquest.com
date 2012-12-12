<?php
/* @var $wp_post wpPost */
?>

<div id="FeaturedItemsHeader">
  <?php
    if ($wp_post_image = $wp_post->getPostThumbnail('original'))
    {
      echo cq_image_tag($wp_post_image, array('alt' => $wp_post->getPostTitle()));
    }
  ?>
</div>


