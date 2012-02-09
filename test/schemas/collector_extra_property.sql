DROP TABLE IF EXISTS `collector_extra_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_extra_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_name` varchar(255) NOT NULL,
  `property_value` text,
  `collector_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_extra_property_FI_1` (`collector_id`),
  CONSTRAINT `collector_extra_property_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_extra_property` WRITE;
/*!40000 ALTER TABLE `collector_extra_property` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_extra_property` ENABLE KEYS */;
UNLOCK TABLES;
