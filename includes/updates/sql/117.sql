REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '1.1.7', 'default');

UPDATE `#__wj_portal_fieldsordering` SET sys = 1 WHERE field = 'wpjobportal_user_email';
ALTER TABLE `#__wj_portal_jobs` CHANGE `salarymin` `salarymin` float DEFAULT NULL;
ALTER TABLE `#__wj_portal_jobs` CHANGE `salarymax` `salarymax` float DEFAULT NULL;