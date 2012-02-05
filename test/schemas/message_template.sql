DROP TABLE IF EXISTS `message_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `message_template` WRITE;
/*!40000 ALTER TABLE `message_template` DISABLE KEYS */;
INSERT  IGNORE INTO `message_template` (`id`, `subject`, `body`, `description`, `updated_at`, `created_at`) VALUES (1,'Welcome to CollectorsQuest.com','<p>Welcome to Collectors\' Quest - the destination place for all collectors.</p>\r\n<p>Get started by <a href=\"{route.collection_create}\">adding your own collection</a> or just click around to find some great <a href=\"{route.collections}\">collections</a> and <a href=\"{route.collectors}\">collectors</a>.</p>\r\n<p>What\'s in your collection?</p>','','2010-02-10 09:37:20',NULL),(2,'{sender.display_name} sent you a message on CollectorsQuest...','<p>{sender.display_name} sent you a message.<br /><br />--------------------<br />{message.subject}<br /><br />{message.body}<br />--------------------<br /><br />To reply to this message, follow the link below:<br />{route.message_reply}<br /><br />___<br />This message was intended for {receiver.email}. Want to control which emails you receive from CollectorsQuest.com? Go to:<br />{route.manage_preferences}</p>','','2010-02-10 15:50:53','2010-02-10 15:50:53');
/*!40000 ALTER TABLE `message_template` ENABLE KEYS */;
UNLOCK TABLES;
