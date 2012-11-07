<?php
/**
 * @var $form  CollectionCreateForm
 * @var $model string
 * @var $collection_id integer
 */
?>

<form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
      method="post" id="fileupload-c" class="ajax form-horizontal form-modal" enctype="multipart/form-data">
<div id="fileupload-input-box">
  <h1><?= $model == 'collectible' ? 'Add a New Item' : 'Create Collection'?> - Step 1</h1>
  <?= $form['thumbnail']->renderRow(); ?>

  <input type="hidden" name="model" value="<?= $model ?>">
  <?php if ($collection_id) : ?>
  <input type="hidden" name="collection_id" value="<?= $collection_id ?>">
  <?php endif; ?>

  <div id="dropzone-wrapper" class="dropzone-container">
    <div id="dropzone1" class="collectibles-to-sort no-items-to-sort-box Chivo webfont spacer-inner">
      <span class="info-no-items-to-sort" style="text-align: center">
        &nbsp;&nbsp;<strong>Drag and drop</strong> photos from your desktop
      </span>
    </div>
  </div>
</div>

  <div id="fileupload-box" class="hide">

      <h3>Uploading file, please wait...</h3>


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

  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Next
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields(); ?>
</form>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-upload">
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
  <tr class="template-download">
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

<script type="text/javascript">
  $(document).ready(function()
  {
    'use strict';

    // Dialog should be closed only with cancel upload button
    $('.modal-body.opened').dialog2('options', {'closeOnOverlayClick': false, 'closeOnEscape': false});
    $('.modal-body.opened').parent('.modal').find('.modal-header a.close').hide();

    // Initialize the jQuery File Upload widget:
    $('#fileupload-c').fileupload({stop:function(){}});
    $('#fileupload-c').fileupload('option', 'autoUpload', true);
    $('#fileupload-c').fileupload('option', 'dropZone', $('#dropzone1'));
    $('#fileupload-c').fileupload('option', 'limitConcurrentUploads', 1);

    $('#fileupload-c')
      .bind('fileuploadadd', function (e, data) {
        // Remove already added items and show upload box
        $('.template-upload').remove();
        $('#fileupload-box').removeClass('hide');
      })
      .bind('fileuploadstart', function(e, data) {
        // Hide drop zone and file input
        $('#fileupload-input-box').addClass('hide');
        $('.modal-footer').hide();
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
      })
      .bind('fileuploadcompleted', function (e, data)
        {
          // Destroy file upload and show dialog with next step
          var options = {
            modal: true
          };
          options.content = data.result[0].redirect;
          $('#fileupload-c').stop();
          $('#fileupload-c').fileupload('destroy');
          $('.modal-body.opened').dialog2('close');
          $("<div></div>").dialog2(options);
        });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload-c').fileupload(
      'option', 'redirect',
      window.location.href.replace(
        /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
      )
    );

    $('#fileupload-c').fileupload('option', {
      maxFileSize: 10000000,
      acceptFileTypes: /(\.|\/)(gif|jpe?g|png|bmp)$/i
    });

    // Load existing files:
    $('#fileupload-c').each(function () {
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
