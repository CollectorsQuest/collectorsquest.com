<?php
  /* @var $message  PrivateMessage   */ $message;
  /* @var $messages PrivateMessage[] */ $messages;
  /* @var $reply_form ComposePrivateMessageForm */ $reply_form;

  use_javascript('/js/jquery/elastic.js');
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
      </td>
      <td class="subject"> <?= $message->getSubject(); ?></td>
    </tr>
    <tr>
      <td class="message"><?php
        $body = $message->getBody();

        // Replace all the {route.*} tags with their true URL
        while (preg_match('/{(route\.([\w_]+))}/iu', $body, $matches))
        {
          $body = str_replace($matches[0], url_for($matches[2]), $body);
        }

        // Make sure there are no special tags left
        $body = preg_replace('/({[\w\.]+})/iu', '', $body);

        echo (!$message->getIsRich()) ? cqStatic::linkify($body, false) : $body;
      ?></td>
    </tr>
  <?php endforeach; ?>

    <tr>
      <td colspan="">Write a reply</td>
      <td class="">
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
      </td>
    </tr>
</table>

