<?php
/**
 * @var $collectible Collectible
 */
?>

<form action="<?= url_for('mycq_collectible_by_slug', $collectible); ?>"
      method="post" class="form-horizontal">

  <div class="row-fluid">
    <div class="span4">
      <div id="main-image-set">
        <div class="main-image-set-container">
          <ul class="thumbnails">
            <li class="span12">
              <div class="thumbnail">
                <i class="icon icon-download-alt drop-zone-large ui-droppable"></i>
                <span class="info-text">
                   Drag and drop the main image<br> of your collectible here.
                </span>
              </div>
            </li>
            <li class="span4">
              <div class="thumbnail">
                <i class="icon icon-download-alt drop-zone ui-droppable"></i>
              </div>
            </li>
            <li class="span4">
              <div class="thumbnail">
                <i class="icon icon-download-alt drop-zone ui-droppable"></i>
              </div>
            </li>
            <li class="span4">
              <div class="thumbnail">
                <i class="icon icon-download-alt drop-zone ui-droppable"></i>
              </div>
            </li>
          </ul>
        </div>
      </div>

    </div><!-- ./span4 -->
    <div class="span8">
      <?php
        $link = link_to(
          'View public Collectible page &raquo;',
          'collectible_by_slug', array('sf_subject' => $collectible),
          array('class' => 'text-v-middle link-align')
        );
        cq_sidebar_title(
          $collectible->getName(), $link,
          array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
        );
      ?>

      <?= $form; ?>
    </div><!-- ./span8 -->

    <div class="row-fluid">
      <div class="span12">

        <div class="form-actions text-center spacer-inner-15">
          <a href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $collectible, 'cmd' => 'delete', 'encrypt' => '1')); ?>"
             class="btn red-button spacer-left pull-left spacer-left"
             onclick="return confirm('Are you sure you want to delete this Collectible?');">
            Delete Collectible
          </a>
          <button type="submit" class="btn btn-primary blue-button">Save changes</button>
          <button class="btn gray-button spacer-left">Cancel</button>
        </div>

      </div>
    </div>
  </div>
</form>




<script type="text/javascript">
$(document).ready(function()
{
  $(".chzn-select").chosen();

  $('input.tag').tagedit({
    autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
    // return, comma, semicolon
    breakKeyCodes: [ 13, 44, 59 ]
  });

  $('#collectible_description').wysihtml5({
    "font-styles": false, "image": false, "link": false
  });
});
</script>
