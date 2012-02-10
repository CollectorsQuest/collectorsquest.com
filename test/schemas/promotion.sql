DROP TABLE IF EXISTS `promotion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_code` varchar(255) NOT NULL,
  `promotion_name` varchar(255) NOT NULL,
  `promotion_desc` text,
  `amount` float DEFAULT NULL,
  `amount_type` enum('Fix','Percentage') NOT NULL DEFAULT 'Fix',
  `no_of_time_used` int(11) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promotion_U_I` (`promotion_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `promotion` WRITE;
/*!40000 ALTER TABLE `promotion` DISABLE KEYS */;
INSERT  IGNORE INTO `promotion` (`id`, `promotion_code`, `promotion_name`, `promotion_desc`, `amount`, `amount_type`, `no_of_time_used`, `expiry_date`, `created_at`, `updated_at`) VALUES (1,'CQ2011','Beta Testers Promotions','The promotion is given to the sellers who help us with the initial testing of the Marketplace piece.',100,'Fix',100,'2011-06-30 23:59:59','2011-05-29 22:47:17','2011-05-29 22:47:20'),(2,'CQ2011-DHX11','Free subscription!','The promotion is given to the sellers who help us with the initial testing of the Marketplace piece.',250,'Fix',975,'2011-07-05 19:49:24','2011-06-05 19:49:24','2011-06-14 18:53:53');
/*!40000 ALTER TABLE `promotion` ENABLE KEYS */;
UNLOCK TABLES;
