REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.2.3', 'default');

ALTER TABLE `#__wj_portal_slug` 
ADD `pagetitle` VARCHAR(255) NULL DEFAULT NULL AFTER `description`, 
ADD `defaultpagetitle` VARCHAR(255) NULL DEFAULT NULL AFTER `pagetitle`, 
ADD `modulename` VARCHAR(255) NULL DEFAULT NULL AFTER `defaultpagetitle`,
ADD `titleoptions` VARCHAR(255) NULL DEFAULT NULL AFTER `modulename`;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'wpjobportal',
    `defaultpagetitle` = 'New In WP Job Portal [separator] [sitename]',
    `pagetitle` = 'New In WP Job Portal [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 1;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'wpjobportal',
    `defaultpagetitle` = 'WP Job Portal Login [separator] [sitename]',
    `pagetitle` = 'WP Job Portal Login [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 2;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobseeker',
    `defaultpagetitle` = 'Jobseeker Control Panel [separator] [sitename]',
    `pagetitle` = 'Jobseeker Control Panel [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 3;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'employer',
    `defaultpagetitle` = 'Employer Control Panel [separator] [sitename]',
    `pagetitle` = 'Employer Control Panel [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 4;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobseeker',
    `defaultpagetitle` = 'Jobseeker My Stats [separator] [sitename]',
    `pagetitle` = 'Jobseeker My Stats [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 5;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'employer',
    `defaultpagetitle` = 'Employer My Stats [separator] [sitename]',
    `pagetitle` = 'Employer My Stats [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 6;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resume',
    `defaultpagetitle` = 'Resumes [separator] [sitename]',
    `pagetitle` = 'Resumes [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 7;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = 'Jobs [separator] [sitename]',
    `pagetitle` = 'Jobs [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 8;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'company',
    `defaultpagetitle` = 'My Companies [separator] [sitename]',
    `pagetitle` = 'My Companies [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 9;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'company',
    `defaultpagetitle` = 'Add Company [separator] [sitename]',
    `pagetitle` = 'Add Company [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 10;
UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = 'My Jobs [separator] [sitename]',
    `pagetitle` = 'My Jobs [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 11;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = 'Add Job [separator] [sitename]',
    `pagetitle` = 'Add Job [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 12;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'department',
    `defaultpagetitle` = 'My Departments [separator] [sitename]',
    `pagetitle` = 'My Departments [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 13;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'department',
    `defaultpagetitle` = 'Add Department [separator] [sitename]',
    `pagetitle` = 'Add Department [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 14;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'department',
    `defaultpagetitle` = 'Department Information [separator] [sitename]',
    `pagetitle` = 'Department Information [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 15;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'company',
    `defaultpagetitle` = '[name] [location] [separator] [sitename]',
    `pagetitle` = '[name] [location] [separator] [sitename]',
    `titleoptions` = '[name],[location],[separator],[sitename]'
WHERE `id` = 17;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resume',
    `defaultpagetitle` = '[applicationtitle] [jobcategory] [separator] [sitename]',
    `pagetitle` = '[applicationtitle] [jobcategory] [separator] [sitename]',
    `titleoptions` = '[applicationtitle],[jobcategory],[jobtype],[location],[separator],[sitename]'
WHERE `id` = 18;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = '[title] [location] [separator] [sitename]',
    `pagetitle` = '[title] [location] [separator] [sitename]',
    `titleoptions` = '[title],[companyname],[jobcategory],[jobtype],[location],[separator],[sitename]'
