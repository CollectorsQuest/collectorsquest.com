DROP TABLE IF EXISTS `featured`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `featured` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `featured_type_id` tinyint(4) NOT NULL,
  `featured_model` varchar(64) NOT NULL,
  `featured_id` int(11) DEFAULT NULL,
  `tree_left` int(11) NOT NULL DEFAULT '0',
  `tree_right` int(11) NOT NULL DEFAULT '0',
  `tree_scope` int(11) NOT NULL DEFAULT '0',
  `eblob` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `position` tinyint(4) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `featured` WRITE;
/*!40000 ALTER TABLE `featured` DISABLE KEYS */;
/*!40000 ALTER TABLE `featured` ENABLE KEYS */;
UNLOCK TABLES;
