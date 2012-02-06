<table id="messages-sent" class="span-18 messages">
 <tbody>
  <tr><td colspan="3"></td></tr>
  <?php foreach ($messages as $message): ?>
  <?php
    $receiver = $message->getCollectorRelatedByReceiver();
  ?>
  <tr class="<?= ($message->getIsRead() == 0) ? 'unread' : 'read'; ?>">
    <td width="10" style="text-align: center;">
      <div class="icon <?= ($message->getIsRead() == 0) ? 'unread' : (($message->getIsReplied() == 0) ? 'replied' : 'normal'); ?>">&nbsp;</div>
    </td>
    <td class="sender">
      <div style="float: left; margin-right: 10px;">
        <?php echo link_to_collector($receiver, 'stack', array('target' => '_blank')); ?>
      </div>
      <div style="font-weight: <?= ($message->getIsRead() == 0) ? 'bold' : 'normal'; ?>;">
        To: <?php echo link_to_collector($receiver, 'text', array('target' => '_blank')) ?><br>
        <small style="color: #777777;"><?php echo $message->getCreatedAt('M/d/Y'); ?></small>
      </div>
    </td>
    <td class="message">
      <div style="font-weight: <?= ($message->getIsRead() == 0) ? 'bold' : 'normal'; ?>;">
        <?php echo link_to($message->getSubject(), '@message_show?id='. $message->getId()); ?>
      </div>
      <div style="font-size: 12px; color: #777777;">
        <?php echo truncate_text(strip_tags($message->getBody()), 140, '...', true); ?>
      </div>
    </td>
  </tr>
  <?php endforeach; ?>
 </tbody>
</table>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $("table#messages-sent td.message a").bigTarget({
    hoverClass: 'pointer',
    clickZone : 'td:eq(0)'
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
