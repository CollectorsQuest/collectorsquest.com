<?php
/* @var $collectible Collectible */
?>
<div class="gray-well cf">
  <div class="pull-left">
    <ul class="nav nav-pills spacer-bottom-reset">
      <li>
        <?php
          if (
            ($collectible->isForSale() || $sf_params->get('available_for_sale') === 'yes') &&
             $sf_params->get('return_to', 'collection') !== 'collection'
          )
          {
            $link = link_to(
              '<i class="icon-arrow-left"></i> Back to My Market', '@mycq_marketplace'
            );
          }
          else
          {
            $link = link_to(
              '<i class="icon-arrow-left"></i> Back to Collection', 'mycq_collection_by_section',
              array('id' => $collection->getId(), 'section' => 'collectibles')
            );
          }

          echo $link;
        ?>
      </li>
      <li>
        <a href="<?= url_for_collectible($collectible) ?>">
          <i class="icon-globe"></i>
          Public View
        </a>
      </li>
      <li class="dropdown" id="menu1">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
          More Actions
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
            <?php if ($sf_user->isAdmin()): ?>
              <li>
                  <a href="<?= url_for('mycq_collectible_by_slug', array(
                    'sf_subject' => $collectible,
                    'cmd' => 'togglePublic',
                    'encrypt' => 1
                  )) ?>">
                      <i class="icon icon-refresh"></i>
                      <?php echo $collectible->getIsPublic() ? 'Make not public' : 'Make public'; ?>
                  </a>
              </li>
            <?php endif; ?>
          <li>
            <a href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $collectible, 'cmd' => 'delete', 'encrypt' => '1')); ?>"
               onclick="return confirm('Are you sure you want to delete this Item?');">
              <i class="icon-trash"></i>
              Delete Item
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
  <?php /*
    <div class="pull-right">
      <a href="javascript:void(0)">Back to Collection »</a>
    </div>
    */ ?>
</div>
