<div class="tags-container-sidebar">
  <p><?= $title; ?></p>
  <?php
    foreach ($tags as $tag)
    {
      echo link_to(
        $tag, 'tag', array('tag' => $tag),
        array(
          'title' => sprintf('Explore incredible and unique %s collections!', $tag),
          'class' => 'tags'
        )
      );
    }
  ?>
</div>
