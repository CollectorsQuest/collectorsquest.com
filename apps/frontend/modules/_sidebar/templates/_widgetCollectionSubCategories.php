<?php
  /**
   * @var $categories               ContentCategory[]
   * @var $subcategories            ContentCategory[]
   * @var $sub_subcategories        ContentCategory[]
   * @var $current_category         ContentCategory
   * @var $current_sub_category     ContentCategory
   * @var $current_sub_subcategory  ContentCategory
   */

  cq_sidebar_title($current_category);
?>

<div class="onecolumn cf">
  <ul>
    <?php foreach ($subcategories as $i => $subcategory): ?>
      <li>
        <?php
          if ($subcategory != $current_sub_category):
            echo ($subcategory) ? link_to_content_category($subcategory) : '';
          else:
              if (empty($sub_subcategories) || $current_sub_subcategory->isNew())
              {
                echo ($subcategory) ? $subcategory : '';
              }
              else
              {
                echo ($subcategory) ? link_to_content_category($subcategory) : '';
              }
        ?>
          <ul>
            <?php foreach ($sub_subcategories as $j => $sub_subcategory): ?>
              <li>
                <?php
                  if ($sub_subcategory != $current_sub_subcategory):
                    echo ($sub_subcategory) ? link_to_content_category($sub_subcategory) : '';
                  else:
                    echo ($sub_subcategory) ? $sub_subcategory : '';
                  endif;
                ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
