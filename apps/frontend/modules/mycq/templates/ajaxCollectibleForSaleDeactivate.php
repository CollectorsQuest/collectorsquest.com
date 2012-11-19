<?php /* @var $collectible_id integer */ ?>

<a data-id="<?= $collectible_id; ?>"
   class="relist btn btn-mini" onclick="return confirm('Are you sure you sure you want to re-list this item?')">
  <i class="icon-undo"></i>&nbsp;Re-list
</a>

<script>
  $(document).ready(function()
  {
    $('a.relist').click(function(e)
    {
      e.preventDefault();
      $(this).parent().parent().showLoading();

      $(this).parent().load(
        '<?php echo url_for('@ajax_mycq?section=collectibleForSale&page=relist&id=') ?>' + $(this).data('id'),
        function() {
          $(this).parent().parent().hideLoading();
          $(this).parent().find('td.status').html('Active');
        }
      );

      return false;
    });

  });
</script>
