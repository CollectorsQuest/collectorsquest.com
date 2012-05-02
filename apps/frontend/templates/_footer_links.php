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
        © <?= date('Y'); ?> Collectors Quest, Inc. &nbsp; • &nbsp;
        <a href="http://nytm.org/made" title="Made in NY">Made by hand in NY</a>
      </div>
    </div>
  </div>
</div>
