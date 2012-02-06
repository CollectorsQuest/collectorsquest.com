DROP TABLE IF EXISTS `collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `graph_id` int(11) DEFAULT NULL,
  `collection_category_id` int(11) DEFAULT NULL,
  `collector_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `description` text NOT NULL,
  `num_items` int(11) DEFAULT '0',
  `num_views` int(11) DEFAULT '0',
  `num_comments` int(11) DEFAULT '0',
  `num_ratings` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0',
  `is_public` tinyint(1) DEFAULT '1',
  `is_featured` tinyint(1) DEFAULT '0',
  `comments_on` tinyint(1) DEFAULT '1',
  `rating_on` tinyint(1) DEFAULT '1',
  `eblob` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collection_U_1` (`graph_id`),
  KEY `collection_FI_1` (`collection_category_id`),
  KEY `collection_FI_2` (`collector_id`),
  CONSTRAINT `collection_FK_1` FOREIGN KEY (`collection_category_id`) REFERENCES `collection_category` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collection_FK_2` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collection` WRITE;
/*!40000 ALTER TABLE `collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection` ENABLE KEYS */;
UNLOCK TABLES;
