REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '1.0.5', 'default');

DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'heighesteducation';
DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'employer_supervisor';
DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'section_resume';
DELETE FROM `#__wj_portal_fieldsordering` WHERE field = 'resume';


