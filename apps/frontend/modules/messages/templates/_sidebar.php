<ul class="nav nav-pills nav-stacked nav-private-messages">
  <li id="inbox"><?= link_to('Inbox', '@messages_inbox'); ?></li>
  <li id="sent"><?= link_to('Sent', '@messages_sent'); ?></li>
  <li id="compose"><?= link_to('Compose', '@messages_compose'); ?></li>
</ul>