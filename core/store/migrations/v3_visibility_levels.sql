-- v3: Replace visibility column with integer levels

ALTER TABLE `pages` ADD COLUMN `visibility_level` int(11) DEFAULT 50;
UPDATE `pages` SET `visibility_level` = CASE 
  WHEN `visibility` = 'public' THEN 50
  WHEN `visibility` = 'rss-only' THEN 40
  WHEN `visibility` = 'hidden' THEN 20
  ELSE 50
END;
ALTER TABLE `pages` DROP COLUMN `visibility`;
ALTER TABLE `pages` RENAME COLUMN `visibility_level` TO `visibility`;
