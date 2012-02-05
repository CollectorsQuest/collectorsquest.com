DROP TABLE IF EXISTS `video_playlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) DEFAULT NULL,
  `playlist_id` int(11) DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `video_playlist_FI_1` (`video_id`),
  KEY `video_playlist_FI_2` (`playlist_id`),
  CONSTRAINT `video_playlist_FK_1` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`),
  CONSTRAINT `video_playlist_FK_2` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `video_playlist` WRITE;
/*!40000 ALTER TABLE `video_playlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_playlist` ENABLE KEYS */;
UNLOCK TABLES;
