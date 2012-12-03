<?php
/* @var $pager    cqSphinxPager */
?>

<?php if ($suggestion = $pager->getDidYouMean($sf_params->get('q'))): ?>
<p class="alert alert-info">
  Did you mean: <strong><i><?= link_to($suggestion, '@search?q='. $suggestion); ?></i></strong>
</p>
<?php endif; ?>

<h1 class="Chivo webfont">Search results <small>for <?= $sf_params->get('q') ?> (no results)</small></h1>

<br/>
<i class="icon-exclamation-sign" style="float: left; font-size: 46px; margin-right: 10px; color: #DF912F;"></i>
Sorry! We can't find anything that matches your search.
Try a broader search, or browse around for other neat stuff.
(Or you can <?= link_to('upload something new', '@mycq_collections'); ?> to the site!)

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
