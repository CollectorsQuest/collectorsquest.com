<?php
  /** @var $collector_addresses CollectorAddress[] */

  SmartMenu::setSelected('mycq_profile_tabs', 'addresses');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_profile_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <?php cq_sidebar_title('Edit Your Address Book'); ?>

        <div class="collector-addresses-holder">
          <h2>Shopping Address</h2>

          <?php if (count($collector_addresses)): ?>
            <div class="collector-addresses">

            <?php foreach ($collector_addresses as $key => $address): ?>
              <div class="address-row ">
                <span class=""><?= $key+1 ?>.</span>
                <div class="address-data clearfix">
                  <?php
                    include_partial('mycq/partials/collector_address', array(
                        'collector_address' => $address,
                    ));
                  ?>

                  <fieldset class="pull-right form-container-center">
                    <span>
                      <?php if ($address->getIsPrimary()): ?>
                      <strong class="text-success">This your primary address</strong>
                      <?php else: ?>
                      <?= link_to('Click here', 'mycq_profile_addresses_make_primary', $address); ?> to make this your primary address.
                      <?php endif; ?>
                    </span>
                    <div class="form-actions">
                      <?php
                        echo link_to('Edit Address',
                          array(
                            'sf_route' => 'mycq_profile_addresses_edit',
                            'sf_subject' => $address
                          ),
                          array('class' => 'btn btn-warning spacer-left')
                        );
                      ?>
                      <?php
                        echo link_to(
                          '<i class="icon-trash"></i> Delete',
                          array(
                            'sf_route' => 'mycq_profile_addresses_delete',
                            'sf_subject' => $address
                          ),
                          array('class' => 'btn spacer-left')
                        );
                      ?>
                    </div>
                  </fieldset>

                </div> <!-- .address-data -->
              </div> <!-- .address-row -->
            <?php endforeach; ?>
            </div> <!-- .collector-addresses -->

          <?php else: ?>
            <p class="spacer-20">
              You have no addresses currently entered. <br/>
            </p>
          <?php endif; ?>
          <a class="btn btn-primary" href="<?= url_for('@mycq_profile_addresses_new') ?>">Enter a new address</a>
          <br />
          <br />
        </div> <!-- .collector-address-holder -->

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
