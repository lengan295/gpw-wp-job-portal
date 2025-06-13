REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.1.2', 'default');

UPDATE `#__wj_portal_fieldsordering` SET `showonlisting` = '1', `cannotshowonlisting` = '0' WHERE `id` = 58;
ALTER TABLE `#__wj_portal_fieldsordering` ADD `is_section_headline` TINYINT NULL DEFAULT '0' AFTER `section`;
UPDATE `#__wj_portal_fieldsordering` SET is_section_headline = 1 WHERE field LIKE 'section_%';

DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'heighesteducation';
DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'employer_supervisor';
DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'section_resume';
DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'resume';

