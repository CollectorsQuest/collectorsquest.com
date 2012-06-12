<?php
  /** @var $collector_addresses CollectorAddress[] */
?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li>
      <a href="<?= url_for('@mycq_profile'); ?>">Personal Information</a>
    </li>
    <li>
      <a href="<?= url_for('@mycq_profile_account_info') ?>">Account Information</a>
    </li>
    <li class="active">
      <a href="<?= url_for('@mycq_profile_addresses'); ?>">Mailing Addresses</a>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <?php
        $link = link_to(
          'View public profile &raquo;', 'collector/me/index',
          array('class' => 'text-v-middle link-align')
        );
        cq_sidebar_title('Edit Your Mailing Addresses', $link, array('left' => 8, 'right' => 4));
        ?>

        <div class="collector-addresses-holder">
          <h2>Your addresses</h2>

          <?php if (count($collector_addresses)): ?>
            <div class="collector-addresses">

            <?php foreach ($collector_addresses as $key => $address): ?>
              <div class="address-row">
                <span><?= $key+1 ?>.</span>
                <div class="address-data">
                  <?php include_partial('collector_address',
                     array('collector_address' => $address)); ?>

                  <fieldset class="form-container-center">
                    <div class="form-actions">
                      <?= link_to('Edit Addresses', array('sf_route' => 'mycq_profile_addresses_edit', 'sf_subject' => $address), array('class' => 'btn blue-button spacer-left')) ?>
                      <?= link_to('Delete', array('sf_route' => 'mycq_profile_addresses_delete', 'sf_subject' => $address), array('class' => 'btn red-button spacer-left')) ?>
                    </div>
                  </fieldset>

                </div> <!-- .address-data -->
              </div> <!-- .address-row -->
            <?php endforeach; ?>
            </div> <!-- .collector-addresses -->

          <?php else: ?>
            <p>
              You have no addresses currently entered. <br/>
            </p>
          <?php endif; ?>
          Click here to add a new address:
          <a href="<?= url_for('@mycq_profile_addresses_new') ?>">Enter a new address</a>
        </div> <!-- .collector-address-holder -->

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
