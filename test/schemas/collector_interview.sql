DROP TABLE IF EXISTS `collector_interview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector_interview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) DEFAULT NULL,
  `collection_category_id` int(11) DEFAULT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `catch_phrase` varchar(128) NOT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collector_interview_FI_1` (`collector_id`),
  KEY `collector_interview_FI_2` (`collection_category_id`),
  KEY `collector_interview_FI_3` (`collection_id`),
  CONSTRAINT `collector_interview_FK_1` FOREIGN KEY (`collector_id`) REFERENCES `collector` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collector_interview_FK_2` FOREIGN KEY (`collection_category_id`) REFERENCES `collection_category` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collector_interview_FK_3` FOREIGN KEY (`collection_id`) REFERENCES `collection` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector_interview` WRITE;
/*!40000 ALTER TABLE `collector_interview` DISABLE KEYS */;
INSERT  IGNORE INTO `collector_interview` (`id`, `collector_id`, `collection_category_id`, `collection_id`, `title`, `catch_phrase`, `is_active`, `created_at`) VALUES (1,2,102,35,'Dino-palooza','Dinocollector shows us that there is an upside to extinction... more toys!',1,'2008-10-06 15:01:30'),(2,9,51,133,' A Little Help From My Friends','Francesa76 counts on family and friends to deliver the goods.  ',1,'2008-12-15 10:16:58'),(3,11,755,143,'Political Interview 2','<b>Trbuttons</b> enlightens us on how to speak softly and carry a big pin.',1,'2009-02-18 11:13:53'),(4,13,766,174,'michael jackson interview 2','<b>Tofunky</b> embraces all that is funk. This week she shares her supa\' fine collection of Michael Jackson related comics. ',1,'2009-07-24 12:33:08'),(5,1,772,2,'batman interview 2','Holy collectors, Batman! Check out <b>robotbacon</b>\'s collection that is all about the Dark Knight.',1,'2009-08-28 14:54:42');
/*!40000 ALTER TABLE `collector_interview` ENABLE KEYS */;
UNLOCK TABLES;
