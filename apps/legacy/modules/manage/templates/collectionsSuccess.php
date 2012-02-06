<?php
/**
 * @var  cqUser  $sf_user
 *
 * @var  sfPropelPager      $pager
 * @var  Collection[]       $collections
 * @var  CollectionDropbox  $dropbox
 */

if ($pager->getPage() == 1)
{
  include_partial(
    'collections/list_view_collection',
    array('collection' => $dropbox, 'editable' => true, 'i' => 0)
  );

  cq_button_set(
    array(
      array(
        'value' => __('Empty Dropbox'),
        'route' => '@manage_dropbox?cmd=empty&encrypt=1',
        'options' => array(
          'confirm' => __('Are you sure you want to empty your whole Dropbox?'),
          'class' => 'cancel yellow', 'style' => 'float: right; margin-right: 5px;'
        )
      ),
      array(
        'value' => __('Add Collectibles to Your Dropbox'), 'route' => 'fancybox_collection_add_collectibles(0)',
        'options' => array('class' => 'create yellow')
      ),
      array(
        'value' => $sf_user->hasCredential('seller') ? __('Edit/Sell Collectibles') : __('Edit Collectibles'),
        'route' => '@manage_collectibles',
        'options' => array('class' => 'edit yellow', 'style' => 'float: right; margin-right: 5px;')
      )
    ),
    array('class' => 'span-17 prepend-1 rounded')
  );
}

foreach ($collections as $i => $collection)
{
  // Show the collection in list view
  include_partial(
    'collections/list_view_collection',
    array('collection' => $collection, 'editable' => true, 'i' => $i + 1)
  );

  cq_button_set(
    array(
      array(
        'value' => __('Delete Collection'),
        'route' => '@manage_collection_by_slug?id='. $collection->getId() .'&slug='. $collection->getSlug() .'&cmd=delete&encrypt=1',
        'options' => array(
          'confirm' => __('Are you sure you want to delete this collection?'),
          'class' => 'cancel yellow', 'style' => 'float: right; margin-right: 5px;'
        )
      ),
      array(
        'value' => __('Edit Collection'), 'route' => '@manage_collection_by_slug?id='. $collection->getId() .'&slug='. $collection->getSlug(),
        'options' => array('class' => 'edit yellow', 'style' => 'float: right;')
      ),
      array(
        'value' => __('Add More Collectibles'), 'route' => 'fancybox_collection_add_collectibles('.$collection->getId().')',
        'options' => array('class' => 'create yellow')
      ),
      array(
        'value' => $sf_user->hasCredential('seller') ? __('Edit/Sell Collectibles') : __('Edit Collectibles'),
        'route' => '@manage_collectibles_by_slug?id='. $collection->getId() .'&slug='. $collection->getSlug(),
        'options' => array('class' => 'edit yellow')
      )
    ),
    array('class' => 'span-17 prepend-1 rounded')
  );
}
?>

<br class="clear" /><br />
<div class="span-19 last" style="margin-bottom: 25px">
  <?php
    include_partial(
      'global/pager',
      array('pager' => $pager, 'options' => array('url' => url_for('@manage_collections')))
    );
  ?>
</div>
