DROP TABLE IF EXISTS `private_message_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `private_message_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `private_message_template` WRITE;
/*!40000 ALTER TABLE `private_message_template` DISABLE KEYS */;
INSERT  IGNORE INTO `private_message_template` (`id`, `subject`, `body`, `description`, `created_at`, `updated_at`) VALUES (1,'Welcome to Collectors\' Quest','Welcome to Collectors\' Quest - the destination place for all collectors & sellers.\r\n        Get started by <a href=\"http://www.collectorsquest.com/collection/create.html\">adding your own collection</a> or just click around to find some great <a href=\"http://www.collectorsquest.com/collections/filter/most-recent\">collections</a> and <a href=\"http://www.collectorsquest.com/collectors\">collectors</a>.\r\n\r\n        What\'s in your collection?','The welcome email to be sent to collectors upon signup','2011-05-29 21:42:28','2011-05-29 21:42:26'),(2,'Welcome to Collectors\' Quest','Welcome to Collectors\' Quest - the destination place for all collectors & sellers.\r\n        Get started by <a href=\"http://www.collectorsquest.com/collection/create.html\">adding your own collection for sale</a> or just click around to find some great <a href=\"http://www.collectorsquest.com/collections/filter/most-recent\">collections</a> and <a href=\"http://www.collectorsquest.com/collectors\">collectors</a>.\r\n\r\n        What do you have for sale?','The welcome email to be sent to sellers upon signup','2011-05-29 21:42:28','2011-05-29 21:42:26');
/*!40000 ALTER TABLE `private_message_template` ENABLE KEYS */;
UNLOCK TABLES;
