<?php
  /* @var $message  PrivateMessage   */ $message;
  /* @var $messages PrivateMessage[] */ $messages;
  /* @var $reply_form ComposePrivateMessageForm */ $reply_form;

  if ($messages->getFirst()->getReceiver() == $sf_user->getCollector()->getId())
  {
    SmartMenu::setSelected('mycq_messages_sidebar', 'inbox');
  }
  else
  {
    SmartMenu::setSelected('mycq_messages_sidebar', 'sent');
  }
?>

<table class="private-message-thread table table-striped table-bordered">
  <tbody>
  <?php foreach ($messages as $message):
    $sender = $message->getCollectorRelatedBySender();
    $receiver = $message->getCollectorRelatedByReceiver();
  ?>
    <tr class="table-condensed"
      <?php if ($messages->isLast()): ?>
        id="latest-message"
      <?endif; ?>
    >
      <td class="sender" rowspan="<?= $message->hasAttachedCollectionOrCollectible() ? 3 : 2 ?>">
        <span>From:&nbsp;<?= link_to_if($sender, $sender, 'collector_by_slug', $sender); ?></span>
        <br/>
        <span><?= time_ago_in_words_or_exact_date($message->getCreatedAt()); ?></span>
        <br/>
        <div class="spacer-inner-top-7">
          <?= link_to_collector($sender, 'image', array('width' => 100, 'height' => null)); ?>
        </div>
        <br/>
        <span>To:&nbsp;<?= link_to_if($receiver, $receiver, 'collector_by_slug', $receiver); ?></span>
      </td>
      <td class="subject"><b><?= $message->getSubject(); ?></b></td>
    </tr>
    <tr>
      <td class="message" rowspan="1"><div class="message-holder"><?php
        $body = $message->getBody();

        // Replace all the {route.*} tags with their true URL
        while (preg_match('/{(route\.([\w_]+))}/iu', $body, $matches))
        {
          $body = str_replace($matches[0], url_for($matches[2]), $body);
        }

        // Make sure there are no special tags left
        $body = preg_replace('/({[\w\.]+})/iu', '', $body);

        echo (!$message->getIsRich()) ? cqStatic::linkify($body, false) : $body;
      ?></div></td>
    </tr>
    <?php if ($message->hasAttachedCollectionOrCollectible()): ?>
    <tr>
      <td class="message-attached-info">
      <?php if ($collectible = $message->getAttachedCollectible()): ?>
        This message was sent regarding the collectible <?= link_to_collectible($collectible, 'text'); ?>.
      <?php elseif ($collection = $message->getAttachedCollection()): ?>
        This message was sent regarding the collection <?= link_to_collection($collection, 'text'); ?>.
      <?php endif; ?>
      </td>
    </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>

<div class="reply-form">
  <span class="reply-form-title">Write a reply</span>
  <?= form_tag('@messages_compose', array('class' => 'form-private-message-reply form-horizontal')) ?>
  <fieldset>
    <?= $reply_form->renderUsing('Bootstrap', array(
    'subject' => array('class' => 'span7'),
    'body'    => array('class' => 'span7', 'rows' => 6),
  )); ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Send reply" />
    </div>
  </fieldset>
  </form>
</div>
