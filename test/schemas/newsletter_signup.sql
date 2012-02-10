DROP TABLE IF EXISTS `newsletter_signup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_signup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `newsletter_signup` WRITE;
/*!40000 ALTER TABLE `newsletter_signup` DISABLE KEYS */;
INSERT  IGNORE INTO `newsletter_signup` (`id`, `email`, `name`) VALUES (1,'kangov@collectorsquest.com','Kiril Angov');
/*!40000 ALTER TABLE `newsletter_signup` ENABLE KEYS */;
UNLOCK TABLES;
