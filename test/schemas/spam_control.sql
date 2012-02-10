DROP TABLE IF EXISTS `spam_control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spam_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` enum('email','phone','ip','regex','session') NOT NULL DEFAULT 'regex',
  `value` varchar(64) NOT NULL,
  `credentials` set('read','create','edit','comment') NOT NULL DEFAULT 'read',
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `is_throttled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spam_control_U_1` (`field`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `spam_control` WRITE;
/*!40000 ALTER TABLE `spam_control` DISABLE KEYS */;
/*!40000 ALTER TABLE `spam_control` ENABLE KEYS */;
UNLOCK TABLES;
