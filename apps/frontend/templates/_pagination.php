<?php
/**
 * @var  $options  array
 * @var  $pager    sfPropelPager|PropelModelPager
 * @var  $sf_request sfWebRequest
 * @var  $sf_response sfWebResponse
 * @var  $url string
 * @var  $mark string
 */
$page = 1;
$linkPrev = $linkNext = false;
?>

<?php if ($pager->haveToPaginate()): ?>
<div class="pagination" id="<?= $options['id']; ?>">
  <ul>
    <?php if ($pager->getPage() != 1): ?>
    <li class="prev">
      <?php
      $linkPrev =  $url . $mark . $options['page_param'] . '=' . $pager->getPreviousPage();
      ?>
      <?= link_to(' &larr; ', $linkPrev); ?>
    </li>
    <?php else: ?>
    <li class="disabled"><a href="javascript:void(0);"> &larr; </a></li>
    <?php endif; ?>
    <?php if ($pager->getPage() > 5): ?>
    <li><?= link_to(1, $url . $mark . $options['page_param'] . '=1'); ?></li>
    <li class="disabled"><a href="javascript:void(0);"> ... </a></li>
    <?php endif; ?>
    <?php foreach ($pager->getLinks() as $page): ?>
    <?php if ($page != $pager->getPage()): ?>
      <li><?= link_to($page, $url . $mark . $options['page_param'] . '=' . $page); ?></li>
      <?php else: ?>
      <li class="active"><a href="javascript:void(0);"><?= $page ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php if ($pager->getLastPage() > $page): ?>
    <li class="disabled"><a href="javascript:void(0);"> ... </a></li>
    <li><?= link_to($pager->getLastPage(), $url . $mark . $options['page_param'] . '=' . $pager->getLastPage()); ?></li>
    <?php endif; ?>
    <?php if ($pager->getPage() != $pager->getCurrentMaxLink()): ?>
    <li class="next">
      <?php $linkNext = $url . $mark . $options['page_param'] . '=' . $pager->getNextPage(); ?>
      <?= link_to(' &rarr; ', $linkNext); ?>
    </li>
    <?php else: ?>
    <li class="disabled"><a href="javascript:void(0);"> &rarr; </a></li>
    <?php endif; ?>

    <?php if (@$options['show_all'] ? : false): ?>
    <li style="margin-left: 10px;">
      <?= link_to(__('show all'), $url . $mark . $options['page_param'] . '=1&show=all'); ?>
    </li>
    <?php endif; ?>
  </ul>
</div>

<?php slot('prev_next'); ?>
  <?php if ($linkPrev): ?>
    <link rel="prev" href="<?= $linkPrev ?>" />
  <?php endif; ?>
  <?php if ($linkNext): ?>
    <link rel="next" href="<?= $linkNext ?>" />
  <?php endif; ?>
  <link rel="start" href="<?= $url ?>" />
<?php end_slot(); ?>

<?php endif; ?>
