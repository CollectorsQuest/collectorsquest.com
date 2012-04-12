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
            <button class="btn btn-primary blue-button pull-left" type="submit">Contact us</button>
          </div>
          <p><a href="#"><i class="s-16-icon-facebook ico-16px-alignment"></i> Follow us on Facebook</a></p>
          <p><a href="#" ><i class="s-16-icon-twitter ico-16px-alignment"></i> Follow us on Twitter</a></p>
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
          <li class="icon_big_email">
              <p>You have in <a href="#" class="bold-links">your inbox</a></p>
          </li>
          <li class="icon_big_battery">
              <p>Your profile is 50% complete. <a href="#" class="bold-links">Add a collection</a> in minutes. (+10%)</p>
          </li>
          <li class="footer-profile-box-h-list">
            <ul class="row-fluid">
              <li class="span6 icon_big_add">
                <a href="#" class="bold-links">Upload a<br> photo</a>
              </li>
              <li class="span6 icon_big_organize">
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
            <i class="icon_big_box"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Show Off</h2>
              <p>Share your passion with a world of interested people by organizing your collections with our easy to use tools.</p>
            </div>
          </li>
          <li>
            <i class="icon_big_piggy_bank"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Get Paid</h2>
              <p>It’s easy to sell an item once you’re a member. Just choose “I’m a seller” during the sign up process.</p>
            </div>
          </li>
          <li>
            <i class="icon_big_question"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Help/FAQ</h2>
              <p>Want to know how to get more out of your membership? <a href="#">Watch</a> our helpful videos today!</p>
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
