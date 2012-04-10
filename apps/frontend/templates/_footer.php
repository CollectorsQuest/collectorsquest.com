<footer id="footer">
  <div class="footer-inner">
    <div class="row-fluid">
      <div class="span4">
        <div class="aboutus-footer-inner">
          <h2 class="Chivo">About Collectors’ Quest</h2>
          <p>
            Collectors’ Quest is an interactive community and marketplace for the passionate collector. Collectors can meet others who share their interests, organize and catalog their collections, as well as buy, sell or trade with others...
            <a href="#">Learn more</a>
          </p>
          <div class="row-spacing-footer">
            <button class="btn btn-primary blue-button pull-left" type="submit">Contact us</button>
          </div>
          <p><a href="#"><i class="s-16-icon-facebook ico-16px-alignment"></i> Follow us on Facebook</a></p>
          <p><a href="#" ><i class="s-16-icon-twitter ico-16px-alignment"></i> Follow us on Twitter</a></p>
        </div>
      </div><!-- .span4 -->

      <div class="span4">

        <?php if (!$sf_user->isAuthenticated()): ?>

        <div id="footer-form-signup">
          <h2 class="Chivo">Sign Up</h2>

          <form action="<?= url_for('@collector_signup'); ?>" class="form-horizontal form-footer">
            <?= $signup_form->renderUsing('BootstrapWithRowFluid'); ?>
            <div class="row-fluid row-spacing">
              <div class="span9 top-padding-10">
                Sign up using&nbsp;
                <a href="#" rel="tooltip" title="Sign up using facebook" class="s-16-icon-facebook">
                  <i class="hide-text">Sign up using facebook</i>
                </a>
                <a href="#" title="Sign up using twitter" class="s-16-icon-twitter">
                  <i class="hide-text">Sign up using twitter</i>
                </a>
                <a href="#" title="Sign up using google" class="s-16-icon-google">
                  <i class="hide-text">Sign up using google</i>
                </a>
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
          <h2 class="Chivo">Log In</h2>
          <form action="<?= url_for('@login'); ?>" class="form-horizontal form-footer">
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
        <h2 class="Chivo">Welcome back, <?= $sf_user->getCollector()->getDisplayName() ?>!</h2>
        <ul class="footer-profile-box">
          <li class="icon_big_email">
              <p>You have in <a href="#" class="bold-links">your inbox</a></p>
          </li>
          <li class="icon_big_battery">
              <p>Your profile is 50% complete. <a href="#" class="bold-links">Add a collection</a> in minutes. (+10%)</p>
          </li>
          <li>
            <div class="row-fluid">
              <div class="span6 icon_big_add">
                  <p><a href="#" class="bold-links">Upload<br> a photo</a></p>
              </div>
              <div class="span6 icon_big_organize">
                  <p><a href="#" class="bold-links">Organize your<br> collection</a></p>
              </div>
            </div>
          </li>
        </ul> <!-- .footer-pofile-box -->

        <div class="row-fluid top-padding-10">
          <div class="span12">
            <button class="btn btn-primary blue-button" type="submit">My Profile</button>
            <?= link_to('Log out', '@logout'); ?>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- .span4 -->

      <div class="span4">
        <ul class="footer-info-box">
          <li>
            <i class="icon_big_box"></i>
            <div class="info-box-text">
              <h2 class="Chivo">Show Off</h2>
              <p>Share your passion with a world of interested people by organizing your collections with our easy to use tools.</p>
            </div>
          </li>
          <li>
            <i class="icon_big_piggy_bank"></i>
            <div class="info-box-text">
              <h2 class="Chivo">Get Paid</h2>
              <p>It’s easy to sell an item once you’re a member. Just choose “I’m a seller” during the sign up process.</p>
            </div>
          </li>
          <li>
            <i class="icon_big_question"></i>
            <div class="info-box-text">
              <h2 class="Chivo">Help/FAQ</h2>
              <p>Want to know how to get more out of your membership? <a href="#">Watch</a> our helpful videos today!</p>
            </div>
          </li>
        </ul>
      </div><!--/span-->
    </div><!--/row-->
  </div><!--/footer-inner-->

  <div id="footer-links">
    <div class="footer-links-inner">
      <div class="row-fluid">
        <div class="span7">
          <ul role="footer-links">
            <li><?= link_to('About Us', '@page?slug=about'); ?></li>
            <li><?= link_to('Contact', '@page?slug=contact-us'); ?></li>
            <li><?= link_to('Terms', '@page?slug=terms-and-conditions'); ?></li>
            <li><?= link_to('RSS', '@page?slug=rss-feeds'); ?></li>
            <li><?= link_to('Help/FAQ', '@page?slug=rss-feeds'); ?></li>
            <li><?= link_to('Report an Error', '@feedback', array('target' => '_blank', 'style' => 'color: red;')); ?></li>
          </ul>
        </div>
        <div class="span5 text-right">
          <?= link_to('CollectorsQuest.com', '@homepage', array('title' => 'Interactive community and marketplace for the collectible community', 'style' => 'text-decoration: none;')); ?>
          © <?= date('Y'); ?> All rights reserved &nbsp; • &nbsp; <a href="http://nytm.org/made" title="Made in NY">Made by hand in NY</a>
        </div>
      </div>
    </div>
  </div>

  <a id="top-link" href="#" class="btn btn-large sticky">
    <i class="icon-arrow-up"></i> Scroll<br/> to Top
  </a>
</footer>
