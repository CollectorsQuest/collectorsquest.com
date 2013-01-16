<?php
  /* @var $collector Collector */
  $comments = $collector->getNotificationsComment();
  $messages = $collector->getNotificationsMessage();

  SmartMenu::setSelected('mycq_profile_tabs', 'email_preferences');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_profile_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <div class="row">
          <div class="span12 boolean-selection">
            <span class="">Receive notifications of comments on your collections and collectibles: </span>
            <?php
              echo link_to(
                $comments ? 'Yes' : '&nbsp;No&nbsp;',
                '@mycq_profile_email_preferences?comments=' . ((integer) !$comments),
                array('class' => 'pull-right btn btn-mini ' . ($comments ? 'btn-success' : 'btn-danger'))
              );
            ?>
          </div>
        </div>

        <div class="row">
          <div class="span12 boolean-selection">
            <span class="">Receive notifications for new private messages: </span>
            <?php
              echo link_to(
                $messages ? 'Yes' : '&nbsp;No&nbsp;',
                '@mycq_profile_email_preferences?messages=' . ((integer) !$messages),
                array('class' => 'pull-right btn btn-mini ' . ($messages ? 'btn-success' : 'btn-danger'))
              );
            ?>
          </div>
        </div>

      </div><!-- .tab-content-inner -->
    </div><!-- .tab-pane.active -->

  </div><!-- .tab-content -->
</div><!-- #mycq-tabs -->

<script>
'use strict';
$(document).ready(function(){
  $('.boolean-selection .btn').hover(function(){ // hover-in
    var original_button_state = this.className.match(/btn-(?:success|danger)/)[0],
        $this = $(this);

    $this.data('original-button-state', original_button_state)
         .data('original-text', $this.text())
         .html('&nbsp;Change&nbsp;')
         .removeClass(original_button_state)
         .addClass('btn-warning')
  }, function(){ // hover-out
    var $this = $(this);

    $this.removeClass('btn-warning')
         .addClass($this.data('original-button-state'))
         .text($this.data('original-text'));
  });
})
</script>
