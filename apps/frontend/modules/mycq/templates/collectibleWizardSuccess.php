<?php
/**
 * @var $upload_form CollectibleUploadForm
 */


if ($collectible->getMultimediaCount('image') > 0)
{
  slot(
    'mycq_dropbox_info_message',
    'To add another view of this item, drag an image
       into the "Alternate View" boxes below your main image.'
  );
}
else
{
  slot(
    'mycq_dropbox_info_message',
    'Drag a photo below to set it as the "Main Image" for this item.'
  );
}
?>

<div class="collecton-wizard" id="accordion2">
  <div class="accordion-group<?= $step == 1 ? ' active' : '' ?>">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #1
        <span class="description">
          Categorization
        </span>
      </div>
    </div>
    <div class="accordion-body collapse<?= $step == 1 ? ' in' : '' ?>">
      <div class="accordion-inner">
      <div class="row-fluid">
        <div class="span4">

          <div id="files-wz1"></div>

          <div id="dropzone-wz1" class="dropzone single-file">
            <div class="dropzone-inner">
              <div class="alt-view-slot Chivo webfont drop-help">
                <i class="icon icon-plus"></i>
                <span class="info-text">Drop file here</span>
              </div>

              <div id="main-image-set" class="image-view-slot">
                <?php
                include_component('mycq', 'collectibleMultimedia',
                  array('collectible' => $collectible, 'mainOnly' => true));
                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="span8">

          <form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
                method="post" id="fileupload-wz1" class="ajax form-horizontal" enctype="multipart/form-data">

            <?= $upload_form['thumbnail']->renderRow(); ?>

            <input type="hidden" name="model" value="<?= $model ?>">
            <input type="hidden" name="set-main" value="1">
            <?= $upload_form->renderHiddenFields(); ?>

          </form>

            <?php
            include_partial(
              'mycq/partials/collectible_wizard_st1',
              array('form' => $step1, 'upload_form' => $upload_form)
            );
            ?>

        </div>
      </div>

      <!-- The template to display files available for upload -->
      <script id="template-upload-wz1" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <div class="template-upload file-box">
          {% if (file.error) { %}
          <div class="error">{%=localeC.fileupload.errors[file.error] || file.error%}</div>
          {% } else if (o.files.valid && !i) { %}
          <div class="preview">
            <span class="fade"></span>
          </div>
          <div class="row-fluid fade">
            <div class="span9 progress progress-info progress-striped active" style="min-height:20px">
              <div class="bar" style="width:0;"></div>
            </div>
            <div class="cancel span2 pull-right">
              {% if (!i) { %}
              <button class="btn btn-warning btn-mini">
                <span>{%=locale.fileupload.cancel%}</span>
              </button>
              {% } %}
            </div>
          </div>
          {% } %}
        </div>
        {% } %}

      </script>

      <!-- The template to display files available for download -->
      <script id="template-download-wz1" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <div class="template-download file-box">
          {% if (file.error) { %}
          <div class="error">
            {%=localeC.fileupload.errors[file.error] || file.error%}
          </div>
          {% } %}
        </div>
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

          $(".chzn-select").chosen();

          $(document).bind('dragover', function (e)
          {
            var dropZone = $('#dropzone-wz1 .dropzone-inner'),
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
          $('#fileupload-wz1').fileupload();
          $('#fileupload-wz1').fileupload('option', 'autoUpload', true);
          $('#fileupload-wz1').fileupload('option', 'dropZone', $('#dropzone-wz1'));
          $('#fileupload-wz1').fileupload('option', 'filesContainer', $('#files-wz1'));
          $('#fileupload-wz1').fileupload('option', 'limitConcurrentUploads', 1);
          $('#fileupload-wz1').fileupload('option', 'maxNumberOfFiles', 1);
          $('#fileupload-wz1').fileupload('option', 'uploadTemplateId', 'template-upload-wz1');
          $('#fileupload-wz1').fileupload('option', 'downloadTemplateId', 'template-download-wz1');
          $('#fileupload-wz1').fileupload('option', 'previewMaxWidth', 294);
          $('#fileupload-wz1').fileupload('option', 'previewMaxHeight', 800);
          $('#fileupload-wz1').fileupload('option', 'prependFiles', true);
          $('#fileupload-wz1')
              .bind('fileuploadadd', function (e, data)
              {
                if ($('#files-wz1 .file-box .error').length != 0)
                {
                  $('#files-wz1 .file-box .error').closest('.file-box').remove();
                }
                $('#files-wz1').show();
                $('#dropzone-wz1 .dropzone-inner').addClass('in-progress');

                setTimeout(function ()
                {
                  if (data.isValidated)
                  {
                    $('#files-wz1 .template-upload .fade').addClass('in');
                  }
                  else
                  {
                    if ($('#files-wz1 .template-upload .preview').length == 0)
                    {
                      $('#dropzone-wz1 .dropzone-inner').removeClass('in-progress');
                      $('#fileupload-wz1').fileupload('option', 'maxNumberOfFiles', 1); //update files count
                      $('#main-image-set').load(
                          '<?= url_for('@ajax_mycq?section=component&page=collectibleMultimedia&collectible_id=' .
                            $collectible->getId() . '&mainOnly=true' ); ?>',
                          function () {
                            $('#main-image-set .main-image-set-container').hideLoading();
                          });
                    }
                  }
                }, 100);

              })
              .bind('fileuploadstop', function(e, data)
              {
                if ($('#files-wz1 .template-upload .preview').length == 0)
                {
                  $('#dropzone-wz1 .dropzone-inner').removeClass('in-progress');

                  $('#fileupload-wz1').fileupload('option', 'maxNumberOfFiles', 1); //update files count
                  $('#main-image-set .main-image-set-container').showLoading();
                  $('#main-image-set').load(
                      '<?= url_for('@ajax_mycq?section=component&page=collectibleMultimedia&collectible_id=' .
                        $collectible->getId() .  '&mainOnly=true' ); ?>',
                      function () {
                        $('#main-image-set .main-image-set-container').hideLoading();
                      });
                }
              })

              .bind('fileuploadcompleted', function (e, data)
              {
                $('#files-wz1').html('').hide();
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

        });
      </script>

      </div>
    </div>
  </div>

  <div class="button-wrapper<?= $step != 1 ? ' hide' : '' ?>">
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step1', 'data-next' => 'wz-step2'
    ));?>
  </div>



  <div class="accordion-group<?= $step == 2 ? ' active' : '' ?>">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #2
        <span class="description">
          Description
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">
        <?php
        include_partial(
          'mycq/partials/collectible_wizard_st2',
          array('form' => $step2)
        );
        ?>

      </div>
    </div>
  </div>



  <div class="button-wrapper<?= $step != 2 ? ' hide' : '' ?>">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left wz-back', 'data-target' => 'wz-step1', 'data-current' => 'wz-step2')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step2', 'data-next' => 'wz-step3'
    ));?>
  </div>



  <div class="accordion-group<?= $step == 3 ? ' active' : '' ?>">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #3
        <span class="description">
          Alternative Images
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner"  id="wz-step3">

        <?php
        include_partial(
          'mycq/partials/collectible_wizard_st3',
          array('form' => $step3, 'collectible' => $collectible)
        );
        ?>

      </div>
    </div>
  </div>



  <div class="button-wrapper<?= $step != 3 ? ' hide' : '' ?>">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left wz-back', 'data-target' => 'wz-step2', 'data-current' => 'wz-step3')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array(
      'class' => 'btn btn-primary pull-right wz-next', 'data-target' => 'wz-step3', 'data-next' => 'wz-step4'
    ));?>
  </div>


  <div class="accordion-group">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #4
        <span class="description">
          Finish
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner" id="wz-step4">

        <div class="row">
          <div class="span10 offset4 spacer-bottom-20">
            <h2>Congratulation your collectible successfully added!</h2>
          </div>
        </div>
        <div class="row spacer-bottom-15">
          <div class="span4 offset5"><p>Please choose your next action:</p></div>
        </div>
        <div class="row spacer-bottom-15">
          <div class="span4 offset5"><p>See this item like other see it</p></div>
          <div class="span3">
            <a href="<?= url_for_collectible($collectible); ?>" class="btn btn-primary">Public View</a>
          </div>
        </div>
        <div class="row spacer-bottom-15">
          <div class="span4 offset5">Other item configurations</div>
          <div class="span3">
            <a href="<?= url_for('mycq_collectible_by_slug', $collectible) ?>" class="btn btn-primary">Edit</a>
          </div>
        </div>

        <?php if ($collection = $collectible->getCollection()): ?>
          <div class="row spacer-bottom-15">
            <div class="span4 offset5">Add another item to "<?= $collection->getName() ?>" collection</div>
            <div class="span3">
              <a href="<?= url_for('@mycq_collectible_create_wizard?collection_id=' . $collection->getId()); ?>"
                 class="btn btn-primary">Add Item</a>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>








