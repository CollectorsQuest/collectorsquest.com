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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_friend` WRITE;
/*!40000 ALTER TABLE `collector_friend` DISABLE KEYS */;
INSERT  IGNORE INTO `collector_friend` (`id`, `collector_id`, `friend_id`, `created_at`) VALUES (1,3,1,'2012-02-10 13:03:52'),(2,2,1,'2012-02-10 13:03:52'),(3,3,2,'2012-02-10 13:03:52'),(4,3,6,'2012-02-10 13:03:52'),(5,3,13,'2012-02-10 13:03:52');
/*!40000 ALTER TABLE `collector_friend` ENABLE KEYS */;
UNLOCK TABLES;
