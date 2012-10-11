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
            <a class="brand" href='<?php echo url_for('homepage') ?>'>
                CollectorsQuest.com
            </a>


            <ul class="nav">
                <li>
                    <a href='<?php echo url_for_backend('homepage') ?>'>
                        <i class="icon-wrench icon-white"></i>&nbsp;Go to Backend
                    </a>
                </li>
              <?php if (count($items)) : ?>
                <?php foreach ($items as $name => $sub_item): ?>
                  <?php if (count($sub_item)): ?>
                    <li class="dropdown">
                      <a href="#nogo" data-toggle="dropdown" class="dropdown-toggle">
                        <?= $name ?> <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                       <?php foreach ($sub_item as $key => $item): ?>
                         <li>
                             <?= content_tag('a', $item['label'], $item['attributes']) ?>
                         </li>
                       <?php endforeach; ?>
                       </ul>
                    </li>
                  <?php endif ?>
                <?php endforeach; ?>
              <?php endif ?>
            </ul>
            <ul class="nav pull-right">
                <li>
                  <?php
                  echo link_to_backend(
                    '<i class="icon-lock icon-white"></i>&nbsp;' . __('Admin Logout'), 'ice_backend_signout', array(),
                    array('onclick' => 'return confirm("You will be also logged out of your webmail. Are you sure you want to continue?")')
                  );
                  ?>
                </li>
            </ul>
        </div>
    </div>
</div>