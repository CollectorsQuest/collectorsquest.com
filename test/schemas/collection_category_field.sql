DROP TABLE IF EXISTS `collection_category_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection_category_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collection_category_id` int(11) NOT NULL,
  `custom_field_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collection_category_field` (`collection_category_id`,`custom_field_id`),
  KEY `collection_category_field_FI_2` (`custom_field_id`),
  CONSTRAINT `collection_category_field_FK_1` FOREIGN KEY (`collection_category_id`) REFERENCES `collection_category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collection_category_field_FK_2` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_field` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collection_category_field` WRITE;
/*!40000 ALTER TABLE `collection_category_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection_category_field` ENABLE KEYS */;
UNLOCK TABLES;
