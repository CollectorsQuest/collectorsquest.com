DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `slug` varchar(64) DEFAULT NULL,
  `description` text NOT NULL,
  `type` varchar(64) NOT NULL,
  `length` int(11) DEFAULT NULL,
  `filename` varchar(128) DEFAULT NULL,
  `thumb_small` varchar(128) DEFAULT NULL,
  `thumb_large` varchar(128) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `uploaded_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
INSERT  IGNORE INTO `video` (`id`, `title`, `slug`, `description`, `type`, `length`, `filename`, `thumb_small`, `thumb_large`, `is_published`, `published_at`, `uploaded_at`, `created_at`) VALUES (1,'Holy Collection Batman! ','holy-collection-batman','Frank goes batty over the Caped Crusader and the Justice League\'s inner circle.','Programming',112,'batman.flv','batman.jpg','batman_front.jpg',1,'2007-05-27 09:14:01','2007-05-27 09:14:01','2007-05-27 09:14:01'),(2,'Trading Up ','trading-up','Eryka will do anything to get one more Pokemon card. Mom, hide your keys!','Programming',76,'pokemon.flv','pokemon.jpg','pokemon_front.jpg',1,'2007-05-27 09:14:02','2007-05-27 09:14:02','2007-05-27 09:14:02'),(3,'I\'ll Drink to That ','ill-drink-to-that','Jeremy likes them dark and full-bodied...and we haven\'t even started on his taste in wine.','Programming',132,'wine.flv','wine.jpg','wine_front.jpg',1,'2007-05-27 09:14:03','2007-05-27 09:14:03','2007-05-27 09:14:03'),(4,'Heavy Metal ','heavy-metal','Ty travels the world over to bring a little piece of metal back home','Programming',111,'metal.flv','metal.jpg','metal_front.jpg',1,'2007-05-27 09:14:04','2007-05-27 09:14:04','2007-05-27 09:14:04'),(5,'Too Much Time on His Hands ','too-much-time-on-his-hands','Almost none of Stephen\'s 200 clocks are set to the same time. It\'s enough to drive one cuckoo!','Programming',105,'clock.flv','clock.jpg','clock_front.jpg',1,'2007-05-27 09:14:05','2007-05-27 09:14:05','2007-05-27 09:14:05'),(6,'Take Me Out ','take-me-out','Bruce goes All-American in his quest for the best in baseball memorabilia.','Programming',193,'baseball.flv','baseball.jpg','baseball_front.jpg',1,'2007-05-27 09:14:06','2007-05-27 09:14:06','2007-05-27 09:14:06'),(7,'Puppy Love ','puppy-love','Frank\'s poodles are on parade in a high traffic zone - his bathroom!','Programming',74,'poodle.flv','poodle.jpg','poodle_front.jpg',1,'2007-05-27 09:14:07','2007-05-27 09:14:07','2007-05-27 09:14:07'),(8,'Shake It Up ','shake-it-up','Judy is a seasoned collector of salt and pepper shakers, from the whimsical to the downright wacky.','Programming',67,'salt.flv','salt.jpg','salt_front.jpg',1,'2007-05-27 09:14:08','2007-05-27 09:14:08','2007-05-27 09:14:08'),(9,'Bust a Move ','bust-a-move','Chuck is heads above the rest when it comes to his cookie jar collection.','Programming',95,'cookie.flv','cookie.jpg','cookie_front.jpg',1,'2007-05-27 09:14:09','2007-05-27 09:14:09','2007-05-27 09:14:09'),(10,'Into the Groove ','into-the-groove','Ron tells us what gets his tables spinning on his quest to complete his funk and soul collections.','Programming',83,'music.flv','music.jpg','music_front.jpg',1,'2007-05-27 09:14:10','2007-05-27 09:14:10','2007-05-27 09:14:10'),(11,'And It\'s Not Even Christmas... ','and-its-not-even-christmas','Sideshow Collectibles rolls out limited edition series for Universal Monsters, LOTR, Marvel, Star Wars, James Bond, and Kiss among others.','Event',45,'sideshow.flv','sideshow.jpg','sideshow_front.jpg',0,'2007-05-27 09:14:11','2007-05-27 09:14:11','2007-05-27 09:14:11'),(12,'What Barbie Means to Me ','what-barbie-means-to-me','A Mattel expert talks about her nostalgic relationship with Barbie.','Event',43,'barbie.flv','barbie.jpg','barbie_front.jpg',0,'2007-05-27 09:14:11','2007-05-27 09:14:11','2007-05-27 09:14:11'),(13,'Along for the Ride ','along-for-the-ride','Corgi capitalizes on the upcoming summer blockbusters with Spiderman, Pirates of the Caribbean, Star Wars and The Golden Compass product.','Event',41,'corgi.flv','corgi.jpg','corgi_front.jpg',0,'2007-05-27 09:14:11','2007-05-27 09:14:11','2007-05-27 09:14:11'),(14,'Up, Up and Away ','up-up-and-away','Our very own Collin David speaks about why he\'s excited about DC Super Heroes new product line.','Event',46,'mattel.flv','mattel.jpg','mattel_front.jpg',0,'2007-05-27 09:14:11','2007-05-27 09:14:11','2007-05-27 09:14:11'),(15,'A Doll\'s Life ','a-dolls-life','Madame Alexander premieres its Desperate Housewives and Wicked dolls and reintroduces Eloise, Madeleine and Cessete.','Event',41,'alex.flv','alex.jpg','alex_front.jpg',0,'2007-05-27 09:14:11','2007-05-27 09:14:11','2007-05-27 09:14:11'),(16,'Move Over Chitty ','move-over-chitty','Robert Luczun shares his love of comics by covering a vintage car with all of our favorites.','Event',44,'carguy.flv','carguy.jpg','carguy_front.jpg',1,'2007-05-27 09:14:03','2007-05-27 09:14:03','2007-05-27 09:14:03'),(17,'Going to X-tremes ','going-to-x-tremes','Two X-Men fans show us what they got, what they love and what they have to have.','Event',44,'phx.flv','phx.jpg','phx_front.jpg',1,'2007-05-27 09:14:04','2007-05-27 09:14:04','2007-05-27 09:14:04'),(18,'Stormtroopers in Love ','stormtroopers-in-love','Star Wars is the order of the day in life, love and marriage.','Event',44,'troopers.flv','troopers.jpg','troopers_front.jpg',1,'2007-05-27 09:14:06','2007-05-27 09:14:06','2007-05-27 09:14:06'),(19,'Love and Comics ','love-and-comics','The Geppi Museum\'s curator talks about how comic books have affected our culture.','Event',44,'geppi.flv','geppi.jpg','geppi_front.jpg',1,'2007-05-27 09:14:05','2007-05-27 09:14:05','2007-05-27 09:14:05'),(20,'NY Comic Con 2007 ','ny-comic-con-2007','Pan the crowds as Collectors\' Quest takes you through a quick video montage of the event.','Event',44,'opening.flv','opening.jpg','opening_front.jpg',1,'2007-05-27 09:14:02','2007-05-27 09:14:02','2007-05-27 09:14:02'),(21,'You\'ll Never Forget Your First ','youll-never-forget-your-first','Comic Con Fans tell us what got their mojo going.','Event',44,'first.flv','first.jpg','first_front.jpg',1,'2007-05-27 09:14:01','2007-05-27 09:14:01','2007-05-27 09:14:01'),(22,'What\'s in Your Collection? ','whats-in-your-collection','Collectors wax poetic about their comic book faves.','Event',44,'collection.flv','collection.jpg','collection_front.jpg',1,'2007-05-27 09:14:07','2007-05-27 09:14:07','2007-05-27 09:14:07'),(23,'The Drycleaning Must Cost a Fortune ','the-drycleaning-must-cost-a-fortune','NY Comic Con in all of its costumed glory','Event',44,'close.flv','close.jpg','close_front.jpg',1,'2007-05-27 09:14:08','2007-05-27 09:14:08','2007-05-27 09:14:08'),(24,'Bombs Away! ','bombs-away','21st Century Toys premieres an amazingly detailed scale model of the Night Fighter aircraft from the WWII era. Enemies beware!','Event',109,'21stANew.flv','21stANew_front.jpg','21stANew.jpg',1,'2008-05-13 00:00:00',NULL,'2008-04-23 12:57:26'),(25,'Color Me Beautiful ','color-me-beautiful','Madame Alexander introduces the beautiful, authentic Ethidolls series based on historical women from Africa. ','Event',146,'AlexanderEthiNew2.flv','AlexanderEthiNew2_front.jpg','AlexanderEthiNew2.jpg',NULL,NULL,NULL,'2008-04-23 14:04:08'),(26,'Heads Will Roll ','heads-will-roll','Evil Doers can finally have Batman\'s head!  Diamond Comics shows off this awesome ½ scale bust of Gotham\'s Caped Crusader.  ','Event',56,'DiamondDCNewA.flv','DiamondDCNewA_front.jpg','DiamondDCNewA.jpg',1,'2008-05-13 00:00:00',NULL,'2008-04-23 14:32:23'),(27,'Posterboy ','posterboy','DC Direct\'s new line of Batman inspired artwork by a number of amazing artists.','Event',57,'DiamondDCNewB.flv','DiamondDCNewB_front.jpg','DiamondDCNewB.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:44'),(28,'Lookin\' Good! ','lookin-good','DC Direct\'s Ame-Comi series blends popular heroines from the DC universe with the Manga style of Japan. ','Event',65,'DiamondDCNewC.flv','DiamondDCNewC_front.jpg','DiamondDCNewC.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(29,'Something Old, Something New ','something-old-something-new','A fresh look at these re-released classic DC Comic figures as well as a new line complete with interchangeable features!','Event',67,'DiamondDCNewD.flv','DiamondDCNewD_front.jpg','DiamondDCNewD.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(30,'Ultimate Trio ','ultimate-trio','Batman, Superman and Wonder Woman join forces once again in this new action figure series from DC Direct called Trinity!','Event',57,'DiamondDCNewE.flv','DiamondDCNewE_front.jpg','DiamondDCNewE.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(31,'Spreading Some Christmas Fear ','spreading-some-christmas-fear','JUN Planning brings nightmares to life with their ¼ scale diorama scenes from Tim Burton\'s The Nightmare Before Christmas.','Event',95,'Jun2.flv','Jun2_front.jpg','Jun2.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(32,'Kids These Days! ','kids-these-days','Kingstate showcases their new line of quality, lifelike vinyl and porcelain baby dolls.  No shower gifts needed.','Event',83,'Kingstate.flv','Kingstate_front.jpg','Kingstate.jpg',0,NULL,NULL,'2012-02-10 13:05:44'),(33,'Time to Play, Dr. Jones!  ','time-to-play-dr-jones','Lego Systems showcases their new Indiana Jones line featuring sets based off of each of the Indiana Jones movies! ','Event',54,'Lego01.flv','Lego01_front.jpg','Lego01.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(34,'In a Toy Bin Far, Far Away! ','in-a-toy-bin-far-far-away','Lego Systems\' Clone Wars line debuts here at Toy Fair \'08 showcasing the Republic\'s formidable gun ship set. ','Event',49,'Lego02.flv','Lego02_front.jpg','Lego02.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(35,'Go Speedracer! ','go-speedracer','Lego shows off their Speed Racer line in conjunction with the big screen release. Take the Mach 5 out for a test drive on the Grand Prix!','Event',45,'Lego03.flv','Lego03_front.jpg','Lego03.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(36,'There Will Be Hell to Pay! ','there-will-be-hell-to-pay','Mezco Toys brings us their new line of Hellboy action figures and busts just in time for the release of Hellboy 2.','Event',67,'Mezco01.flv','Mezco01_front.jpg','Mezco01.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(37,'There Goes My Hero ','there-goes-my-hero','Mezco debuting their Heroes line at Toy Fair 2008 with these incredibly life-like figures from the hit tv show on NBC. ','Event',101,'Mezco02.flv','Mezco02_front.jpg','Mezco02.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(38,'Magical Mythery Tour ','magical-mythery-tour','Safari Ltd. takes us on a fantastic journey through their Mythological Realms collection.','Event',79,'Safari01.flv','Safari01_front.jpg','Safari01.jpg',1,'2008-05-15 00:00:00',NULL,'2012-02-10 13:05:44'),(39,'Animal Instincts ','animal-instincts','Bring home the jungle to you with  Safari LTD\'s number one line, Wildlife Wonders. ','Event',73,'Safari02.flv','Safari02_front.jpg','Safari02.jpg',1,'2008-05-15 00:00:00',NULL,'2012-02-10 13:05:44'),(40,'Party Down, Papa Smurf! ','party-down-papa-smurf','Schleich presents their 50th anniversary Smurf collection. Even Gargamel and Azrael have joined the celebration!','Event',105,'Schleich01.flv','Schleich01_front.jpg','Schleich01.jpg',1,'2008-05-15 00:00:00',NULL,'2012-02-10 13:05:44'),(41,'Living the Fantasy ','living-the-fantasy','Schleich expands their bestselling  World of Elves line with brand new enchanted figures.','Event',117,'Schleich02.flv','Schleich02_front.jpg','Schleich02.jpg',1,'2008-05-15 00:00:00',NULL,'2012-02-10 13:05:44'),(42,'PEZHEADS: The Producer','pezheads-the-producer','Chris Skeene talks PEZ, the documentary and how he saves on flowers.','Event',66,'ChrisSkeene.flv','ChrisSkeene_front.jpg','ChrisSkeene.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(43,'Pez Pals ','pez-pals','The Gliha\'s, founders of Pezamania, show us some of their favorites from their own collection.','Event',77,'GlihaCouple.flv','GlihaCouple_front.jpg','GlihaCouple.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(44,'Anything but Conventional ','anything-but-conventional','Pez fans gather at the yearly NE Pez Convention and show us their devotion!','Event',51,'MontageDialogue.flv','MontageDialogue_front.jpg','MontageDialogue.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(45,'A Rich and Full Life ','a-rich-and-full-life','Richard Belyski talks about his life as a Pez fanatic. ','Event',93,'RichBelyski.flv','RichBelyski_front.jpg','RichBelyski.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(46,'Pez and Shoulders Above the Rest ','pez-and-shoulders-above-the-rest','Pez guru Shawn Peterson gives us a history lesson on Pez. ','Event',82,'ShawnPeterson.flv','ShawnPeterson_front.jpg','ShawnPeterson.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(47,'He\'s So Dreamy... ','hes-so-dreamy','Steiff unveils their latest pieces at Toy Fair 2008 including Joseph, The Technicolor Dream Bear.','Event',61,'Stife01.flv','Stife01_front.jpg','Stife01.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(48,'Grin and Bear It ','grin-and-bear-it','Steiff presents to us the award winning Duncan teddy bear from Toy Fair 2008.','Event',69,'Stife02.flv','Stife02_front.jpg','Stife02.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(49,'The Unbearable Likeness of Being ','the-unbearable-likeness-of-being','Steiff presents the limited edition Teddy Roosevelt bear.','Event',65,'Stife03.flv','Stife03_front.jpg','Stife03.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(50,'Speak Up! ','speak-up','Steiff\'s 100th anniversary of the Growler makes Teddy roar!','Event',62,'Stife04.flv','Stife04_front.jpg','Stife04.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(51,'Masterpiece Pooch ','masterpiece-pooch','Steiff debuts their life-like Lumpy puppy. No walking required!','Event',64,'Stife05.flv','Stife05_front.jpg','Stife05.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(52,'Take to the Skies ','take-to-the-skies','TMC Pacific Modelworks\' propels us through an\r\nexploration of scale airplane replicas. Barrel\r\nrolls optional.\r\n','Event',114,'TMC01.flv','TMC01_front.jpg','TMC01.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(53,'Wings of Glory ','wings-of-glory','TMC Pacific Modelworks pilots\r\nus through a fleeting glance at their collection of model airplane\r\nsquadrons.\r\n','Event',73,'TMC02.flv','TMC02_front.jpg','TMC02.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(54,'Fly Me to the Moon ','fly-me-to-the-moon','TMC Pacific\r\nModelworks takes us on an extraterrestrial journey through their new series of scale replica space capsules.\r\n','Event',116,'TMC03.flv','TMC03_front.jpg','TMC03.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(55,'Movie Madness ','movie-madness','Tonner introduces their new lines of cinematic figures from Harry Potter, Golden Compass, Pirates of the Caribbean and more!','Event',141,'Tonner01.flv','Tonner01_front.jpg','Tonner01.jpg',1,'2008-05-13 00:00:00',NULL,'2012-02-10 13:05:44'),(56,'Fairy, Fairy Quite Contrary ','fairy-fairy-quite-contrary','Charisma Brands takes flight with it\'s new line of Whispering Willow Fairies.','Event',110,'Whisperin01.flv','Whisperin01_front.jpg','Whisperin01.jpg',1,'2008-05-15 00:00:00',NULL,'2012-02-10 13:05:45'),(57,'Road Trip','road-trip','The first American Muscle Car - an 1907 American Underslung, takes Best in Show by owner Van Horneff.','Event',171,'cars/Underslung Final 800.flv','cars/Underslung Final 800_front.jpg','cars/Underslung Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2008-09-02 19:38:46'),(58,'Yeah Baby!','yeah-baby','Larry Sachs shows us the sex appeal of the Corvette from The Spy Who Shagged Me.','Event',165,'cars/Austin Vette Final 800.flv','cars/Austin Vette Final 800_front.jpg','cars/Austin Vette Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2008-09-02 19:38:46'),(59,'That\'s a Spice-y Ferrari!','thats-a-spice-y-ferrari','F1 Fan, Lenny Sabino, speaks about his Italian pride and joy.','Event',110,'cars/Ferrari Final 800.flv','cars/Ferrari Final 800_front.jpg','cars/Ferrari Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:45'),(60,'Founding Fathers','founding-fathers','Founders Evan Cyglar and Denis O\'Leary III give us a history lesson on the Scarsdale Concours.','Event',0,'cars/Founders Final 800.flv','cars/Founders Final 800_front.jpg','cars/Founders Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:45'),(61,'Out of This World','out-of-this-world','Jaguar XK120 owner, Bernard Hoffman rides in style in the 1st sports car produced after WWII.','Event',124,'cars/Jaguar Final 800.flv','cars/Jaguar Final 800_front.jpg','cars/Jaguar Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:45'),(62,'Built for Speed','built-for-speed','Koenigsegg dealer, Giacomo Ciaccia pumps us up about the world\'s fastest production car.','Event',101,'cars/Koenigsegg Final 800.flv','cars/Koenigsegg Final 800_front.jpg','cars/Koenigsegg Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:45'),(63,'Still the One','still-the-one','Gregory Balcerak, owner of the Lamborghini Jota Americana, shows off his one of kind car on a daily basis.','Event',128,'cars/Lambo Final 800.flv','cars/Lambo Final 800_front.jpg','cars/Lambo Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:45'),(64,'Ready to Rumble','ready-to-rumble','Here we learn about the masterpiece that is the 1936 Packard from John Buonano of Black Horse Garage.','Event',0,'cars/Packard Final 800.flv','cars/Packard Final 800_front.jpg','cars/Packard Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:45'),(65,'A Jewel of A Time','a-jewel-of-a-time','Michael Wilson reveals how two high school pals convinced Wilson & Son to sponsor their first show.','Event',197,'cars/Wilson Final 800.flv','cars/Wilson Final 800_front.jpg','cars/Wilson Final 800.jpg',1,'2008-09-08 00:00:00',NULL,'2012-02-10 13:05:45'),(66,'Eye of the Beholder','eye-of-the-beholder','Old car journalist, Gregg Merksamer, enlightens us on the judging process.','Event',0,'cars/Announcer Final 800.flv','cars/Announcer Final 800_front.jpg','cars/Announcer Final 800.jpg',1,'2008-09-09 00:00:00','2008-09-09 00:00:00','2008-09-08 15:23:13');
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;
