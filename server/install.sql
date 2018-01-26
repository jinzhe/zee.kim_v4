DROP TABLE IF EXISTS `music`;

CREATE TABLE `music` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT '',
  `view` int(4) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
DROP TABLE IF EXISTS `post_photo`;

CREATE TABLE `post_photo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `sort` smallint(3) unsigned DEFAULT '0',
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
DROP TABLE IF EXISTS `token`;

CREATE TABLE `token` (
  `token` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `token` (`token`) VALUES('a3e66fff-baff-5142-6bd4-981753025158');