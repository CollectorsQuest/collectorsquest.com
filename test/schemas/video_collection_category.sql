DROP TABLE IF EXISTS `video_collection_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_collection_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) DEFAULT NULL,
  `collection_category_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `video_collection_category_FI_1` (`video_id`),
  KEY `video_collection_category_FI_2` (`collection_category_id`),
  CONSTRAINT `video_collection_category_FK_1` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`),
  CONSTRAINT `video_collection_category_FK_2` FOREIGN KEY (`collection_category_id`) REFERENCES `collection_category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `video_collection_category` WRITE;
/*!40000 ALTER TABLE `video_collection_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_collection_category` ENABLE KEYS */;
UNLOCK TABLES;
