<?php
/**
 * @var $collector Collector
 * @var $sf_params sfParameterHolder
 */
?>
<div class="slot1-inner-mycq">
  <div class="row-fluid">
    <div class="span10">
      <?php
        $links = link_to('View Public Profile', '@collector_me') .
                 '<span style="color: #fff;">&nbsp; | &nbsp;</span>'.
                 link_to('Log Out', '@logout', array('class'=>'logout-link'));

        cq_page_title($collector->getDisplayName(), $links);
      ?>

      <div id="profile-subnavbar" class="navbar">
        <div class="navbar-inner">
          <div class="container">
            <div class="nav-collapse">
              <ul class="nav">
                <?php
                  if (IceGateKeeper::open('mycq_homepage'))
                  {
                    $active = in_array($sf_params->get('action'), array('index')) ? 'active' : null;
                    echo '<li class="'. $active .'">', link_to('Home', '@mycq'), '</li>';
                  }
                ?>
                <?php
                  $active = in_array($sf_params->get('action'), array('profile')) ? 'active' : null;
                  echo '<li class="'. $active .'">', link_to('Profile', '@mycq_profile'), '</li>';
                ?>
                <?php
                  $active = in_array($sf_params->get('action'), array('collections', 'collection', 'collectible')) ? 'active' : null;
                  echo '<li class="'. $active .'">', link_to('Collections', '@mycq_collections'), '</li>';
                ?>
                <?php
                  if (IceGateKeeper::open('mycq_marketplace'))
                  {
                    $active = in_array($sf_params->get('action'), array('marketplace')) ? 'active' : null;
                    $active = in_array($sf_params->get('module'), array('seller')) ? 'active' : $active;
                    echo '<li class="'. $active .'">', link_to('Store <sup>βeta</sup>', '@mycq_marketplace'), '</li>';
                  }
                ?>
                <?php
                  $active = in_array($sf_params->get('module'), array('messages')) ? 'active' : null;
                  $text = sprintf('Messages (%s)', $sf_user->getUnreadMessagesCount());
                  echo '<li class="'. $active .'" style="border-right: 1px solid #4B3B3B;">', link_to($text, '@messages_inbox'), '</li>';
                ?>
                <?php
                  if (IceGateKeeper::open('mycq_wanted'))
                  {
                    $active = in_array($sf_params->get('action'), array('wanted')) ? 'active' : null;
                    echo '<li class="'. $active .'" style="border-right: 1px solid #4B3B3B;">', link_to('Wanted', '@mycq_wanted'), '</li>';
                  }
                ?>
              </ul>
            </div><!-- /.nav-collapse -->
          </div>
        </div><!-- /navbar-inner -->
      </div>
    </div>
    <div class="span2 upload-items-wrapper">
      <form action="<?= url_for('@ajax_mycq?section=collectibles&page=upload&batch='. $batch); ?>"
            id="fileupload" method="POST" enctype="multipart/form-data">

        <span class="upload-items-button fileinput-button">
          <i class="icon-plus icon-white"></i> upload items
          <input type="file" name="files[]" multiple="multiple">
        </span>

        <div id="fileupload-modal" class="modal hide fade">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>Uploading items, please wait...</h3>
          </div>
          <div class="modal-body">
            <div class="alert alert-info alert-gcf">
              <strong>NOTE:</strong> If you want to upload more than one file at a time, please
              <?php
              echo link_to(
                'click here.', 'http://www.google.com/chromeframe',
                array('target' => '_blank')
              );
              ?>
            </div>

            <!-- The table listing the files available for upload/download -->
            <table class="table table-striped">
              <thead>
              <tr>
                <td>Preview</td>
                <td colspan="4">Name</td>
              </tr>
              </thead>
              <tbody class="files"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <div class="span4 fileupload-progress fade">
              <!-- The global progress bar -->
              <div class="progress progress-success progress-striped active">
                <div class="bar" style="width:0;"></div>
              </div>
            </div>
            <!-- The extended global progress information -->
            <div class="span5 progress-extended">&nbsp;</div>
            <div class="span3">
              <a href="<?= url_for('@mycq_upload_cancel?batch='. $batch); ?>"
                 class="btn btn-primary btn-danger">
                Cancel Upload
              </a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include_component('mycq', 'dropbox'); ?>

  <a href="#" class="dropzone-container-slide pull-right">
    <span class="close-dropzone">Open Items to sort <i class="icon-caret-down"></i></span>
    <span class="open hidden">Close Items to sort <i class="icon-caret-up"></i></span>
  </a>
