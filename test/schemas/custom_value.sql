DROP TABLE IF EXISTS `custom_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collection_id` int(11) NOT NULL,
  `collectible_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value_text` varchar(255) DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `value_numeric` float DEFAULT NULL,
  `value_bool` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_value_FI_1` (`collection_id`),
  KEY `custom_value_FI_2` (`collectible_id`),
  CONSTRAINT `custom_value_FK_1` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`id`) ON DELETE CASCADE,
  CONSTRAINT `custom_value_FK_2` FOREIGN KEY (`collectible_id`) REFERENCES `collectible` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `custom_value` WRITE;
/*!40000 ALTER TABLE `custom_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_value` ENABLE KEYS */;
UNLOCK TABLES;
