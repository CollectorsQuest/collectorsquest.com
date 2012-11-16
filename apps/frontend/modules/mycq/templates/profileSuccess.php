<?php
  /**
   * @var $collector       Collector
   * @var $collector_form  CollectorEditForm
   * @var $avatar_form     CollectorAvatarForm
   * @var $image iceModelMultimedia
   * @var $aviary_hmac_message string
   */

  // set input-xxlarge as the default class of widgets
  foreach($collector_form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }

  SmartMenu::setSelected('mycq_profile_tabs', 'personal_info');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_profile_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <?php cq_section_title('Edit Your Profile') ?>
        <form method="post" action="<?= url_for('@mycq_profile') ?>"
              class="form-horizontal" enctype="multipart/form-data">
          <?= $avatar_form->renderHiddenFields(); ?>
          <?= $avatar_form->renderGlobalErrors(); ?>

          <fieldset class="form-container-center">
            <div class="control-group">
              <label for="input01" class="control-label">Profile Photo</label>
              <div class="controls">
                <div class="row-fluid">
                  <div class="span12 spacer-bottom-20">
                    <?= $avatar_form['filename']->render(array('style' => 'width: auto;')); ?>
                    <button type="submit" class="btn btn-primary spacer-left">
                      Upload File
                    </button>
                    <?= $avatar_form['filename']->renderError(); ?>
                    <div class="help-block">
                      <strong>Note:</strong> All popular image formats are supported
                      but the image file should be less than 10MB in size!
                    </div>
                  </div>
                  <div class="span3 avatar">
                    <?php
                      echo image_tag_collector(
                        $collector, '235x315',
                        array(
                          'width' => 138, 'height' => 185,
                          'class' => 'thumbnail', 'style' => 'background: #fff;'
                        )
                      );
                    ?>
                    <?php if (!empty($aviary_hmac_message)): ?>
                      <span class="multimedia-edit holder-icon-edit"
                            data-original-image-url="<?= src_tag_multimedia($image, 'original') ?>"
                            data-post-data='<?= $aviary_hmac_message; ?>'>

                        <i class="icon icon-camera"></i><br/>
                        Edit Photo
                      </span>
                    <?php endif; ?>
                  </div>
                  <div class="span8">
                    <div class="cf spacer-bottom-15">
                      You can also choose one of these default avatars:
                    </div>

                    <?php foreach ($avatars as $id): ?>
                    <div class="avatars-suggestion">
                      <?php
                        echo cq_image_tag(
                          'frontend/multimedia/Collector/default/100x100/'. $id. '.jpg',
                          array(
                            'width' => 57, 'height' => 57, 'data-id' => $id,
                            'class' => 'thumbnail avatars', 'style' => 'background: #fff;'
                          )
                        );
                      ?>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </form>

        <form method="post" action="<?= url_for('@mycq_profile') ?>" class="form-horizontal">
          <?= $collector_form->renderHiddenFields(); ?>
          <?= $collector_form->renderAllErrors(); ?>

          <fieldset class="brown-dashes form-container-center">
            <?= $collector_form['display_name']->renderRow(); ?>
            <?= $collector_form['collector_type']->renderRow(); ?>
            <?= $collector_form['about_what_you_collect']->renderRow(); ?>
            <?= $collector_form['about_what_you_sell']->renderRow(); ?>

          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <?= $collector_form['gender']->renderRow(); ?>

            <div class="control-group">
              <label class="control-label" for="input01">Where are you from?</label>
              <div class="controls form-inline clearfix" style="width: 540px">
                <div class="pull-left">
                  <label for="<?= $collector_form['zip_postal']->renderId(); ?>">
                    <?= $collector_form['zip_postal']->renderLabelName(); ?>
                  </label>
                  <?= $collector_form['zip_postal']->render(array('class' => 'span3')); ?>
                </div>

                <div class="inset-input pull-right with-required-token">
                  <label for="<?= $collector_form['country_iso3166']->renderId(); ?>" class="spacer-left">
                    <?= $collector_form['country_iso3166']->renderLabelName(); ?>
                  </label>
                  <span class="required-token-relative">*</span>
                  <?= $collector_form['country_iso3166']->render(array('class' => 'span4')); ?>
                </div>
              </div>
            </div>

            <?= $collector_form['birthday']->renderRow(array(
              'class' => 'span2 inline',
            )); ?>
            <?= $collector_form['about_me']->renderRow(); ?>
            <?= $collector_form['about_collections']->renderRow(); ?>
            <?= $collector_form['about_interests']->renderRow(); ?>
          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button type="reset" class="btn spacer-left">Cancel</button>
            </div>
          </fieldset>
        </form> <!-- CollectorEditForm -->

      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
    <div class="tab-pane" id="tab4">
      <div class="tab-content-inner spacer">
        <?php
        $link = link_to(
          'View public profile &raquo;', 'collector/me/index',
          array('class' => 'text-v-middle link-align')
        );
        cq_section_title('Edit Your Profile Settings', $link, array('left' => 8, 'right' => 4));
        ?>
        <p>Settings Content</p>
      </div><!-- .tab-content-inner -->
    </div><!-- #tab4.tab-pane -->
  </div><!-- .tab-content -->
</div>


<script>
$(document).ready(function()
{
  $('img.avatars').click(MISC.modalConfirm(
    'Change avatar', 'Are you sure you want to change your avatar to one of our defaults?', function()
  {
    $('div.avatar').showLoading();

    var $id = $(this).data('id');

    $.ajax({
      url: '<?= url_for('@ajax_mycq?section=collector&page=avatarFromDefault'); ?>',
      type: 'get', data: { avatar_id: $id },
      success: function()
      {
        $('div.avatar img').attr('src', '/images/frontend/multimedia/Collector/default/235x315/' + $id + '.jpg');
        $('div.avatar').hideLoading();
      },
      error: function()
      {
        $('div.avatar').hideLoading();
      }
    });
  }, true));

});
</script>
