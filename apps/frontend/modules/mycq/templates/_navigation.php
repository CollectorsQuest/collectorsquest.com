<?php
/**
 * @var $collector Collector
 * @var $sf_params sfParameterHolder
 */

/**
 * We want to show the dropbox and the Upload Photos functionality
 * only in selected parts of MyCQ (ex. collections and marketplace)
 */
has_component_slot('mycq_upload_photos') ? $spans = array(10, 2) : $spans = array(12, 0);

?>
<div class="slot1-inner-mycq">
  <div class="row-fluid">
    <div class="span<?= $spans[0]; ?> upload-items-wrapper-l">
      <?php
        $links = link_to('View Public Profile', '@collector_me') .
                 '<span class="white">&nbsp; | &nbsp;</span>'.
                 link_to('Log Out', '@logout', array('class'=>'logout-link'));

        cq_page_title($collector->getDisplayName(), $links);
      ?>

      <div id="profile-subnavbar" class="navbar">
        <div class="navbar-inner">
          <div class="container">
            <div class="nav-collapse">
              <ul class="nav">
                <?= SmartMenu::generate('mycq_menu'); ?>
              </ul>
            </div><!-- /.nav-collapse -->
          </div>
        </div><!-- /navbar-inner -->
      </div>
    </div>
    <?php if ($spans[1] > 0): ?>
    <div class="span<?= $spans[1]; ?> upload-items-wrapper-r">
      <?php include_component_slot('mycq_upload_photos'); ?>
    </div>
    <?php endif; ?>
  </div>

  <?php if ($spans[1] > 0 && ($slot = get_component_slot('mycq_dropbox'))): ?>
    <?= $slot; ?>
    <div class="row-fluid">
      <div class="span10 upload-items-wrapper-l"></div>
      <div class="span2 upload-items-wrapper-r">
        <a href="javascript:void(0)" class="dropzone-container-slide pull-right <?= $sf_user->getMycqDropboxOpenState() ? 'open' : '' ?>">
          <span class="open-dropzone">
            Open Uploaded Photos <i class="icon-caret-down"></i>
          </span>
          <span class="close-dropzone">
            Close Uploaded Photos <i class="icon-caret-up"></i>
          </span>
        </a>
      </div>
    </div>
  <?php elseif ($spans[1] > 0): ?>
    <br><br>
  <?php endif; ?>
</div>

<script>
$(document).ready(function()
{
  'use strict';

  $(".dropzone-container-slide").click(function()
  {
    var $this = $(this);
    var $dropzone_wrapper = $('#dropzone-wrapper');

    if ($dropzone_wrapper.hasClass('hidden'))
    {
      // we apply "display:none" to the element with .hide()
      // and then remove the .hidden class, which is "display:none !important;"
      // and breaks js interactions
      $dropzone_wrapper.hide().toggleClass('hidden');
    }

    if ($this.hasClass('open'))
    {
      $dropzone_wrapper.slideToggle("slow", function() {
        $this.toggleClass('open');
      });
    }
    else
    {
      $this.toggleClass('open');
      $dropzone_wrapper.slideToggle("slow");
    }

    $.cookie(
      window.cq.cookies.mycq_dropbox_state,
      // stupid $#!@... $.cookie(name, false) will set a cookie with
      // the STRING "false", and !"false" === false
      // Setting $.cookie(name, 0) will set the STRING "0", and again:
      // !"0" === false, so we need this parseInt bullshit AND manual integer asign
      !parseInt($.cookie(window.cq.cookies.mycq_dropbox_state)) && 1 || 0,
      { expires: 10 * 365, path: '/' }
    );

    return false;
  });
});
</script>
