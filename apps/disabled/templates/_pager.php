<?php
/** @var $pager sfPropelPager */
/** @var $sf_request sfWebRequest */

use_stylesheet('legacy/pagination.css', 'last');

$options['title']      = (!empty($options['title']) && stripos($options['title'], '%d')) ? $options['title'] : __('Page %d');
$options['page_param'] = @$options['page_param'] ? : 'page';

$url          = isset($options['url']) ? $options['url'] : $sf_request->getUri();
$questionMark = strpos($url, '?');
$params       = array();
$page         = 1;
if (false !== $questionMark)
{
  $queryStr = substr($url, $questionMark + 1);
  $url      = substr($url, 0, $questionMark);
  foreach (explode('&', $queryStr) as $param)
  {
    $item = explode('=', $param);
    if ($item[0] == $options['page_param'])
    {
      continue;
    }
    $params[$item[0]] = $item[1];
  }
}

$url .= '?' . http_build_query($params);
$mark = !empty($params) ? '&' : '';
// Remove any "page=\d+" from the URL
//$options['url'] = preg_replace(
//  '/(\?|&)?' . $options['page_param'] . '=\d+/iu', '',
//  @$options['url'] ?: $sf_request->getUri()
//);

?>

<?php if (@$pager instanceof sfPropelPager): ?>
<?php if ($pager->haveToPaginate()): ?>
  <div class="pagination">
    <?php if ($pager->getPage() != 1): ?>
    <span class="previous">
      <?php echo link_to('&laquo; ' . __('previous'), $url . $mark . $options['page_param'] . '=' . $pager->getPreviousPage()); ?>
    </span>
    <?php else: ?>
    <span class="disabled">&laquo; <?php echo  __('previous'); ?></span>
    <?php endif; ?>
    <?php if ($pager->getPage() > 5): ?>
    <span><?php echo link_to(1, $url . $mark . $options['page_param'] . '=1', array('class' => 'page')); ?></span>
    ...
    <?php endif; ?>
    <?php foreach ($pager->getLinks() as $page): ?>
    <?php if ($page != $pager->getPage()): ?>
      <span><?php echo link_to($page, $url . $mark . $options['page_param'] . '=' . $page); ?></span>
      <?php else: ?>
      <span class="current"><?php echo $page ?></span>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php if ($pager->getLastPage() > $page): ?>
    ...
    <span><?php echo link_to($pager->getLastPage(), $url . $mark . $options['page_param'] . '=' . $pager->getLastPage(), array('class' => 'page')); ?></span>
    <?php endif; ?>
    <?php if ($pager->getPage() != $pager->getCurrentMaxLink()): ?>
    <span class="next">
          <?php echo link_to(__('next') . ' &raquo;', $url . $mark . $options['page_param'] . '=' . $pager->getNextPage()); ?>
        </span>
    <?php else: ?>
    <span class="disabled"><?php echo __('next'); ?> &raquo;</span>
    <?php endif; ?>
    <?php if ('all' == $sf_request->getParameter('show')): ?>
    <span style="margin-left: 10px;">
          <?php
      echo link_to(
        __('show all'),
          $url . $mark . $options['page_param'] . '=1&show=all',
        ($pager->getNbResults() > 100) ?
            array('onclick' => 'return confirm("' . __('You have more than 100 items to show, this may slow down your browser a lot. Are you sure you want to continue?') . '");') :
            array()
      );
      ?>
      </span>
    <?php endif; ?>
  </div>
  <br clear="all" />
  <?php endif; ?>
<?php endif; ?>

<script language="javascript" type="text/javascript">
  $(function () {
    $("div.pagination a").bigTarget(
        {
          hoverClass:'pointer',
          clickZone:'span:eq(0)'
        });
  });
</script>
