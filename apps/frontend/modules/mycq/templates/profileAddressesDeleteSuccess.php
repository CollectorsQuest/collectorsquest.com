<?php
  /* @var $collector_address CollectorAddress */
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

        <p>
          Click the Confirm button below to permanently remove this
          address from your address book.
        </p>

        <div class="address-data">
          <?php include_partial('collector_address',
             array('collector_address' => $collector_address)); ?>
          <div class="actions">
            <?php echo link_to('Confirm Delete', array(
                'sf_route' => 'mycq_profile_addresses_delete',
                'sf_subject' => $collector_address,
              ), array(
                'class' => 'btn btn-danger',
                'method' => 'delete',
            )); ?>
          </div>
        </div> <!-- .address-data -->

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
