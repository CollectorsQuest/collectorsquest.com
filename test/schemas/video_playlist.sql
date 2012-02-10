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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `video_playlist` WRITE;
/*!40000 ALTER TABLE `video_playlist` DISABLE KEYS */;
INSERT  IGNORE INTO `video_playlist` (`id`, `video_id`, `playlist_id`, `position`) VALUES (1,15,2,NULL),(2,14,2,NULL),(3,13,2,NULL),(4,12,2,NULL),(5,11,2,NULL),(6,16,3,NULL),(7,17,3,NULL),(8,18,3,NULL),(9,19,3,NULL),(10,20,3,NULL),(11,21,3,NULL),(12,22,3,NULL),(13,23,3,NULL),(14,34,6,NULL),(15,55,6,NULL),(16,33,6,NULL),(17,31,6,NULL),(18,36,6,NULL),(19,26,7,NULL),(20,28,7,NULL),(21,29,7,NULL),(22,30,7,NULL),(23,37,7,NULL),(24,47,9,NULL),(25,48,9,NULL),(26,49,9,NULL),(27,50,9,NULL),(28,24,5,NULL),(29,52,5,NULL),(30,54,5,NULL),(31,53,5,NULL),(32,35,5,NULL),(33,38,10,NULL),(34,56,10,NULL),(35,41,10,NULL),(36,40,10,NULL),(37,25,11,NULL),(38,32,11,NULL),(39,65,12,NULL),(40,64,12,NULL),(41,63,12,NULL),(42,62,12,NULL),(43,61,12,NULL),(44,60,12,NULL),(45,59,12,NULL),(46,58,12,NULL),(47,57,12,NULL),(48,66,12,NULL),(49,46,8,NULL),(50,43,8,NULL),(51,45,8,NULL),(52,44,8,NULL),(53,42,8,NULL),(54,7,1,NULL),(55,5,1,NULL),(56,8,1,NULL),(57,10,1,NULL),(58,1,1,NULL),(59,6,4,NULL),(60,2,4,NULL),(61,4,4,NULL),(62,9,4,NULL);
/*!40000 ALTER TABLE `video_playlist` ENABLE KEYS */;
UNLOCK TABLES;
