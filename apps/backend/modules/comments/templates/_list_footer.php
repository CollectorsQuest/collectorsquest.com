<script type="text/javascript">
  jQuery(document).ready(function () {
    var inlineSave = $('<button type="button" id="inline-save">Save</button>');
    $('.sf_admin_list_td_body')
        .dotdotdot({height:36})
        .unbind('click')
        .bind('click', function (e) {
          var control = $(this).find('.dotdotdot[contentEditable!="true"]');
          if (control) {
            $(control)
                .html($(this).triggerHandler('originalContent'))
                .attr('contentEditable', true)
                .after(inlineSave)
            ;
            $(inlineSave).click(function (e) {
              var content = $(this).prev('.dotdotdot').html();
              var id = $(this).parent().parent().find('input[type="checkbox"][name="ids[]"]').val();

              $.ajax({
                url:'<?php echo url_for('@comment_ajax_update_content') ?>',
                method:'POST',
                dataType: 'json',
                data:{
                  id:id,
                  content:content
                },
                success:function (data) {
                  if ('success' == data.status) {
                    $(inlineSave).prev('.dotdotdot')
                        .trigger('update')
                        .attr('contentEditable', false)
                    ;
                    $(inlineSave).remove();
                  }
                  else {
                    ;
                  }
                }
              });

              e.stopPropagation();
            });
          }
          e.stopPropagation();
        });

    $(document)
        .unbind('click')
        .bind('click', function (e) {
          $('.sf_admin_list_td_body .dotdotdot[contentEditable="true"]')
              .attr('contentEditable', false)
              .each(function (item) {

              })
              .trigger('update');
          $('button#inline-save').remove();
          e.stopPropagation();
        });

  });
</script>
