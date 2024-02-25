SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `manufacturer`;
DROP TABLE IF EXISTS `type`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `product_image`;
DROP TABLE IF EXISTS `post`;

SET foreign_key_checks = 1;

CREATE TABLE `user` (
  `username` VARCHAR(30) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `title` ENUM('Mr','Mrs','Ms','Miss'),
  `first_name` VARCHAR(40) NOT NULL,
  `last_name` VARCHAR(40) NOT NULL,
  `gender` ENUM('Male','Female') ,
  `dob` DATE ,
  `image` VARCHAR(100) DEFAULT 'images/users/placeholder.png',
  `joined` DATE NOT NULL,
  `is_admin` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`username`),
  UNIQUE (`email`)
);

INSERT INTO `user` (`username`, `email`, `password`, `title`, `first_name`, `last_name`, `gender`, `dob`, `image`, `joined`, `is_admin`)
VALUES
	('Phil','philip.henning.1@city.ac.uk','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','Mr','Phil','Henning','Male','1982-09-23','images/users/Phil.jpg','2014-01-01', 1 ),
	('Joe', 'test3@city.ac.uk','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','Mrs','Joe','Blogg','Male','1980-01-06','images/users/Joe.jpg','2014-03-01', 0),
	('Jane', 'test1@city.ac.uk','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','Miss','Jane','Doe','Female','1960-01-01','images/users/Jane.jpg','2014-01-01', 0),
	('John', 'test2@city.ac.uk','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','Mr','John','Doe','Male','1970-01-01','images/users/John.jpg','2014-02-01', 0);	

CREATE TABLE `manufacturer` (
  `id` SMALLINT(3) NOT NULL,
  `manufacturer` VARCHAR(50) NOT NULL,
  `year_established` YEAR(4),
  `headquarters` VARCHAR(60),
  `image` VARCHAR(100),
  `website` VARCHAR(150),
  `about` TEXT,
  PRIMARY KEY (`id`)
);

