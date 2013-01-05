<div class="row-fluid">
  <div class="span7">
    <div id="dropzone-wz2">
      <ul class="thumbnails" id="files-wz2">
        <?php $multimedia = $collectible->getMultimedia(0, 'image', false); ?>
        <?php if (count($multimedia)): ?>
        <?php foreach ($multimedia as $image): ?>
          <li class="span3">
            <div class="thumbnail">
              <i class="icon icon-remove-sign" data-multimedia-id="<?= $image->getId() ?>"></i>
              <?= image_tag_multimedia($image, '150x150', array('width' => 92, 'height' => 92)); ?>
            </div>
          </li>
          <?php endforeach; ?>
        <?php endif; ?>

        <li class="span3" id="always-last">
          <div class="thumbnail">
            <div class="alt-view-slot">
              <i class="icon icon-plus white-alternate-view"></i>
            <span class="info-text">
              Alternate<br> View
            </span>
            </div>
          </div>
        </li>



      </ul>
    </div>
  </div>
  <div class="span5">
    <form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
          method="post" id="fileupload-wz2" class="ajax form-horizontal" enctype="multipart/form-data">
      <?= $form; ?>
      <input type="hidden" name="model" value="Collectible">
      <input type="hidden" name="set-alter" value="1">
    </form>
  </div>
</div>



<!-- The template to display files available for upload -->
<script id="template-upload-wz2" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <li class="span3 template-upload">
    <div class="thumbnail"><i class="icon icon-remove-sign" data-multimedia-id="122168"></i>
      {% if (file.error) { %}
      <div class="error">{%=localeC.fileupload.errors[file.error] || file.error%}</div>
      {% } else if (o.files.valid && !i) { %}
      <div class="preview">
        <span class="fade"></span>
      </div>

      {% } %}
    </div>
  </li>
  {% } %}

</script>

<!-- The template to display files available for download -->
<script id="template-download-wz2" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <li class="span3 template-download">
    <div class="thumbnail">
      <i class="icon icon-remove-sign" data-multimedia-id="{%=file.multimediaid%}"></i>
      {% if (file.error) { %}
      <div class="error">
        {%=localeC.fileupload.errors[file.error] || file.error%}
      </div>
      {% } else { %}
      {% if (file.thumbnail) { %}
      <img class="multimedia" width="92" height="92"  src="{%=file.thumbnail%}">

      {% } %}

      {% } %}

    </div>
  </li>
  {% } %}
</script>

