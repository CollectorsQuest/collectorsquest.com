DROP TABLE IF EXISTS `interview_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interview_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_interview_id` int(11) NOT NULL,
  `question` varchar(128) NOT NULL,
  `answer` text NOT NULL,
  `photo` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interview_question_FI_1` (`collector_interview_id`),
  CONSTRAINT `interview_question_FK_1` FOREIGN KEY (`collector_interview_id`) REFERENCES `collector_interview` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `interview_question` WRITE;
/*!40000 ALTER TABLE `interview_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `interview_question` ENABLE KEYS */;
UNLOCK TABLES;
