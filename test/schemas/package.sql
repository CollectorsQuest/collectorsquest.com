DROP TABLE IF EXISTS `package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_type` enum('Casual','Power') NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `package_description` text,
  `max_items_for_sale` int(11) DEFAULT NULL,
  `package_price` float DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `package` WRITE;
/*!40000 ALTER TABLE `package` DISABLE KEYS */;
INSERT  IGNORE INTO `package` (`id`, `plan_type`, `package_name`, `package_description`, `max_items_for_sale`, `package_price`, `created_at`, `updated_at`) VALUES (1,'Casual','1 item only','1 item only',1,2.5,'2011-06-05 19:49:24','2011-06-05 19:49:24'),(2,'Casual','Up to 5 items ($2.25 ea)','Up to 5 items ($2.25 ea)',5,11.25,'2011-06-05 19:49:24','2011-06-05 19:49:24'),(3,'Casual','Up to 15 items ($2.00 ea)','Up to 15 items ($2.00 ea)',15,30,'2011-06-05 19:49:24','2011-06-05 19:49:24'),(4,'Casual','Up to 25 items ($1.65 ea)','Up to 25 items ($1.65 ea)',25,41.5,'2011-06-05 19:49:24','2011-06-05 19:49:24'),(5,'Power','Up to 1000 items ($.15 ea)','Up to 1000 items ($.15 ea)',1000,150,'2011-06-05 19:49:24','2011-06-05 19:49:24'),(6,'Power','Unlimited','Unlimited items for sale',9999,250,'2011-06-05 19:49:24','2011-06-05 19:49:24'),(7,'Power','Free subscription!','Free as in \"Beer\"!',9999,250,'2011-06-05 19:49:24','2011-06-05 19:49:24');
/*!40000 ALTER TABLE `package` ENABLE KEYS */;
UNLOCK TABLES;
