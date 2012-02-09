DROP TABLE IF EXISTS `collection_collectible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection_collectible` (
  `collection_id` int(11) NOT NULL,
  `collectible_id` int(11) NOT NULL,
  `score` int(11) DEFAULT '0',
  `position` int(11) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`collection_id`,`collectible_id`),
  KEY `collection_collectible_FI_2` (`collectible_id`),
  CONSTRAINT `collection_collectible_FK_1` FOREIGN KEY (`collection_id`) REFERENCES `collectible` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collection_collectible_FK_2` FOREIGN KEY (`collectible_id`) REFERENCES `collectible` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collection_collectible` WRITE;
/*!40000 ALTER TABLE `collection_collectible` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection_collectible` ENABLE KEYS */;
UNLOCK TABLES;
