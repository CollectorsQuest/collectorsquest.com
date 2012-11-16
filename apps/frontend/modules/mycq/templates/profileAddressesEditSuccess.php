<?php
  /* @var $form FrontendCollectorAddressForm */
  foreach($form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }

  SmartMenu::setSelected('mycq_profile_tabs', 'addresses');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_profile_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <?php
          $link = link_to(
            'Return to manage address book &raquo;', '@mycq_profile_addresses',
            array('class' => 'text-v-middle link-align')
          );
          cq_section_title('Edit your address', $link, array('left' => 8, 'right' => 4));
        ?>

        <?= form_tag('@mycq_profile_addresses_edit?id='.$form->getObject()->getId(), array('class' => 'form-horizontal')); ?>
          <?= $form->renderHiddenFields(); ?>
          <?= $form->renderAllErrors(); ?>

          <?= $form; ?>
          <div class="form-actions">
            <input type="submit" class="btn btn-primary" value="Save & Continue" />
          </div>
        </form>

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
