<?php
  /** @var $collector       Collector */
  /** @var $collector_form  CollectorEditForm */
  /** @var $avatar_form     CollectorAvatarForm */
  /** @var $email_form      CollectorEmailChangeForm */

  // set input-xxlarge as the default class of widgets
  foreach($collector_form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }
  // set input-xxlarge as the default class of widgets
  foreach($email_form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }

?>


<div id="mycq-tabs">
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
      <div class="tab-content-inner spacer-top">
        <br />
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
                    <button type="submit" class="btn btn-primary blue-button">Upload File</button>
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
            <?= $collector_form['about_most_expensive_item']->renderRow(); ?>
            <?= $collector_form['about_annually_spend']->renderRow(); ?>
          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <?= $collector_form['gender']->renderRow(); ?>

            <div class="control-group">
              <label class="control-label" for="input01">Where are you from?</label>
              <div class="controls form-inline" style="width: 530px">
                <div style="float: left">
                  <label for="<?= $collector_form['zip_postal']->renderId(); ?>">
                    <?= $collector_form['zip_postal']->renderLabelName(); ?>
                  </label>
                  <?= $collector_form['zip_postal']->render(array('class' => 'span3')); ?>
                </div>

                <div class="with-required-token" style="float: right">
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
            <div class="control-group">
              <label for="textarea" class="control-label">Username</label>
              <div class="controls spacer-top-5">
                <span class="brown">
                  <?= $collector->getUsername(); ?>
                </span>
              </div>
            </div>
            <?= $collector_form['old_password']->renderRow(); ?>
            <?= $collector_form['password']->renderRow(); ?>
            <?= $collector_form['password_again']->renderRow(); ?>
          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary blue-button">Save changes</button>
              <button type="reset" class="btn gray-button spacer-left">Cancel</button>
            </div>
          </fieldset>
        </form> <!-- CollectorEditForm -->

        <form action="<?= url_for('@mycq_profile'); ?>" class="form-horizontal" method="post">
          <?= $email_form->renderHiddenFields(); ?>

          <fieldset class="brown-dashes form-container-center">
            <div class="control-group row">
              <div class="offset4 span8">
                <?= $email_form->renderGlobalErrors(); ?>
              </div>
            </div>
            <div class="control-group">
              <label for="textarea" class="control-label">Current email</label>
              <div class="controls spacer-top-5">
                <span class="brown">
                  <?= $collector->getEmail(); ?>
                </span>
              </div>
            </div>
            <?= $email_form['password']->renderRow(); ?>
            <?= $email_form['email']->renderRow(); ?>
            <?= $email_form['email_again']->renderRow(); ?>
          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary blue-button">Change email</button>
              <button type="submit" class="btn gray-button spacer-left">Cancel</button>
              <div class="spacer-left-25">
                <p class="brown spacer-top spacer-left-35">
                  Your email address will not change until you confirm it via email
                </p>
              </div>
            </div>
          </fieldset>
        </form> <!-- CollectorEmailChangeForm -->


        <!-- easy comment in/out -- >
          <fieldset class="brown-dashes form-container-center">
            <div class="control-group">
              <label for="inlineCheckboxes" class="control-label">Wanted Items</label>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" checked="" value="option1" id="optionsRadios1" name="optionsRadios">
                  Display
                </label>
                <label class="radio inline">
                  <input type="radio" checked="" value="option1" id="optionsRadios1" name="optionsRadios">
                  Keep private
                </label>
              </div>
            </div>
            <div class="control-group">
              <label for="textarea" class="control-label">Username</label>
              <div class="controls spacer-top-5">
                <span class="brown">
                  cdavid
                </span>
              </div>
            </div>
            <div class="control-group">
              <label for="textarea" class="control-label">Member Since</label>
              <div class="controls spacer-top-5">
                <span class="brown">
                  December 11, 2009
                </span>
              </div>
            </div>

          </fieldset>
          <fieldset class="brown-dashes form-container-center">
            <div class="control-group">
              <label for="textarea" class="control-label">New Email</label>
              <div class="controls spacer-top-5">
                <span class="brown">
                  collin@collectorsquest.com	Confirmed
                </span>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input01">New Email</label>
              <div class="controls">
                <input type="text" class="input-xxlarge" id="input01">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input01">Confirm New Email</label>
              <div class="controls">
                <input type="text" class="input-xxlarge" id="input01">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input01">Your Password</label>
              <div class="controls">
                <input type="text" class="input-xxlarge" id="input01">
              </div>
            </div>
            <div class="form-actions">
              <button class="btn btn-primary blue-button" type="submit">Change Email</button>
              <p class="brown spacer-top">
                Your email address will not change until you confirm it via email
              </p>
            </div>
          </fieldset>
          <fieldset class="brown-dashes form-container-center">
            <div class="control-group">
              <label for="inlineCheckboxes" class="control-label">Check the subscriptions you would like to receive:</label>
              <div class="controls">
                <label class="radio">
                  <input type="radio" checked="" value="option1" id="optionsRadios1" name="optionsRadios">
                  <strong>CQ Promotions</strong>
                  <p class="help-block disabled spacer-top-reset">- the latest items for sale by our community of collectors (Published Weekly)</p>
                </label>
                <label class="radio">
                  <input type="radio" checked="" value="option1" id="optionsRadios1" name="optionsRadios">
                  <strong>CQ Newsletter</strong>
                  <p class="help-block disabled spacer-top-reset">- the latest news about our community of collectors (Published Twice a Week)</p>
                </label>
              </div>
            </div>
            <div class="form-actions">
              <button class="btn btn-primary blue-button" type="submit">Save Subscriptions</button>
            </div>
          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <div class="control-group">
              <label for="input01" class="control-label">I collect: <span class="red-bold">*</span></label>
              <div class="controls">
                <input type="text" id="input01" class="input-xxlarge">
                <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
              </div>
            </div>
            <div class="control-group">
              <label for="input01" class="two-lines control-label">What’s the most you’ve ever spent on an item?</label>
              <div class="controls">
                <input type="text" id="input01" class="input-xxlarge">
                <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
              </div>
            </div>
            <div class="control-group">
              <label for="optionsCheckbox" class="control-label">Checkbox</label>
              <div class="controls">
                <label class="checkbox">
                  <input type="checkbox" value="option1" id="optionsCheckbox">
                  Option one is this and that&mdash;be sure to include why it's great
                </label>
              </div>
            </div>
            <div class="control-group">
              <label for="optionsCheckbox" class="control-label">Checkbox</label>
              <div class="controls">
                <label class="checkbox">
                  <input type="checkbox" value="option1" id="optionsCheckbox">
                  Option one is this and that&mdash;be sure to include why it's great
                </label>
              </div>
            </div>
            <div class="control-group">
              <label for="select01" class="control-label">Select list</label>
              <div class="controls">
                <select id="select01">
                  <option>something</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label for="select01" class="control-label">Birthday</label>
              <div class="controls">
                <select name="Month" class="span2 inline">value=""
                    <option value="">Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
                <select name="Day" class="span2 inline">
                  <option value="">Day</option>
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                </select>
                <select name="Year" class="span2 inline">
                  <option value="">Year</option>
                  <option>1997</option>
                  <option>1998</option>
                  <option>1999</option>
                  <option>2000</option>
                </select>
                <span class="inline control-description-r">Year will not be displayed</span>
              </div>
            </div>

            <div class="control-group">
              <label for="multiSelect" class="control-label">Multicon-select</label>
              <div class="controls">
                <select id="multiSelect" multiple="multiple">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label for="fileInput" class="control-label">File input</label>
              <div class="controls">
                <input type="file" id="fileInput" class="input-file">
              </div>
            </div>
            <div class="control-group">
              <label for="textarea" class="control-label">Textarea</label>
              <div class="controls">
                <textarea rows="3" id="textarea" class="input-xxlarge"></textarea>
              </div>
            </div>
            <div class="control-group">
              <label for="disabledInput" class="control-label disabled">Personal Website</label>
              <div class="controls">
                <input type="text" disabled="" placeholder="This feature is only available for sellers…" id="disabledInput" class="input-xxlarge disabled">
                <p class="help-block disabled">This feature is only available for sellers…</p>
              </div>
            </div>
          </fieldset>
        <!-- -->


      </div><!-- /.tab-content-inner -->

    </div><!-- /.tab-pane -->
  </div><!-- /.tab-content -->
</div>


<script>
$(document).ready(function()
{
  $('img.avatars').click(function()
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

  });

  $('div.avatar .icon-remove-sign').click(function()
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
  });
});
</script>
