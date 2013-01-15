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
        <?php include_partial('mycq/partials/collectible_wizard_step1', array('form' => $form)); ?>
      </div>
    </div>
  </div>

  <div class="button-wrapper<?= $step != 1 ? ' hide' : '' ?>">
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step1', 'data-next' => 'wz-step2'
    ));?>
  </div>

  <div class="button-wrapper">
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right', 'onclick' => "$('#fileupload-wz1').submit(); return false;"
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
