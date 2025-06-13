REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.1.8', 'default');

INSERT INTO `#__wj_portal_fieldsordering` (`field`, `fieldtitle`, `ordering`, `section`, `is_section_headline`, `placeholder`, `description`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`, `visible_field`, `visibleparams`) VALUES
('full_name', 'Name', 1, 0, 0, NULL, NULL, 5, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
('email', 'Email', 2, 0, 0, NULL, NULL, 5, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
('phone', 'Phone', 3, 0, 0, NULL, NULL, 5, 1, 1, 0, 0, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
('message', 'Message', 4, 0, 0, NULL, NULL, 5, 1, 1, 0, 0, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
('resume', 'Resume', 5, 0, 0, NULL, NULL, 5, 1, 1, 0, 0, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL);



UPDATE `#__wj_portal_fieldsordering` SET `cannotunpublish` = '0' WHERE `wp_wj_portal_fieldsordering`.`id` = 4;
UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0' WHERE `wp_wj_portal_fieldsordering`.`id` = 4;

UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'jobcategory';
UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'jobtype';
UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'startpublishing';
UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'stoppublishing';
UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'description' AND id = 31;

UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'email_address' AND id = 46;
UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'last_name' AND id = 45;
UPDATE `#__wj_portal_fieldsordering` SET `sys` = '0', `cannotunpublish` = '0' WHERE field = 'job_category' AND id = 52;




ALTER TABLE `#__wj_portal_resume` ADD `quick_apply` TINYINT NULL DEFAULT '0' AFTER `price`;

ALTER TABLE `#__wj_portal_jobapply` ADD `quick_apply` TINYINT NULL DEFAULT '0' AFTER `userpackageid`, ADD `apply_message` TEXT NULL DEFAULT NULL AFTER `quick_apply`;


INSERT INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES ('quick_apply_for_user', '0', 'quick_apply', NULL);

INSERT INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES ('quick_apply_for_visitor', '0', 'quick_apply', NULL);
INSERT INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES ('quick_apply_captcha', '1', 'quick_apply', NULL);


INSERT INTO `#__wj_portal_emailtemplates` (`uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES
(NULL, 'new-message', NULL, 'WP Job Portal : New Message', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Message</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {RECIPIENT_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You have received a message from {SENDER_NAME} (<strong style=\"color: #4b4b4d;\">{SENDER_USER_ROLE}</strong>). You can view and respond to the message by clicking the below button.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{MESSAGE_LINK}\">View Message</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00');

INSERT INTO `#__wj_portal_emailtemplates_config` (`emailfor`, `admin`, `employer`, `jobseeker`, `jobseeker_visitor`, `employer_visitor`) VALUES
('new_message', 0, 0, 0, 0, 0);


