<div id="sidebar">
<?php if (is_page()) : ?>
  <?php if (function_exists('dynamic_sidebar')) dynamic_sidebar('static-page-sidebar'); ?>
<?php elseif (is_singular()): ?>
  <?php if (function_exists('dynamic_sidebar')) dynamic_sidebar('singular-sidebar'); ?>
<?php else : ?>
  <?php if (function_exists('dynamic_sidebar')) dynamic_sidebar('non-singular-sidebar'); ?>
<?php endif; ?>
</div>
