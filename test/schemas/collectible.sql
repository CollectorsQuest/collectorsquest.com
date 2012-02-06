DROP TABLE IF EXISTS `collectible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `graph_id` int(11) DEFAULT NULL,
  `collector_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `description` text NOT NULL,
  `num_comments` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0',
  `position` int(11) DEFAULT '0',
  `is_name_automatic` tinyint(1) DEFAULT '0',
  `eblob` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collectible_U_1` (`graph_id`),
  UNIQUE KEY `collectible_U_2` (`slug`),
  KEY `collectible_FI_1` (`collector_id`),
  KEY `collectible_FI_2` (`collection_id`),
  CONSTRAINT `collectible_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collectible_FK_2` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible` WRITE;
/*!40000 ALTER TABLE `collectible` DISABLE KEYS */;
/*!40000 ALTER TABLE `collectible` ENABLE KEYS */;
UNLOCK TABLES;
