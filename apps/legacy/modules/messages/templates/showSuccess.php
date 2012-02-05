<table id="messages-show" class="span-18">
 <tbody>
  <?php foreach ($messages as $i => $m): ?>
  <tr style="border-bottom: 1px solid #E1E1E1; margin-top: 5px; margin-bottom: 5px;">
    <td class="sender">
      <?php if ($m->getSender() == $sf_user->getId()): ?>
        <div style="float: left; margin-right: 10px;">
          <?php echo link_to_collector($sf_user->getCollector(), 'stack'); ?>
        </div>
        <div>
          <?= __('You'); ?><br>
          <font style="font-size: 12px; color: #777777;"><?php echo $m->getCreatedAt('M/d/Y'); ?></font>
        </div>
      <?php else: ?>
        <div style="float: left; margin-right: 10px;">
          <?php echo link_to_collector($m->getCollectorRelatedBySender(), 'stack'); ?>
        </div>
        <div>
          <?php echo link_to_collector($m->getCollectorRelatedBySender(), 'text'); ?><br>
          <font style="font-size: 12px; color: #777777;"><?php echo $m->getCreatedAt('M/d/Y'); ?></font>
        </div>
      <?php endif; ?>
    </td>
    <td class="message">
      <?php if ($message->getSubject() != $m->getSubject() && $message->getReplySubject() != $m->getSubject()): ?>
        <h4 style="margin: 5px 0;"><?= $m->getSubject(); ?></h4>
      <?php endif; ?>

      <?php
        $body = $m->getBody();

        // Replace all the {route.*} tags with their true URL
        while (preg_match('/{(route\.([\w_]+))}/iu', $body, $matches))
        {
          $body = str_replace($matches[0], url_for($matches[2]), $body);
        }

        // Make sure there are no special tags left
        $body = preg_replace('/({[\w\.]+})/iu', '', $body);

        echo (!$m->getIsRich()) ? cqStatic::linkify($body, false) : $body;
      ?>
    </td>
  </tr>
  <?php if ($i != count($messages)-1): ?>
    <tr>
      <td colspan="2" style="border-top: 1px solid #ccc; height: 1px;"></td>
    </tr>
  <?php endif; ?>
  <?php endforeach; ?>
 </tbody>
</table>

<?php
  if (!$sf_user->isOwnerOf($message))
  {
    include_component('messages', 'reply', array('message' => $message));
  }
?>
