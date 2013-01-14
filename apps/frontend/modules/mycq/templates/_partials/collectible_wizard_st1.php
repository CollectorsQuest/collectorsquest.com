<?php
/**
 * @var $form CollectibleWizardStep1Form
 */

?>

<form action="<?= url_for('@mycq_collectible_create_wizard'); ?>"
      method="post" id="fileupload-wz1" class="ajax form-horizontal" enctype="multipart/form-data">
  <div id="dropzone-wz1">

    <div id="form-box">
      <div class="box">
        <?= $form ?>
        <input type="hidden" name="formats[big]" value="300x0">
        <input type="hidden" name="formats[small]" value="190x190">
        <p class="althelp fade">Drag and drop help text</p>
        <div style="clear: both; height: 1px;"></div>
      </div>
      <div class="spacer"></div>
    </div>

    <ul class="thumbnails" id="files-wz1">
      <?php foreach($form->getMultimedia() as $mm): ?>
      <li class="template-download in">
        <?php
        if ($mm instanceof iceModelMultimedia)
        {
          $multimedia = $mm;
          $f = 'mm-' . $mm->getId();
        }
        else
        {
          $multimedia = $mm->getPrimaryImage();
          $f = 'upload-' . $mm->getId();
        }
        ?>
        <div class="thumbnail">
          <i data-multimedia-id="<?= $multimedia->getId() ?>" class="icon icon-remove-sign"></i>
          <img width="300" src="<?= src_tag_multimedia($multimedia, '300x0');?>" class="multimedia big" />
          <img width="190" height="190" src="<?= src_tag_multimedia($multimedia, '19:15x60');?>"
               class="multimedia small" />
        </div>
        <input type="hidden" value="<?= $f ?>" name="collectible[files][]">
      </li>
      <?php endforeach ?>

      <li id="always-last">
        <div class="thumbnail">
          <div class="alt-view-slot">
            <i class="icon icon-plus white-alternate-view"></i>
                <span class="info-text small">
                  Alternate<br> View
                </span>
                <span class="info-text big">
                  Drag and drop your main image here from your <strong>"Uploaded&nbsp;Photos"</strong>
                  or use the <strong>Browse</strong> button on the right.
                </span>
          </div>
        </div>
      </li>

    </ul>

  </div>
</form>



