  <div id="footer" class="span-25 append-bottom last">
    <div class="span-11">
      <ul>
        <li><?= __('Pages:'); ?></li>
        <li><?= link_to('about us', '@page?slug=about'); ?></li>
        <li>•</li>
        <li><?= link_to('contact', '@page?slug=contact-us'); ?></li>
        <li>•</li>
        <li><?= link_to('terms', '@page?slug=terms-and-conditions'); ?></li>
        <li>•</li>
        <li><?= link_to('rss', '@page?slug=rss-feeds'); ?></li>
        <li>•</li>
        <li><?= link_to('report an error', '@feedback', array('target' => '_blank', 'style' => 'color: red;')); ?></li>
      </ul>
    </div>

    <div class="span-13" style="float: right; text-align: right;">
      <?= link_to('CollectorsQuest.com', '@homepage', array('style' => 'text-decoration: none; color: #666;')); ?>
      © <?= date('Y'); ?> All rights reserved &nbsp; • &nbsp; Powered by
      <a href="http://www.symfony-project.org" title="Symfony" rel="nofollow" target="_blank"
         style="text-decoration: none;">
        <img src="/images/symfony.gif" style="vertical-align: middle;" alt="Symfony"/>
      </a>
      <?= ' @ r'. SVN_REVISION; ?>
    </div>
  </div>

  <?php
    if (has_slot('footer'))
    {
      echo get_slot('footer');
    }
  ?>
</div>
<div id="loading" class="rounded">
  <img src="/images/loading.large.gif" alt="Loading, please wait..." style="margin-top: 100px;"/></div>
<div id="fb-root"></div>

<?php include_partial('global/javascripts'); ?>
<?php include_partial('global/ad_slots'); ?>
<?php // include_partial('global/olark'); ?>

<?php cqStats::timing('collectorsquest.modules.'. $sf_context->getModuleName() .'.'. $sf_context->getActionName(), cqTimer::getInstance()->getElapsedTime()); ?>
<!-- Page generated in <?= cqTimer::getInstance()->getElapsedTime(); ?> seconds //-->
</body>
</html>
