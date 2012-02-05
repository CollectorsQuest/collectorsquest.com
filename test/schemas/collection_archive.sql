DROP TABLE IF EXISTS `collection_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection_archive` (
  `id` int(11) NOT NULL,
  `graph_id` int(11) DEFAULT NULL,
  `collection_category_id` int(11) DEFAULT NULL,
  `collector_id` int(11) DEFAULT NULL,
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
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_archive_I_1` (`id`),
  KEY `collection_archive_I_2` (`graph_id`),
  KEY `collection_archive_I_3` (`collection_category_id`),
  KEY `collection_archive_I_4` (`collector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collection_archive` WRITE;
/*!40000 ALTER TABLE `collection_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection_archive` ENABLE KEYS */;
UNLOCK TABLES;
