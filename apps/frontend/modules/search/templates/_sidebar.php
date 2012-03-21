<div class="well" style="padding: 8px 0; margin-top: 80px;">
  <ul class="nav nav-list">
    <li class="nav-header">Filter by type:</li>
    <li>
      <a href="#" style="padding-left: 37px;">All Types</a>
    </li>
    <li style="color: #999; padding: 3px 22px;">Collections (0)</li>
    <li><a href="#" style="padding-left: 37px;">Collectors (16)</a></li>
    <li class="active"><a href="#"><i class="icon-ok"></i>&nbsp;Collectibles (3)</a></li>
    <li class="active"><a href="#"><i class="icon-ok"></i>&nbsp;News Articles (10)</a></li>
    <li style="color: #999; padding: 3px 22px;">Video (0)</li>

    <li>&nbsp;</li>
    <li class="nav-header">Filter by category:</li>
    <?php foreach ($categories as $category): ?>
      <li>
        <a href="#" style="padding-left: 37px;">
          <?= $category->getName(); ?> <span style="color: #999;">(<?= rand(10,50); ?>)</span>
        </a>
      </li>
    <?php endforeach; ?>

    <li>&nbsp;</li>
    <li class="divider"></li>
    <li>
      <a href="#">
        <i class="icon-info-sign"></i>
        Need help finding something? Click here!
      </a>
    </li>
  </ul>
</div>
