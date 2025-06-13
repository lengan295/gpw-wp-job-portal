<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="wpjobportaladmin-wrapper">
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <div id="wpjobportal-wrapper-top">
            <div id="wpjobportal-wrapper-top-left">
                <div id="wpjobportal-breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('Dashboard','wp-job-portal')); ?>">
                                <?php echo esc_html(__('Dashboard','wp-job-portal')); ?>
                            </a>
                        </li>
                        <li><?php echo esc_html(__("Addon's List and Features",'wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-help-btn" class="wpjobportal-help-btn">
                    <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/help.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>
        </div>
        <!-- top head -->
        <div id="wpjobportal-head">
            <h1 class="wpjobportal-head-text"><?php echo esc_html(__('Add-On’s For Job Portal','wp-job-portal')); ?></h1>
        </div>
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- addon list -->
            <div class="wpjobportaladmin-add-on-page-wrp">
                <div class="add-on-page-cnt">
                    <div class="add-on-sec-header">
                        <h1 class="add-on-header-tit"><?php echo esc_html(__('Add-On’s For Job Portal','wp-job-portal')); ?></h1>
                        <div class="add-on-header-text"><?php echo esc_html(__('Get trusted WordPress add on’s. Guaranteed to work fast, safe to use, beautifully coded, packed with features and easy to use.','wp-job-portal')); ?></div>
                    </div>
                    <div class="add-on-msg">
                        <h3 class="add-on-msg-txt"><?php echo esc_html(__('Save big with an exclusive membership plan today!','wp-job-portal')); ?></h3>
                        <a title="<?php echo esc_html(__('Show','wp-job-portal')); ?>" href="https://wpjobportal.com/pricing/" class="add-on-msg-btn"><i class="fa fa-cart"></i> <?php echo esc_html(__('show bundle pack','wp-job-portal')); ?></a>
                    </div>
                    <div class="add-on-list">
                        <div class="add-on-item address-data">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/elegant-icon.png" alt="<?php echo esc_html(__('Address Data','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Elegant Design','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('This add-on will change the Job Portal pages to a simple yet powerful and rich web design that involves focusing on clean aesthetics, intuitive navigation, and functional elements while ensuring performance and responsiveness.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/elegant-design/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item address-data">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/address-data.png" alt="<?php echo esc_html(__('Address Data','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Address Data','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for users to see address data for states, cities or both. Admin will upload that file.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/address-data/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>

                        <div class="add-on-item adv-res-builder">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/resume-manager.png" alt="<?php echo esc_html(__('Advance Resume Builder','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Advance Resume Builder','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('This add-on offers to job seekers create a resume with multiple options like multiple addresses, institutions, employers, references and skills.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/advance-resume-builder/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item copy-job">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/copy-job.png" alt="<?php echo esc_html(__('Copy Job','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Copy Job','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for an employer to copy their jobs. Employers can copy their jobs easily.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/copy-job/ " class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item credits">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/credits.png" alt="<?php echo esc_html(__('Credits','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Credits','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for admin to add multiple credit system against particular actions. Admin can add multiple packages against particular actions.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/credit-system/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item cron-job">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/cron-job.png" alt="<?php echo esc_html(__('Credits','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Cron Job','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for an employer to copy their jobs. Employers can copy their jobs easily.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/cron-job-copy/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item cust-fields">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/custom-field.png" alt="<?php echo esc_html(__('Custom Field','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Advance Custom Fields','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Advance custom fields add-on offers to admin add new custom fields like combo, checkbox, radio button, email and dependent fields.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/advance-custom-fields/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item export">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/export.png" alt="<?php echo esc_html(__('Export','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Export','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Manager offers a feature for Employer and Job Seeker in which they can export Resume information in the form of an excel file easily.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/export/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item feat-comp">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/featured-company.png" alt="<?php echo esc_html(__('Featured Company','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Featured Company','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers an outstanding feature for employers to featured their companies. Employers will be able to featured their desired companies.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/featured-company/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item feat-job">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/featured-job.png" alt="<?php echo esc_html(__('Featured Job','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Featured Job','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for making the jobs as Featured Job. This will help to make it easier for jobseekers to find jobs.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/featured-job/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item feat-res">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/featured-resume.png" alt="<?php echo esc_html(__('Feature Resume','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Featured Resume','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal helps employers to make the resume as Featured Resume. Employers can featured their desired resume from the resume list.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/featured-resume/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item folders">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/folders.png" alt="<?php echo esc_html(__('Folder','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Folder','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for employers to make folders. Employers can make a folder and copy their resumes into the folders.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/folders/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item res-act">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/resume-actions.png" alt="<?php echo esc_html(__('Resume Action','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Resume Action','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for employers to perform some actions on resumes. Employes have some rights to reject or accept the jobseeker resume.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/resume-action/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item res-srch">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/resume-search.png" alt="<?php echo esc_html(__('Resume Search','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Resume Search','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for employers to perform some actions on resumes. Employes have some rights to reject or accept the jobseeker resume.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/resume-save-search/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item job-alert">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/job-alert.png" alt="<?php echo esc_html(__('Job Alert','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Job Alert','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal allows registered users to save their job searches and create alerts that send new jobs via email daily, weekly or fortnightly.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/job-alert/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item messages">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/messages.png" alt="<?php echo esc_html(__('Message','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Message','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a  message feature for Employers and Job Seekers. All the Employers and Job Seekers can message each other.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/messages/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item multi-comp">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/companies.png" alt="<?php echo esc_html(__('Multi Company','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Multi Company','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP job Portal offers a feature for employers to add multi companies. Employers will add their desired multi-companies.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/multi_companies/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item widget">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/widgets.png" alt="<?php echo esc_html(__('Front-end Widgets','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Front-end Widget','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Widgets in WordPress allows you to add content and features in the widgetized areas of your theme which is mostly the sidebar.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/widget/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item multi-res">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/multi-resume.png" alt="<?php echo esc_html(__('Multi Resume','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Multi Resume','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for the jobseeker to add their multi-resume. Jobseeker will be able to create a multiple resume for applying any job.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/multi-resume/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item departments">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/departments.png" alt="<?php echo esc_html(__('Department','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Department','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for Employes to add multi-departments related to jobs. Jobseeker can apply to jobs related to the desired department.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/multi_departments/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item pdf">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/pdf.png" alt="<?php echo esc_html(__('PDF','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('PDF','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offer a feature for Employer and Job Seekers , which allows them to take PDF of Resume and can save it.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/pdf/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item print">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/print.png" alt="<?php echo esc_html(__('Print','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Print','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a Resume and print feature. Employer and Job Seeker can view the Resume page or take a print of the Resume.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/print/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item reports">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/reports.png" alt="<?php echo esc_html(__('Reports','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__("Reports",'wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers multiple reports by jobs, by companies and by resume. Admin can see overall reports of Employer and Job Seeker.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/reports/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item rss">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/rss.png" alt="<?php echo esc_html(__('Rss Feed','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Rss Feed','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers Real Simple Syndication (RSS) to set feeds for the jobs. Admin manipulates RSS settings. Employers and Job Seekers can see the Jobs RSS.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/rss-2/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item shortlist">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/shortlist.png" alt="<?php echo esc_html(__('Shortlist','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Shortlist','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal will help employers and jobseekers to shortlist their desired jobs. They can see their shortlisted jobs on the list.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/shortlist-job/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item social-login">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/social-login.png" alt="<?php echo esc_html(__('Social Login','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Social Login','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal allows registration from social logins. Employer and Job Seekers can create a new account for login or they can use their social media Logins.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/social-login/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item social-share">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/social-share.png" alt="<?php echo esc_html(__('Social Share','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Social Share','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a jobs share feature on various social media sites for Employers and Job Seekers.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/social-share/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item tags">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/tags.png" alt="<?php echo esc_html(__('Tag','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Tags','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for searching the jobs by tags. Employers will add some tags related to jobs search.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/tags/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item tell-a-friend">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/tell-a-friend.png" alt="<?php echo esc_html(__('Tell Friend','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Tell A Friend','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature in which Employer and Job Seekers can share and tell their friends about Jobs by sending them an email.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/tell-a-friend/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item themes">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/themes.png" alt="<?php echo esc_html(__('Themes','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Themes','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Get multiple themes with beautiful color scheme to make your site more beautiful and eye-catchy by just one click.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/plugin-color/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item apply-as-vis">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/apply-as-visitor.png" alt="<?php echo esc_html(__('Visitor Apply Job','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Visitor Apply Job','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('WP Job Portal offers a feature for visitors. A visitor can apply to any company posted jobs. The visitor will apply for the jobs by their resume.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/apply-as-visitor/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                        <div class="add-on-item visi-add-job">
                            <img class="add-on-img" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/add-on-list/visitor-ad-job.png" alt="<?php echo esc_html(__('Visitor Add Job','wp-job-portal')); ?>" />
                            <div class="add-on-name"><?php echo esc_html(__('Visitor Add Job','wp-job-portal')); ?></div>
                            <div class="add-on-txt"><?php echo esc_html(__('Visitor add job add-on offers guests can add job in the system without logged in the system.','wp-job-portal')); ?></div>
                            <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/product/visitor-add-jobs/" class="add-on-btn"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                        </div>
                    </div>
                    <div class="add-on-sec-header">
                        <h1 class="add-on-header-tit"><?php echo esc_html(__('WP Job Portal Add-Ons Bundle Pack','wp-job-portal')); ?></h1>
                        <div class="add-on-header-text"><?php echo esc_html(__('Save big with an exclusive membership plan today!','wp-job-portal')); ?></div>
                    </div>
                    <div class="add-on-bundle-pack-list">
                        <div class="add-on-bundle-pack-item basic">
                            <div class="add-on-bundle-pack-name"><?php echo esc_html(__('Basic','wp-job-portal')); ?></div>
                            <div class="add-on-bundle-pack-normal-price"><?php echo esc_html(__('Normal','wp-job-portal')); ?> $49.00</div>
                            <div class="add-on-bundle-pack-price">$39.00</div>
                            <div class="add-on-bundle-pack-saving-price">$10 <span><?php echo esc_html(__('Savings*','wp-job-portal')); ?></span></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li><?php echo esc_html(__('Advance Custom Fields','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Departments','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Resume Search','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Reports','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Folders','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Social Share','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Tags','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('RSS','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Color Manager','wp-job-portal')); ?></li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/pricing/"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                            </div>
                        </div>
                        <div class="add-on-bundle-pack-item standard">
                            <div class="add-on-bundle-pack-name"><?php echo esc_html(__('Standard','wp-job-portal')); ?></div>
                            <div class="add-on-bundle-pack-normal-price"><?php echo esc_html(__('Normal','wp-job-portal')); ?> $99.00</div>
                            <div class="add-on-bundle-pack-price">$69.00</div>
                            <div class="add-on-bundle-pack-saving-price">$30 <span><?php echo esc_html(__('Savings*','wp-job-portal')); ?></span></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li><?php echo esc_html(__('Featured Job','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Message System','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Tell a Friend','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Copy Job','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Featured Company','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('PDF','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Print','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Address Data','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Search Job','wp-job-portal')); ?></li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/pricing/"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                            </div>
                        </div>
                        <div class="add-on-bundle-pack-item professional">
                            <div class="add-on-bundle-pack-deal-wrp"><span class="add-on-bundle-pack-deal"><?php echo esc_html(__('Best Deal','wp-job-portal')); ?></span></div>
                            <div class="add-on-bundle-pack-name"><?php echo esc_html(__('Professional','wp-job-portal')); ?></div>
                            <div class="add-on-bundle-pack-normal-price"><?php echo esc_html(__('Normal','wp-job-portal')); ?> $149.00</div>
                            <div class="add-on-bundle-pack-price">$99.00</div>
                            <div class="add-on-bundle-pack-saving-price">$50 <span><?php echo esc_html(__('Savings*','wp-job-portal')); ?></span></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li><?php echo esc_html(__('Visitor Apply Job','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Advance Resume Builder','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Credit System','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Resume Action','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Visitor Add Job','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Featured Resume','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Shortlist Job','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Multi Resume','wp-job-portal')); ?></li>
                                <li><?php echo esc_html(__('Job Alert','wp-job-portal')); ?></li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo esc_html(__('buy now','wp-job-portal')); ?>" href="https://wpjobportal.com/pricing/"><?php echo esc_html(__('buy now','wp-job-portal')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
