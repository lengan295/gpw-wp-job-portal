REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.1.3', 'default');

UPDATE `#__wj_portal_fieldsordering` SET `cannotunpublish` = '0' WHERE `id` = 88;

ALTER TABLE `#__wj_portal_fieldsordering` ADD `visible_field` VARCHAR(255) NULL DEFAULT NULL AFTER `j_script`;
ALTER TABLE `#__wj_portal_fieldsordering` ADD `visibleparams` TEXT NULL DEFAULT NULL AFTER `visible_field`;