DROP TABLE IF EXISTS `sf_guard_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sf_guard_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `algorithm` varchar(128) NOT NULL DEFAULT 'sha1',
  `salt` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sf_guard_user_U_1` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `sf_guard_user` WRITE;
/*!40000 ALTER TABLE `sf_guard_user` DISABLE KEYS */;
INSERT  IGNORE INTO `sf_guard_user` (`id`, `username`, `algorithm`, `salt`, `password`, `created_at`, `last_login`, `is_active`, `is_super_admin`) VALUES (1,'kangov','sha1','88a650c045eefaa715ac60a4a739ef02','5e0ed2b66d833267e09b5a0f36b627342c2f77e1','2010-02-09 05:06:16','2011-11-08 12:04:00',1,1),(2,'ekressel','sha1','9e3ad250f0a313af8929d5cd71febbe1','dbc8298b2e1ae9c56454029670c133caf55fe8ba','2010-02-09 07:21:11','2011-11-08 12:07:59',1,1),(3,'http://collectorsquest.com/openid?id=113242502488094107765','sha1','b9092afa31afecabe82ff58f5e06a0e2','33b6175a7d3d19677d76cef732c03e535311fb04','2011-11-09 04:41:21','2012-02-01 15:07:45',1,0),(4,'http://collectorsquest.com/openid?id=103439943196298811318','sha1','e5a2bf53d50b124b938583f91977bcda','5d945e90d7d765af4c018712a7aabe5119fc58f1','2011-11-09 06:49:06','2012-02-01 13:26:11',1,0),(5,'http://collectorsquest.com/openid?id=110524575889695516643','sha1','f10aa6f43983466abf6fbf567b4b75da','847d1ccf7f97a9d062ed62a65fdd38637762fd85','2011-11-17 11:41:13','2012-01-25 12:29:15',1,0),(6,'http://collectorsquest.com/openid?id=116864780517068238415','sha1','62e978b64789a12fa6729420213bb0f4','98ce089bab4c1dbe86a920feb1a8903094d5c16f','2012-01-10 12:59:07','2012-01-19 15:22:05',1,0),(7,'http://collectorsquest.com/openid?id=101412284622327656684','sha1','62e978b64789a12fa6729420213bb0f4','98ce089bab4c1dbe86a920feb1a8903094d5c16f','2012-02-01 14:08:32','2012-02-01 14:08:35',1,1);
/*!40000 ALTER TABLE `sf_guard_user` ENABLE KEYS */;
UNLOCK TABLES;
