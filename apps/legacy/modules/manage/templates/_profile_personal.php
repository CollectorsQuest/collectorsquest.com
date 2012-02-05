<style type="text/css" media="screen">
  .radio_list {
    list-style: none;
    padding-left: 0;
  }
</style>
<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'collector_type', __('Collector type:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= $form['collector_type']->render(); ?>
  <a href="#collector_type_help" id="collector_type_help_link" title="Occasional: I buy only once in a blue moon.
     Casual: If I see something, I might buy it.
     Serious: I am actively seeking new items all of the time.
     Obsessive: I need to have everything I can get my hands on.
     Expert: I work in the trade as a dealer/appraiser or have acquired a vast amount of knowledge in the area I collect.">(What am I?)</a>
</div>
<div style="display: none;">
  <div id="collector_type_help">
    Occasional: I buy only once in a blue moon.<br />
    Casual: If I see something, I might buy it.<br />
    Serious: I am actively seeking new items all of the time.<br />
    Obsessive: I need to have everything I can get my hands on.<br />
    Expert: I work in the trade as a dealer/appraiser or have acquired a vast amount of knowledge in the area I collect.<br />
  </div>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'birthday', __('Birthday:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= $form['birthday']; ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'gender', __('Gender:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_select_tag($form, 'gender', array('width' => 100)); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'zip_postal', __('Zip/Postal Code:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'zip_postal', array('width' => 100)); ?>
  <?= $form['zip_postal']->renderError(); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'country', __('Country:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_select_tag($form, 'country'); ?>
</div>
<br clear="all"/><br />

<div class="span-5" style="text-align: right;">
  <?= cq_label_for($form, 'website', __('Website:')); ?>
  <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-12 last">
  <?= cq_input_tag($form, 'website', array('width' => 400)); ?>
  <?= $form['website']->renderError(); ?>
</div>

<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery('a#collector_type_help_link').fancybox({
      titleShow: false,
    });
  })
</script>