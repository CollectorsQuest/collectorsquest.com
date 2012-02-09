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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `package_transaction` WRITE;
/*!40000 ALTER TABLE `package_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `package_transaction` ENABLE KEYS */;
UNLOCK TABLES;
