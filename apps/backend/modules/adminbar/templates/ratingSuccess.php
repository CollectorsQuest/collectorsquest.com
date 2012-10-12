<div id="object_rating_box" class="spacer-top">
  <?php foreach ($forms as $form): ?>
    <div class="row rating-row">
      <div class="span2 offset1"><?= $form->getObject()->getDimensionLabel() ?></div>
      <div class="span4">
        <?php include_partial('adminbar/ratingForm', array('form' => $form, 'class' => $class, 'id' => $id)); ?>
      </div>
      <?php
        include_partial('adminbar/ratingTotal', array(
          'average_rating' => $form->getObject()->getAverageRating(),
          'total_ratings' => $form->getObject()->getTotalRatings()
        ));
      ?>
    </div>
  <?php endforeach ?>
  <div class="row" id="total_row">
    <div class="span4 offset3" style="text-align: right;">
      &nbsp;
    </div>
    <?php include_partial('adminbar/ratingTotal', array(
      'average_rating' => $average_rating, 'total_ratings' => $total_ratings
    )); ?>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $('.object_rating_form').each(function()
    {
      var form = $(this);
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
            form.closest('.rating-row').find('.rating-total').replaceWith(response.dimension);
          }
          if (response.total)
          {
            $('#total_row').find('.rating-total').replaceWith(response.total);
          }
        }
      });
    });

    $('.object_rating_form input').die().live('change', function(){
      $(this).closest('form').submit();
    });
  });
</script>
