CREATE DATABASE upcashdb;

use upcashdb;


-- CREATE STATEMENTS

CREATE TABLE IF NOT EXISTS `investimento` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(256) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);