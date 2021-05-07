CREATE TABLE `wta_sql_manager` (
`sql_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`sql_db_file` TINYTEXT NOT NULL, 
`sql_name` TINYTEXT DEFAULT "no_name", 
`sql_query` TEXT NOT NULL, 
`sql_datecreate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE `wta_users` (
`user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`user_name` TINYTEXT NOT NULL, 
`user_pass` TINYTEXT NOT NULL, 
`user_lvl` INT NOT NULL
) ENGINE = InnoDB;

