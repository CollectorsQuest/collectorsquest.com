DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disqus_id` char(10) DEFAULT NULL,
  `parent_id` char(10) DEFAULT NULL,
  `collection_id` int(11) NOT NULL,
  `collectible_id` int(11) DEFAULT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `author_name` varchar(128) DEFAULT NULL,
  `author_email` varchar(128) DEFAULT NULL,
  `author_url` varchar(255) DEFAULT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `body` text NOT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comment_U_1` (`disqus_id`),
  KEY `comment_FI_1` (`collection_id`),
  KEY `comment_FI_2` (`collectible_id`),
  KEY `comment_FI_3` (`collector_id`),
  CONSTRAINT `comment_FK_1` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_FK_2` FOREIGN KEY (`collectible_id`) REFERENCES `collectible` (`id`) ON DELETE SET NULL,
  CONSTRAINT `comment_FK_3` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;