WHERE `id` = 19;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'folder',
    `defaultpagetitle` = 'My Folders [separator] [sitename]',
    `pagetitle` = 'My Folders [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 20;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'folder',
    `defaultpagetitle` = 'Add Folder [separator] [sitename]',
    `pagetitle` = 'Add Folder [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 21;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'folder',
    `defaultpagetitle` = 'Folder Information [separator] [sitename]',
    `pagetitle` = 'Folder Information [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 22;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'folder',
    `defaultpagetitle` = 'Folder Resumes [separator] [sitename]',
    `pagetitle` = 'Folder Resumes [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 23;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'message',
    `defaultpagetitle` = 'Jobseeker Messages [separator] [sitename]',
    `pagetitle` = 'Jobseeker Messages [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 24;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'message',
    `defaultpagetitle` = 'Employer Messages [separator] [sitename]',
    `pagetitle` = 'Employer Messages [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 25;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'message',
    `defaultpagetitle` = 'Message [separator] [sitename]',
    `pagetitle` = 'Message [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 26;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'message',
    `defaultpagetitle` = 'Job Messages [separator] [sitename]',
    `pagetitle` = 'Job Messages [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 27;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'message',
    `defaultpagetitle` = 'Messages [separator] [sitename]',
    `pagetitle` = 'Messages [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 29;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resumesearch',
    `defaultpagetitle` = 'Resume Search [separator] [sitename]',
    `pagetitle` = 'Resume Search [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 30;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resumesearch',
    `defaultpagetitle` = 'Resume Save Searches [separator] [sitename]',
    `pagetitle` = 'Resume Save Searches [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 31;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resume',
    `defaultpagetitle` = 'Resume By Categories [separator] [sitename]',
    `pagetitle` = 'Resume By Categories [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 32;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'rss',
    `defaultpagetitle` = 'Resume Rss [separator] [sitename]',
    `pagetitle` = 'Resume Rss [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 33;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credtis',
    `defaultpagetitle` = 'Employer Credits [separator] [sitename]',
    `pagetitle` = 'Employer Credits [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 34;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credtis',
    `defaultpagetitle` = 'Jobseeker Credits [separator] [sitename]',
    `pagetitle` = 'Jobseeker Credits [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 35;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credtis',
    `defaultpagetitle` = 'Employer Purchase History [separator] [sitename]',
    `pagetitle` = 'Employer Purchase History [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 36;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'employer',
    `defaultpagetitle` = 'Employer My Stats [separator] [sitename]',
    `pagetitle` = 'Employer My Stats [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 37;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobseeker',
    `defaultpagetitle` = 'Jobseker My Stats [separator] [sitename]',
    `pagetitle` = 'Jobseker My Stats [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 38;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'employer',
    `defaultpagetitle` = 'Employer Registration [separator] [sitename]',
    `pagetitle` = 'Employer Registration [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 39;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobseeker',
    `defaultpagetitle` = 'Job Seeker Registration [separator] [sitename]',
    `pagetitle` = 'Job Seeker Registration [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 40;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'wpjobportal',
    `defaultpagetitle` = 'User Registration [separator] [sitename]',
    `pagetitle` = 'User Registration [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 41;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resume',
    `defaultpagetitle` = 'Add Resume [separator] [sitename]',
    `pagetitle` = 'Add Resume [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 42;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resume',
    `defaultpagetitle` = 'My Resumes [separator] [sitename]',
    `pagetitle` = 'My Resumes [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 43;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'company',
    `defaultpagetitle` = 'Companies [separator] [sitename]',
    `pagetitle` = 'Companies [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 45;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobapply',
    `defaultpagetitle` = 'My Applied Jobs [separator] [sitename]',
    `pagetitle` = 'My Applied Jobs [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 46;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobapply',
    `defaultpagetitle` = 'Job Applied Resume [separator] [sitename]',
    `pagetitle` = 'Job Applied Resume [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 47;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobsearch',
    `defaultpagetitle` = 'Job Search [separator] [sitename]',
    `pagetitle` = 'Job Search [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 49;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobsearch',
    `defaultpagetitle` = 'Job Save Searches [separator] [sitename]',
    `pagetitle` = 'Job Save Searches [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 50;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'jobalert',
    `defaultpagetitle` = 'Job Alert [separator] [sitename]',
    `pagetitle` = 'Job Alert [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 51;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'rss',
    `defaultpagetitle` = 'Job Rss [separator] [sitename]',
    `pagetitle` = 'Job Rss [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 52;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'shortlistedjobs',
    `defaultpagetitle` = 'Shortlisted Jobs [separator] [sitename]',
    `pagetitle` = 'Shortlisted Jobs [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 53;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Job Seeker Purchase History [separator] [sitename]',
    `pagetitle` = 'Job Seeker Purchase History [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 54;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Job Seeker Rate List [separator] [sitename]',
    `pagetitle` = 'Job Seeker Rate List [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 55;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Employer Rate List [separator] [sitename]',
    `pagetitle` = 'Employer Rate List [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 56;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Job Seeker Credits Log [separator] [sitename]',
    `pagetitle` = 'Job Seeker Credits Log [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 57;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Employer Credits Log [separator] [sitename]',
    `pagetitle` = 'Employer Credits Log [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 58;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'category',
    `defaultpagetitle` = 'Job By Categories [separator] [sitename]',
    `pagetitle` = 'Job By Categories [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 59;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = 'Newest Jobs [separator] [sitename]',
    `pagetitle` = 'Newest Jobs [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 60;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = 'Job By Types [separator] [sitename]',
    `pagetitle` = 'Job By Types [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 61;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = 'Jobs By Cities [separator] [sitename]',
    `pagetitle` = 'Jobs By Cities [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 64;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resume',
    `defaultpagetitle` = 'Resume PDF [separator] [sitename]',
    `pagetitle` = 'Resume PDF [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 65;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'My Invoices [separator] [sitename]',
    `pagetitle` = 'My Invoices [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 67;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'My Packages [separator] [sitename]',
    `pagetitle` = 'My Packages [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 69;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Packages [separator] [sitename]',
    `pagetitle` = 'Packages [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 70;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'My Subscriptions [separator] [sitename]',
    `pagetitle` = 'My Subscriptions [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 71;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'wpjobportal',
    `defaultpagetitle` = 'Edit Profile [separator] [sitename]',
    `pagetitle` = 'Edit Profile [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 72;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'resume',
    `defaultpagetitle` = 'Resume Print [separator] [sitename]',
    `pagetitle` = 'Resume Print [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 75;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Company Payment [separator] [sitename]',
    `pagetitle` = 'Company Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 78;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Featured Company Payment [separator] [sitename]',
    `pagetitle` = 'Featured Company Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 80;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Department Payment [separator] [sitename]',
    `pagetitle` = 'Department Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 81;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Featured Job Payment [separator] [sitename]',
    `pagetitle` = 'Featured Job Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 82;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Job Payment [separator] [sitename]',
    `pagetitle` = 'Job Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 83;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Featured Resume Payment [separator] [sitename]',
    `pagetitle` = 'Featured Resume Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 84;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Job Apply Payment [separator] [sitename]',
    `pagetitle` = 'Job Apply Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 85;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Resume Payment [separator] [sitename]',
    `pagetitle` = 'Resume Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 86;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'job',
    `defaultpagetitle` = 'Newest Jobs [separator] [sitename]',
    `pagetitle` = 'Newest Jobs [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 87;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Resume Save Search Payment [separator] [sitename]',
    `pagetitle` = 'Resume Save Search Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 88;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'coverletter',
    `defaultpagetitle` = 'My Cover Letters [separator] [sitename]',
    `pagetitle` = 'My Cover Letters [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 89;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'coverletter',
    `defaultpagetitle` = 'Add Cover Letter [separator] [sitename]',
    `pagetitle` = 'Add Cover Letter [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 90;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'coverletter',
    `defaultpagetitle` = 'Cover Letter Information [separator] [sitename]',
    `pagetitle` = 'Cover Letter Information [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 91;

UPDATE `#__wj_portal_slug`
SET `modulename` = 'credits',
    `defaultpagetitle` = 'Cover Letter Payment [separator] [sitename]',
    `pagetitle` = 'Cover Letter Payment [separator] [sitename]',
    `titleoptions` = '[separator],[sitename]'
WHERE `id` = 92;


DELETE FROM `#__wj_portal_slug` WHERE id = 28 AND defaultslug="job-types";
DELETE FROM `#__wj_portal_slug` WHERE id = 73 AND defaultslug="resume-print";
DELETE FROM `#__wj_portal_slug` WHERE id = 74 AND defaultslug="resume-print";
DELETE FROM `#__wj_portal_slug` WHERE id = 93 AND defaultslug="jobs-by-cities";
DELETE FROM `#__wj_portal_slug` WHERE id = 94 AND defaultslug="companies";
