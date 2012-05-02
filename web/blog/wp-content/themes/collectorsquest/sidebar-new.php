<?php

if (is_page() && function_exists('dynamic_sidebar') && dynamic_sidebar('static-page-sidebar'))
{
  dynamic_sidebar('static-page-sidebar');
}
else if (is_page())
{
  the_widget('WP_Pages_Widget', $args, $instance);
}



if (is_singular() && function_exists('dynamic_sidebar') && dynamic_sidebar('singular-sidebar'))
{
  dynamic_sidebar('singular-sidebar');
}
else if (is_singular())
{
  the_widget('CQ_300x250ad_widget', $args, $instance);
  the_widget('CQ_Tags_widget', $args, $instance);
  the_widget('CQ_Other_News_widget', $args, $instance);
}



if (function_exists('dynamic_sidebar') && dynamic_sidebar('non-singular-sidebar'))
{
  dynamic_sidebar('non-singular-sidebar');
}
else
{
  the_widget('CQ_300x250ad_widget', $args, $instance);
  the_widget('CQ_Our_Bloggers_widget', $args, $instance);
}
