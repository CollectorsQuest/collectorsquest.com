<h1>Search results <small>for <?= $sf_params->get('q') ?> (no results)</small></h1>

<br/>
<i class="icon-exclamation-sign" style="float: left; font-size: 46px; margin-right: 10px; color: #DF912F;"></i>
We're sorry, but your search returned no relevant results!
Try your search again using less words and/or remove any numbers you might have in there.

<div class="row-fluid" style="margin-top: 50px;">
  <div class="span4 thumbnail link">
    <i class="icon icon-th-large" style="font-size: 55px; float: left; margin: 5px 10px;"></i>
    <h3 style="margin: 5px;"><?= link_to('Browse Collections', '@collections', array('class' => 'target')) ?></h3>
  </div>
  <div class="span4 thumbnail link">
    <i class="icon icon-facetime-video" style="font-size: 55px; float: left; margin: 5px 10px;"></i>
    <h3 style="margin: 5px;"><?= link_to('Explore Videos', 'video/index', array('class' => 'target')) ?></h3>
  </div>
  <div class="span4 thumbnail link">
    <i class="icon icon-shopping-cart" style="font-size: 55px; float: left; margin: 5px 10px;"></i>
    <h3 style="margin: 5px;"><?= link_to('Shop the Market', '@marketplace', array('class' => 'target')) ?></h3>
  </div>
</div>
