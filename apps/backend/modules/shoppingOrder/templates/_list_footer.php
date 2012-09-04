<?php /* TO DO Need better place for this */ ?>
<script type="text/javascript">
  $(document).ready(function()
  {
   var p = $('a[rel="popover"]').popover();
    console.log(p);
    $('.popover button.close').live('click', function(){
      $(this).closest('.popover').removeClass('in').removeAttr('style');
    });
  });
</script>
