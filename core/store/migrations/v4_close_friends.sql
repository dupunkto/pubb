-- v4: Add close friends.

CREATE TABLE IF NOT EXISTS `friends` (
  `contact_id` int(11) NOT NULL,
  `token` varchar(7) NOT NULL,
  `clearance` int(11) NOT NULL DEFAULT 30,
  UNIQUE(`token`),
  PRIMARY KEY (`contact_id`)
);