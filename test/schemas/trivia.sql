DROP TABLE IF EXISTS `trivia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trivia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `trivia` WRITE;
/*!40000 ALTER TABLE `trivia` DISABLE KEYS */;
INSERT  IGNORE INTO `trivia` (`id`, `content`, `created_at`) VALUES (1,'Action Comics No 1, published 1938, was sold in the 90\'s for $185,000?','2007-08-28 13:59:59'),(2,'The creator of Barbie and the creator of Matchbox Cars are married?','2007-08-31 15:05:48'),(3,'The Stradivari Kreutzer violin sold for $946,000 in 1988?','2007-09-06 13:56:29'),(4,'Leonardo da Vinci\'s notebook was bought by Bill Gates in 1994 for $30,8 million?','2007-09-06 13:57:13'),(5,'The name for a teddy bear collector is archtophilist or arctophile?','2007-09-06 13:57:41'),(6,'Collectors of dolls are called plangonologists?','2007-09-06 13:58:01'),(7,'A collector of paper money is called a notaphilist?','2007-09-06 13:58:17'),(8,'A labeorphilist is a collector of beer bottles?','2007-09-06 13:58:27'),(9,'A collector of butterflies is called a lepidopterist?','2007-09-06 13:58:37'),(10,'A collector of matchbooks and matchbook covers is a phillumenist?','2007-09-06 13:58:45'),(11,'A collector of antiques is an antiquarian?','2007-09-06 13:58:55'),(12,'If you collect obsidian and syenite you are called a rock hound?','2007-09-06 13:59:09'),(13,'A stamp collector is called a philatelist?','2007-09-06 13:59:17'),(14,'A pernalogist is a collector of pearls?','2007-09-06 13:59:26'),(15,'Cinephiles are film collectors?','2007-09-06 13:59:35'),(16,'A bibiophilist collects books?','2007-09-06 13:59:48'),(17,'A copoclephilist collects key rings?','2007-09-06 14:00:00'),(18,'A deltiologist collects post cards?','2007-09-06 14:00:12'),(19,'A collector of hi-fi equipment is called an audiophile?','2007-09-06 14:00:23'),(20,'People who collect seeds are called Spermologist','2007-09-06 14:00:37'),(21,'Vincent Van Gogh sold only one painting while he was alive?','2007-09-06 14:01:13');
/*!40000 ALTER TABLE `trivia` ENABLE KEYS */;
UNLOCK TABLES;
