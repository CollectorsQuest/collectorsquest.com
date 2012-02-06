DROP TABLE IF EXISTS `newsletter_signup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_signup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `newsletter_signup` WRITE;
/*!40000 ALTER TABLE `newsletter_signup` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_signup` ENABLE KEYS */;
UNLOCK TABLES;
