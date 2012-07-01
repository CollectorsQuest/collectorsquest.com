<?php $batch = cqStatic::getUniqueId(32); ?>

<form action="<?= url_for('@ajax_mycq?section=collectibles&page=upload&batch='. $batch); ?>"
      id="fileupload" method="POST" enctype="multipart/form-data">

  <span class="upload-items-button fileinput-button">
    <i class="icon-plus icon-white"></i> Upload Photos
    <input type="file" name="files[]" multiple="multiple">
  </span>

  <div id="fileupload-modal" class="modal hide fade">
    <div class="modal-header">
      <h3>Uploading files, please wait...</h3>
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
      <table class="table table-striped" style="width: 515px;">
        <thead>
        <tr>
          <td>Preview</td>
          <td colspan="3">Name</td>
          <td>Status</td>
        </tr>
        </thead>
        <tbody class="files"></tbody>
      </table>
    </div>
    <div class="modal-footer">
      <div class="span3 fileupload-progress fade">
        <!-- The global progress bar -->
        <div class="progress progress-info progress-striped active">
          <div class="bar" style="width:0;"></div>
        </div>
      </div>
      <!-- The extended global progress information -->
      <div class="span5 progress-extended">&nbsp;</div>
      <div class="span4">
        <a href="<?= url_for('@mycq_upload_cancel?batch='. $batch); ?>" id="button-fileupload"
           class="btn btn-danger" data-loading-text="Cancelling...">
          Cancel Upload
        </a>
      </div>
    </div>
  </div>
</form>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-upload fade">
    <td class="preview"><span class="fade"></span></td>
    <td class="name"><span>{%=o.formatFileName(file.name)%}</span></td>
    {% if (file.error) { %}
    <td class="error" colspan="2">
      {%=locale.fileupload.errors[file.error] || file.error%}
    </td>
    <td>
      <span class="label label-important">{%=locale.fileupload.error%}</span>
    </td>
    {% } else if (o.files.valid && !i) { %}
    <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
    <td>
      <div class="progress progress-info progress-striped active">
        <div class="bar" style="width:0;"></div>
      </div>
    </td>
    <td class="cancel">
      {% if (!i) { %}
      <button class="btn btn-warning btn-mini">
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
    <td class="name"><span>{%=o.formatFileName(file.name)%}</span></td>
    <td class="error" colspan="2">
      {%=locale.fileupload.errors[file.error] || file.error%}
    </td>
    <td>
      <span class="label label-important">{%=locale.fileupload.error%}</span>
    </td>
    {% } else { %}
    <td class="preview">
      {% if (file.thumbnail) { %}
      <img src="{%=file.thumbnail%}" height="50"/>
      {% } %}
    </td>
    <td class="name" colspan="3"><span>{%=file.name%}</span></td>
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

  // Initialize the jQuery File Upload widget:
  $('#fileupload').fileupload();
  $('#fileupload').fileupload('option', 'autoUpload', true);
  $('#fileupload').fileupload('option', 'dropZone', $('#dropzone'));
  $('#fileupload').fileupload('option', 'limitConcurrentUploads', 3);

  $('#fileupload')
    .bind('fileuploadstart', function(e, data) {
      $('#fileupload-modal').modal({backdrop: 'static', keyboard: false, show: true});
    })
    .bind('fileuploadstop', function(e, data)
    {
      var finish = '<?= url_for('@mycq_upload_finish?batch='. $batch); ?>';

      if ($('#fileupload-modal td.error').length > 0)
      {
        $('#button-fileupload').html('Finish Upload');
        $('#button-fileupload').removeClass('btn-danger');
        $('#button-fileupload').attr('href', finish);
      }
      else
      {
        window.location.href = finish;
      }
    });

  // Enable iframe cross-domain access via redirect option:
  $('#fileupload').fileupload(
    'option', 'redirect',
    window.location.href.replace(
      /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
    )
  );

  $('#fileupload').fileupload('option', {
    maxFileSize: 10000000,
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
