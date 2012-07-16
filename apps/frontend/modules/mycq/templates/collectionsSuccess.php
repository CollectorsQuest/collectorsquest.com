<?php
/**
 * @var $total integer
 */
?>

<div class="mycq-collections-wrapper">
  <div class="row-fluid sidebar-title spacer-top-reset spacer-inner">
    <div class="span5 link-align">
      <h3 class="Chivo webfont">My Collections (<?= $total ?>)</h3>
    </div>
    <div class="span7">
      <?php if ($total > 0): ?>
      <div class="sort-search-box">
        <div class="input-append pull-right">
          <form action="<?= url_for('@ajax_mycq?section=component&page=collections') ?>"
                id="form-mycq-collections" method="post">
            <div class="btn-group">
              <div class="append-left-gray">Sort by <strong id="sortByName">Most Recent</strong></div>
              <a class="btn gray-button dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
              </ul>
            </div>
            <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
            <input type="hidden" value="most-recent" id="sortByValue" name="s">
          </form>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row mycq-collections-4x9">
    <div class="row-content" id="collectibles">
      <?php include_component('mycq', 'collections'); ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $('.add-new-zone').on('mouseenter', function() {
      var $this = $(this);
      $this.find('i.icon-plus')
        .removeClass('icon-plus')
        .addClass('icon-hand-up')
        .show();
    });
    $('.add-new-zone').on('mouseleave', function() {
      var $this = $(this);
      $this.find('i.icon-hand-up')
        .removeClass('icon-hand-up')
        .addClass('icon-plus')
        .show();
    });
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
      $('#collectibles').parent().showLoading();

      $('#collectibles').load(
        $url +'?p=1', $form.serialize(),
        function(data) {
          $('#collectibles').parent().hideLoading();
        }
      );

      return false;
    });
  });
</script>
