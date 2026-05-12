-- v5: Add client_ip column to views.

ALTER TABLE `views` ADD COLUMN `client_ip` text DEFAULT NULL;
