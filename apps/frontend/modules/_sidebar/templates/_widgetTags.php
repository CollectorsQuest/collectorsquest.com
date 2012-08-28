<?php
/*
 * @var $height stdClass
 * @var $title string
 *
 * approximately how many rows of tags we have
 */
  $tag_rows = (integer) (count($tags) / 4 + 1);

  if (!isset($height)):
    $height = new stdClass;
    $height->value=0;
  endif;
?>

<?php if ($height->value > (63 + 33 * $tag_rows)): ?>

  <?php cq_sidebar_title($title, null); ?>
  <?php $height->value -= 63; ?>

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
  <?php $height->value -= 33 * $tag_rows; ?>

<?php endif;
