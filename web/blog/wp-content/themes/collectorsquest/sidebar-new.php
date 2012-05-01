<?php

if (is_page() && function_exists('dynamic_sidebar'))
{
  dynamic_sidebar('static-page-sidebar');
}
else if (is_singular() && function_exists('dynamic_sidebar'))
{
  dynamic_sidebar('singular-sidebar');
}
else if (function_exists('dynamic_sidebar'))
{
  dynamic_sidebar('non-singular-sidebar');
}
