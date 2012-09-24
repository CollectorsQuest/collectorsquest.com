<script type="text/javascript">
$(document).ready(function()
{
  $(document).controls();
});
</script>
<div id="footer-links">
  <div class="footer-links-inner">
    <div class="row-fluid">
      <div class="span7">
        <ul role="footer-links">
          <li><?= link_to('About Us', 'blog_page', array('slug' => 'about-us', 'ref' => cq_link_ref('footer'))); ?></li>
          <li><?= link_to('Press Room', 'blog_page', array('slug' => 'press-room', 'ref' => cq_link_ref('footer'))); ?></li>
          <li><?= link_to('Contact', 'blog_page', array('slug' => 'contact-us', 'ref' => cq_link_ref('footer'))); ?></li>
          <li><?= link_to('Terms', 'blog_page', array('slug' => 'terms-and-conditions', 'ref' => cq_link_ref('footer'))); ?></li>
          <li><?= link_to('RSS', 'blog_page', array('slug' => 'rss-feeds', 'ref' => cq_link_ref('footer'))); ?></li>
          <li><?= link_to('Help/FAQ', 'blog_page', array('slug' => 'cq-faqs/general-questions', 'ref' => cq_link_ref('footer'), '_decode' => 1)); ?></li>
          <li><?= link_to('Report an Error', '@ajax_feedback', array('class' => 'red open-dialog', 'onclick' => 'return false;')); ?></li>
        </ul>
      </div>
      <div class="span5 text-right">
        © <?= date('Y'); ?> Collectors Quest, Inc. &nbsp; • &nbsp;
        <a href="http://nytm.org/made" title="Made in NY" rel="nofollow">Made by hand in NY</a>
      </div>
    </div>
  </div>
</div>
