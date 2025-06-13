REPLACE INTO `#__wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('versioncode', '2.3.4', 'default');

INSERT INTO `#__wj_portal_config` 
(`configname`, `configvalue`, `configfor`, `addon`) VALUES 
('job_search_ai_form', '0', 'job', 'aijobsearch'),
('job_list_ai_filter', '0', 'job', 'aijobsearch'),
('show_suggested_jobs_button', '1', 'job', 'aisuggestedjobs'),
('show_suggested_jobs_dashboard', '1', 'job', 'aisuggestedjobs'),
('resume_search_ai_form', '0', 'resume', 'airesumesearch'),
('resume_list_ai_filter', '0', 'resume', 'airesumesearch'),
('show_suggested_resumes_button', '1', 'resume', 'aisuggestedresumes'),
('show_suggested_resumes_dashboard', '1', 'resume', 'aisuggestedresumes');


