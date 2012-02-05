DROP TABLE IF EXISTS `collectible_for_sale_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible_for_sale_archive` (
  `id` int(11) NOT NULL,
  `collectible_id` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `condition` enum('excellent','very good','good','fair','poor') NOT NULL,
  `is_price_negotiable` tinyint(1) DEFAULT '0',
  `is_shipping_free` tinyint(1) DEFAULT '0',
  `is_sold` tinyint(1) DEFAULT '0',
  `is_ready` tinyint(1) DEFAULT '0' COMMENT 'Show in the market or no',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collectible_for_sale_archive_I_1` (`id`),
  KEY `collectible_for_sale_archive_I_2` (`collectible_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible_for_sale_archive` WRITE;
/*!40000 ALTER TABLE `collectible_for_sale_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collectible_for_sale_archive` ENABLE KEYS */;
UNLOCK TABLES;
