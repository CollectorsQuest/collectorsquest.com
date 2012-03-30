<div class="row-fluid" style="border-bottom: 1px dotted red;">
  <div class="span9">
    <h3 style="color: #125375; font-family: 'Chivo', sans-serif;">Collections Directory</h3>
  </div>
  <div class="span3" style="padding-top: 5px; text-align: right;">
    <?= link_to('See all >>', '@collections_categories'); ?>
  </div>
</div>
<div class="row-fluid">
  <?php foreach ($categories as $i => $category): ?>
  <div class="span6" style="margin-left: 0;">
    <?= ($category) ? link_to_collection_category($category, 'text') : ''; ?>
  </div>
  <?php endforeach; ?>
</div>
