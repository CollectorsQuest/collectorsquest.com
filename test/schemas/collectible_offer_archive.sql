DROP TABLE IF EXISTS `collectible_offer_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible_offer_archive` (
  `id` int(11) NOT NULL,
  `collectible_id` int(11) DEFAULT NULL,
  `collectible_for_sale_id` int(11) DEFAULT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `status` enum('pending','counter','rejected','accepted') NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collectible_offer_archive_I_1` (`id`),
  KEY `collectible_offer_archive_I_2` (`collectible_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible_offer_archive` WRITE;
/*!40000 ALTER TABLE `collectible_offer_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collectible_offer_archive` ENABLE KEYS */;
UNLOCK TABLES;
