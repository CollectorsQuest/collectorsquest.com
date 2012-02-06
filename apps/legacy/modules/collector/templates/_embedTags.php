<?php if (isset($collector) && $collector instanceof Collector): ?>
<ul style="list-style: none; padding-left: 0;">
  <li>
    <b><?= __('Share URL'); ?> -</b>
    <small><?= __('Email & IM'); ?></small>
  <li><input type="text" value="<?= url_for('@collector_by_id?id='.$collector->getId().'&slug='.$collector->getSlug(), true); ?>" style="width: 100%;"></li>
  <li>&nbsp;</li>
  <li>
    <b><?= __('IMG Code'); ?> -</b>
    <small><?= __('Forums & Bulletin Boards'); ?></small>
  </li>
  <li><input type="text" value='[IMG]<?= image_tag_collector($collector); ?>[/IMG]' style="width: 100%"></li>
  <li>&nbsp;</li>
  <li>
    <b><?= __('HTML Tag'); ?> -</b>
    <small><?= __('Image'); ?></small>
  </li>
  <li><input type="text" value='<?= link_to_collector($collector, 'image', array('absolute' => true, 'target' => '_blank')); ?>' style="width: 100%"></li>
  <li>&nbsp;</li>
  <li>
    <b><?= __('HTML Tag'); ?> -</b>
    <small><?= __('Text'); ?></small>
  </li>
  <li><input type="text" value='<?= link_to_collector($collector, 'text', array('absolute' => true, 'target' => '_blank')); ?>' style="width: 100%"></li>
</ul>
<?php endif; ?>
