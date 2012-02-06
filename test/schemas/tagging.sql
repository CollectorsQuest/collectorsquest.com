DROP TABLE IF EXISTS `tagging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tagging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `taggable_model` varchar(50) DEFAULT NULL,
  `taggable_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tagging_I_1` (`taggable_model`,`taggable_id`),
  KEY `tagging_FI_1` (`tag_id`),
  CONSTRAINT `tagging_FK_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `tagging` WRITE;
/*!40000 ALTER TABLE `tagging` DISABLE KEYS */;
/*!40000 ALTER TABLE `tagging` ENABLE KEYS */;
UNLOCK TABLES;
