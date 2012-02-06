DROP TABLE IF EXISTS `collector_identifier_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_identifier_archive` (
  `id` int(11) NOT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `identifier` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_identifier_archive_I_1` (`id`),
  KEY `collector_identifier_archive_I_2` (`collector_id`),
  KEY `collector_identifier_archive_I_3` (`identifier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_identifier_archive` WRITE;
/*!40000 ALTER TABLE `collector_identifier_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_identifier_archive` ENABLE KEYS */;
UNLOCK TABLES;
