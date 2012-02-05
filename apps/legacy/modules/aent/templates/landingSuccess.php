<?php slot('slot_1'); ?>
<div class="span-12 last" style="text-align: center; padding-top: 5px;">
  <a href="http://www.facebook.com/pages/Collectors-Quest/119338990397" target="_blank" title="Follow Collectors' Quest on Facebook"><img src="/images/icons/facebook-follow.png" width="165" height="52" alt="Follow Collectors' Quest on Facebook"></a>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="http://twitter.com/collectorsquest" target="_blank" title="Follow Collectors' Quest on Twitter"><img src="/images/icons/twitter-follow.png" width="165" height="52" alt="Follow Collectors' Quest on Twitter"></a>
</div>
<?php end_slot(); ?>

<table style="width: 960px; margin-top: 5px; margin-left: 10px;">
  <tr>
    <td style="text-align: center;" colspan="5">
      <a href="http://www.history.com?cmpid=Partner_CollectorsQuest" target="_blank">
        <?= image_tag('legacy/aetn/aetn_header.jpg'); ?>
      </a>
    </td>
  </tr>
  <tr><td colspan="5">&nbsp;</td></tr>
  <tr>
    <td style="padding-left: 10px;">
      <a href="http://www.history.com/pawnstars?cmpid=Partner_CollectorsQuest" target="_blank">
        <?= image_tag('legacy/aetn/pawn_stars.jpg'); ?>
      </a>
    </td>
    <td>
      <div style="color: #090779; margin-bottom: 5px; text-align: center;"">Farewell to Arms</div>
      <div class="stack" style="background: url(/images/legacy/stack_small.png); width: 143px; height: 144px; margin: auto; padding: 5px 0 0 14px;">
        <?= link_to_collectible($collectible[1], 'image', array('style' => 'width: 135px; height: 135px;')); ?>
      </div>
      <div style="text-align: center">
        <?= link_to_collectible($collectible[1], 'text', array('style' => 'text-decoration: none; color: #626261;')); ?>
      </div>

      <br clear="all">
      <span style="color: #d83a10;">More Collectibles:</span>
      <ul style="padding-left: 20px; font-size: 80%;">
        <?php foreach ($collectibles[1] as $c): ?>
        <li><?= link_to_collectible($c, 'text', array('style' => 'text-decoration: none; color: #626261;')); ?></li>
        <?php endforeach; ?>
      </ul>
    </td>
    <td>
      <div style="color: #090779; margin-bottom: 5px; text-align: center;"">Lights, Cameras, Action!</div>
      <div class="stack" style="background: url(/images/legacy/stack_small.png); width: 143px; height: 144px; margin: auto; padding: 5px 0 0 14px;">
        <?= link_to_collectible($collectible[2], 'image', array('style' => 'width: 135px; height: 135px;')); ?>
      </div>
      <div style="text-align: center">
        <?= link_to_collectible($collectible[2], 'text', array('style' => 'text-decoration: none; color: #626261;')); ?>
      </div>

      <br clear="all">
      <span style="color: #d83a10;">More Collectibles:</span>
      <ul style="padding-left: 20px; font-size: 80%;">
        <?php foreach ($collectibles[2] as $c): ?>
        <li><?= link_to_collectible($c, 'text', array('style' => 'text-decoration: none; color: #626261;')); ?></li>
        <?php endforeach; ?>
      </ul>
    </td>
    <td>
      <div style="color: #090779; margin-bottom: 5px; text-align: center;">Living in the Past</div>
      <div class="stack" style="background: url(/images/legacy/stack_small.png); width: 143px; height: 144px; margin: auto; padding: 5px 0 0 14px;">
        <?= link_to_collectible($collectible[3], 'image', array('style' => 'width: 135px; height: 135px;')); ?>
      </div>
      <div style="text-align: center">
        <?= link_to_collectible($collectible[3], 'text', array('style' => 'text-decoration: none; color: #626261;')); ?>
      </div>

      <br clear="all">
      <span style="color: #d83a10;">More Collectibles:</span>
      <ul style="padding-left: 20px; font-size: 80%;">
        <?php foreach ($collectibles[3] as $c): ?>
        <li><?= link_to_collectible($c, 'text', array('style' => 'text-decoration: none; color: #626261;')); ?></li>
        <?php endforeach; ?>
      </ul>
    </td>
    <td style="width: 135px; vertical-align: top; padding-top: 30px;">
      <?= link_to(image_tag('legacy/aetn/btn_start_collection.png'), '@collector_signup'); ?><br/><br/>
      <?= link_to(image_tag('legacy/aetn/btn_browse_marketplace.png'), '@marketplace'); ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4"><hr style="border: 1px dashed grey; width: 100%;"></td>
  </tr>
  <tr>
    <td style="padding-left: 10px;">
      <a href="http://www.history.com/pickers?cmpid=Partner_CollectorsQuest" target="_blank">
        <?= image_tag('legacy/aetn/american_pickers.jpg'); ?>
      </a>
    </td>
    <td style="vertical-align: top;">
      <div style="color: #090779; margin-bottom: 5px; text-align: center;"">See Me, Hear Me</div>
      <div class="stack" style="background: url(/images/legacy/stack_small.png); width: 143px; height: 144px; margin: auto; padding: 5px 0 0 14px;">
        <?= link_to_collectible($collectible[4], 'image', array('style' => 'width: 135px; height: 135px;')); ?>
      </div>
      <div style="text-align: center">
        <?= link_to_collectible($collectible[4], 'text', array('style' => 'text-decoration: none; color: #626261;')); ?>
      </div>

      <br clear="all">
      <span style="color: #d83a10;">More Collectibles:</span>
      <ul style="padding-left: 20px; font-size: 80%;">
        <?php foreach ($collectibles[4] as $c): ?>
        <li><?= link_to_collectible($c, 'text', array('style' => 'text-decoration: none; color: #626261;')); ?></li>
        <?php endforeach; ?>
      </ul>
    </td>
    <td style="vertical-align: top;">
      <div style="color: #090779; margin-bottom: 5px; text-align: center;"">Cool and Collected</div>
      <div class="stack" style="background: url(/images/legacy/stack_small.png); width: 143px; height: 144px; margin: auto; padding: 5px 0 0 14px;">
        <?= link_to_collectible($collectible[5], 'image', array('style' => 'width: 135px; height: 135px;')); ?>
      </div>
      <div style="text-align: center">
        <?= link_to_collectible($collectible[5], 'text', array('style' => 'text-decoration: none; color: #626261;')); ?>
      </div>

      <br clear="all">
      <span style="color: #d83a10;">More Collectibles:</span>
      <ul style="padding-left: 20px; font-size: 80%;">
        <?php foreach ($collectibles[5] as $c): ?>
        <li><?= link_to_collectible($c, 'text', array('style' => 'text-decoration: none; color: #626261;')); ?></li>
        <?php endforeach; ?>
      </ul>
    </td>
    <td style="vertical-align: top;">
      <div style="color: #090779; margin-bottom: 5px; text-align: center;"">On the Road Again</div>
      <div class="stack" style="background: url(/images/legacy/stack_small.png); width: 143px; height: 144px; margin: auto; padding: 5px 0 0 14px;">
        <?= link_to_collectible($collectible[6], 'image', array('style' => 'width: 135px; height: 135px;')); ?>
      </div>
      <div style="text-align: center">
        <?= link_to_collectible($collectible[6], 'text', array('style' => 'text-decoration: none; color: #626261;')); ?>
      </div>

      <br clear="all">
      <span style="color: #d83a10;">More Collectibles:</span>
      <ul style="padding-left: 20px; font-size: 80%;">
        <?php foreach ($collectibles[6] as $c): ?>
        <li><?= link_to_collectible($c, 'text', array('style' => 'text-decoration: none; color: #626261;')); ?></li>
        <?php endforeach; ?>
      </ul>
    </td>
    <td style="vertical-align: top; padding-top: 30px;">
      <?= link_to(image_tag('legacy/aetn/btn_start_collection.png'), '@collector_signup'); ?><br/><br/>
      <?= link_to(image_tag('legacy/aetn/btn_browse_marketplace.png'), '@marketplace'); ?>
    </td>
  </tr>
  <tr>
    <td colspan="5">
      <div style="background-color: #f5f8dd; padding: 5px;">
        <div class="box">
          <h2 style="font-size: 18px; font-weight: bold; padding-left: 15px; background: white url('/images/legacy/black-arrow.png') 0 6px no-repeat;">
            Top Collections from Collectors’ Quest
          </h2>
          <br clear="all">
          <?php foreach ($featured as $i => $f): ?>
            <div class="span-5" style="margin-right: 25px; margin-left: 10px; margin-bottom: 40px;">
              <a href="<?= url_for_collection($f->getCollection()); ?>">
                <?= image_tag_collectible($f, '75x75', array('style' => 'float: left; margin-right: 10px;')); ?>
                <?= $featured_texts[$i]; ?>
              </a>
          </div>
          <? if ($i == 3) echo '<br clear="all">'; ?>
          <?php endforeach; ?>
          <br clear="all">
        </div>
      </div>
    </td>
  </tr>
</table>
