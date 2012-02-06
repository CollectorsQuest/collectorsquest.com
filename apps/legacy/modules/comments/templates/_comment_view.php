<?php use_helper('Gravatar'); ?>
<?php use_helper('Date'); ?>

<div class="comment append-bottom clear" id="comment_<?php echo $comment->getId() ?>">
  <div class="span-3 comment_avatar" style="text-align: center;">
  <?php 
    if ($comment->getCollector() !== null)
    {
      echo link_to_collector($comment->getCollector(), $type = 'image');
    }
    else
    {
      echo gravatar_image_tag($comment->getAuthorEmail(), null, null, $comment->getAuthorName());
    }
  ?>
  </div>
  <div class="span-12 comment_info">
    <?php
      if ($comment->getCollector() !== null)
      {
        $author = link_to_collector($comment->getCollector(), 'text');
      }
      else
      {
        $author = link_to_if($comment->getAuthorUrl(), trim($comment->getAuthorName()), $comment->getAuthorUrl(), array('rel' => 'nofollow'));
      }

      $date = __('%1% ago', array('%1%' => distance_of_time_in_words(strtotime($comment->getCreatedAt()))));

      echo __(
        '<span class="comment_author">%1%</span>, <a href="#comment_%2%">%3%</a>',
        array('%1%' => $author, '%2%' => $comment->getId(), '%3%' => $date)
      );

      if (($collectible = $comment->getCollectible()) && !$object instanceof Collectible)
      {
        echo '&nbsp; @ &nbsp;', link_to_collectible($collectible);
      }
    ?>

    <blockquote class="comment_body">
      <?php echo $comment->getBody(); ?>
    </blockquote>
  </div>
</div>
<br clear="all"><br>
