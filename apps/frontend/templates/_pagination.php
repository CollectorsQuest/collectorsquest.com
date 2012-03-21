<?php
/**
 * @var  $options  array
 * @var  $pager    sfPropelPager
 */
?>

<?php if ($pager->haveToPaginate()): ?>
<div class="pagination" id="<?= $options['id']; ?>">
  <ul>
    <?php if ($pager->getPage() != 1): ?>
      <li class="prev">
        <?= link_to(' &larr; ', $options["url"] . $options['page_param'] . '=' . $pager->getPreviousPage()); ?>
      </li>
    <?php else: ?>
      <li class="disabled"><a href="javascript:void(0);"> &larr; </a></li>
    <?php endif; ?>
    <?php if ($pager->getPage() > 5): ?>
      <li><?= link_to(1, $options["url"] . $options['page_param'] . '=1'); ?></li>
      <li class="disabled"><a href="javascript:void(0);"> ... </a></li>
    <?php endif; ?>
    <?php foreach ($pager->getLinks() as $page): ?>
      <?php if ($page != $pager->getPage()): ?>
        <li><?= link_to($page, $options["url"] . $options['page_param'] . '=' . $page); ?></li>
      <?php else: ?>
        <li class="active"><a href="javascript:void(0);"><?= $page ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php if ($pager->getLastPage() > $page): ?>
      <li class="disabled"><a href="javascript:void(0);"> ... </a></li>
      <li><?= link_to($pager->getLastPage(), $options["url"] . $options['page_param'] . '=' . $pager->getLastPage()); ?></li>
    <?php endif; ?>
    <?php if ($pager->getPage() != $pager->getCurrentMaxLink()): ?>
      <li class="next">
        <?= link_to(' &rarr; ', $options["url"] . $options['page_param'] . '=' . $pager->getNextPage()); ?>
      </li>
    <?php else: ?>
      <li class="disabled"><a href="javascript:void(0);"> &rarr; </a></li>
    <?php endif; ?>
    <li style="margin-left: 10px;">
      <?= link_to(__('show all'), $options["url"] . $options['page_param'] . '=1&show=all'); ?>
    </li>
  </ul>
</div>
<?php endif; ?>