<!-- The template to display files available for upload -->
<script id="template-upload-wz1" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <li class="template-upload">
    {% if (file.error) { %}
    <div class="cancel">
      <button class="icon icon-remove-sign"></button>
    </div>
    <div class="error">{%=localeC.fileupload.errors[file.error] || file.error%}</div>
    {% } else if (o.files.valid && !i) { %}
    <div class="progress progress-info progress-striped active big">
      <div class="bar" style="width:0;"></div>
    </div>
    <div class="progress progress-info progress-striped active small">
      <div class="bar" style="width:0;"></div>
    </div>
    <div class="thumbnail">
      {% if (!i) { %}
      <div class="cancel">
        <button class="icon icon-remove-sign"></button>
      </div>
      {% } %}
      <div class="preview small">
        <span></span>
      </div>
      <div class="preview big">
        <span></span>
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
    <div class="delete">
      <button class="icon icon-remove-sign"></button>
    </div>
    <div class="error">
      {%=localeC.fileupload.errors[file.error] || file.error%}
    </div>
    {% } else { %}
    {% if (file.thumbnails) { %}
    <div class="thumbnail">
      <i class="icon icon-remove-sign" data-multimedia-id="{%=file.multimediaid%}"></i>
      <img class="multimedia big" width="300" src="{%=file.thumbnails.big.src%}" />
      <img class="multimedia small" width="{%=file.thumbnails.small.width%}"
           height="{%=file.thumbnails.small.height%}" src="{%=file.thumbnails.small.src%}" />
    </div>
    <input type="hidden" name="collectible[files][]" value="upload-{%=file.donor%}" />
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


  /**
   * Tweak for file upload to support two types of previews
   */
  $.widget('blueimpCC.fileupload', $.blueimpUI.fileupload, {

    _renderPreview: function (file, node, size) {

      var that = this,
          options = this.options,
          dfd = $.Deferred();
      var popt = {
        maxWidth: options.previewMaxWidth,
        minWidth: options.previewMaxWidth,
        // maxHeight: options.previewMaxHeight,
        canvas: options.previewAsCanvas
      }
      if (size == 'small')
      {
        popt.maxWidth =  120;
        popt.maxHeight =  120;
      }
      return ((loadImage && loadImage(
          file,
          function (img) {
            if (size == 'big')
            {
              node.closest('li').find('.progress.big').css('width', $(img).attr('width'));
            }
            node.append(img);
            that._forceReflow(node);
            that._transition(node).done(function () {
              dfd.resolveWith(node);
            });
            if (!$.contains(document.body, node[0])) {
              // If the element is not part of the DOM,
              // transition events are not triggered,
              // so we have to resolve manually:
              dfd.resolveWith(node);
            }
          }, popt
      )) || dfd.resolveWith(node)) && dfd;
    },
    _renderPreviews: function (files, nodes) {

      var that = this,
          options = this.options;
      nodes.find('.preview.big span').each(function (index, element) {
        var file = files[index];
        if (options.previewSourceFileTypes.test(file.type) &&
            ($.type(options.previewSourceMaxFileSize) !== 'number' ||
                file.size < options.previewSourceMaxFileSize)) {
          that._processingQueue = that._processingQueue.pipe(function () {
            var dfd = $.Deferred();
            that._renderPreview(file, $(element), 'big').done(
                function () {
                  dfd.resolveWith(that);
                }
            );
            return dfd.promise();
          });
        }
      });
      nodes.find('.preview.small span').each(function (index, element) {
        var file = files[index];
        if (options.previewSourceFileTypes.test(file.type) &&
            ($.type(options.previewSourceMaxFileSize) !== 'number' ||
                file.size < options.previewSourceMaxFileSize)) {
          that._processingQueue = that._processingQueue.pipe(function () {
            var dfd = $.Deferred();
            that._renderPreview(file, $(element), 'small').done(
                function () {
                  dfd.resolveWith(that);
                }
            );
            return dfd.promise();
          });
        }
      });

      return this._processingQueue;
    }

  });

  /**
   * Dropbox support
   */
  var initDropbox = function()
  {
    $("#dropzone-wz1 #always-last .thumbnail").droppable(
        {
          accept: ".draggable",
          over: function(event, ui)
          {
            var $this = $(this);
            $this.addClass('ui-state-highlight');
            $this.addClass('over');
            $this.find('img').fadeTo('fast', 0)
                .show();
          },
          out: function(event, ui)
          {
            var $this = $(this);
            $this.removeClass("ui-state-highlight");
            $this.removeClass('over');
            $this.find('i.hide').hide();
            $this.find('img').fadeTo('slow', 1);
          },
          drop: function(event, ui)
          {
            var $this = $(this);
            $this.removeClass("ui-state-highlight");
            $this.removeClass('over');
            ui.draggable.draggable('option', 'revert', false);
            ui.draggable.hide();

            var $item =$('<li class="in">' +
                '<div class="thumbnail">' +
                '<i class="icon icon-remove-sign"></i>' +
                '<img width="300" src="' + ui.draggable.data('big-src') +
                '" class="multimedia big" />' +
                '<img width="190" height="190" src="' +
                ui.draggable.find('.multimedia').attr('src') + '" class="multimedia small" />' +
                '</div>' +
                '<input type="hidden" value="upload-' +  ui.draggable.data('multimedia-id') +
                '" name="collectible[files][]">' +
                '</li>');

            $item.appendTo($('#files-wz1'));
            $('i.icon-remove-sign', $item).click(function() {
              ui.draggable.show();
              $(this).closest('li').remove();
            });
            fix_main_image();
          }
        });
  }
  initDropbox();

  /**
   * This function will fix layout by changing $form_box size
   * to remove whitespaces
   */
  var fix_main_image = function()
  {
    var timeout = window.fixMainImageTimeout;

    if (timeout) {
      clearTimeout(timeout);
    }

    window.fixMainImageTimeout = setTimeout(function ()
    {
      window.fixMainImageTimeout = null;

      if ($('#files-wz1 li:not(.template-upload)').length >= 3)
      {
        $('.althelp').addClass('in');
      }
      else
      {
        $('.althelp').removeClass('in');
      }

      var $last = $('#always-last').clone();
      $('#always-last')
          .removeClass('ui-droppable')
          .remove();
      $last.appendTo($('#files-wz1'));
      initDropbox();

      var form_box_h = $('#form-box .box').outerHeight();
      var padding = 18;
      var small_image_h = 92 + padding;

      var $form_box = $('#form-box .spacer');
      //reset styles
      $('#files-wz1 li').each(function(){
        $(this).removeAttr('style');
      })

      var $main_image =  $('#files-wz1 li:first-child');
      if ($main_image.find('.error').length != 0)
      {
        //We have upload error message
        $main_image.css({"height": (form_box_h - padding)+ 'px', 'width':'300px'});
        $form_box.css({"height": '0px'});
      }
      else
      {
        var $image = $main_image.find('.thumbnail .big');
        var h = $image.height() + padding;

        if ($('#files-wz1 li').length == 1)
        {
          h = 300;
        }

        if (h > form_box_h)
        {
          h = h - form_box_h;
          while (h > small_image_h)
          {
            h = h - small_image_h;
          }
          $form_box.css('height', (h) + 'px');
        }
        else
        {
          $main_image.css('padding-bottom', (form_box_h - h) + 'px');
          $form_box.css('height', '0px');
        }

      }
    }, 100);

  };
  fix_main_image();

  $('#files-wz1').sortable({
    cursor: "move",
    placeholder: "ui-state-highlight",
    items: "li:not(#always-last, .template-upload)",
    cancel: "li:first-child",
    //     helper: "clone",
    start: function( event, ui ) {
      $(this).data('main_image_h', $('#files-wz1 li.in:first').height());
    },
    over: function( event, ui ) {
      //Set size for main image placeholder
      var h = $(this).data('main_image_h')
      if (h < $('#form-box .box').outerHeight())
      {
        h = $('#form-box .box').outerHeight() - 18;
      }
      $('#files-wz1 .ui-state-highlight').css({"height": (h - 2) + 'px', 'width': '298px'});


    },
    stop: function( event, ui ) {

      //Save sorting only if main image was changed

      if (!$(this).data('save'))
      {
        $(this).sortable( "cancel" );
      }
      fix_main_image();
    },
    sort: function( event, ui ) {
      //Set flag to save or cancel sorting
      $(this).data('save',$('#files-wz1 li').first().hasClass('ui-state-highlight'));

    }
  });


  $(document).bind('dragover', function (e)
  {
    var dropZone = $('#fileupload-wz1'),
        timeout = window.dropZoneWZ1Timeout;
    if (!timeout) {
      dropZone.addClass('in');
    } else {
      clearTimeout(timeout);
    }
    window.dropZoneWZ1Timeout = setTimeout(function () {
      window.dropZoneWZ1Timeout = null;
      dropZone.removeClass('in');
    }, 100);
  });

  // Initialize the jQuery File Upload widget:
  $('#fileupload-wz1').fileupload({
    url: '<?= url_for('@ajax_mycq?section=collectibles&page=upload'); ?>'
  });
  $('#fileupload-wz1').fileupload('option', 'autoUpload', true);
  $('#fileupload-wz1').fileupload('option', 'dropZone', $('#dropzone-wz1'));
  $('#fileupload-wz1').fileupload('option', 'filesContainer', $('#files-wz1'));
  $('#fileupload-wz1').fileupload('option', 'uploadTemplateId', 'template-upload-wz1');
  $('#fileupload-wz1').fileupload('option', 'downloadTemplateId', 'template-download-wz1');
  $('#fileupload-wz1').fileupload('option', 'previewMaxWidth', 300);
  $('#fileupload-wz1').fileupload('option', 'previewMinWidth', 300);
  $('#fileupload-wz1')
      .bind('fileuploadadd', function (e, data)
      {
        fix_main_image();
      })
      .bind('fileuploadstop', function(e, data)
      {
        fix_main_image();
      })
      .bind('fileuploadfail', function(e, data)
      {
        fix_main_image();
      });

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

  $('#dropzone-wz1 button.icon-remove-sign').live('click', function()
  {
    console.log(11);
    fix_main_image();
  });
  $('#dropzone-wz1 .template-download i.icon-remove-sign').live('click', function()
  {
    var $icon = $(this);

    $icon.hide();
    $icon.closest('li').showLoading();

    $.ajax({
      url: '<?= url_for('@ajax_mycq?section=multimedia&page=delete&encrypt=1'); ?>',
      type: 'post', data: { multimedia_id: $icon.data('multimedia-id') },
      success: function()
      {
        $icon.closest('li').hideLoading();
        $icon.closest('li').remove();
        fix_main_image();
      },
      error: function()
      {
        $icon.closest('li').hideLoading();
        $icon.show();
        fix_main_image();
      }
    });
  });

});
</script>