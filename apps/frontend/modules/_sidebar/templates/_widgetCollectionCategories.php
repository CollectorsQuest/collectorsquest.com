<div class="row-fluid" style="border-bottom: 1px dotted red;">
  <div class="span9">
    <h3 style="color: #125375; font-family: 'Chivo', sans-serif;">Collection Categories</h3>
  </div>
  <div class="span3" style="padding-top: 5px; text-align: right;">
    <a href="#">See all >></a>
  </div>
</div>
<div class="row-fluid">
  <ul>
  <?php foreach ($categories as $category): ?>
    <li><?= link_to_collection_category($category, 'text'); ?></li>
  <?php endforeach; ?>
  </ul>
</div>
