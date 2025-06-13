REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.1.6', 'default');

UPDATE `#__wj_portal_fieldsordering` SET `cannotshowonlisting` = '0' WHERE `id` = 50;

UPDATE `#__wj_portal_fieldsordering` SET `showonlisting` = `published` WHERE `id` = 50;

