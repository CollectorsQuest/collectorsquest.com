<?php
  /**
   * @var $pager PropelModelPager object;
   * @var $filter_by string read|unread|all $filter_by;
   */

  SmartMenu::setSelected('mycq_messages_sidebar', 'inbox');
?>

<form action="<?= url_for('@messages_batch_actions'); ?>" method="post">

  <div class="row-fluid">
    <div class="span8">
      <div class="checkbox-arrow pull-left"></div>
      <div class="private-messages-list-select control-group pull-left">
        <div class="btn-group">
          <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
            Select
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0)" data-select="all">All</a></li>
            <li><a href="javascript:void(0)" data-select="none">None</a></li>
            <li><a href="javascript:void(0)" data-select="read">Read</a></li>
            <li><a href="javascript:void(0)" data-select="unread">Unread</a></li>
          </ul>
        </div>
      </div>

      <div class="private-messages-list-actions control-group pull-left">
        <div class="btn-group ">
          <input type="submit" class="btn btn-mini" name="batch_action[mark_as_read]" value="Mark as Read" />
          <input type="submit" class="btn btn-mini" name="batch_action[mark_as_unread]" value="Mark as Unread" />
          <input type="submit" onclick="return confirm('Are you sure you sure you want to delete these messages?')" class="btn btn-mini" name="batch_action[delete]" value="Delete" />
        </div>
      </div>
    </div>

    <div class="span4">
      <span class="pull-left show-all-text">Show:</span>
      <div class="control-group pull-left">
        <div class=" btn-group">
          <?= link_to('All', '@messages_inbox?filter=all', array('class' => 'btn btn-mini '.('all' == $filter_by ? 'active' : '') )); ?>
          <?= link_to('Unread', '@messages_inbox?filter=unread', array('class' => 'btn btn-mini '.('unread' == $filter_by ? 'active' : '') )); ?>
          <?= link_to('Read', '@messages_inbox?filter=read', array('class' => 'btn btn-mini '.('read' == $filter_by ? 'active' : '') )); ?>
        </div>
      </div> <!-- .control-group.pull-left -->
    </div>
  </div>

  <table id="private-messages-inbox" class="private-messages-list table table-bordered">
    <tbody>
    <?php if (!$pager->isEmpty()): foreach ($pager->getResults() as $message):
      $message_link = url_for('messages_show', $message)
        .($message->getIsRead() ? '' : '#latest-message');
    ?>
      <tr
        class="linkify <?= $message->getIsRead() ? 'read' : 'unread' ?>"
        data-url="<?= $message_link; ?>"
      >
        <td class="select-col dont-linkify">
          <input type="checkbox" name="ids[]" value="<?= $message->getId() ?>" class="<?= $message->getIsRead() ? 'read' : 'unread' ?>" />
        </td>
        <td class="sender-col">
          <?= image_tag_collector($message->getCollectorRelatedBySender(),
            '50x50', array('class' => 'avatar')); ?>
          <?= link_to_collector($message->getCollectorRelatedBySender()); ?>
          <p class="font10">
            <?= time_ago_in_words($message->getCreatedAt('U')); ?> ago
          </p>
        </td>
        <td class="message-col">
          <?= link_to($message->getSubject(), $message_link); ?>
          <span>
            <?= Utf8::truncateHtmlKeepWordsWhole($message->getBody(), 150); ?>
          </span>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr>
        <td colspan="5">You have no messages in your inbox</td>
      </tr>
    <?php endif; ?>
    </tbody>
  </table>

</form>

<?php if ($pager->getPage() == 1): ?>

  <?php if ($pager->haveToPaginate()): ?>
    <a href="<?= url_for('@messages_inbox?filter='.$sf_request->getParameter('filter', 'all')) ?>?page=2"
       class="btn btn-small see-more-full" style="width: 590px;">
      See more
    </a>
  <?php endif; ?>

  <?php
//     $link = null; // link_to('See previous challenges »', '@homepage');
//     cq_section_title("The Collectors' Question", $link, array('left' => 8, 'right' => 4));
  ?>

<?php /* 
  <div class="row-fluid relative">
    <img src="<?= $collectors_question['image']; ?>" alt="<?= $collectors_question['title']; ?>" title="<?= $collectors_question['title']; ?>"/>
    <div class="span12" style="position: absolute; top: 65%; background: url(/images/frontend/white.png); padding: 15px 25px; margin: 0;">
      <h2 class="Chivo webfont" style="font-size: 26px; font-weight: bold; font-style: italic;"><?= $collectors_question['title']; ?></h2>
      <?= $collectors_question['content']; ?>
    </div>
  </div>
  <?php include_partial('comments/comments', array('for_object' => $category)); ?>
  */?>
<?php else: ?>

<div class="row-fluid text-center">
  <?php
    include_component(
      'global', 'pagination', array('pager' => $pager)
    );
  ?>
  </div>

<?php endif; ?>
