<?php
  /* @var $collectible_id integer */
?>

<a data-id="<?= $collectible_id; ?>"
   class="deactivate btn btn-mini" onclick="return confirm('Are you sure you sure you want to deactivate this item?')">
  <i class="icon-minus-sign"></i>&nbsp;Deactivate
</a>

<script type="text/javascript">
  $(document).ready(function()
  {
    $('a.deactivate').click(function(e)
    {
      e.preventDefault();
      $(this).parent().parent().showLoading();

      $(this).parent().load(
        '<?php echo url_for('@ajax_mycq?section=collectible&page=deactivate&id=') ?>' + $(this).data('id'),
        function() {
          $(this).parent().parent().hideLoading();
          $(this).parent().find('td.status').html('Inactive');
        }
      );

      return false;
    });

  });
</script>
