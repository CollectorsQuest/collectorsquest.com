<?php
/**
 * @var $total integer
 * @var $incomplete_collections boolean
 * @var $incomplete_collectibles boolean
 * @var $collector Collector
 */
?>

<?php if ($incomplete_collections && cqGateKeeper::open('mycq_incomplete', 'page')): ?>
<div class="alert alert-block alert-notice in">
  <h4 class="alert-heading">
    Some <?= $incomplete_collectibles ? 'items' : 'collections' ?> need your attention!
  </h4>
  <p class="spacer-top">
    You have <?= $incomplete_collectibles ? 'items' : 'collections' ?> which are not fully described yet.
    If you would like other users to be able to see your <?= $incomplete_collectibles ? 'items' : 'collections' ?>,<br/>
    you should describe them as best as you can!
  </p>
  <br/>
  <a class="btn btn-primary"
     href="<?= $incomplete_collectibles ? url_for('@mycq_incomplete_collectibles') : url_for('@mycq_incomplete_collections') ?>">
    Fix Incomplete <?= $incomplete_collectibles ? 'Items' : 'Collections' ?>
  </a>
  <button type="button" class="btn" data-dismiss="alert">Ok</button>
</div>
<?php endif; ?>

<?php
  $link = link_to(
    'Back to Collections &raquo;', '@mycq_collections',
    array('class' => 'text-v-middle link-align')
  );

  cq_sidebar_title(
    'My Collections (' . $total . ')', null,
    array('left' => 8, 'right' => 4, 'class'=>'mycq-red-title row-fluid')
  );

  slot(
    'mycq_dropbox_info_message',
    'To add an item to a collection, drag and drop it into a collection below.'
  );
?>

<div class="gray-well cf">
  <div class="row-fluid">
    <div class="span6">
      <ul class="nav nav-pills spacer-bottom-reset">
        <li>
          <a href="<?= url_for('@ajax_mycq?section=collectible&page=upload&model=collection'); ?>"
             class="open-dialog" onclick="return false;"
             title="Create a new collection by clicking here">
            <i class="icon-plus"></i>
            Create Collection
          </a>
        </li>
        <!--
        <li>
          <a href="<?= url_for('collections_by_collector', $collector) ?>">
            <i class="icon-globe"></i>
            Public View
          </a>
        </li>
        //-->
      </ul>
    </div>
    <div class="span6">
      <?php if ($total > 11): ?>
      <div class="mini-input-append-search">
        <div class="input-append pull-right">
          <form action="<?= url_for('@ajax_mycq?section=component&page=collections') ?>"
                id="form-mycq-collections" method="post">
            <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
            <input type="hidden" value="most-recent" id="sortByValue" name="s">
          </form>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>


<div class="mycq-collections-wrapper">
  <div class="mycq-collections-4x9">
    <div class="row-content" id="collections">
      <?php include_component('mycq', 'collections'); ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $('.dropdown-menu a.sortBy').click(function()
    {
      $('#sortByName').html($(this).data('name'));
      $('#sortByValue').val($(this).data('sort'));

      $('#form-mycq-collections').submit();
    });

    var $url = '<?= url_for('@ajax_mycq?section=component&page=collections', true) ?>';
    var $form = $('#form-mycq-collections');

    $form.submit(function()
    {
      $('#collections').parent().showLoading();

      $('#collections').load(
        $url +'?p=1', $form.serialize(),
        function(data) {
          $('#collections').parent().hideLoading();
        }
      );

      return false;
    });
  });
</script>
