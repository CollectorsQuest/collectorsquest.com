<?php
  /* @var $form FrontendCollectorAddressForm */
?>
<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li>
      <a href="<?= url_for('@mycq_profile'); ?>">Personal Information</a>
    </li>
    <li>
      <a href="<?= url_for('@mycq_profile_account_info'); ?>">Account Information</a>
    </li>
    <li class="active">
      <a href="<?= url_for('@mycq_profile_addresses'); ?>">Mailing Addresses</a>
    </li>
    <li>
      <a href="#tab4" data-toggle="tab">Settings</a>
    </li>
  </ul>
  <?php
    $link = link_to(
      'Return to manage addresses &raquo;', '@mycq_profile_addresses',
      array('class' => 'text-v-middle link-align')
    );
    cq_sidebar_title('Add a new address', $link, array('left' => 8, 'right' => 4));
  ?>

  <?= form_tag('@mycq_profile_addresses_new', array('class' => 'form-horizontal')); ?>
    <?= $form->renderHiddenFields(); ?>
    <?= $form->renderAllErrors(); ?>

    <?= $form; ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary blue-button" value="Save & Continue" />
    </div>
  </form>

</div>
