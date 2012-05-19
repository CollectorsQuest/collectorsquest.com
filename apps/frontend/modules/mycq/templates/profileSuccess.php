<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li class="active">
      <a href="#tab1" data-toggle="tab">Collectibles for Sale</a>
    </li>
    <?php /*
    <li class="pull-right styles-reset">
    <span>
      <a href="#" class="add-new-items-button pull-right">&nbsp;</a>
    </span>
    </li>
    */?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
      <div class="tab-content-inner spacer-top">
        <?php
        $link = link_to(
          'View public profile &raquo;', 'collector/me/index',
          array('class' => 'text-v-middle link-align')
        );
        cq_sidebar_title('Nurz', $link, array('left' => 8, 'right' => 4));
        ?>

        <div class="form-container-center spacer-bottom-15">
          <div class="row">
            <div class="span3">
              <span class="brown-bold pull-right">Profile Photo</span>
            </div>
            <div class="span4">
              <div class="drop-zone-large">
                <a class="plus-icon-holder h-center" href="#">
                  <i class="icon-plus icon-white"></i>
                </a>
                <a class="blue-link" href="#">
                  Click to upload image<br> or choose from<br> images to the right
                </a>
              </div>
            </div>
            <div class="span7 spacer-top-thumb">
              <div class="pull-left spacer-left">
                <img alt="" src="http://placehold.it/70x70">
              </div>
              <div class="pull-left spacer-left">
                <img alt="" src="http://placehold.it/70x70">
              </div>
              <div class="pull-left spacer-left">
                <img alt="" src="http://placehold.it/70x70">
              </div>
              <div class="pull-left spacer-left">
                <img alt="" src="http://placehold.it/70x70">
              </div>
            </div>
          </div>
        </div>


        <form class="form-horizontal">
          <fieldset class="form-container-center">

            <div class="control-group">
              <label for="input01" class="control-label">Nickname</label>
              <div class="controls">
                <input type="text" id="input01" class="input-xxlarge">
              </div>
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
            <div class="form-actions">
              <button class="btn btn-primary blue-button" type="submit">Save changes</button>
              <button class="btn gray-button spacer-left">Cancel</button>
            </div>
          </fieldset>
        </form>


      </div><!-- /.tab-content-inner -->

    </div><!-- /.tab-pane -->
  </div><!-- /.tab-content -->
</div>




