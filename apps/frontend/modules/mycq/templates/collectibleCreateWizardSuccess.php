<?php
/**
 * @var $upload_form CollectibleUploadForm
 */

  slot(
    'mycq_dropbox_info_message',
    'Drag a photo below to set it as the "Main Image" for this item.'
  );

?>

<div class="collecton-wizard" id="accordion2">
  <div class="accordion-group active">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #1
        <span class="description">
          Name & Photos
        </span>
      </div>
    </div>
    <div class="accordion-body collapse in">
      <div class="accordion-inner">
        <?php include_partial('mycq/partials/collectible_wizard_step1', array('form' => $form)); ?>
      </div>
    </div>
  </div>

  <div class="button-wrapper">
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step1', 'data-next' => 'wz-step2'
    ));?>
  </div>

  <div class="button-wrapper">
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
      'class' => 'btn btn-primary pull-right', 'id' => "wizard-step1-submit"
    ));?>
  </div>

  <div class="accordion-group">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #2
        <span class="description">
          Categorization
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">

      </div>
    </div>
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

    </div>
  </div>

</div>
<script>
  $(document).ready(function()
  {
    'use strict';
    $('#wizard-step1-submit').click(function()
    {
      if (!$(this).hasClass('disabled'))
      {
        $('#fileupload-wz1').submit();
      }
      return false;
    });
  });
</script>