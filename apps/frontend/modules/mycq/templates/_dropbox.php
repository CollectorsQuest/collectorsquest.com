<?php
/**
 * @var $total integer
 * @var $batch string
 * @var $instructions array
 *
 * @var $collectibles Collectible[] | PropelObjectCollection
 */
?>

<?php slot('mycq_dropbox_instructions'); ?>
<div class="row-fluid instruction-box <?= $instructions['position']; ?>">
  <div class="span3">
    <span class="<?php echo ($instructions['position'] === 'top') ? 'gray-arrow-up' : 'gray-arrow' ; ?> pull-right">&nbsp;</span>
  </div>
  <div class="span6 hint-text">
    <?= $instructions['text'] ?>
  </div>
  <div class="span3">
    <span class="<?php echo ($instructions['position'] === 'top') ? 'gray-arrow-up' : 'gray-arrow' ; ?>">&nbsp;</span>
  </div>
</div><!-- /.instruction-box -->
<?php end_slot(); ?>

<?php
  if ($instructions['position'] === 'top' && $total > 0)
  {
    include_slot('mycq_dropbox_instructions');
  }
?>

<div class="tab-content-inner">
  <div class="row-fluid">
    <div class="span9">
      <?php
        $link = link_to(
          'Delete all Items', '@mycq_dropbox?cmd=empty&encrypt=1',
          array('style' => 'color: red;', 'class' => 'text-v-middle link-align')
        );
        cq_section_title(
          'Items to Sort ('. $total .')', $total > 0 ? $link : null,
          array('class' => 'row-fluid section-title spacer-top-20')
        );
      ?>
    </div>
    <div class="span3">
      <form action="<?= url_for('@ajax_mycq?section=collectibles&page=upload&batch='. $batch); ?>"
            id="fileupload" class="pull-right spacer-top-20"
            method="POST" enctype="multipart/form-data">

        <span class="btn btn-primary blue-button fileinput-button">
          <i class="icon-plus icon-white"></i>&nbsp;<span>Upload Items</span>
          <input type="file" name="files[]" multiple="multiple">
        </span>

        <div id="fileupload-modal" class="modal hide fade">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
            <h3>Uploading images, please wait...</h3>
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
            <div class="span9 fileupload-progress fade">
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
    </div>
  </div>

  <?php if ($total > 0): ?>
  <div class="collectibles-to-sort">
    <ul class="thumbnails">
      <?php foreach ($collectibles as $collectible): ?>
      <li class="span2 thumbnail" data-collectible-id="<?= $collectible->getId(); ?>">
        <?php
          echo image_tag_collectible(
            $collectible, '75x75', array('max_width' => 72, 'max_height' => 72)
          );
        ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php else: ?>
    There are currently no Items to Sort.
    Please use the <strong>"+ Upload Items"</strong>
    button on the right to upload all your collectibles!
  <?php endif; ?>
</div>

<?php
  if ($instructions['position'] !== 'top' && $total > 0)
  {
    include_slot('mycq_dropbox_instructions');
  }
?>

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

<script>
$(document).ready(function()
{
  $('.collectibles-to-sort li').draggable(
  {
    containment: '#content',
    scroll: false,
    handle: 'img',
    opacity: 0.7,
    revert: true,
    cursor: 'move',
    cursorAt: { top: 36, left: 36 },
    zIndex: 1000
  });
});
</script>
