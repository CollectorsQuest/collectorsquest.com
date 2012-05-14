<header>
  <h1>Sorry, the page you&rsquo;re looking for isn&rsquo;t here anymore.</h1>

  <h2>You didn&rsquo;t do anything wrong. We may have moved the page you&rsquo;re looking for somewhere else.</h2>
</header>

<section class="faq">
  <h1>Did you follow a link from here?</h1>

  <p>If you reached this page from another part of collectorsquest.com, please
    <a href="<?=url_for('feedback')?>">let us know</a> so we can correct our mistake.</p>

  <h1>Did you follow a link from another site?</h1>

  <p>Links from other sites can sometimes be outdated or misspelled.
    <a href="<?=url_for('feedback')?>">Let us know</a> where you came from and we can try to contact the other site in order to fix the problem.
  </p>

  <h1>Did you type the URL?</h1>

  <p>You may have typed the address (URL) incorrectly. Check to make sure you&rsquo;ve got the exact right spelling, capitalization, etc. The
    <a href="<?=url_for('homepage')?>">Collectorsquest home page</a> has links to our most popular content.</p>
</section>

<div class="row-fluid" style="margin-top: 50px;">
  <div class="span4 thumbnail link">
    <i class="icon icon-th-large" style="font-size: 55px; float: left; margin: 5px 10px;"></i>
    <h3 style="margin: 5px;"><?= link_to('Browse Collections', '@collections', array('class' => 'target')) ?></h3>
  </div>
  <div class="span4 thumbnail link">
    <i class="icon icon-facetime-video" style="font-size: 55px; float: left; margin: 5px 10px;"></i>
    <h3 style="margin: 5px;"><?= link_to('Explore Videos', '@video', array('class' => 'target')) ?></h3>
  </div>
  <div class="span4 thumbnail link">
    <i class="icon icon-shopping-cart" style="font-size: 55px; float: left; margin: 5px 10px;"></i>
    <h3 style="margin: 5px;"><?= link_to('Shop the Market', '@marketplace', array('class' => 'target')) ?></h3>
  </div>
</div>
