DROP TABLE IF EXISTS api_request_log;

CREATE TABLE IF NOT EXISTS api_request_log (
	uniqid VARCHAR(23) NOT NULL PRIMARY KEY,
	apiKey VARCHAR(64) NULL,
	requestFunction VARCHAR(128) NOT NULL,
	requestArguments VARCHAR(1024) NOT NULL,
	evecTime DATETIME NOT NULL,
	serverTime DATETIME NULL,
	cacheTime DATETIME NULL,
	response TEXT NULL,
	KEY i_uniqid (uniqid),
	KEY i_kf (apiKey, requestFunction),
	KEY i_kfct (apiKey, requestFunction, cacheTime)) 
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;