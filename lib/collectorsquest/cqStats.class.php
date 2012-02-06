<?php

/**
 * Sends statistics to the stats daemon over UDP
 */
class cqStats extends IceStats
{
  /**
   * @var string The hostname of the StatsD server
   */
  const STATSD_HOST = 'cq-statsd';

  /**
   * @var integer The port of the StatsD server
   */
  const STATSD_PORT = 8125;
}
