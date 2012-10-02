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
      <?php if (count($items)) : ?>
        <?php foreach ($items as $name => $sub_item): ?>
          <?php if (count($sub_item)): ?>
            <ul class="nav">
                <li class="dropdown">
                    <a href="#nogo" data-toggle="dropdown" class="dropdown-toggle">
                        <?= $name ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <?php foreach ($sub_item as $key => $item): ?>
                        <li>
                            <a target="_blank" href="<?= $key ?>">
                                <span><?= $item ?></span>
                            </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
          <?php endif ?>
        <?php endforeach; ?>
      <?php endif ?>
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
