<?php
  /** @var $collector       Collector */
  /** @var $collector_form  CollectorEditForm */
  /** @var $avatar_form     CollectorAvatarForm */

  // set input-xxlarge as the default class of widgets
  foreach($collector_form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }
?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="<?= url_for('@mycq_profile'); ?>">Personal Information</a>
    </li>
    <li>
      <a href="<?= url_for('@mycq_profile_account_info'); ?>">Account Information</a>
    </li>
    <li>
      <a href="<?= url_for('@mycq_profile_addresses'); ?>">Mailing Addresses</a>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <?php
          $link = link_to(
            'View public profile &raquo;', 'collector/me/index',
            array('class' => 'text-v-middle link-align')
          );
          cq_sidebar_title('Edit Your Profile', $link, array('left' => 8, 'right' => 4));
        ?>

        <form method="post" action="<?= url_for('@mycq_profile') ?>"
              class="form-horizontal" enctype="multipart/form-data">
          <?= $avatar_form->renderHiddenFields(); ?>
          <?= $avatar_form->renderGlobalErrors(); ?>

          <fieldset class="form-container-center">
            <div class="control-group">
              <label for="input01" class="control-label">Profile Photo</label>
              <div class="controls">
                <div class="row-fluid">
                  <div class="span12" style="margin-bottom: 20px;">
                    <?= $avatar_form['filename']; ?>
                    <button type="submit" class="btn btn-primary blue-button spacer-left">Upload File</button>
                    <?= $avatar_form['filename']->renderError(); ?>
                    <div class="help-block" style="color: grey;">
                      All popular image formats are supported but the image file should be less than 5MB in size!
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
                    <i class="icon icon-remove-sign"></i>
                  </div>
                  <div class="span8">
                    <div class="cf spacer-bottom-15">
                      You can also choose one of these default avatars:
                    </div>

                    <?php foreach ($avatars as $id): ?>
                    <div class="avatars-suggestion">
                      <?php
                        echo image_tag(
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
            <?= $collector_form['about_purchases_per_year']->renderRow(); ?>

            <div class="control-group">
              <label class="control-label" for="input01">Purchasing habbits</label>
              <div class="controls form-inline clearfix" style="width: 540px">
                <div class="inset-input pull-left">
                  <label for="<?= $collector_form['about_most_expensive_item']->renderId(); ?>">
                    <?= $collector_form['about_most_expensive_item']->renderLabelName(); ?>
                  </label>
                  <?= $collector_form['about_most_expensive_item']->render(array('class' => 'span2')); ?>
                </div>

                <div class="inset-input pull-right">
                  <label for="<?= $collector_form['about_annually_spend']->renderId(); ?>" class="spacer-left">
                    <?= $collector_form['about_annually_spend']->renderLabelName(); ?>
                  </label>
                  <?= $collector_form['about_annually_spend']->render(array('class' => 'span2')); ?>
                </div>
              </div>
            </div>
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
                  <span class="required-token">*</span>
                  <label for="<?= $collector_form['country_iso3166']->renderId(); ?>" class="spacer-left">
                    <?= $collector_form['country_iso3166']->renderLabelName(); ?>
                  </label>
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
            <?= $collector_form['website']->renderRow(); ?>
          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary blue-button">Save changes</button>
              <button type="reset" class="btn gray-button spacer-left">Cancel</button>
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
        cq_sidebar_title('Edit Your Profile Settings', $link, array('left' => 8, 'right' => 4));
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

  $('div.avatar .icon-remove-sign').click(MISC.modalConfirmDestructive(
    'Remove avatar', 'Are you sure you want to remove your avatar?', function()
  {
    var $icon = $(this);

    $('div.avatar').showLoading();

    $.ajax({
      url: '<?= url_for('@ajax_mycq?section=collector&page=avatarDelete&encrypt=1'); ?>',
      type: 'post',
      success: function()
      {
        $('div.avatar').hideLoading();
        $('div.avatar img').attr('src', '/images/frontend/multimedia/Collector/235x315.png');
      },
      error: function()
      {
        $('div.avatar').hideLoading();
      }
    });
  }, true));

});
</script>
