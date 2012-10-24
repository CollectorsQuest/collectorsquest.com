<?php
  /* @var $collectible_id integer */
  /* @todo should transform this into partial with js to activate included */
?>

<a data-id="<?= $collectible_id; ?>"
   class="activate btn btn-mini" onclick="return confirm('Are you sure you sure you want to activate this item?')">
  <i class="icon-ok"></i>&nbsp;Activate
</a>

<script type="text/javascript">
  $(document).ready(function()
  {
    $('a.activate').click(function(e)
    {
      e.preventDefault();
      $(this).parent().parent().showLoading();

      $(this).parent().load(
        '<?php echo url_for('@ajax_mycq?section=collectible&page=activate&id=') ?>' + $(this).data('id'),
        function() {
          $(this).parent().parent().hideLoading();
          $(this).parent().find('td.status').html('Active');
        }
      );

      return false;
    });

  });
</script>
