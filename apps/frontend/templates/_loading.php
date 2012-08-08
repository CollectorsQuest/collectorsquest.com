<?php
/**
 * @var $url string
 */
?>

<div class="text-center">
<?php
  echo link_to(
    image_tag('loading.large.gif', array('size' => '100x100')), $url,
    array('class' => 'auto-close')
  );
?>
</div>
