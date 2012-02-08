<div class="span-25 rounded-top last" style="width: 980px; background: #F7FAE4; height: 50px; margin-top: -5px; margin-bottom: -40px; padding: 0 5px;">&nbsp;</div>
<div class="span-7">
  <div style="margin-left: 10px; padding: 7px 7px 10px; background: transparent url(/images/legacy/profile-photo-bgr.png) no-repeat scroll 0% 0%; width: 250px; height: 320px;">
    <?php echo image_tag_collector($collector, '235x315'); ?>
  </div>
  <ul class="buttons" style="width: 246px; margin-left: 11px; margin-top: 5px;">
    <?php
      if ($sf_user->getId() == $collector->getId())
      {
        $buttons = array(
          0 => array(
            'text' => __('Edit Your Profile'),
            'icon' => 'person',
            'route' => '@manage_profile'
          ),
          1 => array(
            'text' => __('Create a Collection'),
            'icon' => 'plus',
            'route' => '@collection_create'
          ),
          2 => array(
            'text' => sprintf(__('Your Collections (%d)'), $count_collections),
            'icon' => 'image',
            'route' => '@manage_collections'
          )
        );
      }
      else
      {
        $buttons = array(
          0 => array(
            'text' => __('Send a Message'),
            'icon' => 'mail-closed',
            'route' => '@message_compose?to='. $collector->getId()
          ),
          1 => array(
            'text' => sprintf(__('View all Collections (%d)'), $count_collections),
            'icon' => 'image',
            'route' => '@collections_by_collector?collector='. $collector->getSlug()
          )
        );
      }

      include_partial('global/li_buttons', array('buttons' => $buttons));
    ?>
  </ul>
  <br>
  <?php if (is_array($related_collections)): ?>
    <div style="width: 245px; margin-left: 12px; border: 1px solid #ccc;">
      <div style="background-color: #BBDC71; color: #fff; padding: 5px 8px; font-size: 17px;">
        <?= __('You May Also Like...'); ?>
      </div>
      <?php foreach ($related_collections as $i => $related_collection): ?>
        <div id="related_collection_<?= $related_collection->getId(); ?>"
             class="span-6 collection last"
             style="width: 245px; <?php echo ($i%2==0) ? 'background: #F5F8DD;' : null; ?>">
          <div class="stack">
            <?= link_to_collection($related_collection, 'image', array('width' => 50, 'height' => 50)); ?>
          </div>
          <div class="caption">
            <?= link_to_collection($related_collection, 'text', array('truncate' => 50)); ?>
            by
            <?= link_to_collector($related_collection, 'text', array('truncate' => 17)); ?>
          </div>
        </div>
      <?php endforeach; ?>
      <br clear="all">
    </div>
  <?php endif; ?>