INSERT INTO `manufacturer` (`id`, `manufacturer`, `year_established`, `headquarters`, `website`, `image`, `about`)
VALUES
	(1,'Burton','1977','Burlington, Vermont, USA','http://burton.com','images/manufacturers/burton.png', 'Blackadder is the name that encompasses four series of a BBC 1 period British sitcom, along with several one-off instalments. All television episodes starred Rowan Atkinson as anti-hero Edmund Blackadder and Tony Robinson as Blackadder''s dogsbody, Baldrick. Each series was set in a different historical period with the two protagonists accompanied by different characters, though several reappear in one series or another, for example Melchett and Lord Flashheart.'),
	(2,'Ride','1992','Redmond, Washington, USA','http://www.ridesnowboards.com','images/manufacturers/ride.png', 'Bottom is a British sitcom television series that originally aired on BBC2 between 1991 and 1995. It was written by and starring comic duo Adrian Edmondson and Rik Mayall as Richie and Eddie, two flatmates who live on the dole in Hammersmith, London. The programme ran for three series, and was followed by five stage show tours across the United Kingdom between 1993 and 2003, and a feature film Guest House Paradiso. The show is noted for its chaotic, nihilistic humour and violent comedy slapstick.'),
	(3,'Flow','1996','San Clemente, California, USA','http://www.flow.com','images/manufacturers/flow.jpg', 'The Young Ones is a British sitcom, broadcast in Great Britain from 1982 to 1984 in two six-part series. Shown on BBC2, it featured anarchic, offbeat humour which helped bring alternative comedy to television in the 1980s and made household names of its writers and performers. In 1985, it was shown on MTV, one of the first non-music television shows on the fledgling channel.'),
	(4,'The North Face','1968','San Leandro, California, USA','http://www.thenorthface.com','images/manufacturers/north_face.jpg', 'Fawlty Towers is a British sitcom produced by BBC Television that was first broadcast on BBC2 in 1975 and 1979. Twelve episodes were made (two series, each of six episodes). The show was written by John Cleese and his then-wife Connie Booth, both of whom also starred in the show.
The series is set in Fawlty Towers, a fictional hotel in the seaside town of Torquay, on the \"English Riviera\". The plots centre around tense, rude and put-upon owner Basil Fawlty (Cleese), his bossy wife Sybil (Prunella Scales), a comparatively-normal chambermaid Polly (Booth), and hapless Spanish waiter Manuel (Andrew Sachs), showing their attempts to run the hotel amidst farcical situations and an array of demanding and eccentric guests.'),
	(5,'Salomon','1947','Metz-Tessy, France','http://www.salomon.com','images/manufacturers/salomon.png', 'Red Dwarf is a British comedy franchise which primarily comprises ten series (including a ninth mini-series named Back To Earth) of a television science fiction sitcom that aired on BBC Two between 1988 and 1993 and from 1997 to 1999, and on Dave in 2009 and 2012, gaining a cult following. The series was created by Rob Grant and Doug Naylor. In addition to the television episodes, there are four bestselling novels, two pilot episodes for an American version of the show, a radio version produced for BBC Radio 7, tie-in books, magazines and other merchandise.');

CREATE TABLE `type` (
  `id` SMALLINT(6) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `description` TEXT,
  PRIMARY KEY (`id`)
);

INSERT INTO `type` (`id`, `name`, `description`)
VALUES
	(1,'Snowboards', 'Snowboards are boards that are usually the width of one''s foot longways, with the ability to glide on snow. Snowboards are differentiated from monoskis by the stance of the user. In monoskiing, the user stands with feet inline with direction of travel (facing tip of monoski/downhill) (parallel to long axis of board), whereas in snowboarding, users stand with feet transverse (more or less) to the longitude of the board. Users of such equipment may be referred to as snowboarders. Commercial snowboards generally require extra equipment such as bindings and special boots which help secure both feet of a snowboarder, who generally rides in an upright position. These type of boards are commonly used by people at ski hills or resorts for leisure, entertainment and competitive purposes in the activity called snowboarding.'),
	(2,'Skis', 'A ski is a narrow strip of semi-rigid material worn underfoot to glide over snow. Substantially longer than wide and characteristically employed in pairs, skis are attached to ski boots with ski bindings, with either a free, lockable, or partially secured heel.
Originally intended as an aid to travel over snow, they are now mainly used recreationally in the sport of skiing.'),
	(3,'Sleds', 'A sled, sledge, or sleigh is a land vehicle with a smooth underside or possessing a separate body supported by two or more smooth, relatively narrow, longitudinal runners that travels by sliding across a surface. Most sleds are used on surfaces with low friction, such as snow or ice. In some cases, sleds may be used on mud, grass, or even smooth stones. They may be used to transport passengers, cargo, or both. Shades of meaning differentiating the three terms often reflect regional variations depending on historical uses and prevailing climate.'),
	(4,'Ski boots', 'Ski boots are specialized footwear that are used in skiing to provide a way to attach the skier to skis using ski bindings. The ski/boot/binding combination is used to effectively transmit control inputs from the skier''s legs to the snow.
Ski boots were originally made of leather and resembled standard winter boots. As skiing became more specialized as a form of recreation, so too did ski boots, leading to the splitting of designs between those for alpine skiing (downhill) and cross-country skiing. The latter remain similar to standard footwear, but include an attachment point for the bindings near the toe. The former have become much more specialized, rising up the leg in order to transmit sideways rotations of the legs through the bindings and into the skis, a process known as \"edging\". A variety of techniques combine features of cross-country and downhill, notably Randonee, and Telemark, and have led to further customization of boot styles to fill these niches.'),
	(5,'Snowboard boots', 'Snowboard boots are mostly considered soft boots, though alpine snowboarding uses a harder boot similar to a ski boot. A boot''s primary function is to transfer the rider''s energy into the board, protect the rider with support, and keep the rider''s feet warm. A snowboarder shopping for boots is usually looking for a good fit, flex, and looks. Boots can have different features such as lacing styles, heat molding liners, and gel padding that the snowboarder also might be looking for. Tradeoffs include rigidity versus comfort, and built in forward lean, versus comfort.
There are three incompatible types:
Standard (soft) boots fit \"flow\" and \"strap\" bindings and are by far the most common. No part of the boot specifically attaches to the board. Instead, the binding applies pressure in several places to achieve firm contact.
\"Step in\" boots have a metal clasp on the bottom to attach to \"step in\" bindings. The boot must match the binding.
Hard boots are used with special bindings.'),
	(6,'Jackets', 'Jackets are just jackets at the end of the day'),
	(7,'Bindings', 'Bindings are separate components from the snowboard deck and are very important parts of the total snowboard interface. The bindings'' main function is to hold the rider''s boot in place tightly to transfer their energy to the board. Most bindings are attached to the board with three or four screws that are placed in the center of the binding. Although a rather new technology from Burton called Infinite channel system uses two screws, both on the outsides of the binding.
There are several types of bindings. Strap-in, step-in, and hybrid bindings are used by most recreational riders and all freestyle riders.');

CREATE TABLE `product` (
  `id` VARCHAR(10) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `manufacturer_id` SMALLINT(3) NOT NULL,
  `type_id` SMALLINT(6) NOT NULL,
  `price` REAL NOT NULL,
  `year` YEAR(4) NOT NULL,
  `description` TEXT,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`),
  FOREIGN KEY (`type_id`) REFERENCES `type` (`id`),
  UNIQUE (`name`)
);

INSERT INTO `product` (`id`, `name`, `manufacturer_id`, `type_id`, `price`, `year`, `description`)
VALUES
	('fuz_01', 'Fuze', 3, 7, 210.99,'2013', 'Field Marshal Haig''s new plan is to commission a man who can do a splendid painting for the front cover of the next issue of the famous war magazine, King And Country, which Blackadder hates. When Blackadder is made the new Official War Artist, he is allowed to leave the trenches. This episode features no guest stars for the only time in the series.'),
	('gan_01','Gangster', 5, 1, 400.99,'2014', 'In order to avoid being legitimately ordered to go over the top, Blackadder fakes bad phone communications and then shoots a carrier pigeon – which is revealed to be a prized pet of General Melchett. With George as his attorney in a farcical Court Martial and Baldrick offering a rather unique escape kit, can the \"Flanders Pigeon Murderer\" ultimately avoid execution by firing squad? '),
	('gho_01','Ghost', 2, 1, 300.99, '2012', 'With everyone talking about the famous comedian, Charlie Chaplin, Blackadder becomes in charge of the War show and will be shifted off to London. Unfortunately, Melchett has fallen in love with the fair Georgina (George dressed as a woman). After a date with the General, the General asks George to marry him, and George says \"Yes\".
Featured Gabrielle Glaister as Bob for the second time. '),
	('mis_01','Mission', 1, 7, 180.99, '2013', 'After a visit from Lord Flashheart, Blackadder, Baldrick and George intend on joining the \"Twenty Minuters\" at the Royal Flying Corps to go to Paris. After Blackadder and Baldrick crash their planes behind enemy lines, they are captured by the Germans and must prepare for a fate worse than a fate worse than death.
Third appearance of Rik Mayall, for the second time as Lord Flashheart and Gabrielle Glaister as Bob for the third time. Also featured Adrian Edmondson as Baron Manfred von Richthofen.'),
	('moo_01', 'Moon', 1, 1, 300.99, '2013', 'George ends up in field hospital after a bomb strikes Blackadder''s bunker. Melchett and Darling task Blackadder with finding a German spy who is in the hospital and giving away battle plans. Instead, Blackadder embarks on a relationship with Nurse Mary Fletcher-Brown, played by Miranda Richardson.'),
	('sun_01', 'Sun', 1, 1, 350.99, '2014', 'Finally, this time for sure, Blackadder and his friends are going into battle. Baldrick suggests to Blackadder that he pretend to go mad by putting undies on his head and shoving two pencils up his nose, like in the Sudan. Once this fails, Baldrick comes up with another plan that could definitely get Blackadder out of the trenches for sure.
Final episode. The final scene voted ninth most memorable television moment of all time in a 1999 poll of The Observer and Channel Four.');

CREATE TABLE `post` (
`id` INT NOT NULL AUTO_INCREMENT,
`username` VARCHAR(60) NOT NULL,
`product_id` VARCHAR(10) NOT NULL,
`manufacturer_id` SMALLINT(3) NOT NULL,
`post_time` DATETIME NOT NULL,
`comment` TEXT NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`username`) REFERENCES `user` (`username`),
FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`)
);

INSERT INTO `post` (`id`, `username`, `product_id`, `manufacturer_id`, `post_time`, `comment`)
VALUES
	(1,	'Phil',	'moo_01', 1, '2014-03-05 12:05:56', 'Field Marshal Haig is about to make yet another gargantuan effort to move his drinks cabinet six inches closer to Berlin.'),
	(2,	'John', 'gho_01', 2, '2014-03-06 15:16:21', 'Baldrick, you wouldn''t recognise a subtle plan if it painted itself purple and danced naked on a harpsicord singing ''subtle plans are here again.'),
	(3,	'Jane', 'moo_01', 1, '2014-03-07 10:45:13', 'They do say, Mrs M, that verbal insults hurt more than physical pain. They are, of course, wrong, as you will soon discover when I stick this toasting fork into your head.'),
	(4, 'John', 'gan_01', 5, '2014-03-08 09:15:51', 'I couldnt be more petrified if a wild Rhinoceros had just come home from a hard day at the swamp and found me wearing his pyjamas, smoking his cigars and in bed with his wife.'),
	(5, 'Joe', 'gho_01', 2, '2014-03-09 19:29:37', 'I think I''ll write my tombstone - ''Here lies Edmund Blackadder, and he''s bloody annoyed''.'),
	(6,'Phil','fuz_01',3,'2014-03-23 12:26:43','Test comment'),
	(7,'Phil','fuz_01',3,'2014-03-23 12:33:02','This is another comment'),
	(8,'Phil','mis_01',1,'2014-03-23 15:20:19','Yet another test'),
	(9,'Phil','mis_01',1,'2014-03-23 15:20:22','I can not think of anything to say'),
	(10,'Phil','mis_01',1,'2014-03-23 15:54:40','yay i think I have comments working'),
	(11,'Phil','fuz_01',3,'2014-03-24 00:16:12','bloody spiffing good episode!!');
	
	
CREATE TABLE `product_image` (
`id` INT NOT NULL AUTO_INCREMENT,
`product_id` VARCHAR(10) NOT NULL,
`location` VARCHAR(255) NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`product_id`) REFERENCES `product`(`id`)
);

INSERT INTO `product_image` (`product_id`, `location`)
VALUES
	('fuz_01', 'images/products/flow_bnd_fuz_01.jpg'),
	('gan_01', 'images/products/salomon_snb_gan_01.jpg'),
	('gho_01', 'images/products/ride_snb_gho_01.jpg'),
	('mis_01', 'images/products/burton_bnd_mis_01.jpg'),
	('moo_01', 'images/products/burton_snb_moo_01.jpg'),
	('sun_01', 'images/products/burton_snb_sun_01.jpg'),
	('fuz_01', 'images/products/flow_bnd_fuz_02.jpg');
	
	