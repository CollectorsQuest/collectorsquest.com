<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1330360044.
 * Generated on 2012-02-27 11:27:24 by root
 */
class PropelMigration_1330690133
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
		// add the post-migration code here
	}

	public function preDown($manager)
	{
		// add the pre-migration code here
	}

	public function postDown($manager)
	{
		// add the post-migration code here
	}

	/**
	 * Get the SQL statements for the Up migration
	 *
	 * @return array list of the SQL strings to execute for the Up migration
	 *               the keys being the datasources
	 */
	public function getUpSQL()
	{
		return array (
  'propel' => <<<EOF

DROP TABLE IF EXISTS `geo_country`;

CREATE TABLE `geo_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `iso3166` char(2) NOT NULL,
  `currency` char(3) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `zoom` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `geo_country_U_1` (`slug`),
  UNIQUE KEY `geo_country_U_2` (`iso3166`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `geo_country` WRITE;
/*!40000 ALTER TABLE `geo_country` DISABLE KEYS */;

INSERT INTO `geo_country` (`id`, `name`, `slug`, `iso3166`, `currency`, `latitude`, `longitude`, `zoom`)
VALUES
	(1,'Afghanistan','Afghanistan','AF','',0,0,0),
	(2,'Albania','Albania','AL','ALL',0,0,0),
	(3,'Algeria','Algeria','DZ','',0,0,0),
	(4,'American Samoa','American-Samoa','AS','',0,0,0),
	(5,'Andorra','Andorra','AD','EUR',0,0,0),
	(6,'Angola','Angola','AO','',0,0,0),
	(7,'Anguilla','Anguilla','AI','',0,0,0),
	(8,'Antarctica','Antarctica','AQ','',0,0,0),
	(9,'Antigua and Barbuda','Antigua-and-Barbuda','AG','',0,0,0),
	(10,'Argentina','Argentina','AR','',0,0,0),
	(11,'Armenia','Armenia','AM','',0,0,0),
	(12,'Aruba','Aruba','AW','',0,0,0),
	(13,'Australia','Australia','AU','',0,0,0),
	(14,'Austria','Austria','AT','EUR',0,0,0),
	(15,'Azerbaijan','Azerbaijan','AZ','',0,0,0),
	(16,'Bahamas','Bahamas','BS','',0,0,0),
	(17,'Bahrain','Bahrain','BH','',0,0,0),
	(18,'Bangladesh','Bangladesh','BD','',0,0,0),
	(19,'Barbados','Barbados','BB','',0,0,0),
	(20,'Belarus','Belarus','BY','EUR',0,0,0),
	(21,'Belgium','Belgium','BE','EUR',0,0,0),
	(22,'Belize','Belize','BZ','',0,0,0),
	(23,'Benin','Benin','BJ','',0,0,0),
	(24,'Bermuda','Bermuda','BM','',0,0,0),
	(25,'Bhutan','Bhutan','BT','',0,0,0),
	(26,'Bolivia','Bolivia','BO','',0,0,0),
	(27,'Bosnia and Herzegovina','Bosnia-and-Herzegovina','BA','',0,0,0),
	(28,'Botswana','Botswana','BW','',0,0,0),
	(29,'Bouvet Island','Bouvet-Island','BV','',0,0,0),
	(30,'Brazil','Brazil','BR','',0,0,0),
	(31,'British Indian Ocean Territory','British-Indian-Ocean-Territory','IO','',0,0,0),
	(32,'Brunei Darussalam','Brunei-Darussalam','BN','',0,0,0),
	(33,'Bulgaria','Bulgaria','BG','BGN',0,0,0),
	(34,'Burkina Faso','Burkina-Faso','BF','',0,0,0),
	(35,'Burundi','Burundi','BI','',0,0,0),
	(36,'Cambodia','Cambodia','KH','',0,0,0),
	(37,'Cameroon','Cameroon','CM','',0,0,0),
	(38,'Canada','Canada','CA','',0,0,0),
	(39,'Cape Verde','Cape-Verde','CV','',0,0,0),
	(40,'Cayman Islands','Cayman-Islands','KY','',0,0,0),
	(41,'Central African Republic','Central-African-Republic','CF','',0,0,0),
	(42,'Chad','Chad','TD','',0,0,0),
	(43,'Chile','Chile','CL','',0,0,0),
	(44,'China','China','CN','',0,0,0),
	(45,'Christmas Island','Christmas-Island','CX','',0,0,0),
	(46,'Cocos (Keeling) Islands','Cocos-Keeling-Islands','CC','',0,0,0),
	(47,'Colombia','Colombia','CO','',0,0,0),
	(48,'Comoros','Comoros','KM','',0,0,0),
	(49,'Congo','Congo','CG','',0,0,0),
	(50,'Congo, the Democratic Republic of the','Congo-the-Democratic-Republic-of-the','CD','',0,0,0),
	(51,'Cook Islands','Cook-Islands','CK','',0,0,0),
	(52,'Costa Rica','Costa-Rica','CR','',0,0,0),
	(53,'Cote D\'Ivoire','Cote-DIvoire','CI','',0,0,0),
	(54,'Croatia','Croatia','HR','HRK',0,0,0),
	(55,'Cuba','Cuba','CU','',0,0,0),
	(56,'Cyprus','Cyprus','CY','',0,0,0),
	(57,'Czech Republic','Czech-Republic','CZ','CZK',0,0,0),
	(58,'Denmark','Denmark','DK','DKK',0,0,0),
	(59,'Djibouti','Djibouti','DJ','',0,0,0),
	(60,'Dominica','Dominica','DM','',0,0,0),
	(61,'Dominican Republic','Dominican-Republic','DO','',0,0,0),
	(62,'Ecuador','Ecuador','EC','',0,0,0),
	(63,'Egypt','Egypt','EG','',0,0,0),
	(64,'El Salvador','El-Salvador','SV','',0,0,0),
	(65,'Equatorial Guinea','Equatorial-Guinea','GQ','',0,0,0),
	(66,'Eritrea','Eritrea','ER','',0,0,0),
	(67,'Estonia','Estonia','EE','EEK',0,0,0),
	(68,'Ethiopia','Ethiopia','ET','',0,0,0),
	(69,'Falkland Islands (Malvinas)','Falkland-Islands-Malvinas','FK','',0,0,0),
	(70,'Faroe Islands','Faroe-Islands','FO','',0,0,0),
	(71,'Fiji','Fiji','FJ','',0,0,0),
	(72,'Finland','Finland','FI','EUR',0,0,0),
	(73,'France','France','FR','EUR',0,0,0),
	(74,'French Guiana','French-Guiana','GF','',0,0,0),
	(75,'French Polynesia','French-Polynesia','PF','',0,0,0),
	(76,'French Southern Territories','French-Southern-Territories','TF','',0,0,0),
	(77,'Gabon','Gabon','GA','',0,0,0),
	(78,'Gambia','Gambia','GM','',0,0,0),
	(79,'Georgia','Georgia','GE','GEL',0,0,0),
	(80,'Germany','Germany','DE','EUR',0,0,0),
	(81,'Ghana','Ghana','GH','',0,0,0),
	(82,'Gibraltar','Gibraltar','GI','',0,0,0),
	(83,'Greece','Greece','GR','EUR',0,0,0),
	(84,'Greenland','Greenland','GL','',0,0,0),
	(85,'Grenada','Grenada','GD','',0,0,0),
	(86,'Guadeloupe','Guadeloupe','GP','',0,0,0),
	(87,'Guam','Guam','GU','',0,0,0),
	(88,'Guatemala','Guatemala','GT','',0,0,0),
	(89,'Guinea','Guinea','GN','',0,0,0),
	(90,'Guinea-Bissau','Guinea-Bissau','GW','',0,0,0),
	(91,'Guyana','Guyana','GY','',0,0,0),
	(92,'Haiti','Haiti','HT','',0,0,0),
	(93,'Heard Island and Mcdonald Islands','Heard-Island-and-Mcdonald-Islands','HM','',0,0,0),
	(94,'Holy See (Vatican City State)','Holy-See-Vatican-City-State','VA','',0,0,0),
	(95,'Honduras','Honduras','HN','',0,0,0),
	(96,'Hong Kong','Hong-Kong','HK','',0,0,0),
	(97,'Hungary','Hungary','HU','HUF',0,0,0),
	(98,'Iceland','Iceland','IS','',0,0,0),
	(99,'India','India','IN','',0,0,0),
	(100,'Indonesia','Indonesia','ID','',0,0,0),
	(101,'Iran, Islamic Republic of','Iran-Islamic-Republic-of','IR','',0,0,0),
	(102,'Iraq','Iraq','IQ','',0,0,0),
	(103,'Ireland','Ireland','IE','EUR',0,0,0),
	(104,'Israel','Israel','IL','',0,0,0),
	(105,'Italy','Italy','IT','EUR',0,0,0),
	(106,'Jamaica','Jamaica','JM','',0,0,0),
	(107,'Japan','Japan','JP','',0,0,0),
	(108,'Jordan','Jordan','JO','',0,0,0),
	(109,'Kazakhstan','Kazakhstan','KZ','KZT',0,0,0),
	(110,'Kenya','Kenya','KE','',0,0,0),
	(111,'Kiribati','Kiribati','KI','',0,0,0),
	(112,'Korea, Democratic People\'s Republic of','Korea-Democratic-Peoples-Republic-of','KP','',0,0,0),
	(113,'Korea, Republic of','Korea-Republic-of','KR','',0,0,0),
	(114,'Kuwait','Kuwait','KW','',0,0,0),
	(115,'Kyrgyzstan','Kyrgyzstan','KG','',0,0,0),
	(116,'Lao People\'s Democratic Republic','Lao-Peoples-Democratic-Republic','LA','',0,0,0),
	(117,'Latvia','Latvia','LV','LVL',0,0,0),
	(118,'Lebanon','Lebanon','LB','',0,0,0),
	(119,'Lesotho','Lesotho','LS','',0,0,0),
	(120,'Liberia','Liberia','LR','',0,0,0),
	(121,'Libyan Arab Jamahiriya','Libyan-Arab-Jamahiriya','LY','',0,0,0),
	(122,'Liechtenstein','Liechtenstein','LI','',0,0,0),
	(123,'Lithuania','Lithuania','LT','LTL',0,0,0),
	(124,'Luxembourg','Luxembourg','LU','',0,0,0),
	(125,'Macao','Macao','MO','',0,0,0),
	(126,'Macedonia, the Former Yugoslav Republic of','Macedonia-the-Former-Yugoslav-Republic-of','MK','',0,0,0),
	(127,'Madagascar','Madagascar','MG','',0,0,0),
	(128,'Malawi','Malawi','MW','',0,0,0),
	(129,'Malaysia','Malaysia','MY','',0,0,0),
	(130,'Maldives','Maldives','MV','',0,0,0),
	(131,'Mali','Mali','ML','',0,0,0),
	(132,'Malta','Malta','MT','',0,0,0),
	(133,'Marshall Islands','Marshall-Islands','MH','',0,0,0),
	(134,'Martinique','Martinique','MQ','',0,0,0),
	(135,'Mauritania','Mauritania','MR','',0,0,0),
	(136,'Mauritius','Mauritius','MU','',0,0,0),
	(137,'Mayotte','Mayotte','YT','',0,0,0),
	(138,'Mexico','Mexico','MX','',0,0,0),
	(139,'Micronesia, Federated States of','Micronesia-Federated-States-of','FM','',0,0,0),
	(140,'Moldova, Republic of','Moldova-Republic-of','MD','',0,0,0),
	(141,'Monaco','Monaco','MC','',0,0,0),
	(142,'Mongolia','Mongolia','MN','',0,0,0),
	(143,'Montserrat','Montserrat','MS','',0,0,0),
	(144,'Morocco','Morocco','MA','',0,0,0),
	(145,'Mozambique','Mozambique','MZ','',0,0,0),
	(146,'Myanmar','Myanmar','MM','',0,0,0),
	(147,'Namibia','Namibia','NA','',0,0,0),
	(148,'Nauru','Nauru','NR','',0,0,0),
	(149,'Nepal','Nepal','NP','',0,0,0),
	(150,'Netherlands','Netherlands','NL','EUR',0,0,0),
	(151,'Netherlands Antilles','Netherlands-Antilles','AN','',0,0,0),
	(152,'New Caledonia','New-Caledonia','NC','',0,0,0),
	(153,'New Zealand','New-Zealand','NZ','',0,0,0),
	(154,'Nicaragua','Nicaragua','NI','',0,0,0),
	(155,'Niger','Niger','NE','',0,0,0),
	(156,'Nigeria','Nigeria','NG','',0,0,0),
	(157,'Niue','Niue','NU','',0,0,0),
	(158,'Norfolk Island','Norfolk-Island','NF','',0,0,0),
	(159,'Northern Mariana Islands','Northern-Mariana-Islands','MP','',0,0,0),
	(160,'Norway','Norway','NO','NOK',0,0,0),
	(161,'Oman','Oman','OM','',0,0,0),
	(162,'Pakistan','Pakistan','PK','',0,0,0),
	(163,'Palau','Palau','PW','',0,0,0),
	(164,'Palestinian Territory, Occupied','Palestinian-Territory-Occupied','PS','',0,0,0),
	(165,'Panama','Panama','PA','',0,0,0),
	(166,'Papua New Guinea','Papua-New-Guinea','PG','',0,0,0),
	(167,'Paraguay','Paraguay','PY','',0,0,0),
	(168,'Peru','Peru','PE','',0,0,0),
	(169,'Philippines','Philippines','PH','',0,0,0),
	(170,'Pitcairn','Pitcairn','PN','',0,0,0),
	(171,'Poland','Poland','PL','PLN',0,0,0),
	(172,'Portugal','Portugal','PT','EUR',0,0,0),
	(173,'Puerto Rico','Puerto-Rico','PR','',0,0,0),
	(174,'Qatar','Qatar','QA','',0,0,0),
	(175,'Reunion','Reunion','RE','',0,0,0),
	(176,'Romania','Romania','RO','RON',0,0,0),
	(177,'Russian Federation','Russian-Federation','RU','RUB',0,0,0),
	(178,'Rwanda','Rwanda','RW','',0,0,0),
	(179,'Saint Helena','Saint-Helena','SH','',0,0,0),
	(180,'Saint Kitts and Nevis','Saint-Kitts-and-Nevis','KN','',0,0,0),
	(181,'Saint Lucia','Saint-Lucia','LC','',0,0,0),
	(182,'Saint Pierre and Miquelon','Saint-Pierre-and-Miquelon','PM','',0,0,0),
	(183,'Saint Vincent and the Grenadines','Saint-Vincent-and-the-Grenadines','VC','',0,0,0),
	(184,'Samoa','Samoa','WS','',0,0,0),
	(185,'San Marino','San-Marino','SM','',0,0,0),
	(186,'Sao Tome and Principe','Sao-Tome-and-Principe','ST','',0,0,0),
	(187,'Saudi Arabia','Saudi-Arabia','SA','',0,0,0),
	(188,'Senegal','Senegal','SN','',0,0,0),
	(189,'Serbia and Montenegro','Serbia-and-Montenegro','CS','',0,0,0),
	(190,'Seychelles','Seychelles','SC','',0,0,0),
	(191,'Sierra Leone','Sierra-Leone','SL','',0,0,0),
	(192,'Singapore','Singapore','SG','',0,0,0),
	(193,'Slovakia','Slovakia','SK','EUR',0,0,0),
	(194,'Slovenia','Slovenia','SI','EUR',0,0,0),
	(195,'Solomon Islands','Solomon-Islands','SB','',0,0,0),
	(196,'Somalia','Somalia','SO','',0,0,0),
	(197,'South Africa','South-Africa','ZA','',0,0,0),
	(198,'South Georgia and the South Sandwich Islands','South-Georgia-and-the-South-Sandwich-Islands','GS','',0,0,0),
	(199,'Spain','Spain','ES','EUR',0,0,0),
	(200,'Sri Lanka','Sri-Lanka','LK','',0,0,0),
	(201,'Sudan','Sudan','SD','',0,0,0),
	(202,'Suriname','Suriname','SR','',0,0,0),
	(203,'Svalbard and Jan Mayen','Svalbard-and-Jan-Mayen','SJ','',0,0,0),
	(204,'Swaziland','Swaziland','SZ','',0,0,0),
	(205,'Sweden','Sweden','SE','SEK',0,0,0),
	(206,'Switzerland','Switzerland','CH','CHF',0,0,0),
	(207,'Syrian Arab Republic','Syrian-Arab-Republic','SY','',0,0,0),
	(208,'Taiwan, Province of China','Taiwan-Province-of-China','TW','',0,0,0),
	(209,'Tajikistan','Tajikistan','TJ','',0,0,0),
	(210,'Tanzania, United Republic of','Tanzania-United-Republic-of','TZ','',0,0,0),
	(211,'Thailand','Thailand','TH','',0,0,0),
	(212,'Timor-Leste','Timor-Leste','TL','',0,0,0),
	(213,'Togo','Togo','TG','',0,0,0),
	(214,'Tokelau','Tokelau','TK','',0,0,0),
	(215,'Tonga','Tonga','TO','',0,0,0),
	(216,'Trinidad and Tobago','Trinidad-and-Tobago','TT','',0,0,0),
	(217,'Tunisia','Tunisia','TN','',0,0,0),
	(218,'Turkey','Turkey','TR','TRY',0,0,0),
	(219,'Turkmenistan','Turkmenistan','TM','',0,0,0),
	(220,'Turks and Caicos Islands','Turks-and-Caicos-Islands','TC','',0,0,0),
	(221,'Tuvalu','Tuvalu','TV','',0,0,0),
	(222,'Uganda','Uganda','UG','',0,0,0),
	(223,'Ukraine','Ukraine','UA','UAH',0,0,0),
	(224,'United Arab Emirates','United-Arab-Emirates','AE','',0,0,0),
	(225,'United Kingdom','United-Kingdom','GB','GBP',0,0,0),
	(226,'United States','United-States','US','',0,0,0),
	(227,'United States Minor Outlying Islands','United-States-Minor-Outlying-Islands','UM','',0,0,0),
	(228,'Uruguay','Uruguay','UY','',0,0,0),
	(229,'Uzbekistan','Uzbekistan','UZ','',0,0,0),
	(230,'Vanuatu','Vanuatu','VU','',0,0,0),
	(231,'Venezuela','Venezuela','VE','',0,0,0),
	(232,'Viet Nam','Viet-Nam','VN','',0,0,0),
	(233,'Virgin Islands, British','Virgin-Islands-British','VG','',0,0,0),
	(234,'Virgin Islands, U.s.','Virgin-Islands-U.s.','VI','',0,0,0),
	(235,'Wallis and Futuna','Wallis-and-Futuna','WF','',0,0,0),
	(236,'Western Sahara','Western-Sahara','EH','',0,0,0),
	(237,'Yemen','Yemen','YE','',0,0,0),
	(238,'Zambia','Zambia','ZM','',0,0,0),
	(239,'Zimbabwe','Zimbabwe','ZW','',0,0,0);
EOF
,
);
	}

	/**
	 * Get the SQL statements for the Down migration
	 *
	 * @return array list of the SQL strings to execute for the Down migration
	 *               the keys being the datasources
	 */
	public function getDownSQL()
	{
		return array (
      'propel' => '
        DROP TABLE geo_country;
      ',
    );
	}

}
