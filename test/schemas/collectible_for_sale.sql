DROP TABLE IF EXISTS `collectible_for_sale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible_for_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collectible_id` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `condition` enum('excellent','very good','good','fair','poor') NOT NULL,
  `is_price_negotiable` tinyint(1) DEFAULT '0',
  `is_shipping_free` tinyint(1) DEFAULT '0',
  `is_sold` tinyint(1) DEFAULT '0',
  `is_ready` tinyint(1) DEFAULT '0' COMMENT 'Show in the market or no',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collectible_for_sale_item` (`collectible_id`),
  CONSTRAINT `collectible_for_sale_FK_1` FOREIGN KEY (`collectible_id`) REFERENCES `collectible` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible_for_sale` WRITE;
/*!40000 ALTER TABLE `collectible_for_sale` DISABLE KEYS */;
/*!40000 ALTER TABLE `collectible_for_sale` ENABLE KEYS */;
UNLOCK TABLES;
