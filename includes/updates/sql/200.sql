REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.0.0', 'default');

INSERT INTO  `#__wj_portal_fieldsordering` (`field`, `fieldtitle`, `ordering`, `section`, `placeholder`, `description`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`)
 VALUES ('facebook_link', 'Facebook Link', 26, '', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
        ('youtube_link', 'Youtube Link', 27, '', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
        ('twiter_link', 'Twitter Link', 28, '', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
        ('linkedin_link', 'Linkedin Link', 29, '', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '');



ALTER TABLE `#__wj_portal_companies` 
ADD `facebook_link` VARCHAR(255) NULL AFTER `serverid`, 
ADD `twiter_link` VARCHAR(255) NULL AFTER `facebook_link`, 
ADD `linkedin_link` VARCHAR(255) NULL AFTER `twiter_link`, 
ADD `youtube_link` VARCHAR(255) NULL AFTER `linkedin_link`;



INSERT INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES
('coverletter_auto_approve', '1', 'coverletter', 'coverletter'),
('formcoverletter', '1', 'jscontrolpanel', 'coverletter'),
('mycoverletter', '1', 'jscontrolpanel', 'coverletter'),
('vis_jsformcoverletter', '1', 'jscontrolpanel', 'coverletter'),
('vis_jsmycoverletter', '1', 'jscontrolpanel', 'coverletter');


INSERT INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES
('jobcity_per_row', '2', 'default', NULL);


INSERT INTO `#__wj_portal_slug` (`slug`, `defaultslug`, `filename`, `description`, `status`) VALUES
('my-coverletters', 'my-coverletters', 'mycoverletters', 'slug for my coverletters page', 1),
('add-coverletter', 'add-coverletter', 'addcoverletter', 'slug for add coverletter page', 1),
('coverletter', 'coverletter', 'viewcoverletter', 'slug for view coverletter page', 1),
('coverletter-payment', 'coverletter-payment', 'paycoverletter', '', 1),
('jobs-by-cities', 'jobs-by-cities', 'jobsbycitites', 'slug for my jobs by cities page', 1),
('companies', 'companies', 'companies', 'slug for companies listing page', 1); 
