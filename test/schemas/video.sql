DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `slug` varchar(64) DEFAULT NULL,
  `description` text NOT NULL,
  `type` varchar(64) NOT NULL,
  `length` int(11) DEFAULT NULL,
  `filename` varchar(128) DEFAULT NULL,
  `thumb_small` varchar(128) DEFAULT NULL,
  `thumb_large` varchar(128) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `uploaded_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
