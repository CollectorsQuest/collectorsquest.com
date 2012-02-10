DROP TABLE IF EXISTS `package_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `collector_id` int(11) NOT NULL,
  `payment_status` varchar(255) DEFAULT 'pending',
  `max_items_for_sale` int(11) DEFAULT NULL,
  `package_price` float DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_transaction_FI_1` (`package_id`),
  KEY `package_transaction_FI_2` (`collector_id`),
  CONSTRAINT `package_transaction_FK_1` FOREIGN KEY (`package_id`) REFERENCES `package` (`id`),
  CONSTRAINT `package_transaction_FK_2` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `package_transaction` WRITE;
/*!40000 ALTER TABLE `package_transaction` DISABLE KEYS */;
INSERT  IGNORE INTO `package_transaction` (`id`, `package_id`, `collector_id`, `payment_status`, `max_items_for_sale`, `package_price`, `expiry_date`, `created_at`) VALUES (1,6,11,'paid',0,0,'2012-06-05 08:06:21','2011-06-06 08:08:21'),(2,6,19,'paid',6,0,'2012-06-08 05:06:10','2011-06-09 17:52:10');
/*!40000 ALTER TABLE `package_transaction` ENABLE KEYS */;
UNLOCK TABLES;
