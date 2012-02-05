DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `is_triple` tinyint(1) DEFAULT '0',
  `triple_namespace` varchar(128) DEFAULT NULL,
  `triple_key` varchar(128) DEFAULT NULL,
  `triple_value` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_I_1` (`name`),
  KEY `tag_I_2` (`triple_namespace`,`triple_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;
