<footer id="footer" style="position: relative;">
  <div class="footer-inner">
    <div class="row-fluid">
      <div class="span4">
        <h2 class="FugazOne">About Collectors’ Quest</h2>
        <p>
          Collectors’ Quest is an interactive community and marketplace for the passionate collector. Collectors can meet others who share their interests, organize and catalog their collections, as well as buy, sell or trade with others...
          <a href="#">Learn more &raquo;</a>
        </p>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Contact us</button>
        </div>
        <p>Follow us on Facebook</p>
        <p>Follow us on Twitter</p>
      </div><!--/span-->

      <div class="span4">
      <?php if (!$sf_user->isAuthenticated()): ?>

        <div id="footer-form-signup">
          <h2 class="FugazOne">Sign Up</h2>

          <?= form_tag('@collector_signup', array('class' => 'form-horizontal')); ?>
            <fieldset>
              <?= new CollectorSignupStep1Form(); ?>
              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Sign Up" />
              </div>
            </fieldset>
          </form>

          <div id="footer-control-login">
            Already have an account? <?= link_to('Log In', '@login', array('id' => 'footer-control-login-button')); ?>
          </div>
        </div><!-- #footer-form-signup -->

        <div id="footer-form-login" style="display: none">
          <h2 class="FugazOne">Log In</h2>
          <?= form_tag('@login', array('class' => 'form-horizontal')) ?>
            <fieldset>
              <?= new CollectorLoginForm() ?>
              <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="Log In" />
              </div>
            </fieldset>
          </form>

          <div id="footer-control-signup" style="display: none">
            Don't have an account yet? <?= link_to('Sign up', '@collector_signup', array('id' => 'footer-control-signup-button')); ?>
          </div>
        </div> <!-- #footer-form-login -->

      <?php else: ?>
        <!-- nothing here yet -->
        <br />
      <?php endif; ?>

      </div><!-- .span4 -->

      <div class="span4">
        <ul class="footer-info-box">
          <li>
            <span class="icon_big_box"></span>
            <div class="info-box-text">
              <h2 class="FugazOne">Show Off</h2>
              <p>Share your passion with a world of interested people by organizing your collections with our easy to use tools.</p>
            </div>
          </li>
          <li>
            <span class="icon_big_piggy_bank"></span>
            <div class="info-box-text">
              <h2 class="FugazOne">Get Paid</h2>
              <p>It’s easy to sell an item once you’re a member. Just choose “I’m a seller” during the sign up process.</p>
            </div>
          </li>
          <li>
            <span class="icon_big_question"></span>
            <div class="info-box-text">
              <h2 class="FugazOne">Help/FAQ</h2>
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
