DROP TABLE IF EXISTS `collectible_offer_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible_offer_archive` (
  `id` int(11) NOT NULL,
  `collectible_id` int(11) DEFAULT NULL,
  `collectible_for_sale_id` int(11) DEFAULT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `status` enum('pending','counter','rejected','accepted') NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collectible_offer_archive_I_1` (`id`),
  KEY `collectible_offer_archive_I_2` (`collectible_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible_offer_archive` WRITE;
/*!40000 ALTER TABLE `collectible_offer_archive` DISABLE KEYS */;
INSERT  IGNORE INTO `collectible_offer_archive` (`id`, `collectible_id`, `collectible_for_sale_id`, `collector_id`, `price`, `status`, `updated_at`, `created_at`, `archived_at`) VALUES (30,24316,1068,1332,20,'','2011-06-21 21:04:53','2009-06-23 14:20:39','2011-06-21 21:04:53'),(38,25733,1320,1425,30,'accepted','2011-06-21 08:03:46','2009-08-02 03:17:26','2011-06-21 08:03:46'),(55,25725,1302,1525,1,'rejected','2011-06-21 08:03:45','2009-09-22 02:56:07','2011-06-21 08:03:45'),(58,25715,1331,1576,50,'pending','2011-06-21 08:03:46','2009-10-07 11:57:00','2011-06-21 08:03:46'),(61,25746,1371,1576,300,'rejected','2011-06-21 08:03:45','2009-10-08 07:22:53','2011-06-21 08:03:45'),(97,30574,1493,2590,50,'pending','2010-02-19 14:00:51','2010-02-19 14:00:51',NULL),(104,24314,1071,2644,4,'','2011-06-21 21:05:40','2010-02-25 00:40:00','2011-06-21 21:05:40'),(111,25725,1302,2644,3,'accepted','2011-06-21 08:03:45','2010-02-25 00:59:46','2011-06-21 08:03:45'),(120,23435,721,6598,25,'pending','2011-06-09 00:09:59','2011-06-09 00:09:59',NULL),(168,61266,2260,8026,112,'pending','2011-09-06 06:22:11','2011-09-06 06:22:11',NULL),(169,56859,2168,8026,450,'pending','2011-09-06 06:22:50','2011-09-06 06:22:50',NULL),(209,23875,1180,9362,15,'pending','2012-01-29 07:47:48','2012-01-29 07:47:48',NULL);
/*!40000 ALTER TABLE `collectible_offer_archive` ENABLE KEYS */;
UNLOCK TABLES;
