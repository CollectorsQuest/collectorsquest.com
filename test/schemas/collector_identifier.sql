DROP TABLE IF EXISTS `collector_identifier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_identifier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) NOT NULL,
  `identifier` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collector_identifier_U_1` (`identifier`),
  KEY `collector_identifier_FI_1` (`collector_id`),
  CONSTRAINT `collector_identifier_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_identifier` WRITE;
/*!40000 ALTER TABLE `collector_identifier` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_identifier` ENABLE KEYS */;
UNLOCK TABLES;
