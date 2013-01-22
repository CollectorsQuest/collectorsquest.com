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
      // display 'regular' popular categories
      echo '<li>', ($category) ? link_to_content_category($category, 'text') : '', '</li>';
    }
  ?>
  </ul>
</div>
