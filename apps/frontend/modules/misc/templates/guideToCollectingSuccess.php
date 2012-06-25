<div class="guide-splash-container">
  <div class="wrapper-top">
    <div class="row-fluid">
      <div class="span7">
        <?php
          cq_sidebar_title(
            'Unlock your free guide to collecting—sign up today!', null,
            array('class' => 'row-fluid title-red spacer-bottom-30')
          );
        ?>
        <div class="row-fluid">
          <div class="span12 relative">
            <span class="guide-splash-page">
              <img  src="/images/frontend/misc/guide-splash-page-img.png" alt="Essential Guide to Collecting">
            </span>
            <div class="spacer-r-290">
              <p>
                <b>Quest Your Best: The Essential Guide to Collecting</b> has something for every
                collector, whether you're just beginning to acquire treasures or you're a dedicated
                hunter looking for the next, perfect addition to your display case.
              </p>
              <p>
                Sign up to become a member and get this electronic guide for free.
                It just takes a minute to complete the form on the right, and we’ll
                email you a link to get the PDF*.<br/><br/>
                * <a href="http://get.adobe.com/reader/" target="_blank">
                    Adobe Acrobat Reader required
                  </a>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="span5">
        <div class="signup-form-splash">
        <?php
          include_partial('global/footer_login_signup', array(
            'signup_form'=> $signup_form,
            'login_form' => $login_form,
            'signup_action' => '@misc_guide_to_collecting',
            'login_action' => '@misc_guide_to_collecting',
           ))
        ?>
        </div>
      </div>
    </div>
  </div>
  <div class="wrapper-bottom">
    <?php
      cq_sidebar_title(
        'Why else should you join?', null,
        array('class' => 'row-fluid section-title-yellow spacer-bottom-30')
      );
    ?>
    <div class="row-fluid">
      <div class="span4">
        <h4 class="Chivo webfont">Show off your collection</h4>
        <p>
          You bought it, now brag about it! Let other collectors see what's hiding in your closets,
          or create a digital archive to use on the go so you don't buy the same thing twice!
          (It's happened to all of us).
        </p>
      </div>

      <div class="span4">
        <h4 class="Chivo webfont">Meet other collectors</h4>
        <p>
          Find other like-minded collectors or branch out and meet new
          friends who can show you a cool thing or two.
        </p>
      </div>

      <div class="span4">
        <h4 class="Chivo webfont">Hear about the latest<br> trends in collecting</h4>
        <p>
          If it's out there, you'll hear about it here in our daily blog and video
          coverage of the latest and greatest from the collecting universe.
        </p>
      </div>

    </div>
  </div>
</div>
