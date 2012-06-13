<?php
/**
 * We have domains like cqstaging.com and cqnext.com which are
 * used for testing and quality control. We want to make sure
 * they never reach google.com (or any other search engine)
 */

require __DIR__ .'/../config/bootstrap.php';

if (SF_ENV !== 'prod')
{
  echo "User-Agent: *\n";
  echo "Disallow: /";
}
else if (file_exists(__DIR__ .'/robots.txt'))
{
  include __DIR__ .'/robots.txt';
}
