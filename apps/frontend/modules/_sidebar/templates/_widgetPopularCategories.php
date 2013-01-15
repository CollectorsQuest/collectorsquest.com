<?php
  $link = link_to(
    'See all &raquo;',
    '@content_categories', array('class' => 'text-v-middle link-align')
  );
  $link = null;

  cq_sidebar_title('Popular Categories', $link);
?>

<div class="twocolumn cf">
  <ul>
    <?php
    /** @var $categories ContentCategory[] */
    foreach ($categories as $i => $category)
    {
      // Special case for the Political Buttons landing page
      if ($category->getId() === 2266)
      {
        // Special case to have Pawn Stars and Picked Off appear alphabetically in list
        echo '<li><strong><em>' . link_to('Pawn Stars', '@aetn_pawn_stars') . '</em></strong></li>';

        echo '<li><strong><em>' . link_to('Picked Off', '@aetn_picked_off') . '</em></strong></li>';

        $route = '@wordpress_featured_items?id=29455&slug=political-buttons';
        echo '<li>', link_to('<strong>Political Buttons</strong> <sup style="color: #cc0000">NEW!</sup>', $route), '</li>';
      }
      else
      {
        // Special case to have American Pickers and American Restoration appear alphabetically in list
        if ($category->getId() === 402)
        {
          echo '<li><strong><em>' . link_to('American Pickers', '@aetn_american_pickers') . '</em></strong></li>';

          echo '<li><strong><em>' . link_to('American Restoration', '@aetn_american_restoration') . '</em></strong></li>';
        }
        // Special case to display Halloween theme page
        if ($category->getId() === 1559)
        {
          $route = '@wordpress_featured_items?id=31565&slug=halloween-collectibles';
          echo '<li>', link_to('<strong>Halloween</strong> <sup style="color: #cc0000">NEW!</sup>', $route), '</li>';
        }

        // display 'regular' popular categories
        echo '<li>', ($category) ? link_to_content_category($category, 'text') : '', '</li>';
      }
    }
    ?>
  </ul>
</div>
