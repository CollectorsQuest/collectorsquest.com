<?php
  /**
   * @var $categories           ContentCategory[]
   * @var $subcategories        ContentCategory[]
   * @var $current_category     ContentCategory
   * @var $current_sub_category ContentCategory
   */

  cq_sidebar_title('Categories');
?>

<div class="onecolumn cf">
  <ul>
    <?php foreach ($categories as $i => $category): ?>
      <li>
        <?php if ($category != $current_category): ?>
          <?= ($category) ? link_to_content_category($category) : ''; ?>
        <?php else: ?>
          <?php
              if ($current_sub_category->isNew())
              {
                echo ($category) ? $category : '';
              }
              else
              {
                echo ($category) ? link_to_content_category($category) : '';
              }
          ?>
          <ul>
            <?php foreach ($subcategories as $j => $subcategory): ?>
              <li>
                <?php if ($subcategory != $current_sub_category): ?>
                  <?= ($subcategory) ? link_to_content_category($subcategory) : ''; ?>
                <?php else: ?>
                  <?= ($subcategory) ? $subcategory : ''; ?>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
