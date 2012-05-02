<script type="text/javascript">
  jQuery(document).ready(function () {
    var inlineSave = $('<button type="button" id="inline-save">Save</button>');
    $('.sf_admin_list_td_body')
        .dotdotdot({height:36})
        .unbind('click')
        .bind('click', function (e) {
          var control = $(this).find('.dotdotdot[contentEditable!="true"]');
          if (control) {
            console.log('enter edit mode');
            console.log(control.contentEditable);
            $(control)
                .html($(this).triggerHandler('originalContent'))
                .attr('contentEditable', true)
                .after(inlineSave)
            ;
            $(inlineSave).click(function (e) {
              var content = $(this).prev('.dotdotdot').html();
              var id = $(this).parent().parent().find('input[type="checkbox"][name="ids[]"]').val();

              if (undefined == id) {
                console.log('failed to find id');
              }

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
                    console.log('fail');
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
                console.log('exit edit mode');
              })
              .trigger('update');
          $('button#inline-save').remove();
          e.stopPropagation();
        });

  });
</script>
