DROP TABLE IF EXISTS `job_run`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_run` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crontab_id` int(11) DEFAULT NULL,
  `context` enum('global','icepique','autohop','bezplatno','burzo','hitimoti','netbox','torena') NOT NULL DEFAULT 'global',
  `unique_key` char(64) NOT NULL,
  `job_handle` char(64) NOT NULL,
  `function_name` varchar(255) DEFAULT NULL,
  `completed` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  `status` enum('pending','queued','running','cancelled','completed','failed') NOT NULL DEFAULT 'pending',
  `cpu_stats` text NOT NULL,
  `memory_stats` text NOT NULL,
  `loadavg_stats` text NOT NULL,
  `priority` smallint(5) unsigned NOT NULL DEFAULT '1',
  `is_background` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_run_U_1` (`unique_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `job_run` WRITE;
/*!40000 ALTER TABLE `job_run` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_run` ENABLE KEYS */;
UNLOCK TABLES;
