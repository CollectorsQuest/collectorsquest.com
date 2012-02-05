DROP TABLE IF EXISTS `collector_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_archive` (
  `id` int(11) NOT NULL,
  `graph_id` int(11) DEFAULT NULL,
  `facebook_id` varchar(20) DEFAULT NULL,
  `username` varchar(64) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `sha1_password` varchar(40) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `user_type` enum('Collector','Seller') NOT NULL DEFAULT 'Collector',
  `items_allowed` int(11) DEFAULT NULL,
  `what_you_collect` varchar(255) DEFAULT NULL,
  `purchases_per_year` int(11) DEFAULT '0',
  `what_you_sell` varchar(255) DEFAULT NULL,
  `annually_spend` float DEFAULT '0',
  `most_expensive_item` float DEFAULT '0',
  `company` varchar(255) DEFAULT NULL,
  `locale` varchar(5) DEFAULT 'en_US',
  `score` int(11) DEFAULT '0',
  `spam_score` int(11) DEFAULT '0',
  `is_spam` tinyint(1) DEFAULT '0',
  `is_public` tinyint(1) DEFAULT '1',
  `session_id` varchar(32) DEFAULT NULL,
  `last_seen_at` datetime DEFAULT NULL,
  `eblob` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_archive_I_1` (`id`),
  KEY `collector_archive_I_2` (`graph_id`),
  KEY `collector_archive_I_3` (`facebook_id`),
  KEY `collector_archive_I_4` (`slug`),
  KEY `collector_archive_I_5` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_archive` WRITE;
/*!40000 ALTER TABLE `collector_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_archive` ENABLE KEYS */;
UNLOCK TABLES;
