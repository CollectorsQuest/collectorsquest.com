DROP TABLE IF EXISTS `collectible_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectible_archive` (
  `id` int(11) NOT NULL,
  `graph_id` int(11) DEFAULT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `description` text NOT NULL,
  `num_comments` int(11) DEFAULT '0',
  `batch_hash` varchar(32) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `position` int(11) DEFAULT '0',
  `is_name_automatic` tinyint(1) DEFAULT '0',
  `eblob` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collectible_archive_I_1` (`id`),
  KEY `collectible_archive_I_2` (`graph_id`),
  KEY `collectible_archive_I_3` (`collector_id`),
  KEY `collectible_archive_I_4` (`slug`),
  KEY `collectible_archive_I_5` (`batch_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `collectible_archive` WRITE;
/*!40000 ALTER TABLE `collectible_archive` DISABLE KEYS */;
INSERT  IGNORE INTO `collectible_archive` (`id`, `graph_id`, `collector_id`, `name`, `slug`, `description`, `num_comments`, `batch_hash`, `score`, `position`, `is_name_automatic`, `eblob`, `updated_at`, `created_at`, `archived_at`) VALUES (56543,7849,6610,'ANTIQUE EP MAGIC LANTERN OUTFIT WITH SLIDES &amp; BOX  c1880','antique-ep-magic-lantern-outfit-with-slides-amp-box-c1880','<p>Early EP Magic Lantern outfit c1880\'s. Brass lens looks clear, has bronze toned paw feet. Stands approximately 13 inches high with chimney. Burner needs wicks &amp; fuel tank resolder. Comes with 10 viewable glass slides in need of edge rebinding. Has worn original box with label inside cover.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:11','2011-06-24 11:12:38','2011-06-24 11:12:38'),(56589,7861,6610,'LEITZ LEICA IIIa 35mm RANGEFINDER CAMERA W/ ELMAR LENS c1938','leitz-leica-iiia-35mm-rangefinder-camera-w-elmar-lens-c1938','<p>A clean pre WWII Leica IIIa 35mm film rangefinder camera. Serial #269944 (circa 1938). Body shows wear, rubs, a few small dings, areas of light brassing. Has complete body covering. Rangefinder is in good working order &amp; shutter is operating smoothly. Nice Elmar 5cm f3.5 lens, Serial #278301 (circa 1936), clean barrel &amp; optics, no fungus. Comes with metal Leica front cap &amp; worn Leica leather case.  </p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:13','2011-06-24 11:16:16','2011-06-24 11:16:16'),(56590,7862,6610,'KODAK SIGNET 35 1950\'s RETRO 35mm RANGEFINDER CAMERA','kodak-signet-35-1950s-retro-35mm-rangefinder-camera','<p>A clean Kodak Signet 35 rangefinder camera from the 1950\'s. Serial #172324. In overall excellent condition, no dings, and has complete body covering. Rangefinder in good working order. Shutter responding, not tested with film. Clean Ektar 44mm f3.5 lens with Serial #RR1120L.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:13','2011-06-24 11:22:40','2011-06-24 11:22:40'),(56593,7863,6610,'EARLY WOODEN EASTMAN KODAK CAMERA TRIPOD W/ BALL HEAD c1900','early-wooden-eastman-kodak-camera-tripod-w-ball-head-c1900','<p>A sturdy, well made wood &amp; brass camera tripod marked \"Eastman Kodak Company Patent Applied For\", circa early 1900\'s. Uncommon version with lever type leg locks, 4 section legs. All locks &amp; clamps are in good working order. Stands approximately 54 inches high fully extended. Very nice original finish. Rare early brass ball head included with 1/4 20 mounting threads.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:13','2011-06-24 11:32:22','2011-06-24 11:32:22'),(56594,7864,6610,'VOIGTLANDER VITESSA N 35mm RF CAMERA w/ SKOPAR, CASE &amp; IB c1954','voigtlander-vitessa-n-35mm-rf-camera-w-skopar-case-amp-ib-c1954','<p><strong>A clean Voigtlander Vitessa N (134) 35mm rangefinder camera. No dings or dents, a few minor bright marks. Shutter &amp; advance plunger mechanism is in good working order. Advance/shutter setting plunger has leather glued on top of button. Viewfinder has good brightness, but rangefinder focusing spot is not visible. Camera does focus smoothly with front standard &amp; distance indicator dial moving properly. Synchro Compur shutter is in good working order, not tested with film. Voigtlander Color Skopar 50mm f3.5 lens, Serial #3820490, looks clear, no fungus. Camera comes with original leather case, worn but complete owner\'s manual, and product brochure. Offered as found.</strong></p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:13','2011-06-23 17:42:55','2011-06-23 17:42:55'),(56596,7865,6610,'NIKON F 35mm SLR CAMERA W/ STANDARD PRISM &amp; NIKKOR LENS','nikon-f-35mm-slr-camera-w-standard-prism-amp-nikkor-lens','<p>A Nikon F 35mm SLR camera with standard eye level prism and Nikkor Q 135mm f3.5 lens. Camera shows Serial #6863780 and is in overall very good condition. No dings or dents, complete body covering with some light rubs &amp; bright marks overall. Shutter has smooth response. Standard prism has ex. appearance, only a few light rubs and a small area of separation visible in peak. Nikkor lens has serial #754858 with ex. cosmetics, no dings or dents, smooth focus, shows light film on aperture blades which are still responding nicely. Lens glass looks clean, clear, and fungus free. Comes with body cap for camera &amp; rear cap for lens.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:13','2011-06-23 17:41:12','2011-06-23 17:41:12'),(56597,7866,6610,'MINOX B SUBMINIATURE SPY CAMERA W/ CASE &amp; CHAIN ','minox-b-subminiature-spy-camera-w-case-amp-chain','<p>A Minox B subminature camera with leather case &amp; measuring chain. Serial #664416. Appearance is near mint, no dings or dents, and very clean. Shutter &amp; meter are showing good response. Complan 15mm lens looks clear. Collector grade example.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:13','2011-06-23 18:04:18','2011-06-23 18:04:18'),(56599,7867,6610,'MCINTOSH MC-30 POWER AMP TUBE AMPLIFIER A-116-B c1960\'s','mcintosh-mc-30-power-amp-tube-amplifier-a-116-b-c1960s','<p>Here is a well preserved classic McIntosh Model MC-30 Type A-116-B 30 watt mono amplifier, Serial #12547. Amp is clean with exterior showing some light pitting to chrome &amp; some wear/pitting to finish on transformer covers. Has recently been completely gone through &amp; professionally serviced. Amp will arrive with copy of service receipt and set to plug in &amp; use. Hard to find in ready-to-go working condition.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:15','2011-06-23 17:32:18','2011-06-23 17:32:18'),(56600,7868,6610,'PAILLARD BOLEX H-16M 16mm MOVIE CAMERA &amp; BERTHIOT ZOOM c1962 ','paillard-bolex-h-16m-16mm-movie-camera-amp-berthiot-zoom-c1962','<p>An uncommon Bolex H-16M 16mm movie camera, Serial #186216, with a Som Berthiot Pan-Cinor 17-85mm f2 zoom lens. Lens is also marked \"Bte\' S.G.D.G.\" Camera is in excellent cosmetic condition, no dings or dents. Motor runs smoothly, untested with film. Lens has overall ex. barrel cosmetics, only a few small areas of wear mainly on edges &amp; high points. Glass looks clear &amp; fungus free. Zoom and focus operate smoothly. Has reflex finder with diopter adjustment &amp; rubber eye cup.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:26:17','2011-06-23 17:51:08','2011-06-23 17:51:08'),(58221,7915,12,'IMG 2849','img-2849','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:13','2011-06-28 22:48:45','2011-06-28 22:49:43'),(58222,7916,12,'IMG 2848','img-2848','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:13','2011-06-28 22:48:50','2011-06-28 22:49:52'),(59895,8007,12,'Green Goblin','green-goblin-aa68cc','<p>with translucent variant.</p>\n',0,NULL,0,0,0,NULL,'2012-01-17 08:38:27','2011-07-15 14:18:53','2011-07-15 14:37:00'),(59918,8008,12,'IMG 3009','img-3009','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:27','2011-07-16 09:07:49','2011-07-16 09:08:23'),(59919,8009,12,'IMG 3010','img-3010','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:27','2011-07-16 09:07:54','2011-07-16 09:08:26'),(62788,8089,12,'IMG 3274','img-3274','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:39','2011-09-20 23:51:35','2011-09-20 23:52:13'),(62789,8090,12,'IMG 3273','img-3273','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:39','2011-09-20 23:51:41','2011-09-20 23:52:20'),(62790,8091,12,'IMG 3271','img-3271','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:39','2011-09-20 23:51:47','2011-09-20 23:52:16'),(62791,8092,12,'IMG 3270','img-3270','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:41','2011-09-20 23:51:52','2011-09-20 23:52:17'),(62792,8093,12,'IMG 3269','img-3269','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:41','2011-09-20 23:51:57','2011-09-20 23:52:18'),(63457,8131,12,'IMG 3436','img-3436','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:38:48','2011-10-24 09:22:29','2011-10-24 09:22:50'),(66552,8263,12,'IMG 3607','img-3607','\n',0,NULL,0,0,1,NULL,'2012-01-17 08:39:08','2011-12-19 22:22:40','2011-12-19 22:23:59');
/*!40000 ALTER TABLE `collectible_archive` ENABLE KEYS */;
UNLOCK TABLES;
