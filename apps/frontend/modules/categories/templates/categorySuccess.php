<?php
/**
 * @var  $collectors_question array
 *
 * @var  $category    ContentCategory
 * @var  $collection  Collection
 * @var  $pager       PropelModelPager
 */
?>

<?php
  cq_page_title(
    sprintf('%s', $category->getName()),
    link_to('Back to Collections &raquo;', '@collections')
  );
?>

<div class="cf spacer-top-20">
  <?= nl2br($category->getDescription()); ?>
</div>

<br/>
<div class="row" style="margin-left: -12px;">
  <div id="collections" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $collection)
    {
      include_partial(
        'collection/collection_grid_view_square_small',
        array('collection' => $collection, 'i' => $i)
      );
    }
  ?>
  </div>
</div>

<?php if ($pager->getPage() == 1 && $collectors_question): ?>

  <?php if ($pager->haveToPaginate()): ?>
    <a href="<?= url_for('content_category', $category) ?>?page=2"
       class="btn btn-small see-more-full" style="width: 590px;">
      See more
    </a>
  <?php endif; ?>

  <?php
    $link = null; // link_to('See previous challenges »', '@homepage');
    cq_section_title("The Collectors' Question", $link, array('left' => 8, 'right' => 4));
  ?>

  <div class="row-fluid relative">
    <img src="<?= $collectors_question['image']; ?>" alt="<?= $collectors_question['title']; ?>" title="<?= $collectors_question['title']; ?>"/>
    <div class="span12" style="position: absolute; top: 65%; background: url(/images/frontend/white.png); padding: 15px 25px; margin: 0;">
      <h2 class="Chivo webfont" style="font-size: 26px; font-weight: bold; font-style: italic;"><?= $collectors_question['title']; ?></h2>
      <?= $collectors_question['content']; ?>
    </div>
  </div>
  <?php include_component('comments', 'comments', array('for_object' => $category)); ?>

<?php else: ?>

  <div class="row-fluid text-center">
  <?php
    include_component(
      'global', 'pagination', array('pager' => $pager)
    );
  ?>
  </div>

<?php endif; ?>
