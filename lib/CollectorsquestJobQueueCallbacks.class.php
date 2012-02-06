<?php

class CollectorsquestJobQueueCallbacks extends IceJobQueueCallbacks
{
  public function __construct()
  {
    parent::$_jobs_path = dirname(__FILE__).'/jobs';
  }

  /**
   * @param  GearmanJob  $job
   * @param  string      $parameters
   *
   * @return integer
   */
  public function _runJpegOptimizeDaily(GearmanJob $job = null, $parameters = null)
  {
    $this->initialize($job, $parameters);

    $q = MultimediaQuery::create()
       ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
       ->filterByCreatedAt(strtotime('yesterday'), Criteria::GREATER_EQUAL)
       ->filterByCreatedAt(strtotime('today'), Criteria::LESS_THAN);

    $i = 0;
    $total = $q->count();
    $this->progress($i, $total);

    /** @var $multimedia Multimedia[] */
    $multimedia = $q->find();

    foreach ($multimedia as $m)
    {
      $path = dirname($m->getAbsolutePath());

      /** @var $f sfFinder */
      $f = sfFinder::type('file')
         ->maxdepth(0)
         ->name($m->getMd5().'.*.jpg')
         ->not_name($m->getMd5().'.original.jpg');

      $files = $f->in($path);
      foreach ($files as $file)
      {
        $cmd = 'nice -n 19 /usr/bin/jpegoptim --quiet --preserve --strip-all '. $file;
        @exec($cmd);
      }

      $this->progress(++$i, $total);
    }

    return $this->success();
  }

  private final function email($to, $subject, $body, $replyTo = null)
  {
    // Figure out the content type of the email (between HTML and PLAIN text)
    $content_type = strip_tags($body) != $body ? 'text/html' : 'text/plain';

    try
    {
      $mailer = sfContext::getInstance()->getMailer();

      $message = $mailer->compose('no-reply@collectorsquest.com', $to, $subject);
      $message->setFrom('no-reply@collectorsquest.com', 'CollectorsQuest.com');
      $message->setReplyTo($replyTo !== null ? $replyTo : 'no-reply@collectorsquest.com');
      $message->setCharset('UTF-8');
      $message->setBody($body, $content_type);

      // Actually send the email
      $result = $mailer->send($message);
    }
    catch (Exception $e)
    {
      $result = false;
    }

    return $result;
  }
}
