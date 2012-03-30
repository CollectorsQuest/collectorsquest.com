<div style="margin-top: 20px">&nbsp;</div>

<?= cq_ad_slot('300x250', 300, 250) ?>

<br/>
<div class="row-fluid" style="border-bottom: 1px dotted red;">
  <div class="span9">
    <h3 style="color: #125375;">Market Directory</h3>
  </div>
  <div class="span3" style="padding-top: 5px; text-align: right;">
    <a href="#">see all</a>
  </div>
</div>
<br/>

<div class="row-fluid">
  <?php foreach ($categories as $i => $category): ?>
  <div class="span6" style="margin-left: 0;">
    <?= ($category) ? link_to_collection_category($category, 'text') : ''; ?>
  </div>
  <?php endforeach; ?>
</div>

<br/>
<div class="row-fluid" style="border-bottom: 1px dotted red;">
  <div class="span8">
    <h3 style="color: #125375;">Featured Sellers</h3>
  </div>
  <div class="span4" style="padding-top: 5px; text-align: right;">
    <a href="#">browse profiles</a>
  </div>
</div>
<br/>
