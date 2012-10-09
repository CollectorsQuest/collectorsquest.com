<div id="object_rate_box">
  <?php foreach ($forms as $form): ?>
    <div class="row rate-row">
        <div class="span2 offset1"><?= $form->getObject()->getDimensionLabel() ?></div>
        <div class="span4">
          <?php include_partial('adminbar/rateForm', array('form' => $form, 'class' => $class, 'id' => $id)); ?>
        </div>
          <?php include_partial('adminbar/rateTotal', array(
          'average_rate' => $form->getObject()->getAverageRate(),
          'total_rates' => $form->getObject()->getTotalRates()
        )); ?>
    </div>
  <?php endforeach ?>
    <div class="row" id="total_row">
        <div class="span4 offset3">
            Total rate:
        </div>
          <?php include_partial('adminbar/rateTotal', array(
            'average_rate' => $average_rate, 'total_rates' => $total_rates
          )); ?>
    </div>
    <script>
        $(document).ready(function()
        {
            $('.object_rate_form').each(function(){
                var form = $(this)
                form.ajaxForm({
                    dataType: 'json',
                    beforeSubmit: function() {
                      $('.error_list', form).hide();
                    },
                    success: function(response) {
                        if (response.form)
                        {
                            form.html($(response.form).html());
                        }
                        if (response.dimension)
                        {
                            form.closest('.rate-row').find('.rate-total').replaceWith(response.dimension);
                        }
                        if (response.total)
                        {
                            $('#total_row').find('.rate-total').replaceWith(response.total);
                        }
                    }
                });
            });
            $('.object_rate_form input').die().live('change', function(){
                $(this).closest('form').submit();
            });
        });
    </script>

</div>
