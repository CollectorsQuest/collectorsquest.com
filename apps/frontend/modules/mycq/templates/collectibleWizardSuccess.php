<?php
/**
 * @var $upload_form CollectibleUploadForm
 */


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
          Name & Photos
        </span>
      </div>
    </div>
    <div class="accordion-body collapse<?= $step == 1 ? ' in' : '' ?>">
      <div class="accordion-inner">
        <?php include_partial('mycq/partials/collectible_wizard_step1', array('form' => $step1)); ?>
      </div>
    </div>
  </div>

  <div class="button-wrapper<?= $step != 1 ? ' hide' : '' ?>">

    <?php if ($collection): ?>
      <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Back to Collection',
        '@mycq_collection_by_slug?id=' . $collection->getId() . '&slug='. $collection->getSlug(),
        array('class' => 'btn pull-left')); ?>
    <?php else: ?>
      <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Back to Collections', '@mycq_collections',
        array('class' => 'btn pull-left')); ?>
    <?php endif; ?>

    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'fileupload-wz1',
      'data-next' => 'wz-step2', 'id' => 'wizard-step1-submit'
    ));?>

  </div>


  <div class="accordion-group<?= $step == 2 ? ' active' : '' ?>">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #2
        <span class="description">
          Categorization
        </span>
      </div>
    </div>
    <div class="accordion-body collapse<?= $step == 2 ? ' in' : '' ?>">
      <div class="accordion-inner">
        <?php include_partial('mycq/partials/collectible_wizard_step2', array('form' => $step2));?>
      </div>
    </div>
  </div>


  <div class="button-wrapper<?= $step != 2 ? ' hide' : '' ?>">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left wz-back', 'data-target' => 'fileupload-wz1', 'data-current' => 'wz-step2')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step2', 'data-next' => 'wz-step4'
    ));?>
  </div>

  <div class="accordion-group">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #3
        <span class="description">
          Finish
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner" id="wz-step4">

        <div class="row-fluid">
          <div class="span12 offset1 spacer-bottom-20">
            <h2>Congratulations, your item is now discoverable on Collectors Quest!</h2>
          </div>
        </div>
        <div class="row spacer-bottom-15">
          <div class="span4 offset2"><p>See your item like others see it:</p></div>
          <div class="span3">
            <a href="<?= url_for_collectible($collectible); ?>" class="btn btn-primary">Public View</a>
          </div>
        </div>
        <div class="row spacer-bottom-15">
          <div class="span4 offset2">Edit any of the item details:</div>
          <div class="span3">
            <a href="<?= url_for('mycq_collectible_by_slug', $collectible) ?>" class="btn btn-primary">Edit Item</a>
          </div>
        </div>

        <div class="row spacer-bottom-15">
          <div class="span4 offset2">Add another item:</div>
          <div class="span3">
            <a href="<?= url_for('@mycq_collectible_create_wizard'); ?>" class="btn btn-primary">Add New Item</a>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
<script>
  $(document).ready(function()
  {
    <?php if ($step != 1): ?>
      $('#dropzone-wz1 .ui-droppable').each(function(){
        $(this).droppable('disable');
      });
    <?php endif; ?>
    $('.wz-next').click(function(e)
    {
      e.preventDefault();
      if ($(this).hasClass('disabled'))
      {
        return;
      }
      var $this = $('#' + $(this).data('target'))
      var $next = $('#' + $(this).data('next'))
      var $buttons = $(this).closest('.button-wrapper');
      $buttons.find('.btn').addClass('disabled');
      $this.closest('.accordion-inner').showLoading();


      if ($this[0].tagName.toLowerCase() == 'form')
      {
        $this.ajaxSubmit({
          dataType: 'json',
          success: function(response)
          {
            $this.closest('.accordion-inner').hideLoading();

            if (response.Success)
            {
              $buttons.addClass('hide');
              $this.closest('.collapse').collapse('hide').closest('.accordion-group').removeClass('active');
              $next.closest('.accordion-group').addClass('active').find('.collapse').collapse('show');
              $next.closest('.accordion-group').next('.button-wrapper').removeClass('hide')
                  .find('.btn').removeClass('disabled');
              $this.closest('.accordion-inner').find('.ui-droppable').each(function(){
                $(this).droppable('disable');
              });
            }
            else
            {
              $this.replaceWith($(response.form));
              $buttons.find('.btn').removeClass('disabled');
            }
          }
        });
      }
      else
      {
        $this.closest('.accordion-inner').hideLoading();
        $buttons.addClass('hide');
        $this.closest('.collapse').collapse('hide').closest('.accordion-group').removeClass('active');
        $next.closest('.accordion-group').addClass('active').find('.collapse').collapse('show');
        $next.closest('.accordion-group').next('.button-wrapper').removeClass('hide')
            .find('.btn').removeClass('disabled');
      }
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
      $target.closest('.accordion-inner').find('.ui-droppable').each(function(){
        $(this).droppable('enable');
      });
    });
  });
</script>

