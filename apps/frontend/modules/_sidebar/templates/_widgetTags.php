<?php cq_sidebar_title($title, null); ?>

<div class="tags-container-sidebar">
  <?php
    foreach ($tags as $tag)
    {
      echo link_to(
        $tag, 'tag', array('tag' => Utf8::slugify($tag, '-', false)),
        array(
          'title' => sprintf('Explore incredible and unique %s collections!', $tag),
          'class' => 'tags'
        )
      );
    }
  ?>
</div>
