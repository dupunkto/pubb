CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(90) NOT NULL,
  `type` text NOT NULL,
  `title` text DEFAULT NULL,
  `reply_to` text DEFAULT NULL,
  `path` text NOT NULL,
  `draft` int(1) NOT NULL DEFAULT 0,
  -- 'public', 'rss-only', 'hidden'
  `visibility` text NOT NULL,
  `caption` text DEFAULT NULL,
  `published` datetime NOT NULL DEFAULT current_timestamp,
  `updated` datetime NOT NULL DEFAULT current_timestamp,
  UNIQUE (`slug`),
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `volumes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(90) NOT NULL,
  `title` text DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `start_at` DATETIME NOT NULL,
  `end_at` DATETIME NOT NULL,
  UNIQUE (`slug`),
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(90) NOT NULL,
  `path` text NOT NULL,
  `uploaded_as` text DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp,
  UNIQUE (`slug`),
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,

  -- 'outgoing' or 'incoming'
  `type` text NOT NULL,

  -- only applicable on 'outgoing' mentions, or
  -- 'incoming' mentions where source includes a
  -- domain known to be owned by a contact
  `contact_id` int(11) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `source` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handle` TEXT NOT NULL,
  `domain` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `notify` int(1) NOT NULL DEFAULT 1,
  UNIQUE(`handle`),
  UNIQUE(`domain`),
  UNIQUE(`email`),
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text NOT NULL,
  `referer` text DEFAULT NULL,
  `agent` text NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- 'page', 'external', 'listing'
  `type` varchar(90) NOT NULL,
  `label` text NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `ref` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `section_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `menu_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);