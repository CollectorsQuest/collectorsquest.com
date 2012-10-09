<?php
  /**
   * @var $categories                   ContentCategory[]
   * @var $subcategories                ContentCategory[]
   * @var $sub_subcategories            ContentCategory[]
   * @var $sub_sub_subcategories        ContentCategory[]
   * @var $current_category             ContentCategory
   * @var $current_subcategory          ContentCategory
   * @var $current_sub_subcategory      ContentCategory
   * @var $current_sub_sub_subcategory  ContentCategory
   * @var $height stdClass
   */

  if (IceGateKeeper::open('marketplace_categories', 'page'))
  {
    $link = link_to(
      'See all &raquo;', '@marketplace_categories',
      array('class' => 'text-v-middle link-align')
    );
  }
  else
  {
    $link = null;
  }

  cq_sidebar_title($current_category, $link);
  $_height = -63;
?>

<div class="onecolumn cf">
  <ul>
    <?php foreach ($subcategories as $i => $subcategory): ?>
      <li>
        <?php
          if ($subcategory && $subcategory != $current_subcategory)
          {
            echo link_to(
              $subcategory->getName(), 'marketplace_category_by_slug',
              $subcategory, array('title' => $subcategory->getName())
            );
            $_height -= 20;
          }
          else
          {
            if (empty($sub_subcategories) || $current_sub_subcategory->isNew())
            {
              echo ($subcategory) ? $subcategory : '';
              $_height -= 20;
            }
            else if ($subcategory)
            {
              echo link_to(
                $subcategory->getName(), 'marketplace_category_by_slug',
                $subcategory, array('title' => $subcategory->getName())
              );
              $_height -= 20;
            }
        ?>
          <ul>
            <?php foreach ($sub_subcategories as $j => $sub_subcategory): ?>
              <li>
                <?php
                  if ($sub_subcategory != $current_sub_subcategory)
                  {
                    if ($sub_subcategory)
                    {
                      echo link_to(
                        $sub_subcategory->getName(), 'marketplace_category_by_slug',
                        $sub_subcategory, array('title' => $sub_subcategory->getName())
                      );
                      $_height -= 20;
                    }
                  }
                  else
                  {
                    if (empty($sub_sub_subcategories) || $current_sub_sub_subcategory->isNew())
                    {
                      echo ($sub_subcategory) ? $sub_subcategory : '';
                      $_height -= 20;
                    }
                    else if ($sub_subcategory)
                    {
                      echo link_to(
                        $sub_subcategory->getName(), 'marketplace_category_by_slug',
                        $sub_subcategory, array('title' => $sub_subcategory->getName())
                      );
                      $_height -= 20;
                    }
                ?>
                  <ul>
                    <?php foreach ($sub_sub_subcategories as $k => $sub_sub_subcategory): ?>
                      <li>
                        <?php
                          if ($sub_sub_subcategory != $current_sub_sub_subcategory)
                          {
                            if ($sub_sub_subcategory)
                            {
                              echo link_to(
                                $sub_sub_subcategory->getName(), 'marketplace_category_by_slug',
                                $sub_sub_subcategory, array('title' => $sub_sub_subcategory->getName())
                              );
                              $_height -= 20;
                            }
                          }
                          else if ($sub_sub_subcategory)
                          {
                            echo ($sub_sub_subcategory) ? $sub_sub_subcategory : '';
                            $_height -= 20;
                          }
                        ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php } // endif ($sub_subcategory != $current_sub_subcategory) ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php } // endif ($subcategory != $current_subcategory) ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
