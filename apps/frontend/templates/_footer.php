<?php
  use_javascripts_for_form($signup_form);

  $unread_messages = $sf_user->getUnreadMessagesCount();
?>

<footer id="footer">
  <div class="footer-inner">
    <div class="row-fluid">
      <div class="span4">
        <div class="aboutus-footer-inner">
          <h2 class="Chivo webfont">About Collectors Quest</h2>
          <p class="about-us">
            Collectors Quest is here to help you get the most out of your
            collections: post a gallery of your neat stuff to share and use as an archive,
            buy and sell treasures quickly and easily, learn what’s going on in the collecting
            world, and meet other like-minded collectors.
            <?= link_to('Join us', '@collector_signup') ?> to make collecting more fun than ever!
          </p>
          <div class="row-spacing-footer">
            <button class="btn btn-primary blue-button pull-left" type="submit" onclick="location.href='<?=url_for('blog_page', array('slug' => 'contact-us'), true);?>'">Contact Us</button>
          </div>
          <p>
            <a href="http://www.facebook.com/pages/Collectors-Quest/119338990397"
               target="_blank" class="social-link" title="Follow us on Facebook">
              <i class="s-16-icon-facebook social-ico-padding"></i>Follow us on Facebook
            </a>
          </p>
          <p>
            <a href="http://twitter.com/CollectorsQuest"
               target="_blank" class="social-link" title="Follow us on Twitter">
              <i class="s-16-icon-twitter social-ico-padding"></i>Follow us on Twitter
            </a>
          </p>
          <p>
            <a href="http://pinterest.com/CollectorsQuest"
               target="_blank" class="social-link" title="Follow us on Pinterest">
              <i class="s-16-icon-pinterest social-ico-padding"></i>Follow us on Pinterest
            </a>
          </p>
        </div>
      </div><!-- .span4 -->

      <div class="span4">
        <?php if (!$sf_user->isAuthenticated()): ?>

        <div id="footer-form-signup">
          <h2 class="Chivo webfont">Sign Up</h2>

          <form action="<?= url_for('@collector_signup', true); ?>" method="post" class="form-horizontal form-footer">
            <?= $signup_form->renderUsing('BootstrapWithRowFluid'); ?>
            <div class="row-fluid spacer-7">
              <div class="span9 spacer-inner-top">
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
          <form action="<?= url_for('@login', true); ?>" class="form-horizontal form-footer" method="post">
            <?= $login_form->renderUsing('BootstrapWithRowFluid') ?>
            <div class="row-fluid spacer-7">
              <div class="span8 spacer-inner-top">
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
          <li class="footer-pm-box">
            <span class="big-email-icon">
              <span class="pm-counter">
                <?php if ($unread_messages < 1000): ?>
                  <?= $unread_messages; ?>
                <?php else: ?>
                  &#8734; <!-- infinity! -->
                <?php endif; ?>
              </span>
            </span>
            <p>
              You have <?= format_number_choice('[0]no messages|[1]1 message|(1, +Inf]%count% messages',
                array('%count%' => $unread_messages), $unread_messages); ?>
              in <?= link_to('your inbox', '@messages_inbox', array('class' => 'bold-links')); ?>
            </p>
          </li>
          <!--
          <li class="icon-big-battery">
            <p>Your profile is 50% complete. <a href="#" class="bold-links">Add a collection</a> in minutes. (+10%)</p>
          </li>
          //-->
          <li class="footer-profile-box-h-list" style="padding-top: 0;">
            <ul class="row-fluid">
              <li class="span6 add-collectible-img link">
                <a href="<?= url_for('@mycq_collections', true) ?>" class="bold-links target">
                  Upload<br> an item
                </a>
              </li>
              <li class="span6 organize-collection link">
                <a href="<?= url_for('@mycq_collections', true) ?>#my-collections" class="bold-links target">
                  Organize your<br> collections
                </a>
              </li>
            </ul>
          </li>
        </ul> <!-- .footer-pofile-box -->

        <div class="row-fluid spacer-inner-top">
          <div class="span12">
            <a href="<?= url_for('@mycq', true); ?>" class="btn btn-primary blue-button">
              My Profile
            </a>
            <b><?= link_to('Log out', '@logout', array('class' => 'spacer-left logout-link')); ?></b>
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
                <?= link_to('Show&nbsp;Off&nbsp;Now!', '@collector_signup'); ?>
              </p>
            </div>
          </li>
          <?php /**
          <li>
            <i class="big-piggy-bank-icon"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Get Paid</h2>
              <p>
                Do you have something you'd like to sell?
                It's easy! Become a member of Collectors Quest and get started.<br/>
                <?= link_to('Get&nbsp;Paid&nbsp;Now!', '@seller_packages'); ?>
              </p>
            </div>
          </li>
          **/ ?>
          <li>
            <i class="big-question-icon"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Help / FAQ</h2>
              <p>
                Have a question or a concern? Having trouble figuring something out?
                Get the most out of the site by checking out our FAQs.<br/>
                <a href="<?=urldecode(url_for('blog_page', array('slug' => 'cq-faqs/general-questions'), true))?>">Get&nbsp;Help&nbsp;Now!</a>
              </p>
            </div>
          </li>
        </ul>
      </div><!--/span-->
    </div><!--/row-->
  </div><!--/footer-inner-->

  <!--
    <a id="top-link" href="#" class="btn btn-large sticky">
      <i class="icon-arrow-up"></i> Scroll<br/> to Top
    </a>
  //-->
</footer>