</div>
<div class="span-18 last">
  <div class="span-18 append-bottom last">
    <?php
      if ($sf_user->getId() == $collector->getId())
      {
        cq_button(
          __('Edit Your Profile'), '@manage_profile',
          array('class' => 'edit yellow', 'style' => 'float: right; margin-right: 10px;')
        );
      }
    ?>
    <span style="font-size: 24px;"><?= $collector->getDisplayName(); ?></span>
    <span style="font-size: 18px;">
      <?php
        if ($plural == true)
        {
          echo sprintf(__('are %s collectors'),'<i>'. $collector->getCollectorType() .'</i>');
        }
        else
        {
          echo sprintf(
            __('is %s %s collector'),
            in_array(strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')) ? 'an' : 'a',
            '<i>'. $collector->getCollectorType() .'</i>'
          );
        }
      ?>
    </span>
    <br><br>
    <div style="float: right; margin-right: 20px;">
      <fb:like href="<?= url_for_collector($collector, true); ?>" send="true" width="300" show_faces="true"></fb:like>
    </div>
    <?php
      echo __('Gender:'), '&nbsp;&nbsp;', link_to(
        ($collector_profile->getGender() == 'f') ? __('Female') : __('Male'),
        "community/search?gender=".(($collector_profile->getGender() == 'f') ? "Female" : "Male")
      );

      if ($collector_profile->getAddress())
      {
        echo '<br>',  __('Location:'), '&nbsp;&nbsp;', $collector_profile->getAddress();
      }

      if ($collector_profile->getWebsite())
      {
        echo '<br>', __('Website:'), '&nbsp;&nbsp;', link_to(
          $collector_profile->getWebsiteUrl(),
          $collector_profile->getWebsiteUrl(), array('target' => '_blank', 'rel' => 'nofollow')
        );
      }
    ?>
  </div>

  <div class="span-17 append-bottom last">
    <?php if ($collector_profile->getCollecting()): ?>
      <div style='padding: 3px 0 0 0; margin:0;'>
        <b><?php echo __('I collect:'); ?></b>&nbsp;
        <span style='color: #66A3B5'><?= strip_tags($collector_profile->getCollecting()); ?></span>
      </div>
    <?php endif; ?>
    <?php if ($collector_profile->getMostSpent()): ?>
      <div style='padding: 3px 0 0 0; margin:0;'>
        <b><?php echo __("The most I've spent on an item:"); ?></b>&nbsp;
        <span style='color: #66A3B5'>$<?= money_format($collector_profile->getMostSpent(), 2); ?></span>
      </div>
    <?php endif; ?>
    <?php if ($collector_profile->getAnuallySpent()): ?>
      <div style='padding: 3px 0 0 0; margin:0;'>
        <b><?php echo __("Annually I spend about:"); ?></b>&nbsp;
        <span style='color: #66A3B5'>$<?= money_format($collector_profile->getAnuallySpent(), 2); ?></span>
      </div>
    <?php endif; ?>
    <?php if ($collector_profile->getNewItemEvery()): ?>
      <div style='padding: 3px 0 0 0; margin:0;'>
        <b><?php echo __("I purchase a new item:"); ?></b>&nbsp;
        <span style='color: #66A3B5'><?= ucwords($collector_profile->getNewItemEvery()); ?></span>
      </div>
    <?php endif; ?>
  </div>

  <div class="span-17 append-bottom last">
    <?php if ($collector_profile->getAbout()): ?>
    <fieldset>
      <legend><?= __('about %username%', array('%username%'=>$collector)); ?></legend>
      <?php echo nl2br(IceStatic::cleanText($collector_profile->getAbout(), false, 'a, b, u, i, strong')); ?>
    </fieldset>
    <?php endif; ?>

    <?php if ($collector_profile->getCollections()): ?>
    <fieldset>
      <legend><?= __("about %username%'s collections", array('%username%'=>$collector)); ?></legend>
      <?php echo nl2br(IceStatic::cleanText($collector_profile->getCollections(), false, 'a, b, u, i, strong')); ?>
    </fieldset>
    <?php endif; ?>

    <?php if ($collector_profile->getInterests()): ?>
    <fieldset>
      <legend><?= __("%username%'s interests", array('%username%'=>$collector)); ?></legend>
      <?php echo nl2br(IceStatic::cleanText($collector_profile->getInterests(), false, 'a, b, u, i, strong')); ?>
    </fieldset>
    <?php endif; ?>
  </div>

  <div class="span-17 append-bottom last">
  <?php if (count($collections) > 0): ?>
    <fieldset style="background: #fff;">
      <legend><?= __("%username%'s latest collections", array('%username%'=>$collector)); ?></legend>
      <?php
        foreach ($collections as $i => $collection)
        {
          include_partial(
            'collections/grid_view_collection',
            array('collection' => $collection, 'style' => 'padding-left: 10px;', 'class' => ($i == 2) ? 'last' : null)
          );
        }
      ?>
      <?php if ($count_collections > 3): ?>
        <div class="clear" style="float: right;">
          <a href="<?php echo url_for('collections_by_collector', $collector) ?>"><?php echo __("see all %username%'s collections", array('%username%'=>$collector)) ?> (<?php echo $count_collections ?>)</a>
        </div>
      <?php endif; ?>
    </fieldset>
  <?php endif; ?>
  </div>

  <div class="span-17 append-bottom last">
    <fieldset>
      <legend><?= __("enjoyed %username% profile?", array('%username%'=>$collector)); ?></legend>
      <p><?= __('Before you leave, make sure you can find your way back.'); ?></p>
      <p>
        <b><?= __('Direct link:'); ?></b><br>
        <a href="<?= url_for('@collector_by_slug?id='.$collector->getId().'&slug='.$collector->getSlug(), true); ?>" title="<?= $collector->getDisplayName(); ?>">
          <?= url_for('@collector_by_slug?id='.$collector->getId().'&slug='.$collector->getSlug(), true); ?>
        </a>
      </p>
    </fieldset>
  </div>
</div>
