-- v6: Add song column to pages.

ALTER TABLE `pages` ADD COLUMN `song` text DEFAULT NULL;
