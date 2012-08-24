<?php
/**
 * We have domains like cqstaging.com and cqnext.com which are
 * used for testing and quality control. We want to make sure
 * they never reach google.com (or any other search engine)
 */

require __DIR__ .'/../config/bootstrap.php';

/**
 * These domains should be excluded from search engines
 */
$forbidden = array(
  'static.collectorsquest.com',
  'd2y8496azcwpd6.cloudfront.net',
  'web-471984672.us-east-1.elb.amazonaws.com'
);

if (SF_ENV !== 'prod' || in_array(strtolower($_SERVER['HTTP_HOST']), $forbidden, true))
{
  echo "User-Agent: *\n";
  echo "Disallow: /";
}
else if (file_exists(__DIR__ .'/robots.txt'))
{
  include __DIR__ .'/robots.txt';
}
else
{
  echo "User-Agent: *\n";
  echo "Allow: /";
}
