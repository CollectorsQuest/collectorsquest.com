<?php
  /* @var $pager cqPropelModelPager */

  echo ice_image_tag_placeholder('980x250');
?>
<br>
<br>

<p class="truncate js-hide">
  <?php // test intro text ?>
  Aliquam auctor aenean pid, velit ut, quis tempor elementum augue aenean scelerisque?
  Porttitor amet! Enim nascetur. Mauris sed! Augue natoque ac et. Facilisis ac, porta!
  In dictumst? Et, vel nunc, dis sociis adipiscing dolor adipiscing dictumst.
  Sed est velit. Ridiculus rhoncus, vel pid cursus sit augue est pellentesque dapibus integer.
  <br>
  <br>
  Penatibus nascetur risus, scelerisque. Porttitor turpis, massa elementum sagittis penatibus!
  Massa lacus. Platea pellentesque! Tortor scelerisque facilisis magna, in.
  A, a placerat dictumst lectus mauris scelerisque pulvinar porttitor, magna
  odio in ultricies adipiscing porttitor lorem, a a adipiscing, penatibus nec, ridiculus est,
  porta ridiculus scelerisque nisi. Aliquam ut sagittis scelerisque enim rhoncus ac facilisis,
  hac! Ut velit augue adipiscing odio habitasse velit turpis vel lacus! Odio cum elit porta ac.
</p>

<?php cq_page_title('Black History Collectibles'); ?>

<div id="collectibles-holder" class="row thumbnails" style="margin-top: 10px;">
  <?php include_component('aetn', 'blackHistoryCollectiblesForSale'); ?>
</div>

<script>
  $(document).ready(function ()
  {
    $('.truncate').expander({
      slicePoint: 350,
      widow: 2,
      expandEffect: 'show',
      expandText: 'Read More',
      expandPrefix: '',
      userCollapseText: '[^]',
      onSlice: function() { $(this).show(); }
    })
    .show();
  });
</script>
