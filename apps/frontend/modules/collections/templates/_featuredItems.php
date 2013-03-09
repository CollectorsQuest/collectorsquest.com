<?php /** @var $featured_items wpPost[] */ ?>

<br/>
<div class="row">
<?php foreach ($featured_items as $k => $wp_post): ?>

<?php if (0 != $k && 0 == $k % 2): ?>
</div>
<div class="row">
<?php endif; ?>

<div id="wp_post_<?= $wp_post->getId(); ?>_featured"
     data-id="<?= $wp_post->getId(); ?>" class="wp_post_featured span6" style="margin-bottom: 15px;">

  <h3>
    <?php
      $link = link_to(
        $wp_post->getPostTitle(),
        array('sf_route' => 'wordpress_featured_items', 'sf_subject' => $wp_post)
      );

      echo !empty($title) ? $title : $link;
    ?>
  </h3>

  <?php
    echo link_to(
      image_tag_wp_post($wp_post, '300x0', array('style' => 'margin-bottom: 10px;')),
      array('sf_route' => 'wordpress_featured_items', 'sf_subject' => $wp_post)
    );
  ?>
  <?= !empty($excerpt) ? $excerpt : $wp_post->getPostExcerpt(130); ?>

</div>
<?php endforeach; ?>
</div>
