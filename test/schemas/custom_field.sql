DROP TABLE IF EXISTS `custom_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `object` text,
  `validation` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `custom_field` WRITE;
/*!40000 ALTER TABLE `custom_field` DISABLE KEYS */;
INSERT  IGNORE INTO `custom_field` (`id`, `name`, `type`, `object`, `validation`) VALUES (1,'Accessories',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(2,'Appellation',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(3,'Artist',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(4,'Athlete',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(5,'Audience Rating',3,'a:2:{s:4:\"name\";s:10:\"select_tag\";s:6:\"values\";a:6:{i:0;s:7:\"5 stars\";i:1;s:7:\"4 stars\";i:2;s:7:\"3 stars\";i:3;s:7:\"2 stars\";i:4;s:6:\"1 star\";i:5;s:8:\"no stars\";}}',''),(6,'Authors',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(7,'Autographed',4,'a:2:{s:4:\"name\";s:10:\"select_tag\";s:6:\"values\";a:2:{s:3:\"Yes\";s:20:\"Yes, autographed :-)\";s:2:\"No\";s:23:\"No, not autographed :-(\";}}',''),(8,'Body Materials',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(9,'Book Value',3,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:10;}}',''),(10,'Brand Advertised',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(11,'Cast',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(12,'Catalog',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(13,'Category',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(14,'Celebrity Endorsement',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(15,'Color',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(16,'Colorists',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(17,'Composer',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(18,'Condition',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(19,'Country of Origin',1,'a:1:{s:4:\"name\";s:18:\"select_country_tag\";}',''),(20,'Cover Artists',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(21,'Cover Price',3,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:10;}}',''),(22,'Date Acquired',2,'a:2:{s:4:\"name\";s:15:\"select_date_tag\";s:7:\"options\";a:1:{s:10:\"year_start\";i:1900;}}',''),(23,'Date Created',2,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:10;}}',''),(24,'Date Issued',2,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:10;}}',''),(25,'Date Made',2,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:10;}}',''),(26,'Date of Production',2,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:10;}}',''),(27,'Date Published',2,'a:2:{s:4:\"name\";s:14:\"input_date_tag\";s:7:\"options\";a:1:{s:10:\"year_start\";i:1900;}}',''),(28,'Date Signed',2,'a:1:{s:4:\"name\";s:14:\"input_date_tag\";}',''),(29,'Denomination',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(30,'Designer',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(31,'Developer',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(32,'Devices',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(33,'Dimensions',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}','a:2:{s:5:\"regex\";s:36:\"(\\d+)(\\w+)\\/(\\d+)(\\w+)\\/(\\d+)(\\w+)\\/\";s:7:\"message\";s:61:\"These are not valid dimentions. (ex. 14\"/15\"/18\" or 5m/6m/9m)\";}'),(34,'Director',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(35,'Duration',1,'a:3:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:5;}s:6:\"append\";s:7:\"minutes\";}',''),(36,'Edge',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(37,'Edition',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(38,'Edition Number',3,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:3;}}',''),(39,'Editors',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(40,'Format',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(41,'Frequency',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(42,'Game Used',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(43,'Genre',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(44,'Hair Materials',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(45,'Inkers',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(46,'ISBN',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}','a:2:{s:5:\"regex\";s:23:\"(\\d[- ]?){9,9}([0-9xX])\";s:7:\"message\";s:26:\"Invalid ISBN number format\";}'),(47,'Issue',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(48,'Issue Number',3,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:4;}}',''),(49,'Issuing Authority',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(50,'Item Signed',4,'a:2:{s:4:\"name\";s:10:\"select_tag\";s:6:\"values\";a:2:{s:3:\"Yes\";s:15:\"Yes, signed :-)\";s:2:\"Nn\";s:18:\"No, not signed :-(\";}}',''),(51,'Label',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(52,'Language',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(53,'Length',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(54,'Letterers',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(55,'Manufacturer',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(56,'Mark',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(57,'Material',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(58,'Media',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(59,'Mint',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(60,'Mint Mark',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(61,'Model',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(62,'Nib',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(63,'Number of Bottles',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(64,'Original',4,'a:2:{s:4:\"name\";s:10:\"select_tag\";s:6:\"values\";a:2:{s:3:\"Yes\";s:17:\"Yes, original :-)\";s:2:\"No\";s:20:\"No, not original :-(\";}}',''),(65,'Pages',3,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:5;}}',''),(66,'Pencillers',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(67,'Perforation',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(68,'Period',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(69,'Platform',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(70,'Print Type',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(71,'Printer',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(72,'Printing Process',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(73,'Producer(s)',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(74,'Provenance',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(75,'Publisher',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(76,'Region',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(77,'Release Date',2,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(78,'Serial Number',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(79,'Series',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(80,'Series Number',3,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:4;}}',''),(81,'Signature Medium',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(82,'Size',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(83,'Sport',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(84,'Stamp Type',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(85,'Studio',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(86,'Team',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(87,'Title',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(88,'Toy Type',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(89,'Tracks',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(90,'UPC Code',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(91,'Varietal',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(92,'Vehicle Name',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(93,'Vineyard',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(94,'Vintage',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(95,'Volume',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(96,'Watermark',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(97,'Weight',3,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(98,'Wheel',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(99,'Writers',1,'a:1:{s:4:\"name\";s:9:\"input_tag\";}',''),(100,'Year',3,'a:2:{s:4:\"name\";s:9:\"input_tag\";s:7:\"options\";a:1:{s:9:\"maxlength\";i:4;}}','');
/*!40000 ALTER TABLE `custom_field` ENABLE KEYS */;
UNLOCK TABLES;
