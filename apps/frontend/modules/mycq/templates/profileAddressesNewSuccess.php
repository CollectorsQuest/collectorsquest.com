<?php
  /* @var $form FrontendCollectorAddressForm */

  // set input-xxlarge as the default class of widgets
  foreach($form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }

  SmartMenu::setSelected('profile_tabs_navigation', 'mailing_addresses');
?>

<div id="mycq-tabs">
  <?php include_partial('mycq_tabs_navigation'); ?>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <?php
          $link = link_to(
            'Return to manage addresses &raquo;', '@mycq_profile_addresses',
            array('class' => 'text-v-middle link-align')
          );
          cq_sidebar_title('Add a new address', $link, array('left' => 8, 'right' => 4));
        ?>

        <?= form_tag('@mycq_profile_addresses_new', array('class' => 'form-horizontal')); ?>
            <fieldset class="form-container-center">
              <?= $form->renderHiddenFields(); ?>
              <?= $form->renderAllErrors(); ?>

              <?= $form; ?>
            </fieldset>
          <div class="brown-dashes form-container-center">
            <div class="form-actions">
              <input type="submit" class="btn btn-primary blue-button" value="Save & Continue" />
            </div>
          </div>
        </form>
      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
