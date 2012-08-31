<?php
/**
 * @var $level1_categories ContentCategory[]
 * @var $category_other    ContentCategory
 */
?>

<?php cq_page_title('Categories'); ?>

<div id="all-categories" class="row">
  <?php foreach ($level1_categories as $k => $category):
    $level2_links = array();
  ?>

    <?php foreach ($category->getChildren(ContentCategoryQuery::create()->withCollections()->orderBy('Name')) as $child_category): ?>
      <?php $level2_links[] =  link_to_content_category($child_category); ?>
    <?php endforeach; ?>

    <?php if (!empty($level2_links)): ?>
      <div class="span4">
        <h2><?= link_to_content_category($category); ?></h2>
        <?php echo implode(', ', $level2_links); ?>
      </div>
    <?php endif; ?>

  <?php endforeach; ?>

  <?php //display the "Other" category last ?>
  <div class="span4">
    <h2><?= link_to_content_category($category_other); ?></h2>
  </div>
</div>
