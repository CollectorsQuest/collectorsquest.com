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
      <?php $linkPrev = $url . $mark . $options['page_param'] . '=' . $pager->getPreviousPage(); ?>
      <?php
        echo link_to(
          ' &larr; ', $linkPrev,
          array(
            'data-page' => $pager->getPreviousPage(),
            'title' => ' &larr; Go to the previous page'
          )
        );
      ?>
    </li>
    <?php else: ?>
    <li class="disabled"><a href="javascript:void(0);"> &larr; </a></li>
    <?php endif; ?>
    <?php if ($pager->getPage() > 5): ?>
    <li>
      <?php
        echo link_to(
          1, $url . $mark . $options['page_param'] . '=1',
          array('data-page' => 1, 'title' => 'Go to the first page')
        );
      ?>
    </li>
    <li class="disabled"><a href="javascript:void(0);"> ... </a></li>
    <?php endif; ?>
    <?php foreach ($pager->getLinks() as $page): ?>
    <?php if ($page != $pager->getPage()): ?>
      <li>
        <?php
          echo link_to(
            $page, $url . $mark . $options['page_param'] . '=' . $page,
            array('data-page' => $page, 'title' => 'Go to page '. $page)
          );
        ?>
      </li>
      <?php else: ?>
      <li class="active"><a href="javascript:void(0);"><?= $page ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php if (false && $pager->getLastPage() > $page): ?>
    <li class="disabled"><a href="javascript:void(0);"> ... </a></li>
    <li>
      <?php
        echo link_to(
          $pager->getLastPage(),
          $url . $mark . $options['page_param'] . '=' . $pager->getLastPage(),
          array(
            'data-page' => $pager->getLastPage(),
            'title' => 'Go to page '. $pager->getLastPage()
          )
        );
      ?>
    </li>
    <?php endif; ?>
    <?php if ($pager->getPage() != $pager->getCurrentMaxLink()): ?>
    <li class="next">
      <?php $linkNext = $url . $mark . $options['page_param'] . '=' . $pager->getNextPage(); ?>
      <?php
        echo link_to(
          ' &rarr; ', $linkNext,
          array(
            'data-page' => $pager->getNextPage(),
            'title' => 'Go to page '. $pager->getNextPage()
          )
        );
      ?>
    </li>
    <?php else: ?>
    <li class="disabled"><a href="javascript:void(0);"> &rarr; </a></li>
    <?php endif; ?>

    <?php if (@$options['show_all'] ?: false): ?>
    <li class="spacer-left">
      <?= link_to(__('show all'), $url . $mark . $options['page_param'] . '=1&show=all'); ?>
    </li>
    <?php endif; ?>
  </ul>
</div>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value += 92;
  }
?>

<?php
  slot('prev_next');

  echo !empty($linkPrev) ? tag('link', array('rel' => 'prev', 'href' => $linkPrev), true) : null;
  echo !empty($linkNext) ? tag('link', array('rel' => 'next', 'href' => $linkNext), true) : null;
  echo tag('link', array('rel' => 'next', 'href' => $url), true);

  end_slot();
?>

<?php endif; ?>
