<?php
/**
 * @var $form CollectibleWizardStep2Form
 */
?>
<form  action="<?= url_for('ajax_mycq', array('section' => 'collectible', 'page' => 'Wizard')); ?>"
       method="post" class="form-horizontal" id="wz-step2">
  <?= $form; ?>
  <input type="hidden" name="step" value="2" />
  <input type="hidden" name="collectible_id" value="<?= $form->getObject()->getId() ?>" />
</form>

<script type="text/javascript">
  $(document).ready(function()
  {
    $(".chzn-select").chosen();

    $('#collectible_description').wysihtml5({
      "font-styles": false, "image": false, "link": false,
      events:
      {
        "load": function() {
          $('#collectible_description')
              .removeClass('js-hide')
              .removeClass('js-invisible');
        },
        "focus": function() {
          $(editor.composer.iframe).autoResize();
        }
      }
    });
  });
</script>