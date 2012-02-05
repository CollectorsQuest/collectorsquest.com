DROP TABLE IF EXISTS `collector_geocache_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_geocache_archive` (
  `id` int(11) NOT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `country` char(64) DEFAULT NULL,
  `country_iso3166` char(2) DEFAULT NULL,
  `state` varchar(64) DEFAULT NULL,
  `county` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `zip_postal` char(10) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `latitude` decimal(8,5) DEFAULT NULL,
  `longitude` decimal(8,5) DEFAULT NULL,
  `timezone` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_geocache_archive_I_1` (`id`),
  KEY `collector_geocache_archive_I_2` (`collector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_geocache_archive` WRITE;
/*!40000 ALTER TABLE `collector_geocache_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_geocache_archive` ENABLE KEYS */;
UNLOCK TABLES;
