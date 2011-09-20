DROP TABLE `api_request_log`;

DROP TABLE `accounts`;

CREATE TABLE IF NOT EXISTS `api_request_log` (
	`uniqid` VARCHAR(23) NOT NULL PRIMARY KEY,
	`requestFunction` VARCHAR(128) NOT NULL,
	`requestArguments` VARCHAR(255) NOT NULL,
	`evecTime` DATETIME NOT NULL,
	`serverTime` DATETIME NULL,
	`cacheTime` DATETIME NULL,
	`response` TEXT NULL,
	KEY i_uniqid (uniqid),
	KEY i_rfra (requestFunction, requestArguments),
	KEY i_rfract (requestFunction, requestArguments, cacheTime)
) 
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;

CREATE TABLE IF NOT EXISTS `accounts` (  
	`userId` VARCHAR(20) NOT NULL PRIMARY KEY ,  
	`accountId` VARCHAR(20) NOT NULL ,  
	`emailAddress` VARCHAR(100) NOT NULL  
)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;