<?php
/* @var $pager sfPropelPager */
/* @var $commentsCount int */
/* @var $comments Comment[] */
/* @var $sf_request sfWebRequest */
?>

<a name="comments"></a>
<?php echo cq_section_title(__('Comments')); ?>
<br clear="all">
<div id="comments-wrapper" class="prepend-1 span-16">
  <?php if ($pager->getNbResults() > 0): ?>
  <?php foreach ($pager->getResults() as $comment): ?>
    <?php include_partial('comments/comment_view', array(
      'object'  => $object,
      'comment' => $comment
    )); ?>
    <?php endforeach; ?>
  <?php else: ?>
  <p><?php echo __('There are no comments yet!') ?></p>
  <?php endif; ?>
</div>

<?php if ($pager->haveToPaginate()): ?>
<div id="comments-pager">
  <br class="clear" /><br />

  <div class="span-19 last" style="margin-bottom: 25px">
    <?php
    include_partial(
      'global/pager',
      array(
        'pager'   => $pager,
        'options' => array(
          'update'     => 'comments-wrapper',
//            'url'        => $sf_request->getPathInfo() . http_build_query($sf_request->getGetParameters()),
          'page_param' => 'cpage',
        )
      )
    );
    ?>
  </div>
</div>
<?php endif; ?>
