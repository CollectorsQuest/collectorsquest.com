<?php
/**
 * @var $collector Collector
 * @var $sf_params sfParameterHolder
 */
?>
<div class="slot1-inner-mycq">
  <div class="row-fluid">
    <div class="span10">
      <?php
        $links = link_to('Log Out', '@logout', array('class'=>'logout-link')) .
                 '<span style="color: #fff;">&nbsp; | &nbsp;</span>'.
                 link_to('View Public Profile →', '@collector_me');

        cq_page_title($collector->getDisplayName(), $links);
      ?>

      <div id="profile-subnavbar" class="navbar">
        <div class="navbar-inner">
          <div class="container">
            <div class="nav-collapse">
              <ul class="nav">
                <?php
                  $active = in_array($sf_params->get('action'), array('profile')) ? 'active' : null;
                  echo '<li class="'. $active .'">', link_to('Profile', '@mycq_profile'), '</li>';
                ?>
                <?php
                  $active = in_array($sf_params->get('action'), array('collections', 'collection', 'collectible')) ? 'active' : null;
                  echo '<li class="'. $active .'">', link_to('Collections', '@mycq_collections'), '</li>';
                ?>
                <?php
                  if (IceGateKeeper::open('mycq_marketplace'))
                  {
                    $active = in_array($sf_params->get('action'), array('marketplace')) ? 'active' : null;
                    $active = in_array($sf_params->get('module'), array('seller')) ? 'active' : $active;
                    echo '<li class="'. $active .'">', link_to('Store <sup>βeta</sup>', '@mycq_marketplace'), '</li>';
                  }
                ?>
                <?php
                  $active = in_array($sf_params->get('module'), array('messages')) ? 'active' : null;
                  $text = sprintf('Messages (%s)', $sf_user->getUnreadMessagesCount());
                  echo '<li class="'. $active .'" style="border-right: 1px solid #4B3B3B;">', link_to($text, '@messages_inbox'), '</li>';
                ?>
                <?php
                  // $active = in_array($sf_params->get('action'), array('wanted')) ? 'active' : null;
                  // echo '<li class="'. $active .'" style="border-right: 1px solid #4B3B3B;">', link_to('Wanted', '@mycq_wanted'), '</li>';
                ?>
              </ul>
            </div><!-- /.nav-collapse -->
          </div>
        </div><!-- /navbar-inner -->
      </div>
    </div>
    <div class="span2 upload-items-wrapper">
      <a href="#" class="upload-items-button" title="Upload item">
        <i class="icon-plus icon-white"></i> upload items
      </a>
    </div>
  </div>

  <div id="dropzone-wrapper" class="dropzone-container">
    <div class="row-fluid sidebar-title">
      <div class="span8">
      <h3 class="Chivo webfont">Edit Your Profile</h3>
    </div>
      <div class="span4">
        <ul class="h-links-small pull-right">
          <li>
            <a href="#">
              View Demo
            </a>
          </li>
          <li>
            <a href="#">
              Help
            </a>
          </li>
        </ul>
        <a href="#"
           onclick="return confirm(&quot;Are you sure you want to delete all Items to Sort?&quot;)"
           class="btn btn-mini pull-right spacer-right">
          <i class="icon-trash"></i> Delete all Items
        </a>
      </div>
    </div>
    <div class="collectibles-to-sort" id="dropzone">
      <ul class="thumbnails">
        <li  class="span2 thumbnail draggable ui-draggable">
          <img width="72" height="72" alt="" src="http://placehold.it/260x180">
          <i class="icon icon-remove-sign"></i>
        </li>
        <li  class="span2 thumbnail draggable ui-draggable">
          <img width="72" height="72" alt="" src="http://placehold.it/260x180">
          <i class="icon icon-remove-sign"></i>
        </li>
      </ul>
    </div>
  </div>
  <a href="#" class="dropzone-container-slide pull-right">
    <span class="close-dropzone">Open Items to sort <i class="icon-caret-down"></i></span>
    <span class="open hidden">Close Items to sort <i class="icon-caret-up"></i></span>
  </a>
  </a>
</div>

<script>
  $(document).ready(function() {
    $(".dropzone-container-slide").click(function() {
      $("#dropzone-wrapper").slideToggle("slow");
      $(this).find('span').toggleClass('hidden');
      return false;
    });
  });
</script>


