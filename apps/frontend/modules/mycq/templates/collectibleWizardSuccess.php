<?php
if ($collectible->getMultimediaCount('image') > 0)
{
  slot(
    'mycq_dropbox_info_message',
    'To add another view of this item, drag an image
       into the "Alternate View" boxes below your main image.'
  );
}
else
{
  slot(
    'mycq_dropbox_info_message',
    'Drag a photo below to set it as the "Main Image" for this item.'
  );
}
?>

<div class="collecton-wizard" id="accordion2">
  <div class="accordion-group<?= $step == 1 ? ' active' : '' ?>">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #1
        <span class="description">
          Categorization
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">

        <?php
        include_partial(
          'mycq/partials/collectible_wizard_st1',
          array('form' => $step1, 'upload_form' => $upload_form)
        );
        ?>

      </div>
    </div>
  </div>

  <div class="button-wrapper<?= $step != 1 ? ' hide' : '' ?>">
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step1', 'data-next' => 'wz-step2'
    ));?>
  </div>



  <div class="accordion-group<?= $step == 2 ? ' active' : '' ?>">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #2
        <span class="description">
          Description
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">
        <?php
        include_partial(
          'mycq/partials/collectible_wizard_st2',
          array('form' => $step2)
        );
        ?>

      </div>
    </div>
  </div>



  <div class="button-wrapper<?= $step != 2 ? ' hide' : '' ?>">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left wz-back', 'data-target' => 'wz-step1', 'data-current' => 'wz-step2')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step2', 'data-next' => 'wz-step3'
    ));?>
  </div>



  <div class="accordion-group<?= $step == 3 ? ' active' : '' ?>">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #3
        <span class="description">
          Alternative Images
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">

        <?php
        include_partial(
          'mycq/partials/collectible_wizard_st3',
          array('form' => $step3, 'collectible' => $collectible)
        );
        ?>

      </div>
    </div>
  </div>



  <div class="button-wrapper<?= $step != 3 ? ' hide' : '' ?>">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left wz-back', 'data-target' => 'wz-step2', 'data-current' => 'wz-step3')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step3', 'data-next' => 'wz-step4'
    ));?>
  </div>








</div>
<script>
  $(document).ready(function()
  {
    $('.wz-next').click(function(e)
    {
      e.preventDefault();
      if ($(this).hasClass('disabled'))
      {
        return;
      }
      var $form = $('#' + $(this).data('target'))
      var $next = $('#' + $(this).data('next'))
      var $buttons = $(this).closest('.button-wrapper');
      $buttons.find('.btn').addClass('disabled');
      $form.showLoading();
      $form.ajaxSubmit({
        dataType: 'json',
        success: function(response)
        {
          $form.hideLoading();
          if (response.Success)
          {
            $buttons.addClass('hide');
            $form.closest('.collapse').collapse('hide').closest('.accordion-group').removeClass('active');
            $next.closest('.accordion-group').addClass('active').find('.collapse').collapse('show');
            $next.closest('.accordion-group').next('.button-wrapper').removeClass('hide')
                .find('.btn').removeClass('disabled');
          }
          else
          {
            $form.replaceWith($(response.form));
            $buttons.find('.btn').removeClass('disabled');
          }
        }
      });
    });

    $('.wz-back').click(function(e)
    {
      e.preventDefault();
      if ($(this).hasClass('disabled'))
      {
        return;
      }
      var $target = $('#' + $(this).data('target'))
      var $current = $('#' + $(this).data('current'))
      var $buttons = $(this).closest('.button-wrapper');
      $buttons.addClass('hide').find('.btn').addClass('disabled');
      $current.closest('.collapse').collapse('hide').closest('.accordion-group').removeClass('active');
      $target.closest('.accordion-group').addClass('active').find('.collapse').collapse('show');
      $target.closest('.accordion-group').next('.button-wrapper').removeClass('hide')
          .find('.btn').removeClass('disabled');
    });
  });
</script>

