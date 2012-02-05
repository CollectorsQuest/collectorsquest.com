DROP TABLE IF EXISTS `collectible_offer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible_offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collectible_id` int(11) NOT NULL,
  `collectible_for_sale_id` int(11) NOT NULL,
  `collector_id` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `status` enum('pending','counter','rejected','accepted') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collectible_offer_FI_1` (`collectible_id`),
  KEY `collectible_offer_FI_2` (`collectible_for_sale_id`),
  KEY `collectible_offer_FI_3` (`collector_id`),
  CONSTRAINT `collectible_offer_FK_1` FOREIGN KEY (`collectible_id`) REFERENCES `collectible` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collectible_offer_FK_2` FOREIGN KEY (`collectible_for_sale_id`) REFERENCES `collectible_for_sale` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collectible_offer_FK_3` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible_offer` WRITE;
/*!40000 ALTER TABLE `collectible_offer` DISABLE KEYS */;
/*!40000 ALTER TABLE `collectible_offer` ENABLE KEYS */;
UNLOCK TABLES;
