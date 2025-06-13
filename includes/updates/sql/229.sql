REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.2.9', 'default');

UPDATE `#__wj_portal_fieldsordering` set sys = 0, cannotunpublish = 0 WHERE field = 'company';
