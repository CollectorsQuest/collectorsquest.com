<?php
  /* @var $message  PrivateMessage   */ $message;
  /* @var $messages PrivateMessage[] */ $messages;
  /* @var $reply_form ComposePrivateMessageForm */ $reply_form;
  $user_is_recepient = $sf_user->getCollector()->equals($message->getCollectorRelatedByReceiverId());

  if ($user_is_recepient)
  {
    SmartMenu::setSelected('mycq_messages_sidebar', 'inbox');
  }
  else
  {
    SmartMenu::setSelected('mycq_messages_sidebar', 'sent');
  }

  cq_sidebar_title(
    'Conversation with '. ($user_is_recepient
      ? $message->getCollectorRelatedBySenderId()
      : $message->getCollectorRelatedByReceiverId()),
    null,
    array(
      'left' => 8, 'right' => 4,
      'class'=>'mycq-red-title row-fluid messages-header'
    )
  );
?>

<?php if ($user_is_recepient): ?>
<form action="<?= url_for('@messages_thread_actions'); ?>" method="post">
  <input type="hidden" name="thread" value="<?= $message->getThread(); ?>" />
  <div class="row-fluid gray-well messages-header">
    <div class="spacer-top-5 spacer-bottom-5 clearfix">
      <div class="btn-group pull-left">
        <input type="submit" name="thread_action[mark_as_unread]" class="btn btn-mini" value="Mark Unread" />
      </div>
      <div class="btn-group pull-left">
        <input type="submit" name="thread_action[delete]" class="btn btn-mini" value="Delete" onclick="return confirm('Are you sure you want to delete this message?')" />
        <input type="submit" name="thread_action[report_spam]" class="btn btn-mini" value="Report Spam" onclick="return confirm('Are you sure you want to report this message as spam and delete it?')" />
      </div>
    </div>
  </div>
</form>
<?php endif; ?>

<table class="private-message-thread table table-striped table-bordered">
  <tbody>
  <?php foreach ($messages as $message):
    $sender = $message->getCollectorRelatedBySenderId();
    $receiver = $message->getCollectorRelatedByReceiverId();
  ?>
    <tr class="table-condensed"
      <?php if ($messages->isLast()): ?>
        id="latest-message"
      <?endif; ?>
    >
      <td class="sender" rowspan="<?= $message->hasAttachedObject() ? 3 : 2 ?>">
        <span>From:&nbsp;<?= link_to_if($sender, $sender, 'collector_by_slug', $sender); ?></span>
        <br/>
        <span title="<?= $message->getCreatedAt('c'); ?>">
          <?= time_ago_in_words_or_exact_date($message->getCreatedAt()); ?>
        </span>
        <br/>
        <div class="spacer-inner-top-7">
          <?= link_to_collector($sender, 'image'); ?>
        </div>
        <br/>
        <span>To:&nbsp;<?= link_to_collector($receiver) ?: mail_to($message->getReceiverEmail()); ?></span>
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
    <?php if ($message->hasAttachedObject()): ?>
    <tr>
      <td class="message-attached-info">
      <?php if (( $collectible = $message->getAttachedCollectible() )): ?>
        This message was sent regarding the collectible
        <?= link_to_collectible($collectible, 'text'); ?>.
      <?php elseif (( $collection = $message->getAttachedCollection() )): ?>
        This message was sent regarding the collection
        <?= link_to_collection($collection, 'text'); ?>.
      <?php elseif (( $shopping_order = $message->getAttachedShoppingOrder() )): ?>
        This message was sent regarding the shopping order
        <?= link_to(
          $shopping_order->getCollectible()->getName(),
          'mycq_collectible_by_slug',
          $shopping_order->getCollectible()
        ); ?>.
      <?php endif; ?>
      </td>
    </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>

<div class="reply-form">
  <span class="reply-form-title">Write a reply</span>
  <?php unset($reply_form['copy_for_sender']); ?>
  <?= form_tag('@messages_compose', array('class' => 'form-private-message-reply form-horizontal')) ?>
  <fieldset>
    <?= $reply_form->renderUsing('Bootstrap', array(
    'subject' => array('class' => 'span7'),
    'body'    => array('class' => 'span7', 'rows' => 6),
  )); ?>
  	<div class="control-group ">
      <label for="message_copy_for_sender" class=" control-label">&nbsp;</label>
      <div class="controls">
        <label for="message_copy_for_sender">
        	<input type="checkbox" name="<?= $reply_form->getName() ?>[copy_for_sender]"
                 id="<?= $reply_form->getName() ?>_copy_for_sender">
         	Email me a copy of this message to my email address
        </label>
      </div>
    </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Send reply" />
    </div>
  </fieldset>
  </form>
</div>
