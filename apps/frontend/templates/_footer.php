<?php
  use_javascripts_for_form($signup_form);
?>

<footer id="footer">
  <div class="footer-inner">
    <div class="row-fluid">
      <div class="span4">
        <div class="aboutus-footer-inner">
          <h2 class="Chivo webfont">About Collectors’ Quest</h2>
          <p>
            Collectors’ Quest is an interactive community and marketplace for the passionate collector. Collectors can meet others who share their interests, organize and catalog their collections, as well as buy, sell or trade with others...
            <a href="#">Learn more</a>
          </p>
          <div class="row-spacing-footer">
            <button class="btn btn-primary blue-button pull-left" type="submit">Contact Us</button>
          </div>
          <p>
            <a href="http://facebook.com/Collectors.Quest" target="_blank">
              <i class="s-16-icon-facebook social-ico-padding"></i>Follow us on Facebook
            </a>
          </p>
          <p>
            <a href="http://twitter.com/CollectorsQuest" target="_blank">
              <i class="s-16-icon-twitter social-ico-padding"></i>Follow us on Twitter
            </a>
          </p>
          <p>
            <a href="http://pinterest.com/CollectorsQuest" target="_blank">
              <i class="s-16-icon-pinterest social-ico-padding"></i>Follow us on Pinterest
            </a>
          </p>
        </div>
      </div><!-- .span4 -->

      <div class="span4">
        <?php if (!$sf_user->isAuthenticated()): ?>

        <div id="footer-form-signup">
          <h2 class="Chivo webfont">Sign Up</h2>

          <form action="<?= url_for('@collector_signup'); ?>" method="post" class="form-horizontal form-footer">
            <?= $signup_form->renderUsing('BootstrapWithRowFluid'); ?>
            <div class="row-fluid row-spacing">
              <div class="span9 top-padding-10">
                <?php include_partial('global/footer_signup_external_buttons'); ?>
              </div>
              <div class="span3">
                <button type="submit" class="btn btn-primary blue-button pull-right">Submit</button>
              </div>
            </div>
          </form>

          <div id="footer-control-login">
            <span class="pull-right">
              Already have an account? <?= link_to('Log In', '@login', array('id' => 'footer-control-login-button')); ?>
            </span>
          </div>
        </div><!-- #footer-form-signup -->

        <div id="footer-form-login" style="display: none">
          <h2 class="Chivo webfont">Log In</h2>
          <form action="<?= url_for('@login'); ?>" class="form-horizontal form-footer" method="post">
            <?= $login_form->renderUsing('BootstrapWithRowFluid') ?>
            <div class="row-fluid row-spacing">
              <div class="span8 top-padding-10">
                <?php include_partial('global/footer_signup_external_buttons'); ?>
              </div>
              <div class="span4">
                <button type="submit" class="btn btn-primary blue-button pull-right">Log&nbsp;In</button>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span12">
                <span class="pull-right"><?= link_to('Forgot your password?', '@recover_password'); ?></span>
              </div>
            </div>
          </form>

          <div id="footer-control-signup" style="display: none">
            <span class="pull-right">
              Don't have an account yet? <?= link_to('Sign up', '@collector_signup', array('id' => 'footer-control-signup-button')); ?>
            </span>
          </div>
        </div> <!-- #footer-form-login -->

        <?php else: ?>
        <h2 class="Chivo webfont no-margin-bottom">Welcome back, <?= $sf_user->getCollector()->getDisplayName() ?>!</h2>
        <ul class="footer-profile-box cf">
          <li class="icon-big-email">
            <p>You have 1 message in <a href="#" class="bold-links">your inbox</a></p>
          </li>
          <li class="icon-big-battery">
            <p>Your profile is 50% complete. <a href="#" class="bold-links">Add a collection</a> in minutes. (+10%)</p>
          </li>
          <li class="footer-profile-box-h-list">
            <ul class="row-fluid">
              <li class="span6 upload-photo">
                <a href="#" class="bold-links">Upload a<br> photo</a>
              </li>
              <li class="span6 organize-collection">
                <a href="#" class="bold-links">Organize your<br> collection</a>
              </li>
            </ul>
          </li>
        </ul> <!-- .footer-pofile-box -->

        <div class="row-fluid top-padding-10">
          <div class="span12">
            <button class="btn btn-primary blue-button" type="submit">My Profile</button>
            <b><?= link_to('Log out', '@logout', array('class' => 'spacing-left')); ?></b>
          </div>
        </div>
        <?php endif; ?>
      </div><!-- .span4 -->

      <div class="span4">
        <ul class="footer-info-box">
          <li>
            <i class="big-box-icon"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Show Off</h2>
              <p>
                Show your collections to the world! Upload and organize your stuff here.<br/>
                <?= link_to('Show&nbsp;Off&nbsp;Now', '@collector_signup'); ?>
              </p>
            </div>
          </li>
          <li>
            <i class="big-piggy-bank-icon"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Get Paid</h2>
              <p>
                Do you have something you'd like to sell?
                It's easy! Become a member of Collectors Quest and get started.<br/>
                <?= link_to('Get&nbsp;Paid&nbsp;Now', '@seller_signup'); ?>
              </p>
            </div>
          </li>
          <li>
            <i class="big-question-icon"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Help / FAQ</h2>
              <p>
                Have a question or a concern? Having trouble figuring something out?
                Get the most out of the site by checking out our FAQs.<br/>
                <a href="#">Get&nbsp;Help&nbsp;Now!</a>
              </p>
            </div>
          </li>
        </ul>
      </div><!--/span-->
    </div><!--/row-->
  </div><!--/footer-inner-->
  <a id="top-link" href="#" class="btn btn-large sticky">
    <i class="icon-arrow-up"></i> Scroll<br/> to Top
  </a>
</footer>
