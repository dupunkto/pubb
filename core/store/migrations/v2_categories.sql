-- v2: Add category column to pages

ALTER TABLE `pages` ADD COLUMN `category` varchar(90) DEFAULT 'regular';
