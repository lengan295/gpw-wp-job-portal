REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.0.1', 'default');

INSERT IGNORE INTO  `#__wj_portal_fieldsordering` (`id`, `field`, `fieldtitle`, `ordering`, `section`, `placeholder`, `description`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`) 
VALUES
          (94, 'youtube_link', 'Youtube Link', 27, '', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
          (95, 'twiter_link', 'Twitter Link', 28, '', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
          (96, 'linkedin_link', 'Linkedin Link', 29, '', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '');
