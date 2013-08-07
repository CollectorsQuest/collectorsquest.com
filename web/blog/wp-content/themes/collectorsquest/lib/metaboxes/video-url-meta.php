<?php
/**
 * @var $mb WPAlchemy_MetaBox
 */
?>
<div class="my_meta_control video_url_box">

  <?php $mb->the_field('video_url'); ?>

  <label for="video_url">
    <?php _e('YouTube or Vimeo Url:'); ?>
  </label>
  <input type="url" id="video_url" name="<?php $mb->the_name(); ?>" class="video_url_input"
         value="<?php $mb->the_value(); ?>" required="required" />
  <input type="button" id="video_details" value="Get Title & Description" class="button button-primary">
  <span class="spinner"></span>
  <br />
  <img class="video_tmb" style="<?php echo ($mb->get_the_value() == '' ? ' display:none' : '') ?>"
       src="<?= video_image($mb->get_the_value()); ?>" />

  <script type="text/javascript">
    jQuery(function($){
      //Hack - to move box to top
      var $box = $("#_video_url_metabox").clone();
      $("#_video_url_metabox").remove();
      $box.insertBefore("#titlediv");

      $("#video_details").click(function(){
        $("#_video_url_metabox").find(".spinner").show();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: ajaxurl,
          data: {"action": "video_details", url: $("#video_url").val()},
          success: function(data){
            if (data.title)
            {
              $("#title").val(data.title);
              $("#title").focus();
            }
            if (data.info)
            {
              tinyMCE.activeEditor.setContent(data.info, {format : "raw"});
            }
            if (data.thumb)
            {
              $("#_video_url_metabox").find(".video_tmb").attr('src', data.thumb).show();
            }
            $("#_video_url_metabox").find(".spinner").hide();
          }
        });

        return false;
      });
    });
  </script>

</div>
