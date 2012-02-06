DROP TABLE IF EXISTS `collection_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `name` varchar(64) NOT NULL,
  `slug` varchar(64) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collection_category` WRITE;
/*!40000 ALTER TABLE `collection_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection_category` ENABLE KEYS */;
UNLOCK TABLES;
