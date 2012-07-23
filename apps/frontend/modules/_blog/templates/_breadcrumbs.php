<div class="breadcrumbs-inner">
  <div class="pull-right">
    <span class="brown">You are here:</span>
    <?php
      include_component('iceBreadcrumbsModule', 'breadcrumbs');
      echo $data['breadcrumbs'] ? $data['breadcrumbs'] : null;
    ?>
  </div>
</div>
