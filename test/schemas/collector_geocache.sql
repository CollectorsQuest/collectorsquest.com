DROP TABLE IF EXISTS `collector_geocache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_geocache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) NOT NULL,
  `country` char(64) DEFAULT NULL,
  `country_iso3166` char(2) DEFAULT NULL,
  `state` varchar(64) DEFAULT NULL,
  `county` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `zip_postal` char(10) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `latitude` decimal(8,5) DEFAULT NULL,
  `longitude` decimal(8,5) DEFAULT NULL,
  `timezone` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_geocache_FI_1` (`collector_id`),
  CONSTRAINT `collector_geocache_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_geocache` WRITE;
/*!40000 ALTER TABLE `collector_geocache` DISABLE KEYS */;
INSERT  IGNORE INTO `collector_geocache` (`id`, `collector_id`, `country`, `country_iso3166`, `state`, `county`, `city`, `zip_postal`, `address`, `latitude`, `longitude`, `timezone`) VALUES (1,1,'USA','US','NY','Putnam','Putnam Valley','10579','Putnam Valley, NY 10579, USA',41.39138,-73.83014,'America/New_York'),(2,2,'USA','US','KS','Douglas','Lawrence','66046','Lawrence, KS 66046, USA',38.90271,-95.21623,'America/Chicago'),(3,4,'USA','US','NY',NULL,'Brooklyn','11201','Brooklyn, NY 11201, USA',40.69457,-73.99182,'America/New_York'),(4,5,'Sweden','SE',NULL,NULL,NULL,NULL,'Sweden',60.12816,18.64350,NULL),(5,7,'USA','US','MI','Oakland','Farmington','48335','Farmington, MI 48335, USA',42.46434,-83.40564,'America/New_York'),(6,15,'Spain','ES',NULL,NULL,NULL,NULL,'Spain',40.46367,-3.74922,NULL),(7,6,'USA','US','TX','Harris','Cypress','77429','Cypress, TX 77429, USA',30.01411,-95.66683,'America/Chicago'),(8,9,'Italy','IT',NULL,NULL,NULL,NULL,'Italy',41.87194,12.56738,NULL),(9,12,'USA','US','NC','Iredell','Mooresville','28115','Mooresville, NC 28115, USA',35.58429,-80.78794,'America/New_York'),(10,8,'The Netherlands','NL',NULL,NULL,NULL,NULL,'The Netherlands',52.13263,5.29127,NULL),(11,16,'USA','US',NULL,NULL,NULL,NULL,'United States',37.09024,-95.71289,'America/New_York'),(12,14,'Romania','RO',NULL,NULL,NULL,NULL,'Romania',45.94316,24.96676,NULL),(13,14,'Romania','RO',NULL,NULL,NULL,NULL,'Romania',45.94316,24.96676,NULL),(14,14,'Romania','RO',NULL,NULL,NULL,NULL,'Romania',45.94316,24.96676,NULL),(15,19,'USA','US','NH',NULL,'Hinsdale','03451','Hinsdale, NH 03451, USA',42.80834,-72.50865,'America/New_York'),(16,20,'USA','US','NE',NULL,'Omaha','68127','Omaha, NE 68127, USA',41.20735,-96.05204,'America/New_York');
/*!40000 ALTER TABLE `collector_geocache` ENABLE KEYS */;
UNLOCK TABLES;
