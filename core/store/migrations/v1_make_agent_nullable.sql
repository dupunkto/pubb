-- v1: Make `views.agent` nullable

ALTER TABLE `views` RENAME COLUMN `agent` TO `agent.old`;
ALTER TABLE `views` ADD COLUMN `agent` text DEFAULT NULL;
UPDATE `views` SET `agent` = `agent.old`;
ALTER TABLE `views` DROP COLUMN `agent.old`;
