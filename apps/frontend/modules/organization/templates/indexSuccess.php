<?php
  /* @var $organization Organization */
  /* @var $sf_user cqFrontendUser */
?>

<div class="row-fluid header-bar">
    <div class="span9">
        <h1 class="Chivo webfont" style="margin-left: 145px;" itemprop="name">
          <?= $organization->getName(); ?>
        </h1>
    </div>
</div>

<div id="public-profile-info">
  <div class="row-fluid">
    <div class="span9">
      <div class="row-fluid profile-info">
        <div class="span4 thumbnail profile-avatar">
          <?php
            echo image_tag_multimedia($organization->getMultimediaByRole(
                OrganizationPeer::MULTIMEDIA_ROLE_PROFILE
              ),
              '235x315',
              array('max_width' => 138, 'max_height' => 185, 'itemprop' => 'image')
            );
          ?>
        </div>
        <div class="span8 spacer-inner-top" itemprop="jobTitle">

        </div>
      </div>
    </div>
    <div class="span3">
      <span class="stat-area spacer-bottom-20 spacer-inner-bottom-5">
      <?php
        echo format_number_choice(
          '[0] No <span>MEMBERS</span>|[1] 1 <span>MEMBER</span>|(1,+Inf] %1% <span>MEMBERS</span>',
          array('%1%' => $organization->countCollectors()),
          $organization->countCollectors()
        );
      ?>
      </span>
      <span class="stat-area spacer-inner-bottom-5">
      <?php
        echo format_number_choice(
          '[0] No <span>COLLECTIONS</span>|[1] 1 <span>COLLECTION</span>|(1,+Inf] %1% <span>COLLECTIONS</span>',
          array('%1%' => 0),
          0
        );
      ?>
      </span>
    </div>
  </div>
</div>