DROP TABLE IF EXISTS `collector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `graph_id` int(11) DEFAULT NULL,
  `facebook_id` varchar(20) DEFAULT NULL,
  `username` varchar(64) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `sha1_password` varchar(40) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `user_type` enum('Collector','Seller') NOT NULL DEFAULT 'Collector',
  `items_allowed` int(11) DEFAULT NULL,
  `what_you_collect` varchar(255) DEFAULT NULL,
  `purchases_per_year` int(11) DEFAULT '0',
  `what_you_sell` varchar(255) DEFAULT NULL,
  `annually_spend` float DEFAULT '0',
  `most_expensive_item` float DEFAULT '0',
  `company` varchar(255) DEFAULT NULL,
  `locale` varchar(5) DEFAULT 'en_US',
  `score` int(11) DEFAULT '0',
  `spam_score` int(11) DEFAULT '0',
  `is_spam` tinyint(1) DEFAULT '0',
  `is_public` tinyint(1) DEFAULT '1',
  `session_id` varchar(32) DEFAULT NULL,
  `last_seen_at` datetime DEFAULT NULL,
  `eblob` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collector_U_3` (`slug`),
  UNIQUE KEY `collector_U_1` (`graph_id`),
  UNIQUE KEY `collector_U_2` (`facebook_id`),
  UNIQUE KEY `collector_U_4` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collector` WRITE;
/*!40000 ALTER TABLE `collector` DISABLE KEYS */;
INSERT  IGNORE INTO `collector` (`id`, `graph_id`, `facebook_id`, `username`, `display_name`, `slug`, `sha1_password`, `salt`, `email`, `user_type`, `items_allowed`, `what_you_collect`, `purchases_per_year`, `what_you_sell`, `annually_spend`, `most_expensive_item`, `company`, `locale`, `score`, `spam_score`, `is_spam`, `is_public`, `session_id`, `last_seen_at`, `eblob`, `created_at`, `updated_at`) VALUES (1,NULL,'501327639','resonantfish','Robotbacon','robotbacon','94bf9c1e72028441b35d81551eeb36977b032e31','777b485dd5cfbc003525430040587aa0','collin@collectorsquest.com','Seller',1000,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'tbnbcs8jkrj4u3ac42f3p46927','2012-01-31 16:41:44',NULL,'2007-05-31 14:21:52','2012-01-31 16:41:44'),(2,NULL,NULL,'dinocollector','Dinocollector','dinocollector','53ca290fc733df31b90a5fe3d6f0f4bf4ed674bf','93aba57b8ca0c64e9bb3a88f9a9353e1','bfplatt@ku.edu','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2011-06-13 00:00:00',NULL,'2007-06-19 01:15:50','2012-01-05 15:06:03'),(3,NULL,NULL,'poptart','Poptart','poptart','6314456d348131bc694c65b50d14d72ec85899aa','dcf45611bedf847e93193bdeb5dc89b4','Deanna.Pop.Tart@gmail.com','Seller',1000,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2011-06-24 00:00:00',NULL,'2007-06-24 01:24:56','2012-01-20 15:16:22'),(4,NULL,NULL,'pmurph','pmurph','pmurph','8d3fc41fc527899c2b0ca8daf5b3fd035a37d523','d5679e47b535554e781841541ed5a086','paulmurphy777@yahoo.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2007-11-25 00:00:00',NULL,'2007-09-15 22:27:59','2012-01-05 15:10:49'),(5,NULL,NULL,'collectio','Collectio','collectio','37076d6ddaee8c09f8b7e0f6bc425df3a80edd31','48bbd197c92ca1fec8e6a7cc34ed0051','collectio@mail.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,40,0,1,NULL,'2009-01-28 00:00:00',NULL,'2008-01-02 18:42:45','2012-01-05 15:13:44'),(6,NULL,NULL,'blackgem','Blackgem','blackgem','9b01c3491791a71a5857d348888e5e54b12b30ad','0b3345adfdd3211ee6bd841f180c22ec','rselcoe@entouchonline.net','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2009-01-01 00:00:00',NULL,'2008-04-06 11:43:49','2012-01-05 15:25:43'),(7,NULL,NULL,'barry2952','Barry2952','barry2952','4e274a46310e1cc6605a9b94fbc0e874016913b7','60fbf91c8c088cd15ef4bee57b49e953','barry2952@twmi.rr.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'uk0etad72vq2iul0b0i8idh1i3','2010-10-06 00:00:00',NULL,'2008-06-24 17:03:10','2012-01-05 15:30:03'),(8,NULL,NULL,'sleeves','Sleeves','sleeves','08a6f024a0629880139f3a1b0903692736a9530e','f64acb350cfbc08294d2da3de51d2ff2','sleeves@home.nl','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2009-01-22 00:00:00',NULL,'2008-08-23 09:25:20','2012-01-05 15:32:53'),(9,NULL,NULL,'francesca76','Francesca76','francesca76','69f15ff0e3c8534e7576eb3eb03c1a407a8135d4','85e0ca4d66214f86f3820db7fe0c9400','baifrancesca@gmail.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2008-12-21 00:00:00',NULL,'2008-12-12 12:58:56','2012-01-05 15:38:00'),(10,NULL,NULL,'dinoobcessed','Dinoobcessed','dinoobcessed','1f55d8470f54addc19b4cfba31ceaddd3785882e','5e0f08508a2ab196dceec1fd7e8b110d','permian123@yahoo.com','Seller',1000,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'f70go3q86sv5k6fosja8nanob1','2012-01-04 00:00:00',NULL,'2008-12-27 23:20:15','2012-01-05 15:39:00'),(11,NULL,NULL,'trbuttons','Trbuttons','trbuttons','756f60b8c7d9ac188605f4f775cf6d4df75e6c85','5c1fbad789e9cf38e3ad7c623db6fdc4','TRbuttons@aol.com','Seller',-3,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'68tgbaqbqug16250jplgmhfam1','2011-06-15 00:00:00',NULL,'2009-02-04 21:13:10','2012-01-05 15:42:37'),(12,NULL,NULL,'googlecool97','Googlecool97','googlecool97','2cc36b8571d7f6707722531c7cfb73c7c02b6e9a','160fab3d1d5f98eed462d8ffd2ab8b77','brini123@gmail.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2009-06-22 00:00:00',NULL,'2009-03-12 22:00:40','2012-01-05 15:47:27'),(13,NULL,NULL,'tofunky','Tofunky','tofunky','3fb7deb6aee6fdf6e7b662d1a32c99a735523abd','502eff96a82283a5f1a628195523587d','tofunky@mac.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'0bmim9mlsknbelo48cev6iuoj5','2012-01-24 16:01:30',NULL,'2009-05-03 18:58:54','2012-01-24 16:01:30'),(14,NULL,NULL,'wildheart','Wildheart','wildheart','7b7f6bcb4d34431384c129f33deac3bd257c298d','40a85e4bd1b6b51827d4d823657f9dcd','mih.nicolae@yahoo.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'j3b4h3kf33j3lseo4lttbqhc75','2011-04-06 00:00:00',NULL,'2009-06-15 15:10:12','2012-01-05 15:56:54'),(15,NULL,NULL,'duckland','Duckland','duckland','df33ab65ba95345cd45efe70803fc11bf50189f6','fc7f8cae18c1770ea01a2fdbf639670f','iscully@hotmail.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,NULL,'2009-07-21 00:00:00',NULL,'2009-07-15 07:21:05','2012-01-05 15:59:08'),(16,NULL,NULL,'Distinctive Styles','Distinctive Styles','distinctive-styles','2fa12fb5762e7b614d0bb0fabb8ba1d4a8080d37','01537b4417e5b0dd4ce511e2f18c75e9','distinctivestyles@yahoo.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'j3b4h3kf33j3lseo4lttbqhc75','2011-04-06 00:00:00',NULL,'2010-06-27 10:19:23','2012-01-05 17:18:18'),(17,NULL,NULL,'freeburgh','Houston Freeburg Collection','houston-freeburg-collection','8c688dbf80061b40240fe959a1735d38a55f7351','f9920cf3fcf2dc3afe0b729a5442a66f','freeburgh@bellsouth.net','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'02k168r34n7fl3tpvflvasro73','2010-09-12 00:00:00',NULL,'2010-09-12 12:04:44','2012-01-05 17:40:41'),(18,NULL,NULL,'kalexk13','Rubbie Ducks','rubbie-ducks','03c3021f337b6f2652bcb99e9d5de657a64880c9','72e12f57a1dce6824ef20b538b6ca18f','kaley523@gmail.com','Collector',0,NULL,0,NULL,0,0,NULL,'en_US',0,5,0,1,'rc7e7aqdnr8mjbcc6rbgliabl0','2010-09-28 00:00:00',NULL,'2010-09-28 20:51:11','2012-01-05 17:44:01'),(19,NULL,NULL,'donnalee','stardust06242','stardust06242','29913d9ebfbf3a68a4cfe5e5736862e52958bb91','f9a40fb7ef832dda6156ffed71430c4b','stardust466@juno.com','Seller',-29,'',0,'photographica & antiques',0,0,NULL,'en_US',0,5,0,1,NULL,'2011-06-24 00:00:00',NULL,'2011-06-09 17:51:10','2012-01-06 04:35:32'),(20,NULL,NULL,'ShabbyChicPickerChick','ShabbyChicPickerChick','shabbychicpickerchick','81b2e38a6007b53095601c7821daacc17495d210','3c0ed8bec5264b46dd4fe7df60d39ffd','shabbychicpickerchick@hotmail.com','Seller',-1,'Unique pottery, antique cameras, furniture, if it catches my eye...it is a keeper',0,'antiques, collectibles, retro you name it I might have it or will find it..',0,0,'Shabby Chic Picker Chick Antiques & Collectibles','en_US',0,5,0,1,'b132g7j82p1hg39a2o06upgll0','2011-08-24 00:00:00',NULL,'2011-06-13 21:45:21','2012-01-06 04:37:52');
/*!40000 ALTER TABLE `collector` ENABLE KEYS */;
UNLOCK TABLES;
