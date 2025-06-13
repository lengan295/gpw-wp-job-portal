SET SQL_MODE='ALLOW_INVALID_DATES';

REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.3.3', 'default');

INSERT INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES ('allow_search_resume', '2', 'resume', 'resumesearch');


ALTER TABLE `#__wj_portal_jobs` ENGINE = InnoDB;
ALTER TABLE `#__wj_portal_jobs` ADD aijobsearchtext MEDIUMTEXT NULL AFTER price, ADD FULLTEXT aijobsearchtext (aijobsearchtext);
ALTER TABLE `#__wj_portal_jobs` ADD aijobsearchdescription MEDIUMTEXT NULL AFTER aijobsearchtext, ADD FULLTEXT aijobsearchdescription (aijobsearchdescription);

ALTER TABLE `#__wj_portal_resume` ENGINE = InnoDB;
ALTER TABLE `#__wj_portal_resume` ADD airesumesearchtext MEDIUMTEXT NULL AFTER quick_apply, ADD FULLTEXT airesumesearchtext (airesumesearchtext);
ALTER TABLE `#__wj_portal_resume` ADD airesumesearchdescription MEDIUMTEXT NULL AFTER airesumesearchtext, ADD FULLTEXT airesumesearchdescription (airesumesearchdescription);

