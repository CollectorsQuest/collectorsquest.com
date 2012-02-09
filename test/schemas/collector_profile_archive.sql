DROP TABLE IF EXISTS `collector_profile_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_profile_archive` (
  `id` int(11) NOT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `collector_type` enum('Collector','Seller') NOT NULL DEFAULT 'Collector',
  `birthday` date DEFAULT NULL,
  `gender` enum('f','m') DEFAULT NULL,
  `zip_postal` varchar(10) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `country_iso3166` varchar(2) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_seller` tinyint(1) DEFAULT '0',
  `is_image_auto` tinyint(1) DEFAULT '1',
  `preferences` text,
  `notifications` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_profile_archive_I_1` (`id`),
  KEY `collector_profile_archive_I_2` (`collector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_profile_archive` WRITE;
/*!40000 ALTER TABLE `collector_profile_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_profile_archive` ENABLE KEYS */;
UNLOCK TABLES;
