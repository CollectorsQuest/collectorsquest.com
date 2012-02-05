<br clear="all" /><br>

<?php
  $offset = 0;
  $collections = $pager->getResults();
  foreach ($collections as $i => $collection)
  {
    // Show the collection (in grid, list or hybrid view)
    include_partial(
      'collections/'. $display .'_view_collection',
      array(
        'collection' => $collection,
        'culture' => $sf_user->getCulture(),
        'i' => $i
      )
    );

    if (false && !$sf_user->isAuthenticated())
    {
      // Serving ads or showing of a related blog post
      if ('list' == $display && ($i + $offset) == 1)
      {
        !empty($blog_post) ?
          include_partial('collections/list_view_blog_post', array('blog_post' => $blog_post)) :
          include_partial('collections/list_view_ad');

        $offset++;
      }
      else if ('grid' == $display && ($i + $offset) == 4)
      {
        !empty($blog_post) ?
          include_partial('collections/grid_view_blog_post', array('blog_post' => $blog_post)) :
          include_partial('collections/grid_view_ad');

        $offset++;
      }
      else if ('hybrid' == $display && ($i + $offset) == 4)
      {
        !empty($blog_post) ?
          include_partial('collections/hybrid_view_blog_post', array('blog_post' => $blog_post)) :
          include_partial('collections/hybrid_view_ad');

        $offset++;
      }
    }

    echo (($i+$offset+1)%3==0)?'<br clear="all">':'';
  }
?>

<br clear="all">
<div class="span-19 last" style="margin-bottom: 25px">
  <?php include_partial('global/pager', array('pager' => $pager)); ?>
</div>

<?php if (!$sf_user->isAuthenticated()): ?>
<div class="span-19 append-bottom last">
  <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
</div>
<?php endif; ?>
