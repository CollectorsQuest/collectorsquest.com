<div class="footer-user-container">
  <div id="footer-user-info">
    <h3 class="Chivo webfont no-margin-bottom">
      Welcome back, <span class="collector-name"></span>
    </h3>
    <ul class="footer-profile-box cf">
      <li class="footer-pm-box">
        <span class="big-email-icon">
          <span class="pm-counter">
            &#8734; <!-- infinity! -->
          </span>
        </span>
        <p>
          You have <span class="pm-counter-word"></span>
          in <?= link_to('your inbox', '@messages_inbox?ref=' . cq_link_ref('footer'),
            array('class' => 'bold-links')); ?>
        </p>
      </li>
      <li class="icon-big-battery profile-hints">
        <p>
          Your profile is <span class="profile-completed"></span>% complete.
          <span class="profile-hint"></span>
        </p>
      </li>
    </ul>
  </div>
  <ul class="footer-profile-box cf">
    <li class="footer-profile-box-h-list spacer-inner-top-reset">
      <ul class="row-fluid">
        <li class="span6 add-collectible-img link">
          <a href="<?= url_for('@mycq_collections?ref='. cq_link_ref('footer'), true) ?>" class="bold-links target">
            Upload<br> an item
          </a>
        </li>
        <li class="span6 organize-collection link">
          <a href="<?= url_for('@mycq_collections?ref='. cq_link_ref('footer'), true) ?>#my-collections" class="bold-links target">
            Organize your<br> collections
          </a>
        </li>
      </ul>
    </li>
  </ul>

  <div class="row-fluid spacer-inner-top">
    <div class="span12">
      <a href="<?= url_for('@mycq_profile?ref='. cq_link_ref('footer'), true); ?>" class="btn btn-primary">
        My Profile
      </a>
      <b>
        <?php
         echo link_to(
           'Log out', '@logout?ref='. cq_link_ref('footer'),
           array('class' => 'spacer-left logout-link')
         );
        ?>
      </b>
    </div>
  </div>
</div>
