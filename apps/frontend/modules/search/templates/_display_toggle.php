<div id="search-display" class="btn-group" data-toggle="buttons-radio" style="float: right;">
  <a href="<?= $url->replaceQueryString('display', 'grid'); ?>"
     class="btn <?php echo $display == 'grid' ? 'active' : ''; ?>" rel="nofollow">
    <i class="icon-th"></i>
  </a>
  <a href="<?= $url->replaceQueryString('display', 'list'); ?>"
     class="btn <?php echo $display == 'list' ? 'active' : ''; ?>" rel="nofollow">
    <i class="icon-th-list"></i>
  </a>
</div>
