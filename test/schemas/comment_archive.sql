DROP TABLE IF EXISTS `comment_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_archive` (
  `id` int(11) NOT NULL,
  `disqus_id` char(10) DEFAULT NULL,
  `parent_id` char(10) DEFAULT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `collectible_id` int(11) DEFAULT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `author_name` varchar(128) DEFAULT NULL,
  `author_email` varchar(128) DEFAULT NULL,
  `author_url` varchar(255) DEFAULT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `body` text NOT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_archive_I_1` (`id`),
  KEY `comment_archive_I_2` (`collection_id`),
  KEY `comment_archive_I_3` (`collectible_id`),
  KEY `comment_archive_I_4` (`collector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `comment_archive` WRITE;
/*!40000 ALTER TABLE `comment_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment_archive` ENABLE KEYS */;
UNLOCK TABLES;
