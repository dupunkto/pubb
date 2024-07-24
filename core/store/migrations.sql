CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(90) NOT NULL,
  `type` text NOT NULL,
  `volume_id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `reply_to` text DEFAULT NULL,
  `path` text NOT NULL,
  `caption` text DEFAULT NULL,
  `published` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  UNIQUE (`volume_id`, `slug`),
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `volumes` (
  `id` int(11) NOT NULL AUTO_INCREMENT , 
  `slug` varchar(90) NOT NULL, 
  `title` text DEFAULT NULL, 
  `description` longtext DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT , 
  `page_id` int(11) NOT NULL, 
  `source` text NOT NULL, 
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `views` (
  `id` int(11) NOT NULL AUTO_INCREMENT , 
  `page_id` int(11) NOT NULL, 
  `referer` text NOT NULL,
  `agent` text NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(), 
  PRIMARY KEY (`id`)
);
