<ul class="nav nav-pills nav-stacked nav-private-messages">
  <li id="inbox">
    <a href="<?= url_for('@messages_inbox'); ?>" title="Inbox">
      <i class="icon icon-inbox"></i> Inbox
    </a>
  </li>
  <li id="sent">
    <a href="<?= url_for('@messages_sent'); ?>" title="Sent Messages">
      <i class="icon icon-share-alt"></i> Sent Messages
    </a>
  </li>
  <li id="compose">
    <a href="<?= url_for('@messages_compose'); ?>" title="Compose Message">
      <i class="icon icon-edit"></i> Compose Message
    </a>
  </li>
</ul>
