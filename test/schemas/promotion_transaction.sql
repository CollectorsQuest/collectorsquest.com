DROP TABLE IF EXISTS `promotion_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL,
  `collector_id` int(11) NOT NULL,
  `amount` float DEFAULT NULL,
  `amount_type` varchar(255) DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promotion_transaction_FI_1` (`promotion_id`),
  KEY `promotion_transaction_FI_2` (`collector_id`),
  CONSTRAINT `promotion_transaction_FK_1` FOREIGN KEY (`promotion_id`) REFERENCES `promotion` (`id`),
  CONSTRAINT `promotion_transaction_FK_2` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `promotion_transaction` WRITE;
/*!40000 ALTER TABLE `promotion_transaction` DISABLE KEYS */;
INSERT  IGNORE INTO `promotion_transaction` (`id`, `promotion_id`, `collector_id`, `amount`, `amount_type`, `created_at`) VALUES (1,2,11,250,'Fix','2011-06-06 08:08:21'),(2,2,19,250,'Fix','2011-06-09 17:52:10');
/*!40000 ALTER TABLE `promotion_transaction` ENABLE KEYS */;
UNLOCK TABLES;
