<div class="well">
<?php
  foreach($tags as $tag)
  {
    echo link_to($tag, '@tag?tag='. $tag .'&page=1')." &nbsp; ";
  }
?>
</div>
