<?php
/*
 * @var $package_transactions PackageTransaction[]
 * @var $package_transaction  PackageTransaction
 * @var $has_no_credits       boolean
 */

  SmartMenu::setSelected('mycq_marketplace_tabs', 'packages');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

      <?php if($has_no_credits): ?>
        <div class="alert alert-block alert-notice in">
          <h4 class="alert-heading">Oh snap! You are out of credits for listing items for sale!</h4>
          <p class="spacer-top">
            Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
          </p>
          <br/>
          <a class="btn btn-primary" href="<?php echo url_for('@seller_packages') ?>">Buy Credits</a>
          <button type="button" class="btn" data-dismiss="alert">Ok</button>
        </div>
      <?php endif; ?>

        <!-- Credit purchase history -->
        <div class="row-fluid sidebar-title spacer-top">
          <div class="span8">
            <h3 class="Chivo webfont">Credit History</h3>
          </div>
          <!--
          <div class="span4 text-right">
            <span class="show-all-text">
              Show: &nbsp;
            </span>
            <div class="control-group pull-right">
              <div class="btn-filter-all btn-group">
                <a id="filter-paid" class="btn btn-mini btn-filter active" href="#">Paid</a>
                <a id="filter-processing" class="btn btn-mini btn-filter" href="#">Processing</a>
                <a id="filter-expiring" class="btn btn-mini btn-filter " href="#">Expiring</a>
                <a id="filter-expired" class="btn btn-mini btn-filter " href="#">Expired</a>
              </div>
            </div>
          </div>
          //-->
        </div><!-- /.sidebar-title -->

        <table class="table table-credit-history">
          <thead>
          <tr>
            <th>Package</th>
            <th>Credits Purchased</th>
            <th>Purchased On</th>
            <th>Expires On</th>
            <?php if ('dev' == sfConfig::get('sf_environment')): ?>
            <th>Status</th>
            <?php endif; ?>
          </tr>
          </thead>
          <tbody>
          <?php if (count($package_transactions)): foreach ($package_transactions as $package_transaction): ?>
          <?php
            switch ($package_transaction->getPaymentStatus()) {
              case PackageTransactionPeer::PAYMENT_STATUS_PAID:
                $class = '';
                if ($package_transaction->getCredits() - $package_transaction->getCreditsUsed() <= 5)
                  $class = 'alert';
                if ($package_transaction->getExpiryDate('YmdHis') < date('YmdHis'))
                  $class = 'expired';
                break;
              case PackageTransactionPeer::PAYMENT_STATUS_PROCESSING:
                $class = 'processing';
                break;
              default:
                // what are the other cases here?
                break;
            }
          ?>
          <tr class=" <?= $class ?>">
            <td><?= $package_transaction->getPackage()->getPackageName(); ?></td>
            <td><?= $package_transaction->getCredits(); ?></td>
            <td><?= $package_transaction->getCreatedAt('F j, Y'); ?></td>
            <td><?= $package_transaction->getExpiryDate('F j, Y'); ?></td>
            <?php if ('dev' == sfConfig::get('sf_environment')): ?>
            <td>
              <?php
                switch ($class) {
                  case '':
                    echo 'paid';
                    break;
                  case 'processing':
                    echo '<span class="red">processing<br>payment</span>';
                    break;
                  case 'alert':
                    echo 'expiring<br>soon';
                    break;
                  case 'expired':
                    echo 'expired';
                    break;
                }
              ?>
            </td>
            <?php endif; ?>
          </tr>
            <?php endforeach; else: ?>
          <tr>
            <td colspan="<?= 'dev' == sfConfig::get('sf_environment') ? 6 : 5 ?>">
              You have not purchased any packages yet.
            </td>
          </tr>
          <?php endif; ?>
          </tbody>
        </table>
        <br class="cf"/>

        <!-- Items listing history -->
        <div class="row-fluid sidebar-title spacer-top-20" style="margin-bottom: 0;">
          <div class="span8">
            <h3 class="Chivo webfont">Items for Sale History</h3>
          </div>
        </div><!-- /.sidebar-title -->
        <div class="row-fluid messages-row gray-well cf">
          <div class="span8">
            <div class="filter-container">
              <span class="show-all-text pull-left">
                Show: &nbsp;
              </span>
              <div class="control-group">
                <div class="btn-filter-all btn-group">
                  <a id="filter-items-all" class="btn btn-mini btn-filter active" href="#">All</a>
                  <a id="filter-items-active" class="btn btn-mini btn-filter" href="#">Active</a>
                  <a id="filter-items-inactive" class="btn btn-mini btn-filter" href="#">Inactive</a>
                  <a id="filter-items-sold" class="btn btn-mini btn-filter" href="#">Sold</a>
                  <a id="filter-items-expired" class="btn btn-mini btn-filter" href="#">Expired</a>
                </div>
              </div> <!-- /.control-group -->
            </div>
          </div>

          <div class="span4">
            <div class="mini-input-append-search">
              <div class="input-append pull-right">
                <input type="text" class="input-sort-by" id="search-input" name="search" value=""><button class="btn gray-button" id="search-button" type="submit"><strong>Search</strong></button>
                <input type="hidden" name="filter" id="filter-hidden" value="all">
              </div>
            </div>
          </div>

        </div>

        <table class="table table-striped table-items-for-sale-history">
          <thead>
          <tr>
            <th class="items-column">&nbsp;</th>
            <th>Expires</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>

          <?php if ($total): foreach ($collectibles_for_sale as $collectible_for_sale): ?>
          <tr>
            <td>
              <div class="row-fluid items">
                <div class="span2">
                  <a href="" class="thumb">
                    <?php
                      echo link_to_collectible($collectible_for_sale->getCollectible(), 'image', array(
                        'image_tag' => array('width' => 75, 'height' => 75),
                      ));
                    ?>
                  </a>
                </div>
                <div class="span10">
                  <span class="title">
                     <?= link_to_collectible($collectible_for_sale->getCollectible()); ?>
                  </span>
                  <span class="description">
                    <?= $collectible_for_sale->getCollectible()->getDescription('stripped') ?>
                  </span>
                  <span class="price">
                    <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
                  </span>
                  </div>
                </div>
            </td>
            <td>
              <?= $collectible_for_sale->getExpiryDate($format = 'F j, Y'); ?>
            </td>
            <td>
              <?php // should think of another way to approach this ?>
              <?php if ($collectible_for_sale->getIsSold()): ?>
                Sold
              <?php elseif ($collectible_for_sale->getExpiryDate() > date('Y-m-d H:i:s')): ?>
                Active
              <?php elseif($collectible_for_sale->getExpiryDate() == null): ?>
                Inactive
              <?php else: ?>
                Expired
              <?php endif; ?>
            </td>
            <td>
              <?php // should optimize and not use same function calls as for the previous <td> ?>
              <?php if ($collectible_for_sale->getIsSold()): ?>
                -
              <?php elseif ($collectible_for_sale->getExpiryDate() > date('Y-m-d H:i:s')): ?>
                <button class="btn btn-mini" type="button">
                  <i class="icon-minus-sign"></i>&nbsp;Deactivate
                </button>
              <?php elseif($collectible_for_sale->getExpiryDate() == null): ?>
                <button class="btn btn-mini" type="button">
                  <i class="icon-ok"></i>&nbsp;Activate
                </button>
              <?php else: ?>
                <button class="btn btn-mini" type="button">
                  <i class="icon-undo"></i>&nbsp;Re-list
                </button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>

      <div class="row-fluid pagination-wrapper">

        <div class="pagination spacer-top-reset">
          <ul>
            <li class="disabled"><a href="javascript:void(0);"> ← </a></li>
            <li class="active"><a href="javascript:void(0);">1</a></li>
            <li>
              <a data-page="2" title="Go to page 2" href="#">2</a>      </li>
            <li>
              <a data-page="3" title="Go to page 3" href="#">3</a>      </li>
            <li>
              <a data-page="4" title="Go to page 4" href="#">4</a>      </li>
            <li class="next">
              <a data-page="2" title="Go to page 2" href="#"> → </a>    </li>

          </ul>
        </div>

      </div>

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div> <!-- .tab-content -->
</div> <!-- #mycq-tabs -->

