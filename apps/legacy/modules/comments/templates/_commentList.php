<a name="comments"></a>
<?php echo cq_section_title(__('Comments')); ?>
<br clear="all">
<div class="prepend-1 span-16">
  <?php 
    if (count($comments) > 0)
    {
      foreach ($comments as $comment)
      {
        include_partial('comments/comment_view', array('object' => $object, 'comment' => $comment));
      }
    }
    else
    {
      echo '<p>', __('There are no comments yet!'), '</p>';
    }
  ?>
</div>
