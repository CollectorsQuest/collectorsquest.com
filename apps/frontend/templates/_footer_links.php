<div id="footer-links">
  <div class="footer-links-inner">
    <div class="row-fluid">
      <div class="span7">
        <ul role="footer-links">
          <li><?= link_to('About Us', 'blog_page', array('slug' => 'about-us')); ?></li>
          <li><?= link_to('Contact', 'blog_page', array('slug' => 'contact-us')); ?></li>
          <li><?= link_to('Terms', 'blog_page', array('slug' => 'terms-and-conditions')); ?></li>
          <li><?= link_to('RSS', 'blog_page', array('slug' => 'rss-feeds')); ?></li>
          <li><?= urldecode(link_to('Help/FAQ', 'blog_page', array('slug' => 'cq-faqs/general-questions'))); ?></li>
          <li><?= link_to('Report an Error', '@feedback', array('target' => '_blank', 'class' => 'red')); ?></li>
        </ul>
      </div>
      <div class="span5 text-right">
        © <?= date('Y'); ?> Collectors Quest, Inc. &nbsp; • &nbsp;
        <a href="http://nytm.org/made" title="Made in NY">Made by hand in NY</a>
      </div>
    </div>
  </div>
</div>
