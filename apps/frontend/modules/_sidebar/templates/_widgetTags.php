<?php
/*
 * @var $height stdClass
 * @var $title string
 *
 * approximately how many rows of tags we have
 */

$_height = 0;
?>

<?php cq_sidebar_title($title, null); ?>
<?php $_height -= 63; ?>

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
<?php $_height -= 33 * $tag_rows; ?>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
