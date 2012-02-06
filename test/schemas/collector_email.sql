DROP TABLE IF EXISTS `collector_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `hash` varchar(40) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_email_I_1` (`email`),
  KEY `collector_email_FI_1` (`collector_id`),
  CONSTRAINT `collector_email_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_email` WRITE;
/*!40000 ALTER TABLE `collector_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_email` ENABLE KEYS */;
UNLOCK TABLES;
