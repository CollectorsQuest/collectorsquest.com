DROP TABLE IF EXISTS `resource_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `type` varchar(24) NOT NULL DEFAULT 'Blog',
  `name` varchar(128) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `rss` varchar(255) NOT NULL,
  `thumbnail` varchar(64) DEFAULT NULL,
  `blogger` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_entry_FI_1` (`category_id`),
  CONSTRAINT `resource_entry_FK_1` FOREIGN KEY (`category_id`) REFERENCES `resource_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `resource_entry` WRITE;
/*!40000 ALTER TABLE `resource_entry` DISABLE KEYS */;
/*!40000 ALTER TABLE `resource_entry` ENABLE KEYS */;
UNLOCK TABLES;
