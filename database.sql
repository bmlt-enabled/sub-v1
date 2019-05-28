CREATE TABLE `sub`.`subscribers` (
  `id` INT NOT NULL,
  `contact` VARCHAR(45) NOT NULL,
  `is_admin` bit DEFAULT 0,
  PRIMARY KEY (`id`));
