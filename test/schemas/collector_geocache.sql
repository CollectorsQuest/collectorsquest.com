DROP TABLE IF EXISTS `collector_geocache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_geocache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) NOT NULL,
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
  KEY `collector_geocache_FI_1` (`collector_id`),
  CONSTRAINT `collector_geocache_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_geocache` WRITE;
/*!40000 ALTER TABLE `collector_geocache` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_geocache` ENABLE KEYS */;
UNLOCK TABLES;