</div>
<script>
  $(document).ready(function()
  {
    $('.wz-next').click(function(e)
    {
      e.preventDefault();
      if ($(this).hasClass('disabled'))
      {
        return;
      }
      var $this = $('#' + $(this).data('target'))
      var $next = $('#' + $(this).data('next'))
      var $buttons = $(this).closest('.button-wrapper');
      $buttons.find('.btn').addClass('disabled');
      $this.closest('.accordion-inner').showLoading();


      if ($this[0].tagName.toLowerCase() == 'form')
      {
        $this.ajaxSubmit({
          dataType: 'json',
          success: function(response)
          {
            $this.closest('.accordion-inner').hideLoading();

            if (response.Success)
            {
              $buttons.addClass('hide');
              $this.closest('.collapse').collapse('hide').closest('.accordion-group').removeClass('active');
              $next.closest('.accordion-group').addClass('active').find('.collapse').collapse('show');
              $next.closest('.accordion-group').next('.button-wrapper').removeClass('hide')
                  .find('.btn').removeClass('disabled');
              $this.closest('.accordion-inner').find('.ui-droppable').each(function(){
                $(this).droppable('disable');
              });
            }
            else
            {
              $this.replaceWith($(response.form));
              $buttons.find('.btn').removeClass('disabled');
            }
          }
        });
      }
      else
      {
        $this.closest('.accordion-inner').hideLoading();
        $buttons.addClass('hide');
        $this.closest('.collapse').collapse('hide').closest('.accordion-group').removeClass('active');
        $next.closest('.accordion-group').addClass('active').find('.collapse').collapse('show');
        $next.closest('.accordion-group').next('.button-wrapper').removeClass('hide')
            .find('.btn').removeClass('disabled');
      }
    });

    $('.wz-back').click(function(e)
    {
      e.preventDefault();
      if ($(this).hasClass('disabled'))
      {
        return;
      }
      var $target = $('#' + $(this).data('target'))
      var $current = $('#' + $(this).data('current'))
      var $buttons = $(this).closest('.button-wrapper');
      $buttons.addClass('hide').find('.btn').addClass('disabled');
      $current.closest('.collapse').collapse('hide').closest('.accordion-group').removeClass('active');
      $target.closest('.accordion-group').addClass('active').find('.collapse').collapse('show');
      $target.closest('.accordion-group').next('.button-wrapper').removeClass('hide')
          .find('.btn').removeClass('disabled');
      $target.closest('.accordion-inner').find('.ui-droppable').each(function(){
        $(this).droppable('enable');
      });
    });
  });
</script>

