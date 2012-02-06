<br clear="all"/>

<?php if ($blog): ?>
<div id="search-blog">
  <?php
    foreach ($blog as $i => $post)
    {
      // Show the collection (in grid, list or hybrid view)
      include_partial(
        '_blog/list_view_post',
        array('post' => $post, 'culture' => $sf_user->getCulture(), 'i' => $i)
      );
    }
  ?>
<br clear="all"/>
<?php endif; ?>

<?php
  include_partial(
    'global/pager',
    array('pager' => $pager, 'options' => array('url' => '@search_blog?q='. $q))
  );
?>