</div>


<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-upload fade">
    <td class="preview"><span class="fade"></span></td>
    <td class="name"><span>{%=file.name%}</span></td>
    <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
    {% if (file.error) { %}
    <td class="error" colspan="2">
      <span class="label label-important">{%=locale.fileupload.error%}</span>
      {%=locale.fileupload.errors[file.error] || file.error%}
    </td>
    {% } else if (o.files.valid && !i) { %}
    <td>
      <div class="progress progress-success progress-striped active">
        <div class="bar" style="width:0;"></div>
      </div>
    </td>
    <td class="cancel">
      {% if (!i) { %}
      <button class="btn btn-warning">
        <span>{%=locale.fileupload.cancel%}</span>
      </button>
      {% } %}
    </td>
    {% } %}
  </tr>
  {% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-download fade">
    {% if (file.error) { %}
    <td>-</td>
    <td class="name"><span>{%=file.name%}</span></td>
    <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
    <td class="error" colspan="2">
      <span class="label label-important">{%=locale.fileupload.error%}</span>
      {%=locale.fileupload.errors[file.error] || file.error%}
    </td>
    {% } else { %}
    <td class="preview">
      {% if (file.thumbnail) { %}
      <img src="{%=file.thumbnail%}" height="50"/>
      {% } %}
    </td>
    <td class="name" colspan="2"><span>{%=file.name%}</span></td>
    <td class="size"><span>{%=file.size%}</span></td>
    <td class="success">
      <span class="label label-success">Success</span>
    </td>
    {% } %}
  </tr>
  {% } %}
</script>

<script>
$(document).ready(function()
{
  'use strict';

  $(".dropzone-container-slide").click(function()
  {
    var $this = $(this);

    if ($this.hasClass('open'))
    {
      $("#dropzone-wrapper").slideToggle("slow", function()
      {
        $this.find('span').toggleClass('hidden');
        $this.toggleClass('open');
      });
    }
    else
    {
      $("#dropzone-wrapper").slideToggle("slow");
      $this.find('span').toggleClass('hidden');
      $this.toggleClass('open');
    }

    return false;
  });

  if (
    window.location.hash &&
      window.location.hash.substring(1) === 'dropbox'
    ) {
    $(".dropzone-container-slide").click();
  }

  // Initialize the jQuery File Upload widget:
  $('#fileupload').fileupload();
  $('#fileupload').fileupload('option', 'autoUpload', true);
  $('#fileupload').fileupload('option', 'dropZone', $('#dropzone'));
  $('#fileupload').fileupload('option', 'limitConcurrentUploads', 3);

  $('#fileupload')
    .bind('fileuploadstart', function(e, data) {
      $('#fileupload-modal').modal();
    })
    .bind('fileuploadstop', function(e, data) {
      window.location.href = '<?= url_for('@mycq_upload_finish?batch='. $batch); ?>';
    });

  // Enable iframe cross-domain access via redirect option:
  $('#fileupload').fileupload(
    'option', 'redirect',
    window.location.href.replace(
      /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
    )
  );

  $('#fileupload').fileupload('option', {
    maxFileSize: 5000000,
    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
  });

  // Load existing files:
  $('#fileupload').each(function () {
    var that = this;
    $.getJSON(this.action, function (result) {
      if (result && result.length) {
        $(that).fileupload('option', 'done')
          .call(that, null, {result: result});
      }
    });
  });

});
</script>
