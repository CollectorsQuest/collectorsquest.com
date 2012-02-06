DROP TABLE IF EXISTS `collectible_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible_archive` (
  `id` int(11) NOT NULL,
  `graph_id` int(11) DEFAULT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `description` text NOT NULL,
  `num_comments` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0',
  `position` int(11) DEFAULT '0',
  `is_name_automatic` tinyint(1) DEFAULT '0',
  `eblob` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collectible_archive_I_1` (`id`),
  KEY `collectible_archive_I_2` (`graph_id`),
  KEY `collectible_archive_I_3` (`collector_id`),
  KEY `collectible_archive_I_4` (`collection_id`),
  KEY `collectible_archive_I_5` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible_archive` WRITE;
/*!40000 ALTER TABLE `collectible_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collectible_archive` ENABLE KEYS */;
UNLOCK TABLES;
