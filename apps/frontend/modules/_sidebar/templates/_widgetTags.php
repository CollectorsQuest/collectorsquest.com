<?= $title ?>
<ul class="cf" style="list-style: none; padding: 0; margin: 0;">
<?php
  foreach ($tags as $tag)
  {
    echo '<li class="rounded" style="background: #e7e4e4; padding: 0 10px 0 10px; color: #887777; line-height: 24px; float: left; margin: 10px 5px;">';
    echo link_to(
      $tag, 'tag', array('tag' => $tag),
      array(
        'title' => sprintf('Explore incredible and unique %s collections!', $tag),
        'style' => 'color: #887777;'
      )
    );
    echo '</li>';
  }
?>
</ul>
