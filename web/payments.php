<?php

/**
 * http://www.collectorsquest.com/payments.php?processor=amazon
 * http://www.collectorsquest.com/payments.php?processor=google
 * http://www.collectorsquest.com/payments.php?processor=paypal
 */

require __DIR__ .'/../config/bootstrap.php';

require __DIR__ .'/../lib/vendor/symfony/lib/yaml/sfYaml.php';
require __DIR__ .'/../lib/vendor/symfony/lib/util/sfToolkit.class.php';

require __DIR__ .'/../plugins/iceLibsPlugin/lib/IceStatic.class.php';
require __DIR__ .'/../plugins/iceLibsPlugin/lib/IceFunctions.class.php';
require __DIR__ .'/../lib/collectorsquest/cqStatic.class.php';
require __DIR__ .'/../lib/collectorsquest/cqFunctions.class.php';

@mkdir('/www/logs/payments/'. date('Ymd'), 0700, true);
$filename = '/www/logs/payments/'. date('Ymd') .'/'. time() . rand(100, 999) .'.log';
@file_put_contents($filename, 'Request: '. print_r(cqStatic::getRequestHeaders(), true));
@file_put_contents($filename, 'GET:'. print_r($_GET, true), FILE_APPEND);
@file_put_contents($filename, 'POST:'. print_r($_POST, true), FILE_APPEND);

$output = null;
$logger = null;

if (cqStatic::loadZendFramework())
{
  $logger = new Zend_Log();

  /**
   * Email notifications only for serious problems in production mode
   */
  if (SF_ENV === 'prod')
  {
    Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Smtp('localhost'));

    $mail = new Zend_Mail();
    $mail->setFrom('no-reply@collectorsquest.com')->addTo('developers@collectorsquest.com');

    $writer = new Zend_Log_Writer_Mail($mail);
    $writer->setSubjectPrependText('[Payments] ');

    // Only email warning level entries and higher.
    $writer->addFilter(Zend_Log::WARN);

    $logger->addWriter($writer);
  }
  else
  {
    /**
     * Log to http output/console
     */
    $formatter = new Zend_Log_Formatter_Simple(
      '%timestamp% %priorityName% (%priority%):<strong> %message% </strong><br/>' . PHP_EOL
    );
    $writer = new Zend_Log_Writer_Stream('php://output');
    $writer->setFormatter($formatter);

    $logger->addWriter($writer);
  }

  /**
   * Writing everything except debug messeages to the payments log file
   */
  $writer = new Zend_Log_Writer_Stream('/www/logs/payments/'. date('Ymd') .'.log');
  $writer->addFilter(Zend_Log::INFO);
  $logger->addWriter($writer);
}

if ($logger)
{
  $logger->log('Starting request @ '. date(DATE_ISO8601), Zend_Log::INFO);
  $logger->log('Saving request data in '. $filename, Zend_Log::INFO);
}

/**
 * First get the processor we are dealing with and verify it
 */
$processor = strtolower(cqStatic::getParam('processor', null));

  /**
   * Handle each payment processor separately
   */
switch ($processor)
{
  /**
   * @see http://paypal.com
   */
  case 'paypal':
    if ($logger)
    {
      $logger->log('Processing PayPal payment', Zend_Log::INFO);
    }

    // Create the PayPal class and tell it whether it is a sandbox request or not
    $sandbox = SF_ENV !== 'prod' || (isset($_POST['test_ipn']) && $_POST['test_ipn'] == 1);

    break;
}


header('Pragma: public');
header('Expires: Fri, 25 Mar 1983 05:00:00 GMT');
header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

echo !empty($output) ? $output : ';)';
