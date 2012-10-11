<?php cq_dart_slot('300x250', 'collections', 'landing', 'sidebar') ?>

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
          echo '<li><strong>' . link_to('Pawn Stars', '@aetn_pawn_stars') . '</strong></li>';

          if (IceGateKeeper::open('aetn_picked_off', 'page'))
          {
            echo '<li><strong>' . link_to('Picked Off', '@aetn_picked_off') . '</strong></li>';
          }

          $route = '@wordpress_featured_items?id=29455&slug=political-buttons';
          echo '<li>', link_to('<strong>Political Buttons</strong> <sup style="color: #cc0000">NEW!</sup>', $route), '</li>';
        }
        else
        {
          // Special case to have American Pickers and American Restoration appear alphabetically in list
          if ($category->getId() === 402)
          {
            echo '<li><strong>' . link_to('American Pickers', '@aetn_american_pickers') . '</strong></li>';
            if (IceGateKeeper::open('aetn_american_restoration', 'page'))
            {
              echo '<li><strong>' . link_to('American Restoration', '@aetn_american_restoration') . '</strong></li>';
            }
          }

          echo '<li>', ($category) ? link_to_content_category($category, 'text') : '', '</li>';
        }
      }
    ?>
  </ul>
</div>

<?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      'misc_guide_to_collecting', array('ref' => cq_link_ref('sidebar'))
    );
  }
?>

<?php include_component('_sidebar', 'widgetMagnifyVideos'); ?>
