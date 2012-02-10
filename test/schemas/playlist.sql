DROP TABLE IF EXISTS `playlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `slug` varchar(64) DEFAULT NULL,
  `description` text NOT NULL,
  `type` varchar(64) NOT NULL,
  `length` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `playlist` WRITE;
/*!40000 ALTER TABLE `playlist` DISABLE KEYS */;
INSERT  IGNORE INTO `playlist` (`id`, `title`, `slug`, `description`, `type`, `length`, `is_published`, `published_at`, `created_at`) VALUES (1,'Show & Tell #1 ','show-tell-1','Various collectors in their element','Spotlight',441,1,'2007-05-27 09:14:11','2007-05-27 09:14:11'),(2,'Toy Fair ','toy-fair','Toy Fair 2007','Event',216,0,NULL,'2008-04-15 21:58:18'),(3,'NY Comic Con ','ny-comic-con','NY Comic Con 2007','Event',352,1,'2008-04-15 00:00:00','2008-04-15 21:54:10'),(4,'Show & Tell #2 ','show-tell-2','Various collectors in their element','Spotlight',475,1,'2008-05-05 09:14:11','2008-05-05 09:14:11'),(5,'Wings and Wheels ','wings-and-wheels','','Spotlight',NULL,1,'2008-05-13 00:00:00','2008-05-13 10:25:53'),(6,'Lights, Camera, Action! ','lights-camera-action','','Spotlight',NULL,1,'2008-05-13 00:00:00','2008-05-13 10:33:59'),(7,'Heroes and Heroines ','heroes-and-heroines','','Spotlight',NULL,1,'2008-05-13 00:00:00','2008-05-13 10:58:19'),(8,'NE Pez Convention ','ne-pez-convention','','Event',NULL,1,'2008-05-13 00:00:00','2008-05-13 11:07:53'),(9,'So Un-Bearably Cute ','so-un-bearably-cute','','Spotlight',NULL,1,'2008-05-13 00:00:00','2008-05-13 12:04:22'),(10,'Fantastic Figures ','fantastic-figures','','Spotlight',NULL,1,'2008-05-15 00:00:00','2008-05-15 17:16:41'),(11,'A Doll\'s Life ','a-dolls-life','','Spotlight',NULL,NULL,NULL,'2008-05-16 11:04:46'),(12,'Scarsdale Concours ','scarsdale-concours','Scarsdale Concours','Event',NULL,1,'2008-09-08 00:00:00','2008-09-08 17:15:17');
/*!40000 ALTER TABLE `playlist` ENABLE KEYS */;
UNLOCK TABLES;
