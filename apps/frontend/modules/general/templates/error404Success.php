<br/>
<h1 style="font-size: 250%;">Sorry, we seem to have misplaced this page!</h1>

<br/>
<h2 style="color: #877; line-height: 26px; margin-top: 10px;">
  If you reached this page from another part of this website, please
  <?= link_to('let us know', 'blog_page', array('slug' => 'contact-us')); ?> and we'll try to fix our link.
</h2>

<br/>
<section class="404">
  <p>
    If you came from another site, <?= link_to('let us know', 'blog_page', array('slug' => 'contact-us')); ?>
    where you came from so that we can try to fix the problem.
  </p>
  <p>And if you typed the address yourself, check to make sure you got it right!</p>
  <p>
    If you're just totally lost, stop on by our <?= link_to('home page', 'homepage') ?>
    to see our newest stuff, or use the search bar above to find exactly what you're looking for.
  </p>

  <br/>
  <p>Thanks for using Collectors Quest!</p>
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
