CREATE TABLE `sub`.`subscribers` (
  `id` INT AUTO_INCREMENT ,
  `contact` VARCHAR(45) NOT NULL,
  `is_admin` bit DEFAULT 0,
  PRIMARY KEY (`id`));
