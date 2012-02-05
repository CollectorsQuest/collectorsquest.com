<?php
  use_stylesheet('legacy/pagination.css', 'last');

  $options['title'] = (!empty($options['title']) && stripos($options['title'], '%d')) ? $options['title'] : __('Page %d');

  // Remove any "page=\d+" from the URL
  $options['url'] = preg_replace('/(\?|&)?page=\d+/iu', '', isset($options['url']) ? $options['url'] : $sf_request->getUri());
?>

<?php if (@$pager instanceof sfPropelPager): ?>
  <?php if ($pager->haveToPaginate()): ?>
    <div class="pagination">
      <?php if ($pager->getPage() != 1): ?>
        <span class="previous">
          <?php echo link_to('&laquo; '. __('previous'), $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$pager->getPreviousPage()); ?>
        </span>
      <?php else: ?>
        <span class="disabled">&laquo; <?php echo  __('previous'); ?></span>
      <?php endif; ?>
      <?php if ($pager->getPage() > 5): ?>
        <span><?php echo link_to(1, $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page=1', array('class' => 'page')); ?></span>
        ...
      <?php endif; ?>
      <?php foreach ($pager->getLinks() as $page): ?>
        <?php if ($page != $pager->getPage()): ?>
          <span><?php echo link_to($page, $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$page); ?></span>
        <?php else: ?>
          <span class="current"><?php echo $page ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php if ($pager->getLastPage() > $page): ?>
        ...
        <span><?php echo link_to($pager->getLastPage(), $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$pager->getLastPage(), array('class' => 'page')); ?></span>
      <?php endif; ?>
      <?php if ($pager->getPage() != $pager->getCurrentMaxLink()): ?>
        <span class="next">
          <?php echo link_to(__('next').' &raquo;', $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$pager->getNextPage()); ?>
        </span>
      <?php else: ?>
        <span class="disabled"><?php echo __('next'); ?> &raquo;</span>
      <?php endif; ?>
      <span style="margin-left: 10px;">
        <?php if ($pager->getNbResults() < 250): ?>
          <?php
            echo link_to(
              __('show all'),
              $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page=1&show=all',
              ($pager->getNbResults() > 100) ?
                array('onClick' => 'return confirm("'. __('You have more than 100 items to show, this may slow down your browser a lot. Are you sure you want to continue?') .'");') :
                array()
            );
          ?>
        <?php else: ?>

        <?php endif; ?>
      </span>
    </div>
    <br clear="all"/>
  <?php endif; ?>
<?php endif; ?>


<script language="javascript" type="text/javascript">
$(function()
{
  $("div.pagination a").bigTarget(
  {
    hoverClass: 'pointer',
    clickZone : 'span:eq(0)'
  });
});
</script>
