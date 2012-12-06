<?php
  /* @var $form FrontendCollectorAddressForm */

  // set input-xxlarge as the default class of widgets
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
          cq_section_title('Add a new address', $link, array('left' => 8, 'right' => 4));
        ?>

        <?= form_tag('@mycq_profile_addresses_new', array('class' => 'form-horizontal')); ?>
            <fieldset class="form-container-center">
              <?= $form->renderHiddenFields(); ?>
              <?= $form->renderAllErrors(); ?>

              <?= $form; ?>
            </fieldset>
          <div class="brown-dashes form-container-center">
            <div class="form-actions">
              <input type="submit" class="btn btn-primary" value="Save & Continue" />
            </div>
          </div>
        </form>
      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
