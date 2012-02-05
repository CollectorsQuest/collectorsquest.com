<form id="messages-batch-actions" action="<?= url_for('@messages_batch_actions'); ?>" method="post">
<table id="messages-inbox" class="span-18 messages">
  <thead>
   <tr>
     <td colspan="2"><img src="/images/legacy/arrow_lbr.png" alt=""></td>
     <td colspan="2">
       <div style="float: right; padding-top: 2px;">
         <div class="fg-buttonset fg-buttonset-single" style="float: right; margin-top: -2px;">
           <button onClick="parent.location='<?= url_for('@messages_inbox?show=all'); ?>'" class="fg-button ui-state-default ui-corner-left <?= ($sf_params->get('show') == 'all') ? 'ui-priority-primary ui-state-active' : '' ?>">
             <?= __('All'); ?>
           </button>
           <button onClick="parent.location='<?= url_for('@messages_inbox?show=unread'); ?>'" class="fg-button ui-state-default ui-corner-right <?= ($sf_params->get('show') == 'unread') ? 'ui-priority-primary ui-state-active' : '' ?>">
             <?= __('Unread Only'); ?>
           </button>
         </div>
         <?= __('Show:'); ?> &nbsp;
       </div>
       <div class="fg-buttonset fg-buttonset-single cmd-buttons">
         <button type="submit" name="cmd[read]" class="fg-button ui-state-default ui-state-disabled ui-corner-left"><?= __('Mark as Read'); ?></button>
         <button type="submit" name="cmd[unread]" class="fg-button ui-state-default ui-state-disabled"><?= __('Mark as Unread'); ?></button>
         <button type="submit" name="cmd[delete]" class="fg-button ui-state-default ui-state-disabled ui-corner-right"><?= __('Delete'); ?></button>
       </div>
     </td>
   </tr>
 </thead>
 <tbody>
   <tr><td colspan="4" style="height: 15px;"></td></tr>
  <?php foreach ($messages as $message): ?>
  <?php
    $sender = $message->getCollectorRelatedBySender();
  ?>
  <tr class="<?= ($message->getIsRead() == 0) ? 'unread' : 'read'; ?>">
    <td><input type="checkbox" name="message[thread][]" value="<?= $message->getThread(); ?>"></td>
    <td>
      <div class="icon <?= ($message->getIsRead() == 0) ? 'unread' : (($message->getIsReplied() == 0) ? 'replied' : 'normal'); ?>">&nbsp;</div>
    </td>
    <td class="sender">
      <div style="float: left; margin-right: 10px;">
        <?php echo link_to_collector($sender, 'stack', array('target' => '_blank')); ?>
      </div>
      <div style="font-weight: <?= ($message->getIsRead() == 0) ? 'bold' : 'normal'; ?>;">
        <?php echo link_to_collector($sender, 'text', array('target' => '_blank')) ?><br>
        <small style="color: #777777;"><?php echo $message->getCreatedAt('M/d/Y'); ?></small>
      </div>
    </td>
    <td class="message">
      <div style="font-weight: <?= ($message->getIsRead() == 0) ? 'bold' : 'normal'; ?>;">
        <?php echo link_to($message->getSubject(), '@message_show?id='. $message->getId()); ?>
        <span style="color: #ccc">(<?= $message->getThreadCount(); ?>)</span>
      </div>
      <div style="font-size: 12px; color: #777777;">
        <?php echo truncate_text(strip_tags($message->getBody()), 140, '...', true); ?>
      </div>
    </td>
  </tr>
  <?php endforeach; ?>
 </tbody>
 <?php if (count($messages) > 10): ?>
 <tfoot>
   <tr><td colspan="4" style="height: 15px;"></td></tr>
   <tr>
     <td colspan="2"><img src="/images/legacy/arrow_ltr.png" alt=""></td>
     <td colspan="2">
       <div class="fg-buttonset fg-buttonset-single cmd-buttons">
         <button type="submit" name="cmd[read]" class="fg-button ui-state-default ui-state-disabled ui-priority-primary ui-corner-left"><?= __('Mark as Read'); ?></button>
         <button type="submit" name="cmd[unread]" class="fg-button ui-state-default ui-state-disabled"><?= __('Mark as Unread'); ?></button>
         <button type="submit" name="cmd[delete]" class="fg-button ui-state-default ui-state-disabled ui-corner-right"><?= __('Delete'); ?></button>
       </div>
     </td>
   </tr>
 </tfoot>
 <?php endif; ?>
</table>
</form>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $("table#messages-inbox td.message a").bigTarget({
    hoverClass: 'pointer',
    clickZone : 'td:eq(0)'
  });

  $('form#messages-batch-actions').submit(function()
  {
    return ($("form#messages-batch-actions :checkbox[checked]").length > 0) ? true : false;
  });

  $('form#messages-batch-actions :checkbox').change(function()
  {
    if ($("form#messages-batch-actions :checkbox[checked]").length > 0)
    {
      $('.cmd-buttons .fg-button').removeClass('ui-state-disabled');
    }
    else
    {
      $('.cmd-buttons .fg-button').addClass('ui-state-disabled');
    }
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
