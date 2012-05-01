<?php

if (is_page() && function_exists('dynamic_sidebar'))
{
  dynamic_sidebar('static-page-sidebar');

  if(!is_active_widget( '', '', 'WP_Pages_Widget')){
    the_widget('WP_Pages_Widget', $args, $instance);
  }


}
else if (is_singular() && function_exists('dynamic_sidebar'))
{
  dynamic_sidebar('singular-sidebar');

  if(!is_active_widget( '', '', 'CQ_300x250ad_widget')){
    the_widget('CQ_300x250ad_widget', $args, $instance);
  }

  if(!is_active_widget( '', '', 'CQ_Tags_widget')){
    the_widget('CQ_Tags_widget', $args, $instance);
  }

  if(!is_active_widget( '', '', 'CQ_Other_News_widget')){
    the_widget('CQ_Other_News_widget', $args, $instance);
  }


}
else if (function_exists('dynamic_sidebar'))
{
  dynamic_sidebar('non-singular-sidebar');

  if(!is_active_widget( '', '', 'CQ_300x250ad_widget')){
    the_widget('CQ_300x250ad_widget', $args, $instance);
  }

  if(!is_active_widget( '', '', 'CQ_Our_Bloggers_widget')){
    the_widget('CQ_Our_Bloggers', $args, $instance);
  }

}


