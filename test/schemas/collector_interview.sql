DROP TABLE IF EXISTS `collector_interview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_interview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) DEFAULT NULL,
  `collection_category_id` int(11) DEFAULT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `catch_phrase` varchar(128) NOT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_interview_FI_1` (`collector_id`),
  KEY `collector_interview_FI_2` (`collection_category_id`),
  KEY `collector_interview_FI_3` (`collection_id`),
  CONSTRAINT `collector_interview_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collector_interview_FK_2` FOREIGN KEY (`collection_category_id`) REFERENCES `collection_category` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collector_interview_FK_3` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_interview` WRITE;
/*!40000 ALTER TABLE `collector_interview` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_interview` ENABLE KEYS */;
UNLOCK TABLES;
