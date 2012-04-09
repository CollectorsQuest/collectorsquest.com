<?php
  /* @var $message  PrivateMessage   */ $message;
  /* @var $messages PrivateMessage[] */ $messages;

  use_stylesheet('frontend/private-messages.css');
?>

<table class="private-message-thread table table-striped table-bordered">
  <tbody>
  <?php foreach ($messages as $message):
    $sender = $message->getCollectorRelatedBySender();
    $receiver = $message->getCollectorRelatedByReceiver();
  ?>
    <tr class="table-condensed">
      <td class="sender" rowspan="2">
        <span>By <?= link_to($sender, array('sf_route' => 'collector_by_slug', 'sf_subject' => $sender)); ?></span>
        <br/>
        <span><?= time_ago_in_words_or_exact_date($message->getCreatedAt()); ?></span>
        <br/>
        <div class="top-padding"><?= link_to_collector($sender, 'image'); ?></div>
      <td class="subject"> <?= $message->getSubject(); ?>
    <tr>
      <td class="message"><?= $message->getBody(); ?>

  <?php endforeach; ?>
</table>