<script type="text/javascript">
  window.localeC = {
    "fileupload": {
      "errors": {
        "maxFileSize": "File is too big",
        "minFileSize": "File is too small",
        "acceptFileTypes": "The file seems to be in the wrong format. " +
            "Please make sure your photo is 'GIF', 'JPEG' or 'PNG' file and try again!",
        "maxNumberOfFiles": "Sorry, you can upload only one image",
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

    var initDropbox = function()
    {
      $("#dropzone-wz2 #always-last .thumbnail").droppable(
          {
            accept: ".draggable",
            over: function(event, ui)
            {
              var $this = $(this);
              $this.addClass('ui-state-highlight');
              $this.addClass('over');
              $this.find('img').fadeTo('fast', 0)
//      $this.find('i.icon-plus')
//        .removeClass('icon-plus')
//        .addClass('icon-download-alt')
                  .show();
            },
            out: function(event, ui)
            {
              var $this = $(this);
              $this.removeClass("ui-state-highlight");
              $this.removeClass('over');
//      $this.find('i.icon-download-alt')
//        .removeClass('icon-download-alt')
//        .addClass('icon-plus');
              $this.find('i.hide').hide();
              $this.find('img').fadeTo('slow', 1);
            },
            drop: function(event, ui)
            {
              var $this = $(this);
              $this.removeClass("ui-state-highlight");
              $this.removeClass('over');
//      $this.find('i.icon-download-alt')
//        .removeClass('icon-download-alt')
//        .addClass('icon-plus');
              ui.draggable.draggable('option', 'revert', false);
              ui.draggable.hide();

              var $item =$('<li class="span3"><div class="thumbnail">'
                  + '<i class="icon icon-remove-sign" data-multimedia-id="'
                  + ui.draggable.data('multimedia-id')
                  + '"></i>'
                  + '<img class="multimedia" width="92" height="92"  src="'
                  + ui.draggable.find('.multimedia').attr('src')
                  + '">'
                  +'</div></li>');

              $item.appendTo($('#files-wz2'));
              var $last = $('#always-last').clone();
              $('#always-last').removeClass('ui-droppable').remove();
              $last.appendTo($('#files-wz2'));
              initDropbox();

              $item.showLoading();

              $.ajax({
                url: '<?= url_for('@ajax_mycq?section=collectible&page=donateImage'); ?>',
                type: 'GET',
                data: {
                  recipient_id: '<?= $collectible->getId() ?>',
                  donor_id: ui.draggable.data('collectible-id'),
                  is_primary: $this.data('is-primary')
                },
                dataType: 'json',
                success: function()
                {
                  $item.hideLoading();
                },
                error: function(data, response)
                {
                  $item.hideLoading();
                  $item.remove();
                }
              });
            }
          });
    }
    initDropbox();
//    $(document).bind('dragover', function (e)
//    {
//      var dropZone = $('#dropzone-wz1'),
//          timeout = window.dropZoneCTimeout;
//      if (!timeout) {
//        dropZone.show();
//        $('#main-image-set').hide();
//      } else {
//        clearTimeout(timeout);
//      }
//
//
//      window.dropZoneCTimeout = setTimeout(function () {
//        window.dropZoneCTimeout = null;
//        dropZone.hide();
//        $('#main-image-set').show();
//        $('#files-wz1').hide();
//      }, 100);
//    });

    // Initialize the jQuery File Upload widget:
    $('#fileupload-wz2').fileupload();
    $('#fileupload-wz2').fileupload('option', 'autoUpload', true);
    $('#fileupload-wz2').fileupload('option', 'dropZone', $('#dropzone-wz2'));
    $('#fileupload-wz2').fileupload('option', 'filesContainer', $('#files-wz2'));
    $('#fileupload-wz2').fileupload('option', 'uploadTemplateId', 'template-upload-wz2');
    $('#fileupload-wz2').fileupload('option', 'downloadTemplateId', 'template-download-wz2');
    $('#fileupload-wz2').fileupload('option', 'previewMaxWidth', 92);
    $('#fileupload-wz2').fileupload('option', 'previewMaxHeight', 92);

    $('#fileupload-wz2')
        .bind('fileuploadadd', function (e, data)
        {
          setTimeout(function ()
          {
            var $last = $('#always-last').clone();
            $('#always-last').removeClass('ui-droppable').remove();
            $last.appendTo($('#files-wz2'));
            initDropbox();
          },50 );

        }).bind('fileuploadsend', function (e, data)
        {
          var $last = $('#always-last').clone();
          $('#always-last').removeClass('ui-droppable').remove();
          $last.appendTo($('#files-wz2'));
          initDropbox();

        })
        .bind('fileuploadstop', function(e, data)
        {

        })

        .bind('fileuploadcompleted', function (e, data)
        {

          $('#files-wz1').html('').hide();

        });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload-wz2').fileupload(
        'option', 'redirect',
        window.location.href.replace(
            /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
        )
    );

    $('#fileupload-wz2').fileupload('option', {
      maxFileSize: <?= cqStatic::getPHPMaxUploadFileSize() // php file upload limit ?>,
      acceptFileTypes: /(\.|\/)(gif|jpe?g|png|bmp)$/i
    });
    $('#dropzone-wz2 .icon-remove-sign').live('click', function()
    {

      var $icon = $(this);

      $icon.hide();
      $icon.parent('div.thumbnail').showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=multimedia&page=delete&encrypt=1'); ?>',
        type: 'post', data: { multimedia_id: $icon.data('multimedia-id') },
        success: function()
        {
          $icon.parent('div.thumbnail').hideLoading();
          $icon.closest('li').remove();
        },
        error: function()
        {
          $icon.parent('div.thumbnail').hideLoading();
          $icon.show();
        }
      });
    });


  });
</script>


