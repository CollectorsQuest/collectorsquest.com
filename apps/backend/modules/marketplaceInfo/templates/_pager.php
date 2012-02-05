<?php 
use_helper('Form','JavascriptBase');
use_stylesheet('frontend/pagination.css', 'last'); ?>
<?php 
$options['url'] = preg_replace('/(\?|&)?page=\d+/i', '', $options['url']);
if ($pager->haveToPaginate()): ?>

<div class="pagination">
  <?php if ($pager->getPage() != 1): ?>
  <span> <?php echo link_to('&laquo; previous',$options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$pager->getPreviousPage().$options['ssOtherParams'],array('title' => '&laquo; previous'));?> </span>
  <?php else: ?>
  <span class="disabled">&laquo; <?php echo  __('previous'); ?></span>
  <?php endif; ?>
  <?php if ($pager->getPage() > 5): ?>
  <span>
  <?php	echo link_to(1,$options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page=1'.$options['ssOtherParams'],array('title' => '1'));?>
  </span> ...
  <?php endif; ?>
  <?php foreach ($pager->getLinks() as $page): ?>
  <?php if ($page != $pager->getPage()): ?>
  <span> <?php echo link_to($page,$options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$page.$options['ssOtherParams'],array('title' => $page));?> </span>
  <?php else: ?>
  <span class="current"><?php echo $page ?></span>
  <?php endif; ?>
  <?php endforeach; ?>
  <?php if ($pager->getLastPage() > $page): ?>
  ... <span> <?php echo link_to($pager->getLastPage(), $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$pager->getLastPage().$options['ssOtherParams'], array('title' => $pager->getLastPage()));?> </span>
  <?php endif; ?>
  <?php if ($pager->getPage() != $pager->getCurrentMaxLink()): ?>
  <span>
  <?php	echo link_to(__('next'), $options["url"].((strpos($options["url"], '?') !== false)?'&':'?').'page='.$pager->getNextPage().$options['ssOtherParams'], array('title' => __('next')));?>
  </span>
  <?php else: ?>
  <span class="disabled"><?php echo __('next'); ?> &raquo;</span>
  <?php endif; ?>
   <!--<form name="jumber" style="float:right;">
    	Jump to&nbsp;
   	<?php /*
		echo input_tag('jpage', null, array('style' => 'width: 25px;'));
		echo submit_tag('Page',array('title' => 'Jump to','style' => 'cursor:pointer; border:none;'));
		*/
	?>
  </form>-->
</div>
<?php endif; ?>