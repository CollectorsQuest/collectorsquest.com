DROP TABLE IF EXISTS `collector_friend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_friend_FI_1` (`collector_id`),
  KEY `collector_friend_FI_2` (`friend_id`),
  CONSTRAINT `collector_friend_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`),
  CONSTRAINT `collector_friend_FK_2` FOREIGN KEY (`friend_id`) REFERENCES `collector` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_friend` WRITE;
/*!40000 ALTER TABLE `collector_friend` DISABLE KEYS */;
/*!40000 ALTER TABLE `collector_friend` ENABLE KEYS */;
UNLOCK TABLES;
