<?php
  /* @var $pager PropelModelPager */ $pager;

  SmartMenu::setSelected('mycq_messages_sidebar', 'sent');
?>

<table class="private-messages-list table table-bordered">
  <tbody>
  <?php if (!$pager->isEmpty()): foreach ($pager->getResults() as $message): ?>
    <tr
      class="linkify <?= $message->getIsRead() ? 'read' : 'unread' ?>"
      data-url="<?= url_for('messages_show', $message); ?>"
    >
      <td class="sender-col">
        <?= image_tag_collector($message->getCollectorRelatedBySender(),
          '50x50', array('class' => 'avatar')); ?>
        <?= link_to_collector($message->getCollectorRelatedBySender()); ?>
        <p class="font10">
          <?= time_ago_in_words($message->getCreatedAt('U')); ?> ago
        </p>
      </td>
      <td class="message-col">
        <?= link_to($message->getSubject(), 'messages_show', $message); ?>
        <span>
          <?= Utf8::truncateHtmlKeepWordsWhole($message->getBody(), 100); ?>
        </span>
      </td>
    </tr>
  <?php endforeach; else: ?>
    <tr>
      <td colspan="5">You have not sent any messages</td>
    </tr>
  <?php endif; ?>
  </tbody>
</table>

<?php if ($pager->getPage() == 1): ?>

  <?php if ($pager->haveToPaginate()): ?>
    <a href="<?= url_for('@messages_sent') ?>?page=2"
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
