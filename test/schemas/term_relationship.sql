DROP TABLE IF EXISTS `term_relationship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `term_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) DEFAULT NULL,
  `model` varchar(30) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `term_relationship_I_1` (`model`),
  KEY `term_relationship_FI_1` (`term_id`),
  CONSTRAINT `term_relationship_FK_1` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `term_relationship` WRITE;
/*!40000 ALTER TABLE `term_relationship` DISABLE KEYS */;
/*!40000 ALTER TABLE `term_relationship` ENABLE KEYS */;
UNLOCK TABLES;
