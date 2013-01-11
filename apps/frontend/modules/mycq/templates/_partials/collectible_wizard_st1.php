<?php
/**
 * @var $form CollectibleWizardStep1Form
 */

?>

<div id="dropzone-wz1">
  <form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
        method="post" id="fileupload-wz1" class="ajax form-horizontal" enctype="multipart/form-data">

    <?= $form ?>

    <input type="hidden" name="model" value="<?= $model ?>">



  </form>
  <ul class="thumbnails" id="files-wz1">

<!--    <li id="always-last">-->
<!---->
<!--      <div class="thumbnail">-->
<!--        <div class="alt-view-slot">-->
<!--          <i class="icon icon-plus white-alternate-view"></i>-->
<!--            <span class="info-text">-->
<!--              Alternate<br> View-->
<!--            </span>-->
<!--            <span class="info-text first">-->
<!--              Drag and drop your main image here from your <strong>"Uploaded&nbsp;Photos"</strong>-->
<!--              or use the <strong>Browse</strong> button on the right.-->
<!--            </span>-->
<!--        </div>-->
<!--      </div>-->
<!--    </li>-->
  </ul>

</div>




<!-- The template to display files available for upload -->
<script id="template-upload-wz1" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <li class="template-upload">
    {% if (file.error) { %}
    <div class="error">{%=localeC.fileupload.errors[file.error] || file.error%}</div>
    {% } else if (o.files.valid && !i) { %}
    {% if (!i) { %}
    <div class="cancel">
      <button class="icon icon-remove-sign"></button>
    </div>
    {% } %}
    <div class="thumbnail">
      <div class="preview">
        <span class="fade"></span>
      </div>
      <div class="progress progress-info progress-striped active">
        <div class="bar" style="width:0;"></div>
      </div>
    </div>
    {% } %}
  </li>
  {% } %}

</script>

<!-- The template to display files available for download -->
<script id="template-download-wz1" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <li class="template-download">
    {% if (file.error) { %}
    <div class="error">
      {%=localeC.fileupload.errors[file.error] || file.error%}
    </div>
    {% } else { %}
    <div class="cancel">
      <button class="icon icon-remove-sign"></button>
    </div>
    {% if (file.thumbnail) { %}
    <div class="thumbnail">
      <img class="multimedia" height="92" src="{%=file.thumbnail%}">
    </div>
    {% } %}
    {% } %}
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

    var fix_main_image = function(h, w)
    {
      
    }

    $('#files-wz1').sortable({
      placeholder: "ui-state-highlight",
      items: "li:not(#always-last)",
      cancel: "li:first-child",
      helper: "clone",
      stop: function( event, ui ) {
       // console.log($('#files-wz1 li').first().hasClass('ui-state-highlight'));
//        if (!ui.placeholder.is("li:last"))
        if (!$(this).data('save'))
        $(this).sortable( "cancel" );
      },
      sort: function( event, ui ) {
        $(this).data('save',$('#files-wz1 li').first().hasClass('ui-state-highlight'));
//        if (!ui.placeholder.is("li:last"))
//        $(this).sortable( "cancel" );
      }
    });


//    $(document).bind('dragover', function (e)
//    {
//      var dropZone = $('#dropzone-wz1 .dropzone-inner'),
//          timeout = window.dropZoneWZ1Timeout;
//      if (!timeout) {
//        dropZone.addClass('in');
//      } else {
//        clearTimeout(timeout);
//      }
//      window.dropZoneWZ1Timeout = setTimeout(function () {
//        window.dropZoneWZ1Timeout = null;
//        dropZone.removeClass('in');
//      }, 100);
//    });

    // Initialize the jQuery File Upload widget:
    $('#fileupload-wz1').fileupload();
    $('#fileupload-wz1').fileupload('option', 'autoUpload', true);
    $('#fileupload-wz1').fileupload('option', 'dropZone', $('#dropzone-wz1'));
    $('#fileupload-wz1').fileupload('option', 'filesContainer', $('#files-wz1'));
    $('#fileupload-wz1').fileupload('option', 'uploadTemplateId', 'template-upload-wz1');
    $('#fileupload-wz1').fileupload('option', 'downloadTemplateId', 'template-download-wz1');
    $('#fileupload-wz1').fileupload('option', 'previewMaxWidth', 294);
    $('#fileupload-wz1').fileupload('option', 'previewMaxHeight', 298);
    $('#fileupload-wz1')
        .bind('fileuploadadd', function (e, data)
        {
          if ($('#files-wz1 li').length == 0)
          {
            $('#fileupload-wz1').fileupload('option', 'previewMaxWidth', 294);
            $('#fileupload-wz1').fileupload('option', 'previewMaxHeight', 298);
          }
          else
          {
            $('#fileupload-wz1').fileupload('option', 'previewMaxWidth', 120);
            $('#fileupload-wz1').fileupload('option', 'previewMaxHeight', 120);
          }

            //move last item to end and re-init dropdox receiver
            var $last = $('#always-last').clone();
            $('#always-last')
                //.removeClass('ui-droppable')
                .remove();
            $last.appendTo($('#files-wz1'));



        });

//    $('#fileupload-wz1')
//        .bind('fileuploadadd', function (e, data)
//        {
//          if ($('#files-wz1 .file-box .error').length != 0)
//          {
//            $('#files-wz1 .file-box .error').closest('.file-box').remove();
//          }
//          $('#files-wz1').show();
//          $('#dropzone-wz1 .dropzone-inner').addClass('in-progress');
//
//          setTimeout(function ()
//          {
//            if (data.isValidated)
//            {
//              $('#files-wz1 .template-upload .fade').addClass('in');
//            }
//            else
//            {
//              if ($('#files-wz1 .template-upload .preview').length == 0)
//              {
//
//              }
//            }
//          }, 100);
//
//        })
//        .bind('fileuploadstop', function(e, data)
//        {
//          if ($('#files-wz1 .template-upload .preview').length == 0)
//          {
//
//          }
//        })
//
//        .bind('fileuploadcompleted', function (e, data)
//        {
//          if ($('#files-wz1 .file-box .error').length == 0)
//          {
//            $('#files-wz1').html('').hide();
//          }
//          {
//            $('#files-wz1 .file-box').removeAttr('style');
//          }
//        });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload-wz1').fileupload(
        'option', 'redirect',
        window.location.href.replace(
            /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
        )
    );

    $('#fileupload-wz1').fileupload('option', {
      maxFileSize: <?= cqStatic::getPHPMaxUploadFileSize() // php file upload limit ?>,
      acceptFileTypes: /(\.|\/)(gif|jpe?g|png|bmp)$/i
    });

  });
</script>