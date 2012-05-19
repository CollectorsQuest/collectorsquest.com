<?php $batch = cqStatic::getUniqueId(32); ?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs" style="margin-bottom: 0;">
    <li class="active">
      <a href="#tab1" data-toggle="tab">Show Collections</a>
    </li>
    <?php if ($collections_count > 0): ?>
    <li class="pull-right styles-reset">
      <form id="fileupload" action="<?= url_for('@ajax_mycq?section=collectibles&page=upload&batch='. $batch); ?>"
            class="pull-right" method="POST" enctype="multipart/form-data">

        <span class="btn btn-primary blue-button fileinput-button">
          <i class="icon-plus icon-white"></i>&nbsp;<span>Upload Photos</span>
          <input type="file" name="files[]" multiple="multiple">
        </span>

        <div id="fileupload-modal" class="modal hide fade">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>Uploading photos, please wait...</h3>
          </div>
          <div class="modal-body">
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
            <div class="span7 fileupload-progress fade">
              <!-- The global progress bar -->
              <div class="progress progress-success progress-striped active">
                <div class="bar" style="width:0%;"></div>
              </div>
              <!-- The extended global progress information -->
              <div class="progress-extended">&nbsp;</div>
            </div>
            <div class="span3">
              <a href="<?= url_for('@mycq_upload_cancel?batch='. $batch); ?>"
                 class="btn btn-primary btn-danger">
                Cancel Upload
              </a>
            </div>
          </div>
        </div>

      </form>
    </li>
    <?php endif; ?>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">

      <?php
        include_component(
          'mycq', 'dropbox',
          array('instructions' => array(
            'position' => 'bottom',
            'text' => 'Drag and drop collectibles into your collections.')
          )
        );
      ?>

      <div class="tab-content-inner spacer-top">
        <div class="row-fluid sidebar-title spacer-inner-bottom">
          <div class="span5 link-align">
            <h3 class="Chivo webfont">My Collections (<?= $collections_count ?>)</h3>
          </div>
          <div class="span7">
            <?php if ($collections_count > 7): ?>
            <div class="mycq-sort-search-box">
              <div class="input-append">
                <form id="form-explore-collections" method="post" action="/search/collections">
                  <div class="btn-group">
                    <div class="append-left-gray">Sort by <strong id="sortByName">Most Relevant</strong></div>
                    <a class="btn gray-button dropdown-toggle" data-toggle="dropdown" href="#">
                      <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
                      <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                      <li><a data-sort="most-popular" data-name="Most Popular" class="sortBy" href="javascript:">Sort by <strong>Most Popular</strong></a></li>
                    </ul>
                  </div>
                  <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
                  <input type="hidden" value="most-relevant" id="sortByValue" name="s">
                </form>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <?php include_component('mycq', 'collections'); ?>

      </div><!-- /.tab-content-inner -->
    </div>
  </div><!-- /.tab-content -->
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
window.locale = {
  "fileupload": {
    "errors": {
      "maxFileSize": "File is too big",
      "minFileSize": "File is too small",
      "acceptFileTypes": "Filetype not allowed",
      "maxNumberOfFiles": "Max number of files exceeded",
      "uploadedBytes": "Uploaded bytes exceed file size",
      "emptyResult": "Empty file upload result"
    },
    "error": "Error",
    "start": "Start",
    "cancel": "Cancel",
    "destroy": "Delete"
  }
};

$(document).ready(function()
{
  'use strict';

  $('#main').bind('drop dragover', function (e) {
    e.preventDefault();
  });

  // Initialize the jQuery File Upload widget:
  $('#fileupload').fileupload();
  $('#fileupload').fileupload('option', 'autoUpload', true);
  $('#fileupload').fileupload('option', 'dropZone', $('#main'));
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
      /\/[^\/]*$/, '/jquery_xdcomm.html?%s'
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
