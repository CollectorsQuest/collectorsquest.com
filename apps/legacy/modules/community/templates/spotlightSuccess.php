
<br class="clear">
<?php
  if (!empty($featured_collectibles))
  foreach ($featured_collectibles as $i => $collectible)
  {
    // Show the collectible (in grid, list or hybrid view)
    include_partial(
      'collection/grid_view_collectible',
      array(
        'collectible' => $collectible,
        'culture' => $sf_user->getCulture(), 'i' => $i
      )
    );
  }
?>

<br class="clear">
<?php 
  cq_section_title(
    __('Community Sneak Peek') .
    '&nbsp; - &nbsp;<span style="color: #99B82C;">' .
    __("Your insider's view to what's new and cool!") .
    '</span>'
  );
?>

<div class="span-19 last" style="margin-top: 20px;">
  <?php
    foreach ($collections as $i => $collection)
    {
      include_partial(
        'collections/grid_view_collection',
        array('collection' => $collection, 'culture' => $sf_user->getCulture(), 'i' => $i)
      );
    }
  ?>
</div>

<div class="prepend-1 span-17 tag-cloud last" style="text-align: center; margin-top: 20px;">
<?php 
  foreach($city_tags as $tag => $value)
  {
    $tag_url = '@search?only=collectors&city_state='. urlencode($tag) .'&zips='. $value["zip"];
    echo link_to(str_replace(' ', '&nbsp;', $tag), $tag_url, array('class'  => 'tag_popularity_'.($value['count']+3)))." &nbsp; ";
  }
?>
</div>
