DROP TABLE IF EXISTS `collector_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) NOT NULL,
  `collector_type` enum('Collector','Seller') NOT NULL DEFAULT 'Collector',
  `birthday` date DEFAULT NULL,
  `gender` enum('f','m') DEFAULT NULL,
  `zip_postal` varchar(10) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `country_iso3166` varchar(2) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `about` text,
  `collections` text,
  `collecting` varchar(255) DEFAULT NULL,
  `most_spent` int(11) DEFAULT NULL,
  `anually_spent` int(11) DEFAULT NULL,
  `new_item_every` varchar(64) DEFAULT NULL,
  `interests` text,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_seller` tinyint(1) DEFAULT '0',
  `is_image_auto` tinyint(1) DEFAULT '1',
  `preferences` text,
  `notifications` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collector_profile_U_1` (`collector_id`),
  CONSTRAINT `collector_profile_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_profile` WRITE;
/*!40000 ALTER TABLE `collector_profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_profile` ENABLE KEYS */;
UNLOCK TABLES;
