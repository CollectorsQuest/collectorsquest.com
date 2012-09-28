<?php
/**
 * @var IceBackendUser $sf_user
 * @var array $categories
 */
use_helper('cqBackend');
?>

<div id="admin-bar" class="navbar navbar-inverse">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="brand" href='<?php echo url_for_backend('homepage') ?>'>
        Admin Dashboard
      </a>
      <ul class="nav">
        <?php /* foreach ($categories as $name => $category): ?>
        <?php if (iceBackendModule::hasPermission($category, $sf_user)): ?>
          <?php if (iceBackendModule::hasItemsMenu($category['items'])): ?>
            <li class="dropdown">
              <a href="#nogo" data-toggle="dropdown" class="dropdown-toggle">
                <?php echo (!empty($category['icon'])) ? '<i class="icon-'. $category['icon'] .' icon-white"></i>' : ''; ?>
                <?php echo isset($category['name']) ? $category['name'] : $name ?><span class="caret"></span>
              </a>
              <?php include_partial('iceBackendModule/menu_list', array(
              'items'         => $category['items'],
              'items_in_menu' => true
            )) ?>
            </li>
            <?php endif; ?>
          <?php endif; ?>
        <?php endforeach; */ ?>
      </ul>
      <ul class="nav pull-right">
        <li>
          <?php
          echo link_to_backend(
            '<i class="icon-lock icon-white"></i>&nbsp;'.__('Admin Logout'), 'ice_backend_signout',array(),
            array('onclick' => 'return confirm("You will be also logged out of your webmail. Are you sure you want to continue?")')
          );
          ?>
        </li>
      </ul>
    </div>
  </div>
</div>
