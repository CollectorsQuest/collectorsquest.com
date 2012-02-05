DROP TABLE IF EXISTS `sf_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commentable_model` varchar(30) DEFAULT NULL,
  `commentable_id` int(11) DEFAULT NULL,
  `comment_namespace` varchar(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `text` text,
  `author_id` int(11) DEFAULT NULL,
  `author_name` varchar(50) DEFAULT NULL,
  `author_email` varchar(100) DEFAULT NULL,
  `author_website` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_index` (`comment_namespace`,`commentable_model`,`commentable_id`),
  KEY `object_index` (`commentable_model`,`commentable_id`),
  KEY `author_index` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `sf_comment` WRITE;
/*!40000 ALTER TABLE `sf_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `sf_comment` ENABLE KEYS */;
UNLOCK TABLES;
