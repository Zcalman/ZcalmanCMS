CREATE TABLE `documents` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `file` TEXT NOT NULL ,
 `type` TEXT NOT NULL ,
 `size` INT NOT NULL ,
 PRIMARY KEY (`id`) ,
 UNIQUE (
 `id` 
)) ENGINE = MYISAM ;
