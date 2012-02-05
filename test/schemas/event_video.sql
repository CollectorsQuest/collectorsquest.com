DROP TABLE IF EXISTS `event_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `lenght` int(11) DEFAULT NULL,
  `thumb_small` varchar(255) DEFAULT NULL,
  `thumb_large` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_video_FI_1` (`event_id`),
  CONSTRAINT `event_video_FK_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `event_video` WRITE;
/*!40000 ALTER TABLE `event_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_video` ENABLE KEYS */;
UNLOCK TABLES;
