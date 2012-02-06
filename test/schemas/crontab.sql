DROP TABLE IF EXISTS `crontab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crontab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` enum('global','icepique','autohop','bezplatno','burzo','hitimoti','netbox','torena') NOT NULL DEFAULT 'global',
  `minute` char(2) NOT NULL DEFAULT '1',
  `hour` char(2) NOT NULL DEFAULT '5',
  `month` char(2) NOT NULL DEFAULT '*',
  `day_of_week` char(2) NOT NULL DEFAULT '*',
  `day_of_month` char(2) NOT NULL DEFAULT '*',
  `function_name` varchar(255) NOT NULL,
  `parameters` varchar(255) DEFAULT NULL,
  `description` text,
  `priority` smallint(5) unsigned NOT NULL DEFAULT '1',
  `is_active` tinyint(1) DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `crontab` WRITE;
/*!40000 ALTER TABLE `crontab` DISABLE KEYS */;
/*!40000 ALTER TABLE `crontab` ENABLE KEYS */;
UNLOCK TABLES;
