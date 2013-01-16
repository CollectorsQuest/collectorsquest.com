<?php
/* @var $sf_user cqFrontendUser */
/* @var $sf_request cqWebRequest */
?>
<footer id="footer">
  <div class="footer-inner">
    <div class="row-fluid">
      <div class="span4">
        <div class="aboutus-footer-inner">
          <h3 class="Chivo webfont">About Collectors Quest</h3>

          <p class="about-us">
            Collectors Quest is here to help you get the most out of your
            collections: post a gallery of your neat stuff to share and use as an archive,
            buy and sell treasures quickly and easily, learn what’s going on in the collecting
            world, and meet other like-minded collectors.
            <?= link_to('Join us', '@misc_guide_to_collecting?ref='. cq_link_ref('footer')) ?>
            to make collecting more fun than ever!
          </p>

          <div class="contact-us-button">
            <?php
              echo link_to(
                'Contact Us', 'blog_page', array('slug' => 'contact-us', 'ref' => cq_link_ref('footer')),
                array('class' => 'btn btn-primary pull-left')
              );
            ?>
          </div>

          <div class="footer-social-icons">
            <strong>Follow us:</strong>
            <a href="https://www.facebook.com/pages/Collectors-Quest/119338990397"
               target="_blank" class="social-link spacer-left"
               rel="tooltip" title="Follow us on Facebook">
              <i class="s-24-icon-facebook"></i>
            </a>
            <a href="https://twitter.com/CollectorsQuest"
               target="_blank" class="social-link"
               rel="tooltip" title="Follow us on Twitter">
              <i class="s-24-icon-twitter"></i>
            </a>
            <a href="https://plus.google.com/113404032517505188429"
               target="_blank" class="social-link"
               rel="tooltip" title="Follow us on Google+">
              <i class="s-24-icon-google"></i>
            </a>
            <a href="http://pinterest.com/CollectorsQuest"
               target="_blank" class="social-link"
               rel="tooltip" title="Follow us on Pinterest">
              <i class="s-24-icon-pinterest"></i>
            </a>
          </div>

        </div>
      </div>
      <!-- .span4 -->

      <div class="span4">
        <?php
          if ($sf_user->isAuthenticated())
          {
            include_partial('global/footer_authenticated');
          }
          else
          {
            if ($sf_request->isMobileLayout())
            {
              cq_ad_slot(
                cq_image_tag('headlines/2012-06-24_CQGuide_610x107.png',
                  array(
                    'size' => '610x107', 'style' => 'display:block; margin: auto; clear: both;',
                    'class' => 'spacer-bottom-15',
                    'alt_title' => 'Unlock your free guide to collecting—sign up today'
                  )
                ),
                '@misc_guide_to_collecting'
              );
            }
            else
            {
              cq_ad_slot(
                cq_image_tag('headlines/2012-06-24_CQGuide_300x250_footer.png',
                  array(
                    'size' => '300x250', 'style' => 'display:block; margin: auto; clear: both;',
                    'alt_title' => 'Unlock your free guide to collecting—sign up today'
                  )
                ),
                '@misc_guide_to_collecting'
              );
            }
          }
        ?>
      </div>
      <!-- .span4 -->

      <div class="span4">
        <ul class="footer-info-box">
          <li>
            <i class="big-box-icon"></i>
            <div class="info-box-text">
              <h3 class="Chivo webfont">Show Off</h3>
              <p>
                Show your collections to the world! Upload and organize your stuff here.<br />
                <?php
                  if (!$sf_user->isAuthenticated())
                  {
                    echo link_to('Show&nbsp;Off&nbsp;Now!', '@misc_guide_to_collecting?ref='. cq_link_ref('footer'));
                  }
                  else
                  {
                    echo link_to('Show&nbsp;Off&nbsp;Now!', '@mycq_collections?ref='. cq_link_ref('footer'));
                  }
                ?>
              </p>
            </div>
          </li>

          <li>
            <i class="big-piggy-bank-icon"></i>
            <div class="info-box-text">
              <h3 class="Chivo webfont">Get Paid</h3>
              <p>
                Do you have something you'd like to sell?
                It's easy! Become a member of Collectors Quest and get started.<br/>
                <?= link_to('Get&nbsp;Paid&nbsp;Now!', '@seller_signup?ref='. cq_link_ref('footer')); ?>
              </p>
            </div>
          </li>

          <li>
            <i class="big-question-icon"></i>
            <div class="info-box-text">
              <h3 class="Chivo webfont">Help / FAQ</h3>
              <p>
                Have a question or a concern? Having trouble figuring something out?
                Get the most out of the site by checking out our FAQs.<br />
                <?php
                  echo link_to(
                    'Get&nbsp;Help&nbsp;Now!', 'blog_page',
                    array('slug' => 'cq-faqs/general-questions', 'ref' => cq_link_ref('footer'), '_decode' => true),
                    array('absolute' => true)
                  );
                ?>
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

</footer>
