<div class="row-fluid">
  <form class="form-horizontal spacer-bottom-reset">
  <div class="span3">
    <div class="drop-zone-large">
      <a class="plus-icon-holder h-center" href="#">
        <i class="icon-plus icon-white"></i>
      </a>
      <a class="blue-link" href="#">
        Click to Add Main<br>Thumbnail or Drag and Drop<br>from Collection
      </a>
    </div>
    <ul class="stat">
      <li>
        XXX Views
      </li>
      <li>
        XXX Items
      </li>
    </ul>
  </div>
  <div class="span9">
    <?php
    $link = link_to(
      'View collection page &raquo;', 'collector/me/index',
      array('class' => 'text-v-middle link-align')
    );
    cq_sidebar_title('Peecol Figures by eboy', $link, array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title'));
    ?>


      <fieldset>

        <div class="control-group">
          <label class="control-label" for="input01">Nickname</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="input01">
          </div>
        </div>

        <div class="control-group">
          <label class="two-lines control-label" for="input01">Collection Name</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="input01">
            <p class="help-block">Choose at least three descriptive words for your store, separated by commas.</p>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="textarea">Collection Description</label>
          <div class="controls">
            <textarea class="input-xlarge" id="textarea" rows="3"></textarea>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="textarea">Permalink</label>
          <div class="controls spacer-top-5">
            <span class="brown">http://collectorsquest.com/collection/542698</span>
          </div>
        </div>
      </fieldset>

  </div>
  <div class="row-fluid">
    <div class="span12">
      <div class="form-actions text-center">
        <button class="btn btn-primary blue-button" type="submit">Save changes</button>
        <button class="btn gray-button spacer-left">Cancel</button>
      </div>
    </div>
  </div>
  </form>
</div>

