<?php

if (is_page()) {

 if (function_exists('dynamic_sidebar') && is_active_sidebar('static-page-sidebar')) {
   dynamic_sidebar('static-page-sidebar');
 } else {
   the_widget('cq_sub_pages_widget', $args, $instance);
 }

} elseif (is_single()) {

  if (function_exists('dynamic_sidebar') && is_active_sidebar('singular-sidebar')) {
    dynamic_sidebar('singular-sidebar');
  } else {
    the_widget('cq_300x250ad_widget', $args, $instance);
    the_widget('cq_tags_widget', $args, $instance);
    the_widget('cq_other_news_widget', $args, $instance);
  }

} else {

  if (function_exists('dynamic_sidebar') && is_active_sidebar('non-singular-sidebar')) {
    dynamic_sidebar('non-singular-sidebar');
  } else {
    the_widget('cq_300x250ad_widget', $args, $instance);
    the_widget('cq_our_bloggers_widget', $args, $instance);
  }

}
