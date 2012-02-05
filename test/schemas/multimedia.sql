DROP TABLE IF EXISTS `multimedia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multimedia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` char(64) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `name` varchar(128) DEFAULT NULL,
  `md5` char(32) NOT NULL,
  `colors` varchar(128) DEFAULT NULL,
  `orientation` enum('landscape','portrait') DEFAULT 'landscape',
  `source` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `multimedia_U_1` (`model`,`model_id`,`md5`),
  KEY `multimedia_I_1` (`model`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `multimedia` WRITE;
/*!40000 ALTER TABLE `multimedia` DISABLE KEYS */;
/*!40000 ALTER TABLE `multimedia` ENABLE KEYS */;
UNLOCK TABLES;
