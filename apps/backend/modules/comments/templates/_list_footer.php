<script type="text/javascript">
  jQuery(document).ready(function () {
    var inlineSave = $('<button id="inline-save">Save</button>');
    $('.sf_admin_list_td_body')
        .dotdotdot({height: 36})
        .unbind('click')
        .bind('click', function(e) {
            var control = $(this).find('.dotdotdot[contentEditable!="true"]');
            if (control) {
                console.log('enter edit mode');
                console.log(control.contentEditable);
                $(control)
                    .html($(this).triggerHandler('originalContent'))
                    .attr('contentEditable', true)
                    .after(inlineSave)
                ;
            }
            e.stopPropagation();
    });

    $(document)
        .unbind('click')
        .bind('click', function(e) {
        $('.sf_admin_list_td_body .dotdotdot[contentEditable="true"]')
            .attr('contentEditable', false)
            .each(function(item) {
                console.log('exit edit mode');
            })
            .trigger('update');
        $('button#inline-save').remove();
        e.stopPropagation();
    });

  });
</script>
