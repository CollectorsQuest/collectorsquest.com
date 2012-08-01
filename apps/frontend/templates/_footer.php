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
            <?php
              echo link_to(
                'Contact Us', 'blog_page', array('slug' => 'contact-us'),
                array('class' => 'btn btn-primary pull-left')
              );
            ?>
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
      </div>
      <!-- .span4 -->

      <div class="span4">
        <?php
          if (!$sf_user->isAuthenticated())
          {
//            include_partial(
//              'global/footer_login_signup',
//              array('login_form' => $login_form, 'signup_form' => $signup_form)
//            );

            echo link_to(
              image_tag('banners/2012-06-24_CQGuide_300x250_footer.png',
                array('size' => '300x250',
                      'alt' => 'Unlock your free guide to collecting—sign up today'
                )),
              '@misc_guide_to_collecting'
            );
          }
          else
          {
            include_partial(
              'global/footer_authenticated',
              array('collector' => $sf_user->getCollector())
            );
          }
        ?>
      </div>
      <!-- .span4 -->

      <div class="span4">
        <ul class="footer-info-box">
          <li>
            <i class="big-box-icon"></i>
            <div class="info-box-text">
              <h2 class="Chivo webfont">Show Off</h2>
              <p>
                Show your collections to the world! Upload and organize your stuff here.<br />
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
                Get the most out of the site by checking out our FAQs.<br />
                <a href="<?=urldecode(url_for('blog_page', array('slug' => 'cq-faqs/general-questions'), true))?>">Get&nbsp;Help&nbsp;Now!</a>
              </p>
            </div>
          </li>
        </ul>
      </div>
      <!--/span-->
    </div>
    <!--/row-->
  </div>
  <!--/footer-inner-->

  <!--
    <a id="top-link" href="#" class="btn btn-large sticky">
      <i class="icon-arrow-up"></i> Scroll<br/> to Top
    </a>
  //-->
</footer>
