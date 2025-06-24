<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
class WPJOBPORTALactivation {

    static function wpjobportal_activate() {
        // Install Database
        WPJOBPORTALactivation::runSQL();
        WPJOBPORTALactivation::insertMenu();
        WPJOBPORTALactivation::checkUpdates();
        WPJOBPORTALactivation::addCapabilites();
    }

    static private function checkUpdates() {
        include_once WPJOBPORTAL_PLUGIN_PATH . 'includes/updates/updates.php';
        WPJOBPORTALupdates::checkUpdates();
    }

    static private function addCapabilites() {

        $role = get_role( 'administrator' );
        $role->add_cap( 'wpjobportal' );
        $role->add_cap( 'wpjobportal_jobs' );

        // hide (AI) database update required banner for new installs
        update_option( 'wpjobportal_ai_search_data_sync_needed', 0,);

        // upadte email config to remove dummy values from configuration

        // admin email address
        $admin_email = get_option( 'admin_email' );
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config` SET `configvalue` = '".esc_sql($admin_email)."' WHERE `configname`= 'adminemailaddress'";
        wpjobportaldb::query($query);

        // send by email address
        $send_by_email = 'wordpress@' . str_replace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config` SET `configvalue` = '".esc_sql($send_by_email)."' WHERE `configname`= 'mailfromaddress'";
        wpjobportaldb::query($query);
      }

    static private function insertMenu() {
        $pageexist = wpjobportal::$_db->get_var("Select COUNT(id) FROM `" . wpjobportal::$_db->prefix . "posts` WHERE post_content LIKE '%[wpjobportal_jobseeker_controlpanel]%'");
        if ($pageexist == 0) {
            $post = array(
                'post_name' => 'wp-job-portal-jobseeker-controlpanel',
                'post_title' => 'Jobseeker',
                'post_status' => 'publish',
                'post_content' => '[wpjobportal_jobseeker_controlpanel]',
                'post_type' => 'page'
            );
            $pageid = wp_insert_post($post);
            if(is_numeric($pageid)){
                // insert wp job portal jobseeker control panel id to configuration
                $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config` SET `configvalue` = ".esc_sql($pageid)." WHERE `configname`= 'default_pageid'";
                wpjobportaldb::query($query);
            }
        } else {
            wpjobportal::$_db->get_var("UPDATE `" . wpjobportal::$_db->prefix . "posts` SET post_status = 'publish' WHERE post_content LIKE '%[wpjobportal_jobseeker_controlpanel]%'");
        }
        update_option('rewrite_rules', '');

        // set default starting values for new installs

        $company_settings = get_option('wpjobportal_company_document_title_settings');
        $job_settings = get_option('wpjobportal_job_document_title_settings');
        $resume_settings = get_option('wpjobportal_resume_document_title_settings');

        if(empty($company_settings) && empty($job_settings) && empty($resume_settings)){
            update_option( 'wpjobportal_company_document_title_settings', '[name] [location] [separator] [sitename]');
            update_option( 'wpjobportal_job_document_title_settings', '[title] [location] [separator] [sitename]');
            update_option( 'wpjobportal_resume_document_title_settings', ' [applicationtitle] [jobcategory] [separator] [sitename]');
        }
    }

    static private function runSQL() {
        $query = "CREATE TABLE IF NOT EXISTS `".wpjobportal::$_db->prefix."wj_portal_config` (
                  `configname` varchar(255) NOT NULL DEFAULT '',
                  `configvalue` varchar(255) NOT NULL DEFAULT '',
                  `configfor` varchar(255) DEFAULT NULL,
                  `addon` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`configname`),
                  FULLTEXT KEY `config_name` (`configname`),
                  FULLTEXT KEY `config_for` (`configfor`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        wpjobportal::$_db->query($query);
        $runConfig = wpjobportal::$_db->get_var("SELECT COUNT(configname) FROM `" . wpjobportal::$_db->prefix . "wj_portal_config`");
        if ($runConfig == 0) {
          $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) NOT NULL,
              `status` tinyint(4) NOT NULL,
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8;";
            wpjobportal::$_db->query($query);
            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` (`id`, `title`, `status`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'Student (Undergraduate)', 1, 0, 1, 0),(2, 'Student (Graduate)', 1, 0, 2, 0),(3, 'Entry Level', 1, 1, 3, 0),(4, 'Experienced (Non-Manager)', 1, 0, 4, 0),(5, 'Manager', 1, 0, 5, 0),(6, 'Executive (Department Head, SVP, VP etc)', 1, 0, 6, 0),(7, 'Senior Executive (President, CEO, etc)', 1, 0, 7, 0);";
            wpjobportal::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_categories` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `cat_value` varchar(255) DEFAULT NULL,
              `cat_title` varchar(255) DEFAULT NULL,
              `alias` varchar(255) NOT NULL,
              `isactive` smallint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `parentid` int(11) NOT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=239 ;";
            wpjobportal::$_db->query($query);
            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_categories` (`id`, `cat_value`, `cat_title`, `alias`, `isactive`, `isdefault`, `ordering`, `parentid`, `serverid`) VALUES (1, NULL, 'Accounting/Finance', 'accounting-finance', 1, 0, 1, 0, 0),(2, NULL, 'Administrative', 'administrative', 1, 0, 2, 0, 0),(3, NULL, 'Advertising', 'advertising', 1, 0, 3, 0, 0),(4, NULL, 'Airlines/Avionics/Aerospace', 'airlines-avionics-aerospace', 1, 0, 4, 0, 0),(5, NULL, 'Architectural', 'architectural', 1, 0, 5, 0, 0),(6, NULL, 'Automotive', 'automotive', 1, 0, 6, 0, 0),(7, NULL, 'Banking/Finance', 'banking-finance', 1, 0, 7, 0, 0),(8, NULL, 'Biotechnology', 'biotechnology', 1, 0, 8, 0, 0),(9, NULL, 'Civil/Construction', 'civil-construction', 1, 0, 9, 0, 0),(10, NULL, 'Engineering', 'engineering', 1, 0, 10, 0, 0),(11, NULL, 'Cleared Jobs', 'cleared-jobs', 1, 0, 11, 0, 0),(12, NULL, 'Communications', 'communications', 1, 0, 12, 0, 0),(13, NULL, 'Computer/IT', 'computer-it', 1, 1, 13, 0, 0),(14, NULL, 'Construction', 'construction', 1, 0, 14, 0, 0),(15, NULL, 'Consultant/Contractual', 'consultant-contractual', 1, 0, 15, 0, 0),(16, NULL, 'Customer Service', 'customer-service', 1, 0, 16, 0, 0),(17, NULL, 'Defense', 'defense', 1, 0, 17, 0, 0),(18, NULL, 'Design', 'design', 1, 0, 18, 0, 0),(19, NULL, 'Education', 'education', 1, 0, 19, 0, 0),(20, NULL, 'Electrical Engineering', 'electrical-engineering', 1, 0, 20, 0, 0),(21, NULL, 'Electronics Engineering', 'electronics-engineering', 1, 0, 21, 0, 0),(22, NULL, 'Energy', 'energy', 1, 0, 22, 0, 0),(23, NULL, 'Environmental/Safety', 'environmental-safety', 1, 0, 24, 0, 0),(24, NULL, 'Fundraising', 'fundraising', 1, 0, 25, 0, 0),(25, NULL, 'Health/Medicine', 'health-medicine', 1, 0, 26, 0, 0),(26, NULL, 'Homeland Security', 'homeland-security', 1, 0, 27, 0, 0),(27, NULL, 'Human Resources', 'human-resources', 1, 0, 28, 0, 0),(28, NULL, 'Insurance', 'insurance', 1, 0, 29, 0, 0),(29, NULL, 'Intelligence Jobs', 'intelligence-jobs', 1, 0, 30, 0, 0),(30, NULL, 'Internships/Trainees', 'internships-trainees', 1, 0, 31, 0, 0),(31, NULL, 'Legal', 'legal', 1, 0, 32, 0, 0),(32, NULL, 'Logistics/Transportation', 'logistics-transportation', 1, 0, 33, 0, 0),(33, NULL, 'Maintenance', 'maintenance', 1, 0, 34, 0, 0),(34, NULL, 'Management', 'management', 1, 0, 35, 0, 0),(35, NULL, 'Manufacturing/Warehouse', 'manufacturing-warehouse', 1, 0, 36, 0, 0),(36, NULL, 'Marketing', 'marketing', 1, 0, 37, 0, 0),(37, NULL, 'Materials Management', 'materials-management', 1, 0, 38, 0, 0),(38, NULL, 'Mechanical Engineering', 'mechanical-engineering', 1, 0, 39, 0, 0),(39, NULL, 'Mortgage/Real Estate', 'mortgage-real estate', 1, 0, 40, 0, 0),(40, NULL, 'National Security', 'national-security', 1, 0, 41, 0, 0),(41, NULL, 'Part-time/Freelance', 'part-time-freelance', 1, 0, 42, 0, 0),(42, NULL, 'Printing', 'printing', 1, 0, 43, 0, 0),(43, NULL, 'Product Design', 'product-design', 1, 0, 44, 0, 0),(44, NULL, 'Public Relations', 'public-relations', 1, 0, 45, 0, 0),(45, NULL, 'Public Safety', 'public-safety', 1, 0, 46, 0, 0),(46, NULL, 'Research', 'research', 1, 0, 47, 0, 0),(47, NULL, 'Retail', 'retail', 1, 0, 48, 0, 0),(48, NULL, 'Sales', 'sales', 1, 0, 49, 0, 0),(49, NULL, 'Scientific', 'scientific', 1, 0, 50, 0, 0),(50, NULL, 'Shipping/Distribution', 'shipping-distribution', 1, 0, 51, 0, 0),(51, NULL, 'Technicians', 'technicians', 1, 0, 52, 0, 0),(52, NULL, 'Trades', 'trades', 1, 0, 53, 0, 0),(53, NULL, 'Transportation', 'transportation', 1, 0, 54, 0, 0),(54, NULL, 'Transportation Engineering', 'transportation-engineering', 1, 0, 55, 0, 0),(55, NULL, 'Web Site Development', 'web-site-development', 1, 0, 56, 0, 0),(56, NULL, 'Cast Accounting ', 'cast-accounting-', 1, 0, 1, 1, 0),(57, NULL, 'Controllership & Accounting Managment', 'controllership-and-accounting-managment', 1, 0, 2, 1, 0),(58, NULL, 'Payroll ', 'payroll-', 1, 0, 3, 1, 0),(59, NULL, 'Corporate Finance', 'corporate-finance', 1, 0, 4, 1, 0),(60, NULL, 'Administrative Division', 'administrative-division', 1, 0, 1, 2, 0),(61, NULL, 'Autonomous Territories', 'autonomous-territories', 1, 0, 2, 2, 0),(62, NULL, 'Administrative County', 'administrative-county', 1, 0, 3, 2, 0),(63, NULL, 'Administrative Communes', 'administrative-communes', 1, 0, 4, 2, 0),(64, NULL, 'Finance Advertising ', 'finance-advertising-', 1, 0, 1, 3, 0),(65, NULL, 'Advertising-Tourism', 'advertising-tourism', 1, 0, 2, 3, 0),(66, NULL, 'Advertising Social Net', 'advertising-social-net', 1, 0, 3, 3, 0),(67, NULL, 'Distributor Marketing', 'distributor-marketing', 1, 0, 4, 3, 0),(68, NULL, 'Facebook Advertising', 'facebook-advertising', 1, 0, 5, 3, 0),(69, NULL, 'Quality Engineer ', 'quality-engineer-', 1, 0, 1, 4, 0),(70, NULL, 'Office Assistant ', 'office-assistant-', 1, 0, 2, 4, 0),(71, NULL, 'Air Host/hostess', 'air host-hostess', 1, 0, 3, 4, 0),(72, NULL, 'Ticketing/reservation', 'ticketing-reservation', 1, 0, 4, 4, 0),(73, NULL, 'Architectural Drafting', 'architectural-drafting', 1, 0, 1, 5, 0),(74, NULL, 'Enterprize Architecture', 'enterprize-architecture', 1, 0, 2, 5, 0),(75, NULL, 'Architecture Frameworks', 'architecture-frameworks', 1, 0, 3, 5, 0),(76, NULL, 'Automotive Design', 'automotive-design', 1, 0, 1, 6, 0),(77, NULL, 'Autmotive Paints', 'autmotive-paints', 1, 0, 2, 6, 0),(78, NULL, 'Automotive Equipment/Parts', 'automotive equipment-parts', 1, 0, 3, 6, 0),(79, NULL, 'Automotive Search Engine', 'automotive-search-engine', 1, 0, 4, 6, 0),(80, NULL, 'Private Banking', 'private-banking', 1, 0, 1, 7, 0),(81, NULL, 'Stock Brocker', 'stock-brocker', 1, 0, 2, 7, 0),(82, NULL, 'Fractional-reserve Banking', 'fractional-reserve-banking', 1, 0, 3, 7, 0),(83, NULL, 'Mobile Banking', 'mobile-banking', 1, 0, 4, 7, 0),(84, NULL, 'Plant Biotechnology', 'plant-biotechnology', 1, 0, 1, 8, 0),(85, NULL, 'Animal Biotechnology', 'animal-biotechnology', 1, 0, 2, 8, 0),(86, NULL, 'Biotechnology & Medicine', 'biotechnology-and-medicine', 1, 0, 3, 8, 0),(87, NULL, 'Biotechnology & Society', 'biotechnology-and-society', 1, 0, 4, 8, 0),(88, NULL, 'Industrail & Microbial Biotechnonogy', 'industrail-and-microbial-biotechnonogy', 1, 0, 5, 8, 0),(89, NULL, 'Construction (Design & Managment)', 'construction-(design-and-managment)', 1, 0, 1, 9, 0),(90, NULL, 'Construction Engineering ', 'construction-engineering-', 1, 0, 2, 9, 0),(91, NULL, 'Composite Construction', 'composite-construction', 1, 0, 3, 9, 0),(92, NULL, 'Civil Engineering', 'civil-engineering', 1, 0, 1, 10, 0),(93, NULL, 'Software Engineering', 'software-engineering', 1, 0, 2, 10, 0),(94, NULL, 'Nuclear Engineering', 'nuclear-engineering', 1, 0, 3, 10, 0),(95, NULL, 'Ocean Engingeering', 'ocean-engingeering', 1, 0, 4, 10, 0),(96, NULL, 'Transpotation Engineering', 'transpotation-engineering', 1, 0, 5, 10, 0),(97, NULL, 'Security Cleared Jobs', 'security-cleared-jobs', 1, 0, 1, 11, 0),(98, NULL, 'Security Cleared IT Jobs', 'security-cleared-it-jobs', 1, 0, 2, 11, 0),(99, NULL, 'Confidential & Secret Security Clearance Job', 'confidential-and-secret-security-clearance-job', 1, 0, 3, 11, 0),(100, NULL, 'Verbal', 'verbal', 1, 0, 1, 12, 0),(101, NULL, 'E-mail', 'e-mail', 1, 0, 2, 12, 0),(102, NULL, 'Non-verbal', 'non-verbal', 1, 0, 3, 12, 0),(103, NULL, 'Computer Consulting Services', 'computer-consulting-services', 1, 0, 1, 13, 0),(104, NULL, 'Computer Installations Services', 'computer-installations-services', 1, 0, 2, 13, 0),(105, NULL, 'Software Vendors', 'software-vendors', 1, 1, 3, 13, 0),(106, NULL, 'Renovaiton', 'renovaiton', 1, 0, 1, 14, 0),(107, NULL, 'Addition', 'addition', 1, 0, 2, 14, 0),(108, NULL, 'New Construction', 'new-construction', 1, 0, 3, 14, 0),(109, NULL, 'Organization Development', 'organization-development', 1, 0, 1, 15, 0),(110, NULL, 'Construction Management', 'construction-management', 1, 0, 2, 15, 0),(111, NULL, 'Managment Consulting ', 'managment-consulting-', 1, 0, 3, 15, 0),(112, NULL, 'High Touch Customer Service', 'high-touch-customer-service', 1, 0, 1, 16, 0),(113, NULL, 'Low Touch Customer Service', 'low-touch-customer-service', 1, 0, 2, 16, 0),(114, NULL, 'Bad Touch Customer Service', 'bad-touch-customer-service', 1, 0, 3, 16, 0),(115, NULL, 'By Using legal services for the poor', 'by-using-legal-services-for-the-poor', 1, 0, 1, 17, 0),(116, NULL, 'By Using Retained Counsel', 'by-using-retained-counsel', 1, 0, 2, 17, 0),(117, NULL, 'By Self-representation', 'by-self-representation', 1, 0, 3, 17, 0),(118, NULL, 'Project Subtype Design', 'project-subtype-design', 1, 0, 1, 18, 0),(119, NULL, 'Graphic Design', 'graphic-design', 1, 0, 2, 18, 0),(120, NULL, 'Interior Desing', 'interior-desing', 1, 0, 3, 18, 0),(121, NULL, 'IT or Engineering Education', 'it-or-engineering-education', 1, 0, 1, 19, 0),(122, NULL, 'Commerce & Managment', 'commerce-and-managment', 1, 0, 2, 19, 0),(123, NULL, 'Medical Education', 'medical-education', 1, 0, 3, 19, 0),(124, NULL, 'Power Engineering', 'power-engineering', 1, 0, 1, 20, 0),(125, NULL, 'Instrumentation', 'instrumentation', 1, 0, 2, 20, 0),(126, NULL, 'Telecommunication', 'telecommunication', 1, 0, 3, 20, 0),(127, NULL, 'Signal Processing', 'signal-processing', 1, 0, 4, 20, 0),(128, NULL, 'Electromagnetics', 'electromagnetics', 1, 0, 1, 21, 0),(129, NULL, 'Network Analysis', 'network-analysis', 1, 0, 2, 21, 0),(130, NULL, 'Control Systems', 'control-systems', 1, 0, 3, 21, 0),(131, NULL, 'Thermal Energy', 'thermal-energy', 1, 0, 1, 22, 0),(132, NULL, 'Chemical Energy', 'chemical-energy', 1, 0, 2, 22, 0),(133, NULL, 'Electrical Energy', 'electrical-energy', 1, 0, 3, 22, 0),(134, NULL, 'Nuclear Energy', 'nuclear-energy', 1, 0, 4, 22, 0),(135, NULL, 'Software Engineering ', 'software-engineering', 1, 0, 1, 23, 0),(136, NULL, 'Civil Engineering', 'civil-engineering-', 1, 0, 2, 23, 0),(137, NULL, 'Nuclear Engineering', 'nuclear-engineering', 1, 0, 3, 23, 0),(138, NULL, 'Nuclear Safety', 'nuclear-safety', 1, 0, 1, 24, 0),(139, NULL, 'Agriculture Safety', 'agriculture-safety', 1, 0, 2, 24, 0),(140, NULL, 'Occupational Health Safety', 'occupational-health-safety', 1, 0, 3, 24, 0),(141, NULL, 'Unique Fundraisers', 'unique-fundraisers', 1, 0, 1, 25, 0),(142, NULL, 'Sports Fundraiserse', 'sports-fundraiserse', 1, 0, 2, 25, 0),(143, NULL, 'Fundraisers', 'fundraisers', 1, 0, 3, 25, 0),(144, NULL, 'Staying Informed', 'staying-informed', 1, 0, 1, 26, 0),(145, NULL, 'Medical Edcuation ', 'medical-edcuation-', 1, 0, 2, 26, 0),(146, NULL, 'Managing a partucular disease', 'managing-a-partucular-disease', 1, 0, 3, 26, 0),(147, NULL, 'Customs & Border Protection', 'customs-and-border-protection', 1, 0, 1, 27, 0),(148, NULL, 'Federal Law & Enforcement', 'federal-law-and-enforcement', 1, 0, 2, 27, 0),(149, NULL, 'Nation Protection', 'nation-protection', 1, 0, 3, 27, 0),(150, NULL, 'Benefits Administrators', 'benefits-administrators', 1, 0, 1, 28, 0),(151, NULL, 'Executive Compensation Analysts', 'executive-compensation-analysts', 1, 0, 2, 28, 0),(152, NULL, 'Managment Analysts', 'managment-analysts', 1, 0, 3, 28, 0),(153, NULL, 'Health Insurance ', 'health-insurance-', 1, 0, 1, 29, 0),(154, NULL, 'Life Insurance', 'life-insurance', 1, 0, 2, 29, 0),(155, NULL, 'Vehicle Insurance', 'vehicle-insurance', 1, 0, 3, 29, 0),(156, NULL, 'Artificial Intelligence ', 'artificial-intelligence', 1, 0, 1, 30, 0),(157, NULL, 'Predictive Analytics ', 'predictive-analytics', 1, 0, 2, 30, 0),(158, NULL, 'Science & Technology', 'science-and-technology', 1, 0, 3, 30, 0),(159, NULL, 'Work Experience internship', 'work-experience-internship', 1, 0, 1, 31, 0),(160, NULL, 'Research internship', 'research-internship', 1, 0, 2, 31, 0),(161, NULL, 'Sales & Marketing Intern', 'sales-and-marketing-intern', 1, 0, 3, 31, 0),(162, NULL, 'According To Law', 'according-to-law', 1, 0, 1, 32, 0),(163, NULL, 'Defined Rule', 'defined-rule', 1, 0, 2, 32, 0),(164, NULL, 'Shipping ', 'shipping-', 1, 0, 1, 33, 0),(165, NULL, 'Transpotation Managment', 'transpotation-managment', 1, 0, 2, 33, 0),(166, NULL, 'Third-party Logistics Provider', 'third-party-logistics-provider', 1, 0, 3, 33, 0),(167, NULL, 'General Maintenance', 'general-maintenance', 1, 0, 1, 34, 0),(168, NULL, 'Automobile Maintenance', 'automobile-maintenance', 1, 0, 2, 34, 0),(169, NULL, 'Equipment Manitenance', 'equipment-manitenance', 1, 0, 3, 34, 0),(170, NULL, 'Project Managment', 'project-managment', 1, 0, 1, 35, 0),(171, NULL, 'Planning ', 'planning-', 1, 0, 2, 35, 0),(172, NULL, 'Risk Managment', 'risk-managment', 1, 0, 3, 35, 0),(173, NULL, 'Quality Assurance', 'quality-assurance', 1, 0, 1, 36, 0),(174, NULL, 'Product Manager', 'product-manager', 1, 0, 2, 36, 0),(175, NULL, 'Planning Supervisor', 'planning-supervisor', 1, 0, 3, 36, 0),(176, NULL, 'Networking ', 'networking-', 1, 0, 1, 37, 0),(177, NULL, 'Direct Mail Marketing', 'direct-mail-marketing', 1, 0, 2, 37, 0),(178, NULL, 'Media Advertising ', 'media-advertising-', 1, 0, 3, 37, 0),(179, NULL, 'Supply Chain', 'supply-chain', 1, 0, 1, 38, 0),(180, NULL, 'Hazardous Materials Management', 'hazardous-materials-management', 1, 0, 2, 38, 0),(181, NULL, 'Materials Inventory Managment', 'materials-inventory-managment', 1, 0, 3, 38, 0),(182, NULL, 'Aerospace', 'aerospace', 1, 0, 1, 39, 0),(183, NULL, 'Automotive', 'automotive', 1, 0, 2, 39, 0),(184, NULL, 'Biomedical', 'biomedical', 1, 0, 3, 39, 0),(185, NULL, 'Mechanical', 'mechanical', 1, 0, 4, 39, 0),(186, NULL, 'Naval', 'naval', 1, 0, 5, 39, 0),(187, NULL, 'Conventional Mortgage', 'conventional-mortgage', 1, 0, 1, 40, 0),(188, NULL, 'Adjustable Rate Mortgage', 'adjustable-rate-mortgage', 1, 0, 2, 40, 0),(189, NULL, 'Commercial Mortgages', 'commercial-mortgages', 1, 0, 3, 40, 0),(190, NULL, 'Economic Security', 'economic-security', 1, 0, 1, 41, 0),(191, NULL, 'Environmental Security', 'environmental-security', 1, 0, 2, 41, 0),(192, NULL, 'Military Security', 'military-security', 1, 0, 3, 41, 0),(193, NULL, 'Freelance Portfolios', 'freelance-portfolios', 1, 0, 1, 42, 0),(194, NULL, 'Freelance Freedom', 'freelance-freedom', 1, 0, 2, 42, 0),(195, NULL, 'Freelance Jobs', 'freelance-jobs', 1, 0, 3, 42, 0),(196, NULL, 'Offset Lithographp', 'offset-lithographp', 1, 0, 1, 43, 0),(197, NULL, 'Themography Raised Printing', 'themography-raised-printing', 1, 0, 2, 43, 0),(198, NULL, 'Digital Printing ', 'digital-printing-', 1, 0, 3, 43, 0),(199, NULL, 'idea Generation', 'idea-generation', 1, 0, 1, 44, 0),(200, NULL, 'Need Based Generation', 'need-based-generation', 1, 0, 2, 44, 0),(201, NULL, 'Design Solution', 'design-solution', 1, 0, 3, 44, 0),(202, NULL, 'Media Relations', 'media-relations', 1, 0, 1, 45, 0),(203, NULL, 'Media Tours ', 'media-tours-', 1, 0, 2, 45, 0),(204, NULL, 'Newsletters ', 'newsletters-', 1, 0, 3, 45, 0),(205, NULL, 'Automised Security', 'automised-security', 1, 0, 1, 46, 0),(206, NULL, 'Environmental & Social Safety', 'environmental-and-social-safety', 1, 0, 2, 46, 0),(207, NULL, 'Basic Research', 'basic-research', 1, 0, 1, 47, 0),(208, NULL, 'Applied Research', 'applied-research', 1, 0, 2, 47, 0),(209, NULL, 'Methods & Appraches', 'methods-and-appraches', 1, 0, 3, 47, 0),(210, NULL, 'Department Stores', 'department-stores', 1, 0, 1, 48, 0),(211, NULL, 'Discount Stores', 'discount-stores', 1, 0, 2, 48, 0),(212, NULL, 'Supermarkets', 'supermarkets', 1, 0, 3, 48, 0),(213, NULL, 'Sales Contracts', 'sales-contracts', 1, 0, 1, 49, 0),(214, NULL, 'Sales Forecasts', 'sales-forecasts', 1, 0, 2, 49, 0),(215, NULL, 'Sales Managment', 'sales-managment', 1, 0, 3, 49, 0),(216, NULL, 'Scientific Managment', 'scientific-managment', 1, 0, 1, 50, 0),(217, NULL, 'Scientific Research', 'scientific-research', 1, 0, 2, 50, 0),(218, NULL, 'Scientific invenctions', 'scientific-invenctions', 1, 0, 3, 50, 0),(219, NULL, 'Shppping/Distrubution Companies', 'shppping-distrubution companies', 1, 0, 1, 51, 0),(220, NULL, 'Services', 'services', 1, 0, 2, 51, 0),(221, NULL, 'Channels & Softwares', 'channels-and-softwares', 1, 0, 3, 51, 0),(222, NULL, 'Medical Technicians', 'medical-technicians', 1, 0, 1, 52, 0),(223, NULL, 'Electrical Technicians', 'electrical-technicians', 1, 0, 2, 52, 0),(224, NULL, 'Accounting Technicians', 'accounting-technicians', 1, 0, 3, 52, 0),(225, NULL, 'Construction Trade', 'construction-trade', 1, 0, 1, 53, 0),(226, NULL, 'Stock Trade', 'stock-trade', 1, 0, 2, 53, 0),(227, NULL, 'skilled Trade', 'skilled-trade', 1, 0, 3, 53, 0),(228, NULL, 'Option Trade', 'option-trade', 1, 0, 4, 53, 0),(229, NULL, 'Transpotation System', 'transpotation-system', 1, 0, 1, 54, 0),(230, NULL, 'Human-Powered', 'human-powered', 1, 0, 2, 54, 0),(231, NULL, 'Airline,Train,bus,car', 'airline-train-bus-car', 1, 0, 3, 54, 0),(232, NULL, 'Subway & Civil', 'subway-and-civil', 1, 0, 1, 55, 0),(233, NULL, 'Traffic Highway Transpotation', 'traffic-highway-transpotation', 1, 0, 2, 55, 0),(234, NULL, 'Small Business', 'small-business', 1, 0, 1, 56, 0),(235, NULL, 'E-Commerce Sites', 'e-commerce-sites', 1, 0, 2, 56, 0),(236, NULL, 'Portals', 'portals', 1, 0, 3, 56, 0),(237, NULL, 'Search Engines', 'search-engines', 1, 0, 4, 56, 0),(238, NULL, 'Personal,Commercial,Govt', 'personal-commercial-govt', 1, 0, 5, 56, 0);";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_cities` (
                      `id` mediumint(6) NOT NULL AUTO_INCREMENT,
                      `cityName` varchar(255) DEFAULT NULL,
                      `name` varchar(255) DEFAULT NULL,
                      `localname` varchar(255) DEFAULT NULL,
                      `internationalname` varchar(255) DEFAULT NULL,
                      `stateid` smallint(8) DEFAULT NULL,
                      `countryid` smallint(9) DEFAULT NULL,
                      `isedit` tinyint(1) DEFAULT '0',
                      `enabled` tinyint(1) NOT NULL DEFAULT '0',
                      `serverid` int(11) DEFAULT NULL,
                      `latitude` varchar(255) DEFAULT NULL,
                      `longitude` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `countryid` (`countryid`),
                      KEY `stateid` (`stateid`),
                      FULLTEXT KEY `name` (`name`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ";
            wpjobportal::$_db->query($query);

            // inserting cities form file query code start

            if ( ! function_exists( 'WP_Filesystem' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            global $wp_filesystem;
            if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
                $creds = request_filesystem_credentials( site_url() );
                wp_filesystem( $creds );
            }
            // file path for us cities
            $installfile = WPJOBPORTAL_PLUGIN_PATH . 'includes/data/cities/us/cities.txt';
            // check file exsists
            if ($wp_filesystem->exists($installfile)) {
                // reading the file
                $file_contents = $wp_filesystem->get_contents($installfile);
                if ($file_contents !== false) { // if no error then proceed
                    //preparing queries to execute
                    $query = wpjobportalphplib::wpJP_str_replace('#__', wpjobportal::$_db->prefix, $file_contents);

                    $query_array  = explode(';',$query); // breaking queries up to execute seprately
                    foreach ($query_array as $array_key => $single_query) {
                        $single_query = trim($single_query);
                        if($single_query != ''){
                            wpjobportal::$_db->query($single_query); // execute single insert query
                        }
                    }
                }
            }
            // inserting cities form file query code end


            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_companies` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uid` int(11) DEFAULT NULL,
                `name` varchar(255) NOT NULL DEFAULT '',
                `alias` varchar(255) NOT NULL,
                `url` varchar(255) DEFAULT NULL,
                `logofilename` varchar(255) DEFAULT NULL,
                `logoisfile` tinyint(1) DEFAULT '-1',
                `logo` blob,
                `smalllogofilename` varchar(255) DEFAULT NULL,
                `smalllogoisfile` tinyint(1) DEFAULT '-1',
                `smalllogo` tinyblob,
                `tagline` varchar(255) NOT NULL DEFAULT '',
                `contactemail` varchar(255) NOT NULL DEFAULT '',
                `description` text,
                `city` varchar(255) DEFAULT NULL,
                `address1` varchar(255) DEFAULT NULL,
                `address2` varchar(255) DEFAULT NULL,
                `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                `modified` datetime DEFAULT NULL,
                `hits` int(11) DEFAULT NULL,
                `metadescription` text,
                `metakeywords` text,
                `status` tinyint(1) NOT NULL DEFAULT '0',
                `userpackageid` int(11) DEFAULT NULL,
                `price` int(40) DEFAULT NULL,
                `isgoldcompany` tinyint(1) DEFAULT '0',
                `startgolddate` datetime DEFAULT NULL,
                `endgolddate` datetime NOT NULL,
                `endfeatureddate` datetime NOT NULL,
                `isfeaturedcompany` tinyint(1) DEFAULT '0',
                `startfeatureddate` datetime DEFAULT NULL,
                `params` longtext,
                `serverstatus` varchar(255) DEFAULT NULL,
                `serverid` int(11) DEFAULT '0',
                `facebook_link` varchar(255) DEFAULT NULL,
                `twiter_link` varchar(255) DEFAULT NULL,
                `linkedin_link` varchar(255) DEFAULT NULL,
                `youtube_link` varchar(255) DEFAULT NULL,

                PRIMARY KEY (`id`),
                KEY `companies_uid` (`uid`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            wpjobportal::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_companycities` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `companyid` int(11) NOT NULL,
              `cityid` int(11) NOT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `companyid` (`companyid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            wpjobportal::$_db->query($query);

          $query = "INSERT INTO  `" . wpjobportal::$_db->prefix . "wj_portal_config` (`configname`, `configvalue`, `configfor`, `addon`) VALUES
              ('companyautoapprove', '1', 'company', NULL),
              ('comp_city', '1', 'company', NULL),
              ('comp_zipcode', '1', 'company', NULL),
              ('cur_location', '1', 'wpjobportal', NULL),
              ('empautoapprove', '1', 'resume', NULL),
              ('jobautoapprove', '1', 'job', NULL),
              ('job_editor', '1', 'job', NULL),
              ('mailfromaddress', 'sender@yourdomain.com', 'email', NULL),
              ('mailfromname', 'WP JOB PORTAL', 'email', NULL),
              ('newdays', '7', 'job', NULL),
              ('indeedjob_showafter', '12', 'indeedjob', NULL),
              ('indeedjob_jobperrequest', '', 'indeedjob', NULL),
              ('js_cpmessage', '0', 'jscontrolpanel', 'message'),
              ('rss_resume_email', '1', 'rss', 'rssfeedback'),
              ('search_job_showsave', '1', 'searchjob', NULL),
              ('careerbuilder_enabled', '0', 'careerbuilder', NULL),
              ('actk', '0', 'wpjobportal', NULL),
              ('careerbuilder_showafter', '0', 'careerbuilder', NULL),
              ('careerbuilder_jobperrequest', '', 'careerbuilder', NULL),
              ('careerbuilder_emptype', '', 'careerbuilder', NULL),
              ('search_resume_showsave', '1', 'resume', NULL),
              ('careerbuilder_countrycode', '', 'careerbuilder', NULL),
              ('showemployerlink', '1', 'wpjobportal', NULL),
              ('title', 'WP JOB PORTAL', 'default', NULL),
              ('data_directory', 'wpjobportaldata', 'wpjobportal', NULL),
              ('refercode', '', 'wpjobportal', NULL),
              ('careerbuilder_category', '', 'careerbuilder', NULL),
              ('company_logofilezize', '2048', 'company', NULL),
              ('resume_photofilesize', '2048', 'resume', NULL),
              ('offline', '0', 'wpjobportal', NULL),
              ('offline_text', '<p><!--more--></p>\n', 'wpjobportal', NULL),
              ('careerbuilder_developerkey', '', 'careerbuilder', NULL),
              ('visitorview_js_controlpanel', '1', 'visitor', NULL),
              ('visitorview_js_jobcat', '1', 'visitor', NULL),
              ('indeedjob_jobtype', '', 'indeedjob', NULL),
              ('visitorview_js_newestjobs', '1', 'visitor', NULL),
              ('visitorview_js_jobsearch', '1', 'visitor', NULL),
              ('visitorview_js_jobsearchresult', '1', 'visitor', NULL),
              ('visitorview_emp_conrolpanel', '1', 'visitor', NULL),
              ('visitorview_emp_viewcompany', '1', 'visitor', NULL),
              ('visitorview_emp_viewjob', '1', 'visitor', NULL),
              ('allow_jobshortlist', '0', 'wpjobportal', 'shortlist'),
              ('featuredjob_autoapprove', '0', 'featuredjob', 'featuredjob'),
              ('featuredcompany_autoapprove', '0', 'company', 'featuredcompany'),
              ('featuredresume_autoapprove', '0', 'featuredresume', 'featureresume'),
              ('date_format', 'm/d/Y', 'default', NULL),
              ('adminemailaddress', 'admin@yourdomain.com', 'email', NULL),
              ('message_auto_approve', '0', 'messages', 'message'),
              ('conflict_message_auto_approve', '0', 'messages', 'message'),
              ('overwrite_jobalert_settings', '0', 'visitor', 'jobalert'),
              ('visitor_can_apply_to_job', '0', 'visitor', 'visitorapplyjob'),
              ('visitor_show_login_message', '1', 'visitor', NULL),
              ('folder_auto_approve', '0', 'folder', 'folder'),
              ('department_auto_approve', '0', 'department', 'departments'),
              ('formcompany', '1', 'emcontrolpanel', NULL),
              ('mycompanies', '1', 'emcontrolpanel', NULL),
              ('formjob', '1', 'emcontrolpanel', NULL),
              ('myjobs', '1', 'emcontrolpanel', NULL),
              ('formdepartment', '0', 'emcontrolpanel', 'departments'),
              ('mydepartment', '0', 'emcontrolpanel', 'departments'),
              ('empmessages', '0', 'emcontrolpanel', 'message'),
              ('alljobsappliedapplications', '1', 'emcontrolpanel', NULL),
              ('resumesearch', '0', 'emcontrolpanel', 'resumesearch'),
              ('my_resumesearches', '0', 'emcontrolpanel', 'resumesearch'),
              ('my_stats', '1', 'emcontrolpanel', NULL),
              ('myfolders', '0', 'emcontrolpanel', 'folder'),
              ('formresume', '1', 'jscontrolpanel', NULL),
              ('myresumes', '1', 'jscontrolpanel', NULL),
              ('jspurchasehistory', '0', 'jscontrolpanel', 'credits'),
              ('jobalertsetting', '0', 'jscontrolpanel', 'jobalert'),
              ('jobcat', '1', 'jscontrolpanel', NULL),
              ('listnewestjobs', '1', 'jscontrolpanel', NULL),
              ('myappliedjobs', '1', 'jscontrolpanel', NULL),
              ('jobsearch', '1', 'jscontrolpanel', NULL),
              ('my_jobsearches', '1', 'jscontrolpanel', NULL),
              ('jsmy_stats', '1', 'jscontrolpanel', NULL),
              ('jsmessages', '0', 'jscontrolpanel', 'message'),
              ('tmenu_emcontrolpanel', '1', 'topmenu', NULL),
              ('tmenu_emnewjob', '1', 'topmenu', NULL),
              ('tmenu_emmyjobs', '1', 'topmenu', NULL),
              ('tmenu_emmycompanies', '1', 'topmenu', NULL),
              ('tmenu_emsearchresume', '1', 'topmenu', NULL),
              ('tmenu_jscontrolpanel', '1', 'topmenu', NULL),
              ('tmenu_wpjobportalategory', '1', 'topmenu', NULL),
              ('tmenu_jssearchjob', '1', 'topmenu', NULL),
              ('tmenu_jsnewestjob', '1', 'topmenu', NULL),
              ('tmenu_jsmyresume', '1', 'topmenu', NULL),
              ('show_applied_resume_status', '1', 'jobapply', NULL),
              ('jobalert_auto_approve', '0', 'jobalert', 'jobalert'),
              ('resume_contact_detail', '1', 'resume', NULL),
              ('api_primary', 'a2d09c7d76fced01f8be4b1f4cce8bec', 'api', NULL),
              ('api_secondary', '', 'api', NULL),
              ('comp_show_url', '1', 'company', NULL),
              ('employerview_js_controlpanel', '1', 'jscontrolpanel', NULL),
              ('vis_emformjob', '1', 'emcontrolpanel', NULL),
              ('vis_emresumesearch', '1', 'emcontrolpanel', 'resumesearch'),
              ('vis_emmycompanies', '1', 'emcontrolpanel', NULL),
              ('vis_emalljobsappliedapplications', '1', 'emcontrolpanel', NULL),
              ('vis_emformcompany', '1', 'emcontrolpanel', NULL),
              ('tmenu_vis_emsearchresume', '1', 'topmenu', NULL),
              ('tmenu_vis_emmycompanies', '1', 'topmenu', NULL),
              ('tmenu_vis_emmyjobs', '1', 'topmenu', NULL),
              ('tmenu_vis_emnewjob', '1', 'topmenu', NULL),
              ('tmenu_vis_emcontrolpanel', '1', 'topmenu', NULL),
              ('vis_emmy_resumesearches', '1', 'emcontrolpanel', 'resumesearch'),
              ('vis_emmyjobs', '1', 'emcontrolpanel', NULL),
              ('vis_emformdepartment', '0', 'emcontrolpanel', 'departments'),
              ('vis_emmydepartment', '0', 'emcontrolpanel', 'departments'),
              ('vis_emmy_stats', '1', 'emcontrolpanel', NULL),
              ('vis_emmessages', '0', 'emcontrolpanel', 'message'),
              ('vis_emmyfolders', '0', 'emcontrolpanel', 'folder'),
              ('tmenu_vis_jscontrolpanel', '1', 'topmenu', NULL),
              ('tmenu_vis_wpjobportalategory', '1', 'topmenu', NULL),
              ('tmenu_vis_jsnewestjob', '1', 'topmenu', NULL),
              ('tmenu_vis_jsmyresume', '1', 'topmenu', NULL),
              ('vis_jsformresume', '1', 'jscontrolpanel', NULL),
              ('vis_wpjobportalat', '1', 'jscontrolpanel', NULL),
              ('vis_jsmyresumes', '1', 'jscontrolpanel', NULL),
              ('vis_jslistnewestjobs', '1', 'jscontrolpanel', NULL),
              ('vis_jsmyappliedjobs', '1', 'jscontrolpanel', NULL),
              ('vis_jsmy_jobsearches', '1', 'jscontrolpanel', NULL),
              ('vis_jspurchasehistory', '0', 'jscontrolpanel', 'credits'),
              ('vis_wpjobportalearch', '1', 'jscontrolpanel', NULL),
              ('vis_jsmy_stats', '1', 'jscontrolpanel', NULL),
              ('vis_wpjobportallertsetting', '1', 'jscontrolpanel', NULL),
              ('vis_jsmessages', '0', 'jscontrolpanel', 'message'),
              ('tmenu_vis_jssearchjob', '1', 'topmenu', NULL),
              ('rss_job_title', 'Jobs RSS title', 'rss', 'rssfeedback'),
              ('rss_job_description', 'this is some desc text for job rss', 'rss', 'rssfeedback'),
              ('rss_job_categories', '1', 'rss', 'rssfeedback'),
              ('rss_job_image', '1', 'rss', 'rssfeedback'),
              ('rss_resume_categories', '1', 'rss', 'rssfeedback'),
              ('rss_resume_image', '1', 'rss', 'rssfeedback'),
              ('rss_resume_title', 'Resume RSS', 'rss', 'rssfeedback'),
              ('rss_resume_description', 'Resume RSS Show the Latest Resume On Our Sites', 'rss', 'rssfeedback'),
              ('rss_job_ttl', '12', 'rss', 'rssfeedback'),
              ('rss_job_copyright', 'Copyright 2009-2016', 'rss', 'rssfeedback'),
              ('rss_job_webmaster', 'admin@domain.com', 'rss', 'rssfeedback'),
              ('rss_job_editor', 'admin@domain.com', 'rss', 'rssfeedback'),
              ('rss_resume_copyright', 'copy right text', 'rss', 'rssfeedback'),
              ('rss_resume_webmaster', 'web master text', 'rss', 'rssfeedback'),
              ('rss_resume_editor', 'editor text', 'rss', 'rssfeedback'),
              ('rss_resume_ttl', '', 'rss', 'rssfeedback'),
              ('rss_resume_file', '1', 'rss', 'rssfeedback'),
              ('visitor_can_post_job', '1', 'visitor', NULL),
              ('visitor_can_edit_job', '1', 'visitor', NULL),
              ('job_captcha', '1', 'captcha', NULL),
              ('resume_captcha', '1', 'captcha', NULL),
              ('job_rss', '1', 'rss', 'rssfeedback'),
              ('thousand_separator', ',', 'default', NULL),
              ('short_price', '1', 'default', NULL),
              ('decimal_places', '0', 'default', NULL),
              ('resume_rss', '1', 'rss', 'rssfeedback'),
              ('empresume_rss', '1', 'emcontrolpanel', 'rssfeedback'),
              ('wpjobportalrss', '1', 'jscontrolpanel', 'rssfeedback'),
              ('vis_resume_rss', '1', 'emcontrolpanel', 'rssfeedback'),
              ('vis_job_rss', '1', 'jscontrolpanel', 'rssfeedback'),
              ('default_longitude', '118.2426', 'default', NULL),
              ('default_latitude', '34.0549', 'default', NULL),
              ('nooffeaturedjobsinlisting', '2', 'job', 'featuredjob'),
              ('showfeaturedjobsinlistjobs', '0', 'job', 'featuredjob'),
              ('googleadsenseclient', 'ca-pub-8827762976015158', 'googleadds', NULL),
              ('googleadsenseslot', '9560237528', 'googleadds', NULL),
              ('googleadsensewidth', '717', 'googleadds', NULL),
              ('googleadsenseheight', '90', 'googleadds', NULL),
              ('googleadsensecustomcss', '', 'googleadds', NULL),
              ('googleadsenseshowafter', '5', 'googleadds', NULL),
              ('googleadsenseshowinlistjobs', '1', 'googleadds', NULL),
              ('cron_job_alert_key', 'f1877c1756a68271d12db39ddc87dad7', 'wpjobportal', 'cronjob'),
              ('subcategory_limit', '2', 'category', NULL),
              ('defaultradius', '2', 'default', NULL),
              ('mapwidth', '700', 'default', NULL),
              ('mapheight', '400', 'default', NULL),
              ('comp_name', '1', 'company', NULL),
              ('comp_email_address', '1', 'company', NULL),
              ('labelinlisting', '1', 'default', NULL),
              ('jsregister', '1', 'jscontrolpanel', NULL),
              ('vis_jsregister', '1', 'jscontrolpanel', NULL),
              ('empregister', '1', 'emcontrolpanel', NULL),
              ('vis_emempregister', '1', 'emcontrolpanel', NULL),
              ('authentication_client_key', '', 'jobsharing', NULL),
              ('employer_share_fb_like', '0', 'social', 'socialshare'),
              ('hostdata', '7cee62779078f428570048fd5c3541ee', 'hostdata', NULL),
              ('employer_share_fb_share', '0', 'social', 'socialshare'),
              ('employer_share_fb_comments', '0', 'social', 'socialshare'),
              ('employer_share_google_like', '0', 'social', 'socialshare'),
              ('employer_share_google_share', '0', 'social', 'socialshare'),
              ('employer_share_blog_share', '0', 'social', 'socialshare'),
              ('employer_share_friendfeed_share', '0', 'social', 'socialshare'),
              ('employer_share_linkedin_share', '0', 'social', 'socialshare'),
              ('employer_share_digg_share', '0', 'social', 'socialshare'),
              ('employer_share_twitter_share', '0', 'social', 'socialshare'),
              ('employer_share_myspace_share', '0', 'social', 'socialshare'),
              ('employer_share_yahoo_share', '0', 'social', 'socialshare'),
              ('newfolders', '0', 'emcontrolpanel', 'folder'),
              ('vis_emnewfolders', '0', 'emcontrolpanel', 'folder'),
              ('employer_resume_alert_fields', '2', 'email', NULL),
              ('defaultaddressdisplaytype', 'csc', 'default', NULL),
              ('jobseeker_defaultgroup', 'subscriber', 'wpjobportal', NULL),
              ('employer_defaultgroup', 'subscriber', 'wpjobportal', NULL),
              ('default_sharing_city', '', 'jobsharing', NULL),
              ('default_sharing_state', '', 'jobsharing', NULL),
              ('default_sharing_country', '', 'jobsharing', NULL),
              ('job_alert_captcha', '1', 'captcha', NULL),
              ('jobseeker_resume_applied_status', '0', 'email', NULL),
              ('server_serial_number', '', 'jobsharing', NULL),
              ('wpjobportalpdatecount', '4451', 'wpjobportal', NULL),
              ('serialnumber', '34267', 'hostdata', NULL),
              ('zvdk', '', 'hostdata', NULL),
              ('comp_viewalljobs', '1', 'company', NULL),
              ('showapplybutton', '1', 'jobapply', NULL),
              ('applybuttonredirecturl', '', 'jobapply', NULL),
              ('image_file_type', 'png,jpeg,gif,jpg', 'wpjobportal', NULL),
              ('document_file_type', 'pdf, doc, docx, xls, xlsx, odt, txt, jpeg,png,jpg', 'wpjobportal', NULL),
              ('jobsloginlogout', '1', 'jscontrolpanel', NULL),
              ('emploginlogout', '1', 'emcontrolpanel', NULL),
              ('number_of_cities_for_autocomplete', '3', 'city', NULL),
              ('document_file_size', '3072', 'wpjobportal', NULL),
              ('document_max_files', '5', 'wpjobportal', NULL),
              ('max_resume_addresses', '3', 'resume', NULL),
              ('max_resume_institutes', '3', 'resume', NULL),
              ('max_resume_employers', '3', 'resume', NULL),
              ('max_resume_references', '3', 'resume', NULL),
              ('max_resume_languages', '3', 'resume', NULL),
              ('show_only_section_that_have_value', '0', 'resume', NULL),
              ('pagination_default_page_size', '10', 'default', NULL),
              ('vis_jslistjobshortlist', '0', 'jscontrolpanel', 'shortlist'),
              ('listallcompanies', '1', 'jscontrolpanel', NULL),
              ('listjobbytype', '1', 'jscontrolpanel', NULL),
              ('vis_jslistallcompanies', '1', 'jscontrolpanel', NULL),
              ('vis_jslistjobbytype', '1', 'jscontrolpanel', NULL),
              ('listjobshortlist', '0', 'jscontrolpanel', 'shortlist'),
              ('currency_align', '2', 'default', NULL),
              ('job_currency', '$', 'default', NULL),
              ('system_slug', 'wp-job-portal', 'wpjobportal', NULL),
              ('jobtype_per_row', '2', 'jobtype', NULL),
              ('company_contact_detail', '1', 'company', NULL),
              ('system_have_featured_company', '1', 'company', NULL),
              ('system_have_featured_resume', '1', 'resume', NULL),
              ('system_have_featured_job', '1', 'job', NULL),
              ('searchjobtag', '4', 'job', 'tag'),
              ('categories_colsperrow', '3', 'category', NULL),
              ('productcode', 'wpjobportal', 'default', NULL),
              ('versioncode', '2.3.5', 'default', NULL),
              ('producttype', 'free', 'default', NULL),
              ('vis_jscredits', '0', 'jscontrolpanel', 'credits'),
              ('vis_emcredits', '1', 'emcontrolpanel', NULL),
              ('empcredits', '0', 'emcontrolpanel', 'credits'),
              ('jscreditlog', '0', 'jscontrolpanel', 'credits'),
              ('vis_jscreditlog', '0', 'jscontrolpanel', 'credits'),
              ('vis_emcreditlog', '0', 'emcontrolpanel', 'credits'),
              ('empcreditlog', '0', 'emcontrolpanel', 'credits'),
              ('emppurchasehistory', '0', 'emcontrolpanel', 'credits'),
              ('vis_empurchasehistory', '0', 'emcontrolpanel', 'credits'),
              ('jscredits', '0', 'jscontrolpanel', 'credits'),
              ('empratelist', '0', 'emcontrolpanel', 'credits'),
              ('vis_emratelist', '0', 'emcontrolpanel', 'credits'),
              ('visitor_can_add_resume', '1', 'resume', 'visitorapplyjob'),
              ('jsratelist', '0', 'jscontrolpanel', 'credits'),
              ('vis_jsratelist', '0', 'jscontrolpanel', 'credits'),
              ('activity_log_filter', '\"ages\"', 'wpjobportal', NULL),
              ('recaptcha_publickey', '', 'captcha', NULL),
              ('recaptcha_privatekey', '', 'captcha', NULL),
              ('captcha_selection', '2', 'captcha', NULL),
              ('owncaptcha_calculationtype', '0', 'captcha', NULL),
              ('indeedjob_location', '', 'indeedjob', NULL),
              ('disable_employer', '1', 'wpjobportal', NULL),
              ('newtyped_cities', '0', 'city', 'addressdata'),
              ('indeedjob_enabled', '0', 'indeedjob', NULL),
              ('indeedjob_apikey', '', 'indeedjob', NULL),
              ('indeedjob_category', '', 'indeedjob', NULL),
              ('owncaptcha_totaloperand', '2', 'captcha', NULL),
              ('owncaptcha_subtractionans', '0', 'captcha', NULL),
              ('number_of_tags_for_autocomplete', '15', 'tag', 'tag'),
              ('newtyped_tags', '0', 'tag', 'tag'),
              ('loginwithfacebook', '0', 'login', 'sociallogin'),
              ('apikeyfacebook', '', 'facebook', 'sociallogin'),
              ('apikeylinkedin', '', 'linkedin', 'sociallogin'),
              ('loginwithlinkedin', '0', 'login', 'sociallogin'),
              ('applywithfacebook', '0', 'jobapply', 'sociallogin'),
              ('applywithxing', '0', 'jobapply', 'sociallogin'),
              ('clientsecretfacebook', '', 'facebook', 'sociallogin'),
              ('clientsecretlinkedin', '', 'linkedin', 'sociallogin'),
              ('loginwithxing', '0', 'login', 'sociallogin'),
              ('apikeyxing', '', 'xing', 'sociallogin'),
              ('clientsecretxing', '', 'xing', 'sociallogin'),
              ('applywithlinkedin', '0', 'jobapply', 'sociallogin'),
              ('jobs_graph', '1', 'emcontrolpanel', NULL),
              ('resume_graph', '1', 'emcontrolpanel', NULL),
              ('box_newestresume', '1', 'emcontrolpanel', NULL),
              ('box_appliedresume', '1', 'emcontrolpanel', NULL),
              ('vis_jobs_graph', '1', 'emcontrolpanel', NULL),
              ('vis_resume_graph', '1', 'emcontrolpanel', NULL),
              ('vis_box_newestresume', '1', 'emcontrolpanel', NULL),
              ('vis_box_appliedresume', '1', 'emcontrolpanel', NULL),
              ('jsactivejobs_graph', '1', 'jscontrolpanel', NULL),
              ('jssuggestedjobs_box', '1', 'jscontrolpanel', NULL),
              ('jsappliedresume_box', '1', 'jscontrolpanel', NULL),
              ('vis_jsactivejobs_graph', '1', 'jscontrolpanel', NULL),
              ('vis_jssuggestedjobs_box', '1', 'jscontrolpanel', NULL),
              ('vis_jsappliedresume_box', '1', 'jscontrolpanel', NULL),
              ('cap_on_reg_form', '1', 'captcha', NULL),
              ('em_cpmessage', '0', 'emcontrolpanel', 'message'),
              ('em_cpnotification', '1', 'emcontrolpanel', NULL),
              ('categories_numberofjobs', '1', 'category', NULL),
              ('categories_numberofresumes', '1', 'category', NULL),
              ('jobtype_numberofjobs', '1', 'jobtype', NULL),
              ('job_seo', '[title][company][location]', 'seo', NULL),
              ('company_seo', '[name][location]', 'seo', NULL),
              ('resume_seo', '[title][location]', 'seo', NULL),
              ('empmystats', '1', 'emcontrolpanel', NULL),
              ('vis_empmystats', '1', 'emcontrolpanel', NULL),
              ('jsmystats', '1', 'jscontrolpanel', NULL),
              ('vis_jsmystats', '1', 'jscontrolpanel', NULL),
              ('allow_tellafriend', '0', 'job', 'tellfriend'),
              ('emresumebycategory', '1', 'emcontrolpanel', NULL),
              ('vis_emresumebycategory', '1', 'emcontrolpanel', NULL),
              ('default_pageid', '239', 'default', NULL),
              ('visitorview_emp_resumesearch', '1', 'visitor', 'resumesearch'),
              ('visitorview_emp_viewresume', '1', 'visitor', NULL),
              ('visitorview_emp_resumecat', '1', 'visitor', NULL),
              ('google_map_api_key', 'AIzaSyCZcnAK0DiGg8lAXej74e7PlrhkfCM86-M', 'default', NULL),
              ('tell_a_friend_captcha', '1', 'captcha', NULL),
              ('auto_assign_free_package', '0', 'creditpack', NULL),
              ('free_package_purchase_only_once', '1', 'creditpack', NULL),
              ('free_package_auto_approve', '1', 'creditpack', NULL),
              ('register_jobseeker_redirect_page', '146', 'register', NULL),
              ('register_employer_redirect_page', '5', 'register', NULL),
              ('visitor_add_resume_redirect_page', '146', 'visitor', NULL),
              ('visitor_add_job_redirect_page', '6', 'visitor', NULL),
              ('temp_employer_dashboard_stats_graph', '1', 'emcontrolpanel', NULL),
              ('temp_employer_dashboard_useful_links', '1', 'emcontrolpanel', NULL),
              ('temp_employer_dashboard_applied_resume', '1', 'emcontrolpanel', NULL),
              ('temp_employer_dashboard_saved_search', '1', 'emcontrolpanel', NULL),
              ('temp_employer_dashboard_credits_log', '1', 'emcontrolpanel', NULL),
              ('temp_employer_dashboard_purchase_history', '1', 'emcontrolpanel', NULL),
              ('temp_employer_dashboard_newest_resume', '1', 'emcontrolpanel', NULL),
              ('vis_temp_employer_dashboard_stats_graph', '1', 'emcontrolpanel', NULL),
              ('vis_temp_employer_dashboard_useful_links', '1', 'emcontrolpanel', NULL),
              ('vis_temp_employer_dashboard_applied_resume', '1', 'emcontrolpanel', NULL),
              ('vis_temp_employer_dashboard_saved_search', '1', 'emcontrolpanel', NULL),
              ('vis_temp_employer_dashboard_credits_log', '1', 'emcontrolpanel', NULL),
              ('vis_temp_employer_dashboard_purchase_history', '1', 'emcontrolpanel', NULL),
              ('vis_temp_employer_dashboard_newest_resume', '1', 'emcontrolpanel', NULL),
              ('temp_jobseeker_dashboard_jobs_graph', '1', 'jscontrolpanel', NULL),
              ('temp_jobseeker_dashboard_useful_links', '1', 'jscontrolpanel', NULL),
              ('temp_jobseeker_dashboard_apllied_jobs', '1', 'jscontrolpanel', NULL),
              ('temp_jobseeker_dashboard_shortlisted_jobs', '0', 'jscontrolpanel', 'shortlist'),
              ('temp_jobseeker_dashboard_credits_log', '1', 'jscontrolpanel', NULL),
              ('temp_jobseeker_dashboard_purchase_history', '1', 'jscontrolpanel', NULL),
              ('temp_jobseeker_dashboard_newest_jobs', '1', 'jscontrolpanel', NULL),
              ('vis_temp_jobseeker_dashboard_jobs_graph', '1', 'jscontrolpanel', NULL),
              ('vis_temp_jobseeker_dashboard_useful_links', '1', 'jscontrolpanel', NULL),
              ('vis_temp_jobseeker_dashboard_apllied_jobs', '1', 'jscontrolpanel', NULL),
              ('vis_temp_jobseeker_dashboard_shortlisted_jobs', '0', 'jscontrolpanel', 'shortlist'),
              ('vis_temp_jobseeker_dashboard_credits_log', '1', 'jscontrolpanel', NULL),
              ('vis_temp_jobseeker_dashboard_purchase_history', '1', 'jscontrolpanel', NULL),
              ('vis_temp_jobseeker_dashboard_newest_jobs', '1', 'jscontrolpanel', NULL),
              ('slug_prefix', 'jpt-', 'default', NULL),
              ('home_slug_prefix', 'wpjp-', 'default', NULL),
              ('show_total_number_of_jobs', '1', 'job', NULL),
              ('vis_jobsbycities', '1', 'jscontrolpanel', NULL),
              ('jobsbycities', '1', 'jscontrolpanel', NULL),
              ('jobsbycities_jobcount', '1', 'default', NULL),
              ('jobsbycities_countryname', '1', 'default', NULL),
              ('terms_and_conditions_page_company', '0', 'default', NULL),
              ('terms_and_conditions_page_job', '0', 'default', NULL),
              ('terms_and_conditions_page_resume', '0', 'default', NULL),
              ('job_resume_show_all_categories', '1', 'default', NULL),
              ('system_has_cover_letter', '1', 'default', NULL),
              ('submission_type', '1', 'paidsubmission', NULL),
              ('company_currency_perlisting', '1', 'paidsubmission', NULL),
              ('company_price_perlisting', '', 'paidsubmission', NULL),
              ('company_feature_currency_perlisting', '1', 'paidsubmission', NULL),
              ('company_feature_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_perlisting', '1', 'paidsubmission', NULL),
              ('job_currency_feature_perlisting', '1', 'paidsubmission', NULL),
              ('jobs_feature_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_resume_perlisting', '1', 'paidsubmission', NULL),
              ('job_resume_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_featureresume_perlisting', '1', 'paidsubmission', NULL),
              ('job_featureresume_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_department_perlisting', '1', 'paidsubmission', NULL),
              ('job_department_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_jobsavesearch_perlisting', '', 'paidsubmission', NULL),
              ('job_jobsavesearch_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_resumesavesearch_perlisting', '1', 'paidsubmission', NULL),
              ('job_currency_jobapply_perlisting', '1', 'paidsubmission', NULL),
              ('job_currency_viewresumecontact_perlisting', '1', 'paidsubmission', NULL),
              ('job_currency_viewcompanycontact_perlisting', '1', 'paidsubmission', NULL),
              ('job_viewcompanycontact_price_perlisting', '', 'paidsubmission', NULL),
              ('jobexpiry_days_free', '', 'paidsubmission', NULL),
              ('jobexpiry_days_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_price_perlisting', '', 'paidsubmission', NULL),
              ('feature_company_free_expiry', '', 'paidsubmission', NULL),
              ('job_resumesavesearch_price_perlisting', '', 'paidsubmission', NULL),
              ('job_jobapply_price_perlisting', '', 'paidsubmission', NULL),
              ('job_viewresumecontact_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_coverletter_perlisting', '1', 'paidsubmission', NULL),
              ('job_coverletter_price_perlisting', '', 'paidsubmission', NULL),
              ('feature_company_price_listing', '', 'paidsubmission', NULL),
              ('featuredcompany_days_perlisting', '34400', 'paidsubmission', NULL),
              ('featuredjobexpiry_days_perlisting', '30', 'paidsubmission', NULL),
              ('featuredjobexpiry_days_free', '30', 'paidsubmission', NULL),
              ('job_resume_days_free', '30', 'paidsubmission', NULL),
              ('job_resume_days_perlisting', '15', 'paidsubmission', NULL),
              ('job_jobalert_price_perlisting', '', 'paidsubmission', NULL),
              ('job_currency_jobalert_perlisting', '45', 'paidsubmission', NULL),
              ('company_featureexpire_free', '', 'paidsubmission', NULL),
              ('company_featureexpire_price_perlisting', '', 'paidsubmission', NULL),
              ('mappingservice', 'gmap', 'googlemap', NULL),
              ('comp_description', '1', 'company', NULL),
              ('vis_wpjobportalcat', '1', 'jscontrolpanel', NULL),
              ('vis_wpjobportalalertsetting', '1', 'jscontrolpanel', NULL),
              ('wpjobportal_rss', '1', 'jscontrolpanel', 'rssfeedback'),
              ('image_file_size', '2048', 'file', NULL),
              ('default_country', '126', 'city', NULL),
              ('vis_emppurchasehistory', '1', 'emcontrolpanel', NULL),
              ('tmenu_wpjobportalcategory', '1', 'topmenu', NULL),
              ('tmenu_vis_wpjobportalcategory', '1', 'topmenu', NULL),
              ('vis_empratelist', '0', 'emcontrolpanel', 'credits'),
              ('vis_empcreditlog', '0', 'emcontrolpanel', 'credits'),
              ('vis_empcredits', '0', 'emcontrolpanel', 'credits'),
              ('employe_set_register_link', '1', 'default', 'null'),
              ('employe_register_link', '', 'default', 'null'),
              ('jobseeker_set_register_link', '1', 'default', 'null'),
              ('jobseeker_register_link', '', 'default', 'null'),
              ('set_register_redirect_link', '1', 'default', 'null'),
              ('register_redirect_link', '', 'default', 'null'),
              ('set_login_redirect_link', '1', 'default', 'null'),
              ('login_redirect_link', '', 'default', 'null'),
              ('coverletter_auto_approve', '1', 'coverletter', 'coverletter'),
              ('formcoverletter', '1', 'jscontrolpanel', 'coverletter'),
              ('mycoverletter', '1', 'jscontrolpanel', 'coverletter'),
              ('vis_jsformcoverletter', '1', 'jscontrolpanel', 'coverletter'),
              ('vis_jsmycoverletter', '1', 'jscontrolpanel', 'coverletter'),
              ('jobcity_per_row', '2', 'default', NULL),
              ('recaptcha_version',1,'captcha',NULL),
              ('employerstatboxes', '1', 'emcontrolpanel', NULL),
              ('vis_ememployerresumebox', '1', 'emcontrolpanel', NULL),
              ('resumebycategory', '1', 'emcontrolpanel', NULL),
              ('employerresumebox', '1', 'emcontrolpanel', NULL),
              ('vis_jsjobseekernewestjobs', '1', 'jscontrolpanel', NULL),
              ('jobseekernewestjobs', '1', 'jscontrolpanel', NULL),
              ('jobseekerstatboxes', '1', 'jscontrolpanel', NULL),
              ('jobseekerjobapply', '1', 'jscontrolpanel', NULL),
              ('loginlinkforwpuser', '', 'wpjobportal', NULL),
              ('vis_ememresumebycategory', '1', 'emcontrolpanel', NULL),
              ('quick_apply_for_user', '0', 'quick_apply', NULL),
              ('quick_apply_for_visitor', '0', 'quick_apply', NULL),
              ('quick_apply_captcha', '1', 'quick_apply', NULL),
              ('show_wpjobportal_page_title', '1', 'wpjobportal', NULL),
              ('job_seeker_profile_section', '1', 'wpjobportal', NULL),
              ('employer_profile_section', '1', 'wpjobportal', NULL),
              ('default_image', '', 'default', NULL),
              ('wpjobportal_addons_auto_update', 1, 'wpjobportal', NULL),
              ('allow_search_resume', '2', 'resume', 'resumesearch'),
              ('job_search_ai_form', '0', 'job', 'aijobsearch'),
              ('job_list_ai_filter', '0', 'job', 'aijobsearch'),
              ('show_suggested_jobs_button', '1', 'job', 'aisuggestedjobs'),
              ('show_suggested_jobs_dashboard', '1', 'job', 'aisuggestedjobs'),
              ('resume_search_ai_form', '0', 'resume', 'airesumesearch'),
              ('resume_list_ai_filter', '0', 'resume', 'airesumesearch'),
              ('show_suggested_resumes_button', '1', 'resume', 'aisuggestedresumes'),
              ('show_suggested_resumes_dashboard', '1', 'resume', 'aisuggestedresumes')
              ;
              ";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_countries` (
              `id` smallint(9) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) DEFAULT NULL,
              `localname` varchar(255) DEFAULT NULL,
              `internationalname` varchar(255) DEFAULT NULL,
              `namecode` varchar(255) DEFAULT NULL,
              `shortCountry` varchar(255) DEFAULT NULL,
              `continentID` tinyint(11) DEFAULT NULL,
              `dialCode` smallint(8) DEFAULT NULL,
              `enabled` tinyint(1) NOT NULL DEFAULT '0',
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              FULLTEXT KEY `name` (`name`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=215;";
            wpjobportal::$_db->query($query);

            $query = "
            INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_countries` (`id`, `name`, `localname`, `internationalname`, `shortCountry`, `namecode`, `continentID`, `dialCode`, `enabled`, `serverid`) VALUES
            (1, 'United States', 'United States', 'United States', 'US', 'us', 1, 1, 1, 0),
            (2, 'Canada', 'Canada', 'Canada', 'Canada', 'ca', 1, 1, 1, 0),
            (3, 'Bahamas', 'The Bahamas', 'Bahamas', 'Bahamas', 'bs', 1, 242, 1, 0),
            (4, 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'bb', 1, 246, 1, 0),
            (5, 'Belize', 'Belize', 'Belize', 'Belize', 'bz', 1, 501, 1, 0),
            (6, 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'bm', 1, 441, 1, 0),
            (7, 'British Virgin Islands', '', 'British Virgin Islands', 'BVI', 'vg', 1, 284, 1, 0),
            (8, 'Cayman Islands', 'Cayman Islands', 'Cayman Islands', 'CaymanIsl', 'ky', 1, 345, 1, 0),
            (9, 'Costa Rica', 'Costa Rica', 'Costa Rica', 'CostaRica', 'cr', 1, 506, 1, 0),
            (10, 'Cuba', 'Cuba', 'Cuba', 'Cuba', 'cu', 1, 53, 1, 0),
            (11, 'Dominica', '', 'Dominica', 'Dominica', 'dm', 1, 767, 1, 0),
            (12, 'Dominican Republic', 'República Dominicana', 'Dominican Republic', 'DominicanRep', 'do', 1, 809, 1, 0),
            (13, 'El Salvador', 'El Salvador', 'El Salvador', 'ElSalvador', 'sv', 1, 503, 1, 0),
            (14, 'Greenland', 'Kalaallit Nunaat', 'Greenland', 'Greenland', 'gl', 1, 299, 1, 0),
            (15, 'Grenada', '', 'Grenada', 'Grenada', 'gd', 1, 473, 1, 0),
            (16, 'Guadeloupe', '', 'Guadeloupe', 'Guadeloupe', 'gp', 1, 590, 1, 0),
            (17, 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'gt', 1, 502, 1, 0),
            (18, 'Haiti', 'Ayiti', 'Haiti', 'Haiti', 'ht', 1, 509, 1, 0),
            (19, 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'hn', 1, 503, 1, 0),
            (20, 'Jamaica', 'Jamaica', 'Jamaica', 'Jamaica', 'jm', 1, 876, 1, 0),
            (21, 'Martinique', '', 'Martinique', 'Martinique', 'mq', 1, 596, 1, 0),
            (22, 'Mexico', 'México', 'Mexico', 'Mexico', 'mx', 1, 52, 1, 0),
            (23, 'Montserrat', '', 'Montserrat', 'Montserrat', 'ms', 1, 664, 1, 0),
            (24, 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'ni', 1, 505, 1, 0),
            (25, 'Panama', 'Panamá', 'Panama', 'Panama', 'pa', 1, 507, 1, 0),
            (26, 'Puerto Rico', '', 'Puerto Rico', 'PuertoRico', 'pr', 1, 787, 1, 0),
            (27, 'Trinidad and Tobago', 'Trinidad and Tobago', 'Trinidad and Tobago', 'Trinidad-Tobago', 'tt', 1, 868, 1, 0),
            (28, 'United States Virgin Islands', '', 'United States Virgin Islands', 'USVI', 'vi', 1, 340, 1, 0),
            (29, 'Argentina', 'Argentina', 'Argentina', 'Argentina', 'ar', 2, 54, 1, 0),
            (30, 'Bolivia', 'Bolivia', 'Bolivia', 'Bolivia', 'bo', 2, 591, 1, 0),
            (31, 'Brazil', 'Brasil', 'Brazil', 'Brazil', 'br', 2, 55, 1, 0),
            (32, 'Chile', 'Chile', 'Chile', 'Chile', 'cl', 2, 56, 1, 0),
            (33, 'Colombia', 'Colombia', 'Colombia', 'Colombia', 'co', 2, 57, 1, 0),
            (34, 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador', 'ec', 2, 593, 1, 0),
            (35, 'Falkland Islands', 'Falkland Islands', 'Falkland Islands', 'FalklandIsl', 'fk', 2, 500, 1, 0),
            (36, 'French Guiana', '', 'French Guiana', 'FrenchGuiana', 'gf', 2, 594, 1, 0),
            (37, 'Guyana', 'Guyana', 'Guyana', 'Guyana', 'gy', 2, 592, 1, 0),
            (38, 'Paraguay', 'Paraguay / Paraguái', 'Paraguay', 'Paraguay', 'py', 2, 595, 1, 0),
            (39, 'Peru', 'Perú', 'Peru', 'Peru', 'pe', 2, 51, 1, 0),
            (40, 'Suriname', 'Suriname', 'Suriname', 'Suriname', 'sr', 2, 597, 1, 0),
            (41, 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'uy', 2, 598, 1, 0),
            (42, 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 've', 2, 58, 1, 0),
            (43, 'Albania', 'Shqipëria', 'Albania', 'Albania', 'al', 3, 355, 1, 0),
            (44, 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'ad', 3, 376, 1, 0),
            (45, 'Armenia', 'Հայաստան', 'Armenia', 'Armenia', 'am', 3, 374, 1, 0),
            (46, 'Austria', 'Österreich', 'Austria', 'Austria', 'at', 3, 43, 1, 0),
            (47, 'Azerbaijan', 'Azərbaycan', 'Azerbaijan', 'Azerbaijan', 'az', 3, 994, 1, 0),
            (48, 'Belarus', 'Беларусь', 'Belarus', 'Belarus', 'by', 3, 375, 1, 0),
            (49, 'Belgium', 'België / Belgique / Belgien', 'Belgium', 'Belgium', 'be', 3, 32, 1, 0),
            (50, 'Bosnia and Herzegovina', 'Bosna i Hercegovina / Босна и Херцеговина', 'Bosnia and Herzegovina', 'Bosnia-Herzegovina', 'ba', 3, 387, 1, 0),
            (51, 'Bulgaria', 'България', 'Bulgaria', 'Bulgaria', 'bg', 3, 359, 1, 0),
            (52, 'Croatia', 'Hrvatska', 'Croatia', 'Croatia', 'hr', 3, 385, 1, 0),
            (53, 'Cyprus', 'Κύπρος - Kıbrıs', 'Cyprus', 'Cyprus', 'cy', 3, 357, 1, 0),
            (54, 'Czech Republic', 'Česko', 'Czech Republic', 'CzechRep', 'cz', 3, 420, 1, 0),
            (55, 'Denmark', 'Danmark', 'Denmark', 'Denmark', 'dk', 3, 45, 1, 0),
            (56, 'Estonia', 'Eesti', 'Estonia', 'Estonia', 'ee', 3, 372, 1, 0),
            (57, 'Finland', 'Suomi', 'Finland', 'Finland', 'fi', 3, 358, 1, 0),
            (58, 'France', 'France', 'France', 'France', 'fr', 3, 33, 1, 0),
            (59, 'Georgia', 'საქართველო', 'Georgia', 'Georgia', 'ge', 3, 995, 1, 0),
            (60, 'Germany', 'Deutschland', 'Germany', 'Germany', 'de', 3, 49, 1, 0),
            (61, 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'gi', 3, 350, 1, 0),
            (62, 'Greece', 'Ελλάς', 'Greece', 'Greece', 'gr', 3, 30, 1, 0),
            (63, 'Guernsey', '', 'Guernsey', 'Guernsey', 'gg', 3, 44, 1, 0),
            (64, 'Hungary', 'Magyarország', 'Hungary', 'Hungary', 'hu', 3, 36, 1, 0),
            (65, 'Iceland', 'Ísland', 'Iceland', 'Iceland', 'is', 3, 354, 1, 0),
            (66, 'Ireland', 'Ireland', 'Ireland', 'Ireland', 'ie', 3, 353, 1, 0),
            (67, 'Isle of Man', 'Isle of Man', 'Isle of Man', 'IsleofMan', 'im', 3, 44, 1, 0),
            (68, 'Italy', 'Italia', 'Italy', 'Italy', 'it', 3, 39, 1, 0),
            (69, 'Jersey', '', 'Jersey', 'Jersey', 'je', 3, 44, 1, 0),
            (70, 'Kosovo', '', 'Kosovo', 'Kosovo', '', 3, 381, 1, 0),
            (71, 'Latvia', 'Latvija', 'Latvia', 'Latvia', 'lv', 3, 371, 1, 0),
            (72, 'Liechtenstein', '', 'Liechtenstein', 'Liechtenstein', 'li', 3, 423, 1, 0),
            (73, 'Lithuania', 'Lietuva', 'Lithuania', 'Lithuania', 'lt', 3, 370, 1, 0),
            (74, 'Luxembourg', 'Lëtzebuerg', 'Luxembourg', 'Luxembourg', 'lu', 3, 352, 1, 0),
            (75, 'Macedonia', 'Северна Македонија', 'Macedonia', 'Macedonia', 'mk', 3, 389, 1, 0),
            (76, 'Malta', 'Malta', 'Malta', 'Malta', 'mt', 3, 356, 1, 0),
            (77, 'Moldova', 'Moldova', 'Moldova', 'Moldova', 'md', 3, 373, 1, 0),
            (78, 'Monaco', 'Monaco', 'Monaco', 'Monaco', 'mc', 3, 377, 1, 0),
            (79, 'Montenegro', 'Crna Gora / Црна Гора', 'Montenegro', 'Montenegro', 'me', 3, 381, 1, 0),
            (80, 'Netherlands', 'Nederland', 'Netherlands', 'Netherlands', 'nl', 3, 31, 1, 0),
            (81, 'Norway', 'Norge', 'Norway', 'Norway', 'no', 3, 47, 1, 0),
            (82, 'Poland', 'Polska', 'Poland', 'Poland', 'pl', 3, 48, 1, 0),
            (83, 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'pt', 3, 351, 1, 0),
            (84, 'Romania', 'România', 'Romania', 'Romania', 'ro', 3, 40, 1, 0),
            (85, 'Russia', 'Россия', 'Russia', 'Russia', 'ru', 3, 7, 1, 0),
            (86, 'San Marino', 'San Marino', 'San Marino', 'SanMarino', 'sm', 3, 378, 1, 0),
            (87, 'Serbia', 'Србија', 'Serbia', 'Serbia', 'rs', 3, 381, 1, 0),
            (88, 'Slovakia', 'Slovensko', 'Slovakia', 'Slovakia', 'sk', 3, 421, 1, 0),
            (89, 'Slovenia', 'Slovenija', 'Slovenia', 'Slovenia', 'si', 3, 386, 1, 0),
            (90, 'Spain', 'España', 'Spain', 'Spain', 'es', 3, 34, 1, 0),
            (91, 'Sweden', 'Sverige', 'Sweden', 'Sweden', 'se', 3, 46, 1, 0),
            (92, 'Switzerland', 'Schweiz', 'Switzerland', 'Switzerland', 'ch', 3, 41, 1, 0),
            (93, 'Turkey', 'Türkiye', 'Turkey', 'Turkey', 'tr', 3, 90, 1, 0),
            (94, 'Ukraine', 'Україна', 'Ukraine', 'Ukraine', 'ua', 3, 380, 1, 0),
            (95, 'United Kingdom', 'United Kingdom', 'United Kingdom', 'UK', 'gb', 3, 44, 1, 0),
            (96, 'Vatican City', '', 'Vatican City', 'Vatican', 'va', 3, 39, 1, 0),
            (97, 'Afghanistan', 'افغانستان', 'Afghanistan', 'Afghanistan', 'af', 4, 93, 1, 0),
            (98, 'Bahrain', 'البحرين', 'Bahrain', 'Bahrain', 'bh', 4, 973, 1, 0),
            (99, 'Bangladesh', 'বাংলাদেশ', 'Bangladesh', 'Bangladesh', 'bd', 4, 880, 1, 0),
            (100, 'Bhutan', 'འབྲུགཡུལ་', 'Bhutan', 'Bhutan', 'bt', 4, 975, 1, 0),
            (101, 'Brunei', 'Brunei', 'Brunei', 'Brunei', 'bn', 4, 673, 1, 0),
            (102, 'Cambodia', 'ព្រះរាជាណាចក្រ​កម្ពុជា', 'Cambodia', 'Cambodia', 'kh', 4, 855, 1, 0),
            (103, 'China', '中国', 'China', 'China', 'cn', 4, 86, 1, 0),
            (104, 'East Timor', 'Timór Lorosa\'e', 'East Timor', 'EastTimor', 'tl', 4, 670, 1, 0),
            (105, 'Hong Kong', '', 'Hong Kong', 'HongKong', 'hk', 4, 852, 1, 0),
            (106, 'India', 'India', 'India', 'India', 'in', 4, 91, 1, 0),
            (107, 'Indonesia', 'Indonesia', 'Indonesia', 'Indonesia', 'id', 4, 62, 1, 0),
            (108, 'Iran', 'ایران', 'Iran', 'Iran', 'ir', 4, 98, 1, 0),
            (109, 'Iraq', 'العراق', 'Iraq', 'Iraq', 'iq', 4, 964, 1, 0),
            (110, 'Israel', 'ישראל', 'Israel', 'Israel', 'il', 4, 972, 1, 0),
            (111, 'Japan', '日本', 'Japan', 'Japan', 'jp', 4, 81, 1, 0),
            (112, 'Jordan', 'الأردن', 'Jordan', 'Jordan', 'jo', 4, 962, 1, 0),
            (113, 'Kazakhstan', 'Қазақстан', 'Kazakhstan', 'Kazakhstan', 'kz', 4, 7, 1, 0),
            (114, 'Kuwait', 'الكويت', 'Kuwait', 'Kuwait', 'kw', 4, 965, 1, 0),
            (115, 'Kyrgyzstan', 'Кыргызстан', 'Kyrgyzstan', 'Kyrgyzstan', 'kg', 4, 996, 1, 0),
            (116, 'Laos', 'ປະເທດລາວ', 'Laos', 'Laos', 'la', 4, 856, 1, 0),
            (117, 'Lebanon', 'لبنان', 'Lebanon', 'Lebanon', 'lb', 4, 961, 1, 0),
            (118, 'Macau', '', 'Macau', 'Macau', 'mo', 4, 853, 1, 0),
            (119, 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia', 'my', 4, 60, 1, 0),
            (120, 'Maldives', 'ދިވެހިރާއްޖެ', 'Maldives', 'Maldives', 'mv', 4, 960, 1, 0),
            (121, 'Mongolia', 'Монгол улс ᠮᠤᠩᠭᠤᠯ ᠤᠯᠤᠰ', 'Mongolia', 'Mongolia', 'mn', 4, 976, 1, 0),
            (122, 'Myanmar (Burma)', 'မြန်မာ', 'Myanmar (Burma)', 'Myanmar(Burma)', 'mm', 4, 95, 1, 0),
            (123, 'Nepal', 'नेपाल', 'Nepal', 'Nepal', 'np', 4, 977, 1, 0),
            (124, 'North Korea', '조선민주주의인민공화국', 'North Korea', 'NorthKorea', 'kp', 4, 850, 1, 0),
            (125, 'Oman', 'عمان', 'Oman', 'Oman', 'om', 4, 968, 1, 0),
            (126, 'Pakistan', 'پاکستان', 'Pakistan', 'Pakistan', 'pk', 4, 92, 1, 0),
            (127, 'Philippines', 'Philippines', 'Philippines', 'Philippines', 'ph', 4, 63, 1, 0),
            (128, 'Qatar', 'قطر', 'Qatar', 'Qatar', 'qa', 4, 974, 1, 0),
            (129, 'Saudi Arabia', 'السعودية', 'Saudi Arabia', 'SaudiArabia', 'sa', 4, 966, 1, 0),
            (130, 'Singapore', 'Singapore', 'Singapore', 'Singapore', 'sg', 4, 65, 1, 0),
            (131, 'South Korea', '대한민국', 'South Korea', 'SouthKorea', 'kr', 4, 82, 1, 0),
            (132, 'Sri Lanka', 'ශ්‍රී ලංකාව இலங்கை', 'Sri Lanka', 'SriLanka', 'lk', 4, 94, 1, 0),
            (133, 'Syria', 'سوريا', 'Syria', 'Syria', 'sy', 4, 963, 1, 0),
            (134, 'Taiwan', '臺灣', 'Taiwan', 'Taiwan', 'tw', 4, 886, 1, 0),
            (135, 'Tajikistan', 'Тоҷикистон', 'Tajikistan', 'Tajikistan', 'tj', 4, 992, 1, 0),
            (136, 'Thailand', 'ประเทศไทย', 'Thailand', 'Thailand', 'th', 4, 66, 1, 0),
            (137, 'Turkmenistan', 'Türkmenistan', 'Turkmenistan', 'Turkmenistan', 'tm', 4, 993, 1, 0),
            (138, 'United Arab Emirates', 'الإمارات العربية المتحدة', 'United Arab Emirates', 'UAE', 'ae', 4, 971, 1, 0),
            (139, 'Uzbekistan', 'Oʻzbekiston', 'Uzbekistan', 'Uzbekistan', 'uz', 4, 998, 1, 0),
            (140, 'Vietnam', 'Việt Nam', 'Vietnam', 'Vietnam', 'vn', 4, 84, 1, 0),
            (141, 'Yemen', 'اليمن', 'Yemen', 'Yemen', 'ye', 4, 967, 1, 0),
            (142, 'Algeria', 'الجزائر', 'Algeria', 'Algeria', 'dz', 5, 213, 1, 0),
            (143, 'Angola', 'Angola', 'Angola', 'Angola', 'ao', 5, 244, 1, 0),
            (144, 'Benin', 'Bénin', 'Benin', 'Benin', 'bj', 5, 229, 1, 0),
            (145, 'Botswana', 'Botswana', 'Botswana', 'Botswana', 'bw', 5, 267, 1, 0),
            (146, 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'BurkinaFaso', 'bf', 5, 226, 1, 0),
            (147, 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'bi', 5, 257, 1, 0),
            (148, 'Cameroon', 'Cameroun', 'Cameroon', 'Cameroon', 'cm', 5, 237, 1, 0),
            (149, 'Cape Verde', 'Cabo Verde', 'Cape Verde', 'CapeVerde', 'cv', 5, 238, 1, 0),
            (150, 'Central African Republic', 'Ködörösêse tî Bêafrîka', 'Central African Republic', 'CentralAfricanRep', 'cf', 5, 236, 1, 0),
            (151, 'Chad', 'تشاد', 'Chad', 'Chad', 'td', 5, 235, 1, 0),
            (152, 'Congo', 'Congo', 'Congo', 'Congo', 'cg', 5, 242, 1, 0),
            (153, 'Democoratic Republic of Congo', 'République démocratique du Congo', 'Democoratic Republic of Congo', 'D.R Congo', 'cd', 5, 242, 1, 0),
            (154, 'Djibouti', '', 'Djibouti', 'Djibouti', 'dj', 5, 253, 1, 0),
            (155, 'Egypt', 'مصر', 'Egypt', 'Egypt', 'eg', 5, 20, 1, 0),
            (156, 'Equatorial Guinea', 'Guinea Ecuatorial', 'Equatorial Guinea', 'EquatorialGuinea', 'gq', 5, 240, 1, 0),
            (157, 'Eritrea', 'ኤርትራ Eritrea إرتريا', 'Eritrea', 'Eritrea', 'er', 5, 291, 1, 0),
            (158, 'Ethiopia', 'ኢትዮጵያ', 'Ethiopia', 'Ethiopia', 'et', 5, 251, 1, 0),
            (159, 'Gabon', 'Gabon', 'Gabon', 'Gabon', 'ga', 5, 241, 1, 0),
            (160, 'Gambia', 'Gambia', 'Gambia', 'Gambia', 'gm', 5, 220, 1, 0),
            (161, 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'gh', 5, 233, 1, 0),
            (162, 'Guinea', 'Guinée', 'Guinea', 'Guinea', 'gn', 5, 224, 1, 0),
            (163, 'Guinea-Bissau', 'Guiné-Bissau', 'Guinea-Bissau', 'Guinea-Bissau', 'gw', 5, 245, 1, 0),
            (164, 'Cote DIvory', 'Côte d’Ivoire', 'Cote DIvory', 'IvoryCoast', 'ci', 5, 225, 1, 0),
            (165, 'Kenya', 'Kenya', 'Kenya', 'Kenya', 'ke', 5, 254, 1, 0),
            (166, 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho', 'ls', 5, 266, 1, 0),
            (167, 'Liberia', 'Liberia', 'Liberia', 'Liberia', 'lr', 5, 231, 1, 0),
            (168, 'Libya', 'ليبيا', 'Libya', 'Libya', 'ly', 5, 218, 1, 0),
            (169, 'Madagascar', 'Madagasikara', 'Madagascar', 'Madagascar', 'mg', 5, 261, 1, 0),
            (170, 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'mw', 5, 265, 1, 0),
            (171, 'Mali', 'Mali', 'Mali', 'Mali', 'ml', 5, 223, 1, 0),
            (172, 'Mauritania', 'موريتانيا', 'Mauritania', 'Mauritania', 'mr', 5, 222, 1, 0),
            (173, 'Mauritius', 'Maurice', 'Mauritius', 'Mauritius', 'mu', 5, 230, 1, 0),
            (174, 'Morocco', 'المغرب', 'Morocco', 'Morocco', 'ma', 5, 212, 1, 0),
            (175, 'Mozambique', 'Moçambique', 'Mozambique', 'Mozambique', 'mz', 5, 258, 1, 0),
            (176, 'Namibia', 'Namibia', 'Namibia', 'Namibia', 'na', 5, 264, 1, 0),
            (177, 'Niger', 'Niger', 'Niger', 'Niger', 'ne', 5, 227, 1, 0),
            (178, 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria', 'ng', 5, 234, 1, 0),
            (179, 'Reunion', '', 'Reunion', 'Reunion', 're', 5, 262, 1, 0),
            (180, 'Rwanda', 'Rwanda', 'Rwanda', 'Rwanda', 'rw', 5, 250, 1, 0),
            (181, 'Sao Tome and Principe', 'São Tomé e Príncipe', 'Sao Tome and Principe', 'SaoTome-Principe', 'st', 5, 239, 1, 0),
            (182, 'Senegal', 'Sénégal', 'Senegal', 'Senegal', 'sn', 5, 221, 1, 0),
            (183, 'Seychelles', 'Sesel', 'Seychelles', 'Seychelles', 'sc', 5, 248, 1, 0),
            (184, 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'SierraLeone', 'sl', 5, 232, 1, 0),
            (185, 'Somalia', 'الصومال', 'Somalia', 'Somalia', 'so', 5, 252, 1, 0),
            (186, 'South Africa', 'South Africa', 'South Africa', 'SouthAfrica', 'za', 5, 27, 1, 0),
            (187, 'Sudan', 'السودان', 'Sudan', 'Sudan', 'sd', 5, 249, 1, 0),
            (188, 'Swaziland', 'eSwatini', 'Swaziland', 'Swaziland', 'sz', 5, 268, 1, 0),
            (189, 'Tanzania', 'Tanzania', 'Tanzania', 'Tanzania', 'tz', 5, 255, 1, 0),
            (190, 'Togo', 'Togo', 'Togo', 'Togo', 'tg', 5, 228, 1, 0),
            (191, 'Tunisia', 'تونس', 'Tunisia', 'Tunisia', 'tn', 5, 216, 1, 0),
            (192, 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'ug', 5, 256, 1, 0),
            (193, 'Western Sahara', '', 'Western Sahara', 'WesternSahara', 'eh', 5, 212, 1, 0),
            (194, 'Zambia', 'Zambia', 'Zambia', 'Zambia', 'zm', 5, 260, 1, 0),
            (195, 'Zimbabwe', 'Zimbabwe', 'Zimbabwe', 'Zimbabwe', 'zw', 5, 263, 1, 0),
            (196, 'Australia', 'Australia', 'Australia', 'Australia', 'au', 6, 61, 1, 0),
            (197, 'New Zealand', 'New Zealand', 'New Zealand', 'NewZealand', 'nz', 6, 64, 1, 0),
            (198, 'Fiji', 'Viti', 'Fiji', 'Fiji', 'fj', 6, 679, 1, 0),
            (199, 'French Polynesia', '', 'French Polynesia', 'FrenchPolynesia', 'pf', 6, 689, 1, 0),
            (200, 'Guam', '', 'Guam', 'Guam', 'gu', 6, 671, 1, 0),
            (201, 'Kiribati', '', 'Kiribati', 'Kiribati', 'ki', 6, 686, 1, 0),
            (202, 'Marshall Islands', 'Ṃajeḷ', 'Marshall Islands', 'MarshallIsl', 'mh', 6, 692, 1, 0),
            (203, 'Micronesia', '', 'Micronesia', 'Micronesia', 'fm', 6, 691, 1, 0),
            (204, 'Nauru', '', 'Nauru', 'Nauru', 'nr', 6, 674, 1, 0),
            (205, 'New Caledonia', '', 'New Caledonia', 'NewCaledonia', 'nc', 6, 687, 1, 0),
            (206, 'Papua New Guinea', 'Papua Niugini', 'Papua New Guinea', 'PapuaNewGuinea', 'pg', 6, 675, 1, 0),
            (207, 'Samoa', 'Sāmoa', 'Samoa', 'Samoa', 'ws', 6, 684, 1, 0),
            (208, 'Solomon Islands', 'Solomon Islands', 'Solomon Islands', 'SolomonIsl', 'sb', 6, 677, 1, 0),
            (209, 'Tonga', 'Tonga', 'Tonga', 'Tonga', 'to', 6, 676, 1, 0),
            (210, 'Tuvalu', '', 'Tuvalu', 'Tuvalu', 'tv', 6, 688, 1, 0),
            (211, 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'vu', 6, 678, 1, 0),
            (212, 'Wallis and Futuna', '', 'Wallis and Futuna', 'Wallis-Futuna', 'wf', 6, 681, 1, 0),
            (213, 'Comoros', 'Comores Komori جزر القمر', 'Comoros', 'Comoros', 'km', 0, 0, 1, 0),
            (214, 'Cote DIvorie', '', 'Cote DIvorie', 'Cote-DIvorie', '', NULL, NULL, 1, 0);
            ";
            wpjobportal::$_db->query($query);


          $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_currencies` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) DEFAULT NULL,
              `symbol` varchar(255) DEFAULT NULL,
              `code` varchar(255) NOT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `default` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              `smallestunit` int(11) DEFAULT '100',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;";
            wpjobportal::$_db->query($query);

          $query = "  INSERT INTO  `" . wpjobportal::$_db->prefix . "wj_portal_currencies` (`id`, `title`, `symbol`, `code`, `status`, `default`, `ordering`, `serverid`, `smallestunit`) VALUES
          (1, 'US Doller', '$', 'USD', 1, 0, 1, 0, 100),
          (2, 'Pakistani Rupee', 'Rs.', 'PKR', 1, 0, 2, 0, 100),
          (3, 'Pound', '£', 'GBP', 1, 1, 3, 0, 100),
          (4, 'Euro', ' €', 'EUR', 1, 0, 4, 0, 100); ";
          wpjobportal::$_db->query($query);



            /////
         $query = " CREATE TABLE IF NOT EXISTS  `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uid` int(11) DEFAULT NULL,
            `templatefor` varchar(255) DEFAULT NULL,
            `title` varchar(255) DEFAULT NULL,
            `subject` varchar(255) DEFAULT NULL,
            `body` text,
            `status` tinyint(1) DEFAULT NULL,
            `created` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8; ";
          wpjobportal::$_db->query($query);

        $query = " INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES
          (1, 0, 'company-status', NULL, 'WP Job Portal : Company {COMPANY_NAME} has been {COMPANY_STATUS}', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Company Status</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your company (<strong style=\"color: #4b4b4d;\">{COMPANY_NAME}</strong>) has been (<strong style=\"color: #4b4b4d;\">{COMPANY_STATUS}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{COMPANY_LINK}\">View Company</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 1, '2009-08-17 18:08:41'),
          (2, 0, 'company-delete', NULL, 'WP Job Portal : Your Company {COMPANY_NAME} has been deleted', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Delete Company</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"color: #727376; line-height: 2;\">Your company (<strong style=\"color: #4b4b4d;\">{COMPANY_NAME}</strong>) has been deleted.</div>\n</div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved &#8211; Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-17 17:54:48'),
          (3, 0, 'job-status', '', 'WP Job Portal : Your job {JOB_TITLE} has been {JOB_STATUS}.', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Job Status</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your job (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>) has been (<strong style=\"color: #4b4b4d;\">{JOB_STATUS}</strong>).</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{JOB_LINK}\">View Job</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 0, '2009-08-17 22:10:27'),
          (4, 0, 'job-delete', NULL, 'WP Job Portal : Your job {JOB_TITLE} has been deleted.', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Job Delete</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">(<strong style=\"color: #4b4b4d;\">{COMPANY_NAME}</strong>) job (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>) has been deleted.</div>\n</div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-17 22:12:43'),
          (5, 0, 'resume-status', NULL, 'WP Job Portal : Your resume {RESUME_TITLE} has been {RESUME_STATUS}.', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Resume Status</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your resume (<strong style=\"color: #4b4b4d;\">{RESUME_TITLE}</strong>) has been (<strong style=\"color: #4b4b4d;\">{RESUME_STATUS}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{RESUME_LINK}\">View Resume</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-17 22:15:12'),
          (6, 0, 'employer-purchase-credit-pack', NULL, 'WP Job Portal : You have purchased new package {PACKAGE_NAME}', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Employer Purchase Credits Pack</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You have purchased new package (<strong style=\"color: #4b4b4d;\">{PACKAGE_NAME}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">(<strong style=\"color: #4b4b4d;\">{PACKAGE_PRICE}</strong>) credits consumed for this package.</div>\n<div style=\"color: #727376; line-height: 2;\">Package purchased date (<strong style=\"color: #4b4b4d;\">{PACKAGE_PURCHASE_DATE}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{PACKAGE_LINK}\">View Package</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-17 22:14:52'),
          (7, 0, 'jobapply-jobseeker', NULL, 'WP Job Portal : Applied for {JOB_TITLE} job', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Job Apply Jobseeker</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You have to applied for (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>) job in (<strong style=\"color: #4b4b4d;\">{COMPANY_NAME}</strong>) company by (<strong style=\"color: #4b4b4d;\">{RESUME_TITLE}</strong>) resume.</div>\n<div style=\"color: #727376; line-height: 2;\">your resume has been (<strong style=\"color: #4b4b4d;\">{RESUME_APPLIED_STATUS}</strong>).</div>\n</div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-18 16:46:16'),
          (8, 0, 'company-new', '', 'WP JOB PORTAL: New company {COMPANY_NAME} has been received', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Company</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">You have Created New Company {COMPANY_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">We recieve new (<strong style=\"color: #4b4b4d;\">{COMPANY_NAME}</strong>) company.</div>\n<div style=\"color: #727376; line-height: 2;\">Company status is (<strong style=\"color: #4b4b4d;\">{COMPANY_STATUS}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{COMPANY_LINK}\">View Company</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 0, '2009-08-18 16:46:16'),
          (9, 0, 'job-new', '', 'WP Job Portal : New Job {JOB_TITLE} has been received of {COMPANY_NAME} company', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Job</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">We receive new (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>) job of your (<strong style=\"color: #4b4b4d;\">{COMPANY_NAME}</strong>) company.</div>\n<div style=\"color: #727376; line-height: 2;\">Your job status is (<strong style=\"color: #4b4b4d;\">{JOB_STATUS}</strong>).</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{JOB_LINK}\">View Job</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 0, '2009-08-18 16:46:16'),
          (10, 0, 'resume-new', NULL, 'WP Job Portal : New resume {RESUME_TITLE} has beed received', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Resume</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">We receive new resume (<strong style=\"color: #4b4b4d;\">{RESUME_TITLE}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">Your resume has been (<strong style=\"color: #4b4b4d;\">{RESUME_STATUS}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{RESUME_LINK}\">View Resume</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-18 16:46:16');";
          wpjobportal::$_db->query($query);


          $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES(11, 0, 'jobseeker-package-expire', NULL, 'WP Job Portal : {PACKAGE_NAME} has been expired', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Jobseeker Package Expiry</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your (<strong style=\"color: #4b4b4d;\">{PACKAGE_NAME}</strong>) has been expired.</div>\n<div style=\"color: #727376; line-height: 2;\">You had purchased this package at (<strong style=\"color: #4b4b4d;\">{PACKAGE_PURCHASE_DATE}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{PACKAGE_LINK}\">View Package</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-18 16:46:16'),
          (12, 0, 'jobseeker-purchase-credit-pack', NULL, 'WP Job Portal : You purchased new package {PACKAGE_NAME}', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Jobseeker Purchase Credits Pack</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You have purchased new package (<strong style=\"color: #4b4b4d;\">{PACKAGE_NAME}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">(<strong style=\"color: #4b4b4d;\">{PACKAGE_PRICE}</strong>) credits consumed for this package.</div>\n<div style=\"color: #727376; line-height: 2;\">Package purchased date (<strong style=\"color: #4b4b4d;\">{PACKAGE_PURCHASE_DATE}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{PACKAGE_LINK}\">View Package</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-18 16:46:16'),
          (13, NULL, 'employer-package-expire', NULL, 'WP Job Portal : {PACKAGE_NAME} has been expired', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Employer Package Expiry</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your (<strong style=\"color: #4b4b4d;\">{PACKAGE_NAME}</strong>) has been expired.</div>\n<div style=\"color: #727376; line-height: 2;\">You had purchased this package at (<strong style=\"color: #4b4b4d;\">{PACKAGE_PURCHASE_DATE}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{PACKAGE_LINK}\">View Package</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00'),
          (14, NULL, 'jobapply-employer', '', 'WP Job Portal : Job seeker have applied for {JOB_TITLE} job ', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Job Apply Employer</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Mr/Mrs (<strong style=\"color: #4b4b4d;\">{JOBSEEKER_NAME}</strong>) applied for your job (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">Current Applied Resume status is (<strong style=\"color: #4b4b4d;\">{RESUME_APPLIED_STATUS}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">(<strong style=\"color: #4b4b4d;\">{COVER_LETTER_DESCRIPTION}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">(<strong style=\"color: #4b4b4d;\">{RESUME_DATA}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{RESUME_LINK}\">View Resume</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00');";
          wpjobportal::$_db->query($query);
          $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES(15, 0, 'job-new-vis', '', 'WP Job Portal : New Visitor Job {JOB_TITLE} has beed received of {COMPANY_NAME} company', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Visitor Job</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {EMPLOYER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">We receive new (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>) job of your (<strong style=\"color: #4b4b4d;\">{COMPANY_NAME}</strong>) company.</div>\n<div style=\"color: #727376; line-height: 2;\">Your new added job status is (<strong style=\"color: #4b4b4d;\">{JOB_STATUS}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{JOB_LINK}\">View Job</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00');";
           wpjobportal::$_db->query($query);
          $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES(16, NULL, 'employer-new', '', 'WP Job Portal  : New user registered as a employer ', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Employer</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {USER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You are registered as (<strong style=\"color: #4b4b4d;\">{USER_ROLE}</strong>) in this application.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{CONTROL_PANEL_LINK}\">View Control Panel</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00');";
          wpjobportal::$_db->query($query);
          $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES
          (17, NULL, 'jobseeker-new', NULL, 'WP Job Portal : New user registered as a jobseeker', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Jobseeker</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {USER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You are registered as (<strong style=\"color: #4b4b4d;\">{USER_ROLE}</strong>) in this application.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{CONTROL_PANEL_LINK}\">View Control Panel</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00');";
          wpjobportal::$_db->query($query);
         $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (18, NULL, 'resume-new-vis', NULL, 'WP Job Portal :  New resume {RESUME_TITLE} has beed received', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Visitor Resume</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">We receive new resume (<strong style=\"color: #4b4b4d;\">{RESUME_TITLE}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">Your resume has been (<strong style=\"color: #4b4b4d;\">{RESUME_STATUS}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{RESUME_LINK}\">View Resume</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00');";
         wpjobportal::$_db->query($query);
          $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES
          (19, 0, 'jobapply-jobapply', NULL, 'WP Job Portal :  {JOBSEEKER_NAME} apply for {JOB_TITLE}', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Job Apply Admin</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear Admin,</div>\n<div style=\"color: #727376; line-height: 2;\">Mr/Mrs (<strong style=\"color: #4b4b4d;\">{JOBSEEKER_NAME}</strong>) applied for job (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>) from Employer (<strong style=\"color: #4b4b4d;\">{EMPLOYER_NAME}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">(<strong style=\"color: #4b4b4d;\">{RESUME_DATA}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">(<strong style=\"color: #4b4b4d;\">{COVER_LETTER_DESCRIPTION}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{RESUME_LINK}\">View Resume</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-18 16:46:16'),
          (20, 0, 'resume-delete', '', 'WP Job Portal : Your Resume {RESUME_TITLE} has been deleted', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Delete Resume</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your resume (<strong style=\"color: #4b4b4d;\">{RESUME_TITLE}</strong>) has been deleted.</div>\n</div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2009-08-17 17:54:48');";
           wpjobportal::$_db->query($query);

           $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`)VALUES(21, 0, 'applied-resume_status', NULL, 'WP Job Portal : Your applied resume status update', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Applied Resume Status Change</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You are applied for job (<strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong>).</div>\n<div style=\"color: #727376; line-height: 2;\">Your resume has been mark as (<strong style=\"color: #4b4b4d;\">{RESUME_STATUS}</strong>).</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{RESUME_LINK}\">View Resume</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '2011-03-31 16:46:16'),
          (22, NULL, 'job-alert', NULL, 'WP Job Portal : New Job', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Job Alert</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {JOBSEEKER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">We receive new job.</div>\n<div style=\"color: #727376; line-height: 2;\">{JOBS_INFO}.</div>\n<div style=\"color: #727376; line-height: 2;\">Login and view detail at.</div>\n</div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 1, '2016-05-07 00:00:00'),
          (23, NULL, 'job-to-friend', NULL, 'WP Job Portal : Your friend find a job', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Tell To Friend</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear,</div>\n<div style=\"color: #727376; line-height: 2;\">Your Friend (<strong style=\"color: #4b4b4d;\">{SENDER_NAME}</strong>) will send you this mail through our site (<strong style=\"color: #4b4b4d;\">{SITE_NAME}</strong>) to inform you for a job.</div>\n<div style=\"color: #727376; line-height: 2; padding-bottom: 10px; padding-top: 10px;\"><strong style=\"color: #4b4b4d;\">Summary</strong></div>\n<div style=\"border: 1px solid #ebecec;\">\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Title: <strong style=\"color: #4b4b4d;\">{JOB_TITLE}</strong></div>\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Category: <strong style=\"color: #4b4b4d;\">{JOB_CATEGORY}</strong></div>\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\"><strong style=\"color: #4b4b4d;\">{SENDER_MESSAGE}</strong></div>\n<div style=\"color: #727376; padding: 15px;\">Thank You</div>\n</div>\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{CLICK_HERE_TO_VISIT}\">View Job Detail</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 1, '2016-05-07 00:00:00'),
          (24, 0, 'package-purchase-admin', NULL, 'WP JOB PORTAL: Package Purchased', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Purchase Package Admin</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear Admin,</div>\n<div style=\"color: #727376; line-height: 2;\"><strong style=\"color: #4b4b4d;\">{USER_NAME}</strong> have purchased the package <strong style=\"color: #4b4b4d;\">{PACKAGE_TITLE}</strong></div>\n</div>\n<div style=\"border: 1px solid #ebecec;\">\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Title: <strong style=\"color: #4b4b4d;\">{PACKAGE_TITLE}</strong></div>\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Publish Status: <strong style=\"color: #4b4b4d;\">{PUBLISH_STATUS}</strong></div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{PACKAGE_LINK}\">View Package</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 1, '2009-08-17 18:08:41'),
          (25, 0, 'package-status', NULL, 'WP JOB PORTAL: Package Status', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Purchase Status</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {USER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">Your package <strong style=\"color: #4b4b4d;\">{PACKAGE_TITLE}</strong> has been marked as <strong style=\"color: #4b4b4d;\">{PUBLISH_STATUS}</strong></div>\n</div>\n<div style=\"border: 1px solid #ebecec;\">\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Publish Status: <strong style=\"color: #4b4b4d;\">{PUBLISH_STATUS}</strong></div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{PACKAGE_LINK}\">View Package</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 1, '2009-08-17 18:08:41'),
          (26, 0, 'package-purchase', NULL, 'WP JOB PORTAL: Package Purchase', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: Purchase Package</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {USER_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You have successfully purchased package <strong style=\"color: #4b4b4d;\">{PACKAGE_TITLE}</strong></div>\n</div>\n<div style=\"border: 1px solid #ebecec;\">\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Title: <strong style=\"color: #4b4b4d;\">{PACKAGE_TITLE}</strong></div>\n<div style=\"border-bottom: 1px solid #ebecec; color: #727376; padding: 15px;\">Publish Status: <strong style=\"color: #4b4b4d;\">{PUBLISH_STATUS}</strong></div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{PACKAGE_LINK}\">View Package</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', 1, '2009-08-17 18:08:41');";
           wpjobportal::$_db->query($query);

           $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES
			(27, 0, 'new-message', NULL, 'WP Job Portal : New Message', '<div style=\"background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%;\">\n<div style=\"border: 3px dotted #ebecec; width: 600px; display: block; margin: 0 auto; background: #fff;\">\n<div style=\"padding: 15px 20px; background: #3e4095; color: #fff; font-size: 16px; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #4b4b4d;\">Wp Job Portal</div>\n<div style=\"padding: 30px; text-align: center; font-weight: bold; background: #576cf1; color: #fff; text-transform: capitalize; font-size: 22px;\">{SITETITLE}: New Message</div>\n<div style=\"padding: 40px 20px 20px;\">\n<div style=\"padding-bottom: 20px; border-bottom: 1px solid #ebecec;\">\n<div style=\"font-weight: bold; font-size: 18px; margin-bottom: 15px; color: #4b4b4d;\">Dear {RECIPIENT_NAME},</div>\n<div style=\"color: #727376; line-height: 2;\">You have received a message from {SENDER_NAME} (<strong style=\"color: #4b4b4d;\">{SENDER_USER_ROLE}</strong>). You can view and respond to the message by clicking the below button.</div>\n</div>\n<div style=\"padding: 20px 0;\">\n</div>\n<div style=\"padding: 0 0 30px; text-align: center;\"><a style=\"display: inline-block; padding: 15px; background: #576cf1; width: 40%; text-align: center; text-decoration: none; color: #ffff; text-transform: capitalize; border-bottom: 3px solid #4b4b4d;\" href=\"{MESSAGE_LINK}\">View Message</a></div>\n<div style=\"background: #fef2ef; padding: 15px; margin-bottom: 20px; border: 1px solid #eba7a8;\">\n<div style=\"font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #983133; text-transform: uppercase;\">Do not reply TO this E-Mail</div>\n<div style=\"color: #727376; line-height: 2;\">This is an automated e-mail message sent from our support system.<br />\nDo not reply to this e-mail as we cannot receive your reply!</div>\n</div>\n<div style=\"color: #727376; line-height: 2;\">This email was sent from <span style=\"color: #3e4095; display: inline-block; text-decoration: underline;\"> Wp Job Portal System </span> to <span style=\"color: #606062; display: inline-block; text-decoration: underline;\">{EMAIL}</span></div>\n</div>\n<div style=\"background: #4b4b4d; padding: 20px; color: #fff; text-align: center; border-bottom: 5px solid #576cf1;\">© {CURRENT_YEAR} All rights reserved – Wp Job Portal WordPress Plugin</div>\n</div>\n</div>\n', NULL, '0000-00-00 00:00:00');";
           wpjobportal::$_db->query($query);


        $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates_config` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `emailfor` varchar(255) NOT NULL,
              `admin` tinyint(1) NOT NULL,
              `employer` tinyint(1) NOT NULL,
              `jobseeker` tinyint(1) NOT NULL,
              `jobseeker_visitor` tinyint(1) NOT NULL,
              `employer_visitor` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26;";

            wpjobportal::$_db->query($query);


            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates_config` (`id`, `emailfor`, `admin`, `employer`, `jobseeker`, `jobseeker_visitor`, `employer_visitor`) VALUES
                (1, 'add_new_company', 1, 1, 0, 0, 0),
                (2, 'delete_company', 1, 1, 0, 0, 0),
                (3, 'company_status', 0, 1, 0, 0, 0),
                (4, 'job_status', 0, 1, 0, 0, 0),
                (5, 'add_new_job', 1, 0, 0, 0, 0),
                (6, 'add_new_resume', 1, 0, 0, 0, 0),
                (7, 'resume_status', 0, 0, 0, 0, 0),
                (8, 'package_purchase', 0, 1, 0, 0, 0),
                (9, 'package_status', 0, 0, 0, 0, 0),
                (10, 'package-purchase-admin', 0, 1, 0, 0, 0),
                (11, 'jobapply_jobapply', 0, 1, 0, 0, 0),
                (12, 'delete_job', 0, 0, 0, 0, 0),
                (13, 'add_new_employer', 0, 0, 0, 0, 0),
                (14, 'add_new_jobseeker', 0, 0, 0, 0, 0),
                (15, 'add_new_resume_visitor', 0, 0, 0, 0, 0),
                (16, 'add_new_job_visitor', 0, 0, 0, 0, 0),
                (17, 'resume-delete', 0, 0, 0, 0, 0),
                (18, 'applied-resume_status', 0, 0, 1, 0, 0),
                (19, 'new_message', 0, 0, 0, 0, 0);";

            wpjobportal::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_employer_view_resume` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL,
              `resumeid` int(11) NOT NULL,
              `status` tinyint(1) NOT NULL,
              `userpackageid` int(11) NOT NULL,
              `price` int(40) DEFAULT NULL,
              `created` datetime NOT NULL,
              `profileid` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='latin1_swedish_ci';";

          wpjobportal::$_db->query($query);

          $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_jobseeker_view_company` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL,
              `companyid` int(11) NOT NULL,
              `status` tinyint(1) NOT NULL,
              `userpackageid` int(11) NOT NULL,
              `price` int(40) NOT NULL,
              `created` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

            wpjobportal::$_db->query($query);

          $query = " CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `field` varchar(255) NOT NULL,
            `fieldtitle` varchar(255) NOT NULL,
            `ordering` int(11) NOT NULL,
            `section` varchar(255) NOT NULL,
            `is_section_headline` tinyint(4) DEFAULT '0',
            `placeholder` varchar(255) DEFAULT NULL,
            `description` varchar(255) DEFAULT NULL,
            `fieldfor` tinyint(2) NOT NULL,
            `published` tinyint(1) NOT NULL,
            `isvisitorpublished` tinyint(1) NOT NULL,
            `sys` tinyint(1) NOT NULL,
            `cannotunpublish` tinyint(1) NOT NULL,
            `required` tinyint(1) NOT NULL,
            `isuserfield` tinyint(1) NOT NULL,
            `userfieldtype` varchar(255) NOT NULL,
            `userfieldparams` text NOT NULL,
            `search_user` tinyint(1) NOT NULL,
            `search_visitor` tinyint(1) NOT NULL,
            `search_ordering` tinyint(4) DEFAULT NULL,
            `cannotsearch` tinyint(1) NOT NULL,
            `showonlisting` tinyint(1) NOT NULL,
            `cannotshowonlisting` tinyint(1) NOT NULL,
            `depandant_field` varchar(255) NOT NULL,
            `readonly` tinyint(4) NOT NULL,
            `size` int(11) NOT NULL,
            `maxlength` int(11) NOT NULL,
            `cols` int(11) NOT NULL,
            `rows` int(11) NOT NULL,
            `j_script` text NOT NULL,
            `visible_field` varchar(255) DEFAULT NULL,
            `visibleparams` text DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=430 DEFAULT CHARSET=utf8;";

        wpjobportal::$_db->query($query);



        $query = " INSERT INTO  `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` (`id`, `field`, `fieldtitle`, `ordering`, `section`, `is_section_headline`, `placeholder`, `description`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`, `visible_field`, `visibleparams`) VALUES
            (1, 'uid', 'User Id', 1, '', 0, NULL, NULL, 1, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (2, 'name', 'Name', 2, '', 0, '', 'Enter Name Here', 1, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 1, 0, 0, 0, 0, '', NULL, NULL),
            (3, 'url', 'URL', 3, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (4, 'contactemail', 'Contact Email', 5, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 1, 0, 0, 0, 0, '', NULL, NULL),
            (5, 'category', 'Category', 8, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (6, 'logo', 'Logo', 9, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (7, 'description', 'Description', 14, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (8, 'address1', 'Address1', 15, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (9, 'address2', 'Address2', 16, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (10, 'city', 'City', 17, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 0, 1, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (11, 'status', 'Status', 23, '', 0, NULL, NULL, 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (12, 'termsandconditions', 'Terms And Conditions', 24, '', 0, NULL, NULL, 1, 0, 0, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (13, 'jobtitle', 'Title', 1, '', 0, NULL, NULL, 2, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (14, 'company', 'Company', 3, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 1, 0, '', '', 1, 1, NULL, 0, 1, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (15, 'department', 'Department', 5, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (16, 'jobcategory', 'Category', 2, '', 0, NULL, NULL, 2, 1, 1, 0, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (17, 'jobtype', 'Type', 4, '', 0, NULL, NULL, 2, 1, 1, 0, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (18, 'jobstatus', 'Status', 6, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (19, 'jobsalaryrange', 'Salary Range', 7, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (21, 'experience', 'Experience', 10, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (22, 'noofjobs', 'No of Jobs', 13, '', 0, '', '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 1, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (23, 'duration', 'Duration', 9, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (24, 'careerlevel', 'Career Level', 12, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (25, 'map', 'Map', 19, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (26, 'startpublishing', 'Start Publishing', 18, '', 0, NULL, NULL, 2, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (27, 'stoppublishing', 'Stop Publishing', 19, '', 0, 'Enter Data', '', 2, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (28, 'city', 'City', 15, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (29, 'sendemail', 'Send Email', 22, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (30, 'sendmeresume', 'Send me Resume', 23, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (31, 'description', 'Description', 24, '', 0, NULL, NULL, 2, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (32, 'qualifications', 'Qualifications', 25, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (33, 'prefferdskills', 'Prefered Skills', 26, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (34, 'agreement', 'Agreement', 27, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (35, 'filter', 'Filter', 28, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (36, 'emailsetting', 'Email Setting', 29, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (37, 'joblink', 'Redirect on apply', 30, '', 0, NULL, NULL, 2, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (38, 'tags', 'Tags', 17, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (39, 'metadescription', 'Meta Description', 32, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (40, 'metakeywords', 'Meta Keywords', 18, '', 0, NULL, NULL, 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (41, 'termsandconditions', 'Terms And Conditions', 34, '', 0, NULL, NULL, 2, 0, 0, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (42, 'section_personal', 'Personal Information', 0, '1', 1, NULL, NULL, 3, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (43, 'application_title', 'Application Title', 1, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (44, 'first_name', 'First Name', 2, '1', 0, NULL, NULL, 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (45, 'last_name', 'Last Name', 3, '1', 0, NULL, NULL, 3, 1, 1, 0, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (46, 'email_address', 'Email Address', 4, '1', 0, NULL, NULL, 3, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (47, 'cell', 'Cell', 5, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (48, 'nationality', 'Nationality', 6, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (49, 'gender', 'Gender', 7, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (50, 'photo', 'Photo', 8, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (51, 'resumefiles', 'Files', 9, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (52, 'job_category', 'Category', 10, '1', 0, NULL, NULL, 3, 1, 1, 0, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (53, 'jobtype', 'Type', 11, '1', 0, NULL, NULL, 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (55, 'tags', 'Tags', 23, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (56, 'section_address', 'Add Address', 40, '2', 1, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (57, 'address', 'Address', 25, '2', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (58, 'address_city', 'City', 26, '2', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (59, 'address_location', 'Location', 28, '2', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (60, 'section_education', 'Education', 50, '3', 1, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (61, 'institute', 'Institute', 29, '3', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (62, 'institute_certificate_name', 'Certificate Name', 30, '3', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (63, 'institute_study_area', 'Study Area', 31, '3', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (66, 'section_employer', 'Employer', 60, '4', 1, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (67, 'employer', 'Employer Name', 34, '4', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (68, 'employer_position', 'Position', 37, '4', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (70, 'employer_from_date', 'From Date', 41, '4', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (71, 'employer_to_date', 'To Date', 43, '4', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (72, 'employer_phone', 'Phone', 45, '4', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (73, 'employer_address', 'Address', 46, '4', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (74, 'employer_city', 'City', 47, '4', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (75, 'section_skills', 'Skills', 80, '5', 1, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (76, 'skills', 'Skills', 49, '5', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (79, 'section_language', 'Add Language', 100, '8', 1, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (80, 'language', 'Language Name', 59, '8', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (81, 'institute_date_from', 'Date From', 36, '3', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (82, 'institute_date_to', 'Date to', 35, '3', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (83, 'termsandconditions', 'Terms And Conditions', 24, '1', 0, NULL, NULL, 3, 0, 0, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (84, 'tagline', 'Tag Line', 13, '', 0, NULL, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (85, 'salaryfixed', 'Desired Salary', 13, '1', 0, '12', '1', 3, 1, 1, 0, 0, 1, 0, '', '', 1, 1, 102, 0, 1, 0, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (86, 'employer_current_status', 'I currently work there', 42, '4', 0, '16', '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (87, 'wpjobportal_user_email', 'Email', 5, '', 0, NULL, NULL, 4, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (88, 'photo', 'Photo', 4, '', 0, NULL, NULL, 4, 1, 1, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (89, 'wpjobportal_user_first', 'First Name', 1, '', 0, NULL, NULL, 4, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (90, 'wpjobportal_user_last', 'Last Name', 2, '', 0, NULL, NULL, 4, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (91, 'wpjobportal_user_login', 'User Name', 3, '', 0, NULL, NULL, 4, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (92, 'searchable', 'Searchable', 25, '1', 0, NULL, NULL, 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (93, 'facebook_link', 'Facebook Link', 26, '', 0, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (94, 'youtube_link', 'Youtube Link', 27, '', 0, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (95, 'twiter_link', 'Twitter Link', 28, '', 0, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (96, 'linkedin_link', 'Linkedin Link', 29, '', 0, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (97, 'full_name', 'Name', 1, '1', 0, NULL, NULL, 5, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (98, 'email', 'Email', 2, '1', 0, NULL, NULL, 5, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (99, 'phone', 'Phone', 3, '1', 0, NULL, NULL, 5, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (100, 'message', 'Message', 4, '1', 0, NULL, NULL, 5, 1, 1, 0, 0, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL),
            (101, 'resume', 'Resume', 5, '1', 0, NULL, NULL, 5, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, '', NULL, NULL)
            ; ";
        wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) NOT NULL DEFAULT '',
              `isactive` tinyint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;";
            wpjobportal::$_db->query($query);

            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` (`id`, `title`, `isactive`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'University', 1, 1, 1, 0),(2, 'College', 1, 0, 2, 0),(3, 'High School', 1, 0, 3, 0),(4, 'No School', 1, 0, 4, 0);";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `jobid` int(11) NOT NULL DEFAULT '0',
                `uid` int(11) NOT NULL DEFAULT '0',
                `cvid` int(11) DEFAULT NULL,
                `apply_date` datetime DEFAULT NULL,
                `resumeview` tinyint(1) NOT NULL DEFAULT '0',
                `comments` varchar(1000) DEFAULT NULL,
                `rating` float NOT NULL,
                `coverletterid` int(11) DEFAULT NULL,
                `action_status` int(11) DEFAULT NULL,
                `serverstatus` varchar(255) DEFAULT NULL,
                `serverid` int(11) DEFAULT NULL,
                `socialapplied` tinyint(1) NOT NULL,
                `socialprofileid` int(11) NOT NULL,
                `status` int(11) DEFAULT NULL,
                `price` int(11) DEFAULT NULL,
                `userpackageid` int(20) DEFAULT NULL,
                `quick_apply` tinyint(1) NULL DEFAULT '0',
                `apply_message` text NULL,
                PRIMARY KEY (`id`),
                KEY `jobapply_uid` (`uid`),
                KEY `jobapply_jobid` (`jobid`),
                KEY `jobapply_cvid` (`cvid`)
              ) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
              ;";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `jobid` int(11) NOT NULL,
              `cityid` int(11) NOT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `jobid` (`jobid`),
              KEY `cityid` (`cityid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            wpjobportal::$_db->query($query);
            //JOB FIELD DONE
            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_jobs` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL,
              `companyid` int(11) DEFAULT NULL,
              `title` varchar(255) NOT NULL DEFAULT '',
              `alias` varchar(255) NOT NULL,
              `jobcategory` varchar(255) NOT NULL DEFAULT '',
              `jobtype` tinyint(1) UNSIGNED DEFAULT '0',
              `jobstatus` tinyint(3) NOT NULL DEFAULT '1',
              `hidesalaryrange` tinyint(1) DEFAULT '1',
              `description` text,
              `qualifications` text,
              `prefferdskills` text,
              `applyinfo` text,
              `company` varchar(255) NOT NULL DEFAULT '',
              `city` varchar(255) DEFAULT '',
              `zipcode` varchar(255) DEFAULT '',
              `address1` varchar(255) DEFAULT '',
              `address2` varchar(255) DEFAULT '',
              `companyurl` varchar(255) DEFAULT '',
              `contactname` varchar(255) DEFAULT '',
              `contactphone` varchar(255) DEFAULT '',
              `contactemail` varchar(255) DEFAULT '',
              `showcontact` tinyint(1) UNSIGNED DEFAULT '0',
              `noofjobs` int(11) UNSIGNED NOT NULL DEFAULT '1',
              `reference` varchar(255) NOT NULL DEFAULT '',
              `duration` varchar(255) NOT NULL DEFAULT '',
              `heighestfinisheducation` varchar(255) DEFAULT '',
              `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `created_by` int(11) UNSIGNED NOT NULL DEFAULT '0',
              `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `modified_by` int(11) UNSIGNED NOT NULL DEFAULT '0',
              `hits` int(11) UNSIGNED NOT NULL DEFAULT '0',
              `experience` int(11) DEFAULT '0',
              `startpublishing` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `stoppublishing` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `departmentid` varchar(255) DEFAULT NULL,
              `sendemail` tinyint(1) NOT NULL DEFAULT '0',
              `metadescription` text,
              `metakeywords` text,
              `ordering` tinyint(3) NOT NULL DEFAULT '0',
              `aboutjobfile` varchar(255) DEFAULT NULL,
              `status` int(11) DEFAULT '1',
              `educationid` int(11) DEFAULT NULL,
              `degreetitle` varchar(255) DEFAULT NULL,
              `careerlevel` int(11) DEFAULT NULL,
              `map` varchar(1000) DEFAULT NULL,
              `subcategoryid` int(11) DEFAULT NULL,
              `currency` varchar(255) DEFAULT '',
              `jobid` varchar(255) DEFAULT '',
              `longitude` varchar(1000) DEFAULT NULL,
              `latitude` varchar(1000) DEFAULT NULL,
              `isgoldjob` tinyint(1) DEFAULT '0',
              `startgolddate` datetime NOT NULL,
              `endgolddate` datetime NOT NULL,
              `startfeatureddate` datetime NOT NULL,
              `endfeatureddate` datetime NOT NULL,
              `isfeaturedjob` tinyint(1) DEFAULT '0',
              `raf_gender` tinyint(1) DEFAULT NULL,
              `raf_degreelevel` tinyint(1) DEFAULT NULL,
              `raf_experience` tinyint(1) DEFAULT NULL,
              `raf_age` tinyint(1) DEFAULT NULL,
              `raf_education` tinyint(1) DEFAULT NULL,
              `raf_category` tinyint(1) DEFAULT NULL,
              `raf_subcategory` tinyint(1) DEFAULT NULL,
              `raf_location` tinyint(1) DEFAULT NULL,
              `jobapplylink` tinyint(1) NOT NULL,
              `joblink` varchar(255) NOT NULL,
              `params` longtext NOT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              `tags` varchar(255) NOT NULL,
              `salarytype` int(11) DEFAULT NULL,
              `salarymin` float DEFAULT NULL,
              `salarymax` float DEFAULT NULL,
              `salaryduration` int(11) DEFAULT NULL,
              `userpackageid` int(11) DEFAULT NULL,
              `price` int(20) DEFAULT NULL,
              `aijobsearchtext` MEDIUMTEXT NULL,
              `aijobsearchdescription` MEDIUMTEXT NULL,
              PRIMARY KEY (`id`),
              KEY `jobcategory` (`jobcategory`),
              KEY `jobs_companyid` (`companyid`),
              FULLTEXT KEY `aijobsearchtext` (`aijobsearchtext`),
              FULLTEXT KEY `aijobsearchdescription` (`aijobsearchdescription`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            wpjobportal::$_db->query($query);



            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) NOT NULL DEFAULT '',
              `isactive` tinyint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;";
            wpjobportal::$_db->query($query);
            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` (`id`, `title`, `isactive`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'Sourcing', 1, 1, 1, 0),(2, 'Interviewing', 1, 0, 2, 0),(3, 'Closed to New Applicants', 1, 0, 3, 0),(4, 'Finalists Identified', 1, 0, 4, 0),(5, 'Pending Approval', 1, 0, 5, 0),(6, 'Hold', 1, 0, 6, 0);";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) NOT NULL DEFAULT '',
              `color` varchar(255) DEFAULT NULL,
              `isactive` tinyint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              `alias` varchar(300) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;";
            wpjobportal::$_db->query($query);

            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` (`id`, `title`, `color`, `isactive`, `isdefault`, `ordering`, `status`, `serverid`, `alias`) VALUES(1, 'Full-Time', '#00abfa', 1, 1, 1, 1, 1, 'full-time'),(2, 'Part-Time', '#b557b5', 1, 0, 2, 0, 0, 'part-time'),(3, 'Internship', '#11872d', 1, 0, 3, 0, 0, 'internship');";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_resume` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) DEFAULT NULL,
              `created` datetime NOT NULL,
              `last_modified` datetime DEFAULT NULL,
              `published` tinyint(1) DEFAULT NULL,
              `hits` int(11) DEFAULT NULL,
              `application_title` varchar(255) NOT NULL,
              `salaryfixed` varchar(255) NOT NULL,
              `keywords` varchar(255) DEFAULT NULL,
              `alias` varchar(255) NOT NULL,
              `first_name` varchar(255) NOT NULL,
              `last_name` varchar(255) NOT NULL,
              `gender` varchar(255) DEFAULT NULL,
              `email_address` varchar(255) DEFAULT NULL,
              `cell` varchar(255) DEFAULT NULL,
              `nationality` varchar(255) DEFAULT NULL,
              `searchable` tinyint(1) DEFAULT '1',
              `photo` varchar(255) DEFAULT NULL,
              `job_category` int(11) DEFAULT NULL,
              `jobtype` int(11) DEFAULT NULL,
              `status` int(11) NOT NULL,
              `resume` text,
              `skills` text,
              `isgoldresume` tinyint(1) DEFAULT NULL,
              `startgolddate` datetime NOT NULL,
              `startfeatureddate` datetime NOT NULL,
              `endgolddate` datetime NOT NULL,
              `endfeatureddate` datetime NOT NULL,
              `isfeaturedresume` tinyint(1) DEFAULT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              `tags` varchar(500) NOT NULL,
              `params` longtext,
              `userpackageid` int(11) DEFAULT NULL,
              `price` int(20) DEFAULT NULL,
              `quick_apply` tinyint(1) NULL DEFAULT '0',
              `airesumesearchtext` MEDIUMTEXT NULL,
              `airesumesearchdescription` MEDIUMTEXT NULL,
              PRIMARY KEY (`id`),
              FULLTEXT KEY `airesumesearchtext` (`airesumesearchtext`),
              FULLTEXT KEY `airesumesearchdescription` (`airesumesearchdescription`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            wpjobportal::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS  `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `resumeid` int(11) NOT NULL,
                `address` text,
                `address_city` varchar(255) DEFAULT NULL,
                `longitude` varchar(255) NOT NULL,
                `latitude` varchar(255) NOT NULL,
                `created` datetime NOT NULL,
                `last_modified` datetime NOT NULL,
                `params` longtext NOT NULL,
                `serverstatus` varchar(255) DEFAULT NULL,
                `serverid` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `resumeid` int(11) NOT NULL,
              `employer` varchar(255) DEFAULT NULL,
              `employer_from_date` varchar(255) DEFAULT NULL,
              `employer_to_date` varchar(255) DEFAULT NULL,
              `employer_current_status` int(11) NOT NULL,
              `employer_city` varchar(255) DEFAULT NULL,
              `employer_position` varchar(255) DEFAULT NULL,
              `employer_phone` varchar(255) DEFAULT NULL,
              `employer_address` varchar(255) DEFAULT NULL,
              `created` datetime NOT NULL,
              `last_modified` datetime NOT NULL,
              `params` longtext NOT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            wpjobportal::$_db->query($query);


          $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `resumeid` int(11) NOT NULL,
            `filename` varchar(300) DEFAULT NULL,
            `filetype` varchar(255) DEFAULT NULL,
            `filesize` int(11) DEFAULT NULL,
            `created` datetime NOT NULL,
            `last_modified` datetime NOT NULL,
            `serverstatus` varchar(255) DEFAULT NULL,
            `serverid` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
          wpjobportal::$_db->query($query);


          $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `resumeid` int(11) NOT NULL,
            `institute` varchar(255) DEFAULT NULL,
            `institute_certificate_name` varchar(255) DEFAULT NULL,
            `institute_study_area` text,
            `created` datetime NOT NULL,
            `last_modified` datetime NOT NULL,
            `serverstatus` varchar(255) DEFAULT NULL,
            `serverid` int(11) DEFAULT NULL,
            `fromdate` varchar(255) DEFAULT NULL,
            `todate` varchar(255) DEFAULT NULL,
            `params` longtext NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
          wpjobportal::$_db->query($query);



          $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_resumelanguages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `resumeid` int(11) NOT NULL,
          `language` varchar(255) DEFAULT NULL,
          `created` datetime NOT NULL,
          `last_modified` datetime NOT NULL,
          `params` longtext NOT NULL,
          `serverstatus` varchar(255) DEFAULT NULL,
          `serverid` int(11) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
          wpjobportal::$_db->query($query);

         $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) NOT NULL,
              `status` tinyint(4) NOT NULL,
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;";
            wpjobportal::$_db->query($query);


            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` (`id`, `title`, `status`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'Per Year', 1, 0, 1, 0),(2, 'Per Month', 1, 1, 2, 0),(3, 'Per Week', 1, 0, 3, 0),(4, 'Per Day', 1, 0, 4, 0);";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_states` (
              `id` smallint(8) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) DEFAULT NULL,
              `localname` varchar(255) DEFAULT NULL,
              `internationalname` varchar(255) DEFAULT NULL,
              `shortRegion` varchar(255) DEFAULT NULL,
              `countryid` smallint(9) DEFAULT NULL,
              `enabled` tinyint(1) NOT NULL DEFAULT '0',
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `countryid` (`countryid`),
              FULLTEXT KEY `name` (`name`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;";
            wpjobportal::$_db->query($query);

            $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_states` (`id`, `name`, `localname`, `internationalname`, `shortRegion`, `countryid`, `enabled`, `serverid`) VALUES
                    (1, 'Alabama', NULL, NULL, 'AL', 1, 1, 0),
                    (2, 'Alaska', NULL, NULL, 'AK', 1, 1, 0),
                    (3, 'Arizona', NULL, NULL, 'AZ', 1, 1, 0),
                    (4, 'Arkansas', NULL, NULL, 'AR', 1, 1, 0),
                    (5, 'California', NULL, NULL, 'CA', 1, 1, 0),
                    (6, 'Colorado', NULL, NULL, 'CO', 1, 1, 0),
                    (7, 'Connecticut', NULL, NULL, 'CT', 1, 1, 0),
                    (8, 'Delaware', NULL, NULL, 'DE', 1, 1, 0),
                    (9, 'District of Columbia', NULL, NULL, 'DC', 1, 1, 0),
                    (10, 'Florida', NULL, NULL, 'FL', 1, 1, 0),
                    (11, 'Georgia', NULL, NULL, 'GA', 1, 1, 0),
                    (12, 'Hawaii', NULL, NULL, 'HI', 1, 1, 0),
                    (13, 'Idaho', NULL, NULL, 'ID', 1, 1, 0),
                    (14, 'Illinois', NULL, NULL, 'IL', 1, 1, 0),
                    (15, 'Indiana', NULL, NULL, 'IN', 1, 1, 0),
                    (16, 'Iowa', NULL, NULL, 'IA', 1, 1, 0),
                    (17, 'Kansas', NULL, NULL, 'KS', 1, 1, 0),
                    (18, 'Kentucky', NULL, NULL, 'KY', 1, 1, 0),
                    (19, 'Louisiana', NULL, NULL, 'LA', 1, 1, 0),
                    (20, 'Maine', NULL, NULL, 'ME', 1, 1, 0),
                    (21, 'Maryland', NULL, NULL, 'MD', 1, 1, 0),
                    (22, 'Massachusetts', NULL, NULL, 'MA', 1, 1, 0),
                    (23, 'Michigan', NULL, NULL, 'MI', 1, 1, 0),
                    (24, 'Minnesota', NULL, NULL, 'MN', 1, 1, 0),
                    (25, 'Mississippi', NULL, NULL, 'MS', 1, 1, 0),
                    (26, 'Missouri', NULL, NULL, 'MO', 1, 1, 0),
                    (27, 'Montana', NULL, NULL, 'MT', 1, 1, 0),
                    (28, 'Nebraska', NULL, NULL, 'NE', 1, 1, 0),
                    (29, 'Nevada', NULL, NULL, 'NV', 1, 1, 0),
                    (30, 'New Hampshire', NULL, NULL, 'NH', 1, 1, 0),
                    (31, 'New Jersey', NULL, NULL, 'NJ', 1, 1, 0),
                    (32, 'New Mexico', NULL, NULL, 'NM', 1, 1, 0),
                    (33, 'New York', NULL, NULL, 'NY', 1, 1, 0),
                    (34, 'North Carolina', NULL, NULL, 'NC', 1, 1, 0),
                    (35, 'North Dakota', NULL, NULL, 'ND', 1, 1, 0),
                    (36, 'Ohio', NULL, NULL, 'OH', 1, 1, 0),
                    (37, 'Oklahoma', NULL, NULL, 'OK', 1, 1, 0),
                    (38, 'Oregon', NULL, NULL, 'OR', 1, 1, 0),
                    (39, 'Pennsylvania', NULL, NULL, 'PA', 1, 1, 0),
                    (40, 'Rhode Island', NULL, NULL, 'RI', 1, 1, 0),
                    (41, 'South Carolina', NULL, NULL, 'SC', 1, 1, 0),
                    (42, 'South Dakota', NULL, NULL, 'SD', 1, 1, 0),
                    (43, 'Tennessee', NULL, NULL, 'TN', 1, 1, 0),
                    (44, 'Texas', NULL, NULL, 'TX', 1, 1, 0),
                    (45, 'Utah', NULL, NULL, 'UT', 1, 1, 0),
                    (46, 'Vermont', NULL, NULL, 'VT', 1, 1, 0),
                    (47, 'Virginia', NULL, NULL, 'VA', 1, 1, 0),
                    (48, 'Washington', NULL, NULL, 'WA', 1, 1, 0),
                    (49, 'West Virginia', NULL, NULL, 'WV', 1, 1, 0),
                    (50, 'Wisconsin', NULL, NULL, 'WI', 1, 1, 0),
                    (51, 'Wyoming', NULL, NULL, 'WY', 1, 1, 0),
                    (52, 'Alberta', NULL, NULL, 'AB', 2, 1, 0),
                    (53, 'British Columbia', NULL, NULL, 'BC', 2, 1, 0),
                    (54, 'Manitoba', NULL, NULL, 'MB', 2, 1, 0),
                    (55, 'New Brunswick', NULL, NULL, 'NB', 2, 1, 0),
                    (56, 'Newfoundland and Labrador', NULL, NULL, 'NL', 2, 1, 0),
                    (57, 'Northwest Territories', NULL, NULL, 'NT', 2, 1, 0),
                    (58, 'Nova Scotia', NULL, NULL, 'NS', 2, 1, 0),
                    (59, 'Nunavut', NULL, NULL, 'NU', 2, 1, 0),
                    (60, 'Ontario', NULL, NULL, 'ON', 2, 1, 0),
                    (61, 'Prince Edward Island', NULL, NULL, 'PE', 2, 1, 0),
                    (62, 'Quebec', NULL, NULL, 'QC', 2, 1, 0),
                    (63, 'Saskatchewan', NULL, NULL, 'SK', 2, 1, 0),
                    (64, 'Yukon', NULL, NULL, 'YT', 2, 1, 0),
                    (65, 'England', NULL, NULL, 'England', 95, 1, 0),
                    (66, 'Northern Ireland', NULL, NULL, 'NorthernIreland', 95, 1, 0),
                    (67, 'Scotland', NULL, NULL, 'Scottland', 95, 1, 0),
                    (68, 'Wales', NULL, NULL, 'Wales', 95, 1, 0),
                    (86, 'Khyber Pakhtunkhwa', 'خیبر پختونخوا', 'Khyber Pakhtunkhwa', 'NWFP', 126, 1, 0),
                    (87, 'FATA', '', 'FATA', 'FATA', 126, 1, 0),
                    (88, 'Balochistan', 'بلوچستان', 'Balochistan', 'Balochistan', 126, 1, 0),
                    (89, 'Punjab', 'پنجاب', 'Punjab', 'Punjab', 126, 1, 0),
                    (90, 'Capital', 'دار الحکومت', 'Capital', 'Capital', 126, 1, 0),
                    (91, 'Sindh', 'سندھ', 'Sindh', 'Sindh', 126, 1, 0);";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_activitylog` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `description` text NOT NULL,
              `referencefor` varchar(255) NOT NULL,
              `referenceid` int(11) NOT NULL,
              `uid` int(11) NOT NULL,
              `created` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_system_errors` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) DEFAULT NULL,
              `error` text,
              `isview` tinyint(1) DEFAULT '0',
              `created` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uid` int(11) NOT NULL,
                `roleid` int(11) NOT NULL,
                `photo` varchar(255) NOT NULL,
                `first_name` varchar(300) NOT NULL,
                `last_name` varchar(300) NOT NULL,
                `emailaddress` varchar(255) NOT NULL,
                `socialid` varchar(255) NOT NULL,
                `socialmedia` varchar(255) NOT NULL,
                `params` longtext NOT NULL,
                `status` tinyint(1) NOT NULL,
                `created` datetime NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            wpjobportal::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_slug` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `slug` varchar(255) CHARACTER SET utf8 NOT NULL,
              `defaultslug` varchar(255) CHARACTER SET utf8 NOT NULL,
              `filename` varchar(255) CHARACTER SET utf8 NOT NULL,
              `description` varchar(255) CHARACTER SET utf8 NOT NULL,
              `status` tinyint(11) DEFAULT NULL,
              `pagetitle` varchar(255) DEFAULT NULL,
              `defaultpagetitle` varchar(255) DEFAULT NULL,
              `modulename` varchar(255) DEFAULT NULL,
              `titleoptions` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=93;";

            wpjobportal::$_db->query($query);
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        global $wp_filesystem;

        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        $slugprefix = "";
        if($wp_filesystem->exists(WP_PLUGIN_DIR.'/js-jobs/js-jobs.php')){
          $slugprefix = 'wpjobportal-';
        }
        $query = "INSERT INTO `" . wpjobportal::$_db->prefix . "wj_portal_slug` (`id`, `slug`, `defaultslug`, `filename`, `description`, `pagetitle`, `defaultpagetitle`, `modulename`, `titleoptions`, `status`) VALUES
          (1, '".$slugprefix."new-in-wpjobportal', 'new-in-wpjobportal', 'newinwpjobportal', 'slug for new in wp job portal page', 'New In WP Job Portal [separator] [sitename]', 'New In WP Job Portal [separator] [sitename]', 'wpjobportal', '[separator],[sitename]', 1),
          (2, '".$slugprefix."wpjobportal-login', 'wpjobportal-login', 'login', 'slug for login page', 'WP Job Portal Login [separator] [sitename]', 'WP Job Portal Login [separator] [sitename]', 'wpjobportal', '[separator],[sitename]', 1),
          (3, '".$slugprefix."jobseeker-control-panel', 'jobseeker-control-panel', 'controlpanel', 'slug for jobseeker control panel', 'Jobseeker Control Panel [separator] [sitename]', 'Jobseeker Control Panel [separator] [sitename]', 'jobseeker', '[separator],[sitename]', 1),
          (4, '".$slugprefix."employer-control-panel', 'employer-control-panel', 'controlpanel', 'slug for employer control panel', 'Employer Control Panel [separator] [sitename]', 'Employer Control Panel [separator] [sitename]', 'employer', '[separator],[sitename]', 1),
          (5, '".$slugprefix."jobseeker-my-stats', 'jobseeker-my-stats', 'mystats', 'slug for job seeker my stats page', 'Jobseeker My Stats [separator] [sitename]', 'Jobseeker My Stats [separator] [sitename]', 'jobseeker', '[separator],[sitename]', 1),
          (6, '".$slugprefix."employer-my-stats', 'employer-my-stats', 'mystats', 'slug for employer my stats page', 'Employer My Stats [separator] [sitename]', 'Employer My Stats [separator] [sitename]', 'employer', '[separator],[sitename]', 1),
          (7, '".$slugprefix."resumes', 'resumes', 'resumes', 'slug for resume main listing page', 'Resumes [separator] [sitename]', 'Resumes [separator] [sitename]', 'resume', '[separator],[sitename]', 1),
          (8, '".$slugprefix."jobs', 'jobs', 'jobs', 'slug for job main listing page', 'Jobs [separator] [sitename]', 'Jobs [separator] [sitename]', 'job', '[separator],[sitename]', 1),
          (9, '".$slugprefix."my-companies', 'my-companies', 'mycompanies', 'slug for my companies page', 'My Companies [separator] [sitename]', 'My Companies [separator] [sitename]', 'company', '[separator],[sitename]', 1),
          (10, '".$slugprefix."add-company', 'add-company', 'addcompany', 'slug for add company page', 'Add Company [separator] [sitename]', 'Add Company [separator] [sitename]', 'company', '[separator],[sitename]', 1),
          (11, '".$slugprefix."my-jobs', 'my-jobs', 'myjobs', 'slug for my jobs page', 'My Jobs [separator] [sitename]', 'My Jobs [separator] [sitename]', 'job', '[separator],[sitename]', 1),
          (12, '".$slugprefix."add-job', 'add-job', 'addjob', 'slug for add job page', 'Add Job [separator] [sitename]', 'Add Job [separator] [sitename]', 'job', '[separator],[sitename]', 1),
          (13, '".$slugprefix."my-departments', 'my-departments', 'mydepartments', 'slug for my departments page', 'My Departments [separator] [sitename]', 'My Departments [separator] [sitename]', 'department', '[separator],[sitename]', 1),
          (14, '".$slugprefix."add-department', 'add-department', 'adddepartment', 'slug for add department page', 'Add Department [separator] [sitename]', 'Add Department [separator] [sitename]', 'department', '[separator],[sitename]', 1),
          (15, '".$slugprefix."department', 'department', 'viewdepartment', 'slug for view department page', 'Department Information [separator] [sitename]', 'Department Information [separator] [sitename]', 'department', '[separator],[sitename]', 1),
          (17, '".$slugprefix."company', 'company', 'viewcompany', 'slug for view company page', '[name] [location] [separator] [sitename]', '[name] [location] [separator] [sitename]', 'company', '[name],[location],[separator],[sitename]', 1),
          (18, '".$slugprefix."resume', 'resume', 'viewresume', 'slug for view resume page', '[applicationtitle] [jobcategory] [separator] [sitename]', '[applicationtitle] [jobcategory] [separator] [sitename]', 'resume', '[applicationtitle],[jobcategory],[jobtype],[location],[separator],[sitename]', 1),
          (19, '".$slugprefix."job', 'job', 'viewjob', 'slug for view job page', '[title] [location] [separator] [sitename]', '[title] [location] [separator] [sitename]', 'job', '[title],[companyname],[jobcategory],[jobtype],[location],[separator],[sitename]', 1),
          (20, '".$slugprefix."my-folders', 'my-folders', 'myfolders', 'slug for my folders page', 'My Folders [separator] [sitename]', 'My Folders [separator] [sitename]', 'folder', '[separator],[sitename]', 1),
          (21, '".$slugprefix."add-folder', 'add-folder', 'addfolder', 'slug for add folder page', 'Add Folder [separator] [sitename]', 'Add Folder [separator] [sitename]', 'folder', '[separator],[sitename]', 1),
          (22, '".$slugprefix."folder', 'folder', 'viewfolder', 'slug for view folder page', 'Folder Information [separator] [sitename]', 'Folder Information [separator] [sitename]', 'folder', '[separator],[sitename]', 1),
          (23, '".$slugprefix."folder-resumes', 'folder-resumes', 'folderresume', 'slug for folder resume page', 'Folder Resumes [separator] [sitename]', 'Folder Resumes [separator] [sitename]', 'folder', '[separator],[sitename]', 1),
          (24, '".$slugprefix."jobseeker-messages', 'jobseeker-messages', 'jobseekermessages', 'slug for job seeker messages page', 'Jobseeker Messages [separator] [sitename]', 'Jobseeker Messages [separator] [sitename]', 'message', '[separator],[sitename]', 1),
          (25, '".$slugprefix."employer-messages', 'employer-messages', 'employermessages', 'slug for employer messages page', 'Employer Messages [separator] [sitename]', 'Employer Messages [separator] [sitename]', 'message', '[separator],[sitename]', 1),
          (26, '".$slugprefix."message', 'message', 'sendmessage', 'slug for send message page', 'Message [separator] [sitename]', 'Message [separator] [sitename]', 'message', '[separator],[sitename]', 1),
          (27, '".$slugprefix."job-messages', 'job-messages', 'jobmessages', 'slug for job messages page', 'Job Messages [separator] [sitename]', 'Job Messages [separator] [sitename]', 'message', '[separator],[sitename]', 1),
          (29, '".$slugprefix."messages', 'messages', 'messages', 'slug for messages page', 'Messages [separator] [sitename]', 'Messages [separator] [sitename]', 'message', '[separator],[sitename]', 1),
          (30, '".$slugprefix."resume-search', 'resume-search', 'resumesearch', 'slug for resume search page', 'Resume Search [separator] [sitename]', 'Resume Search [separator] [sitename]', 'resumesearch', '[separator],[sitename]', 1),
          (31, '".$slugprefix."resume-save-searches', 'resume-save-searches', 'resumesavesearch', 'slug for resume save search page', 'Resume Save Searches [separator] [sitename]', 'Resume Save Searches [separator] [sitename]', 'resumesearch', '[separator],[sitename]', 1),
          (32, '".$slugprefix."resume-categories', 'resume-categories', 'resumebycategory', 'slug for resume by category page', 'Resume By Categories [separator] [sitename]', 'Resume By Categories [separator] [sitename]', 'resume', '[separator],[sitename]', 1),
          (33, '".$slugprefix."resume-rss', 'resume-rss', 'resumerss', 'slug for resume rss page', 'Resume Rss [separator] [sitename]', 'Resume Rss [separator] [sitename]', 'rss', '[separator],[sitename]', 1),
          (34, '".$slugprefix."employer-credits', 'employer-credits', 'employercredits', 'slug for employer credits page', 'Employer Credits [separator] [sitename]', 'Employer Credits [separator] [sitename]', 'credtis', '[separator],[sitename]', 1),
          (35, '".$slugprefix."jobseeker-credits', 'jobseeker-credits', 'jobseekercredits', 'slug for job seeker credits page', 'Jobseeker Credits [separator] [sitename]', 'Jobseeker Credits [separator] [sitename]', 'credtis', '[separator],[sitename]', 1),
          (36, '".$slugprefix."employer-purchase-history', 'employer-purchase-history', 'employerpurchasehistory', 'slug for employer purchase history page', 'Employer Purchase History [separator] [sitename]', 'Employer Purchase History [separator] [sitename]', 'credtis', '[separator],[sitename]', 1),
          (37, '".$slugprefix."employer-my-stats', 'employer-my-stats', 'employermystats', 'employer my stats page', 'Employer My Stats [separator] [sitename]', 'Employer My Stats [separator] [sitename]', 'employer', '[separator],[sitename]', 1),
          (38, '".$slugprefix."jobseker-my-stats', 'jobseker-my-stats', 'jobseekerstats', 'slug for job seeker stats page', 'Jobseker My Stats [separator] [sitename]', 'Jobseker My Stats [separator] [sitename]', 'jobseeker', '[separator],[sitename]', 1),
          (39, '".$slugprefix."employer-register', 'employer-register', 'regemployer', 'slug for register as employer page', 'Employer Registration [separator] [sitename]', 'Employer Registration [separator] [sitename]', 'employer', '[separator],[sitename]', 1),
          (40, '".$slugprefix."jobseeker-register', 'jobseeker-register', 'regjobseeker', 'reg job seeker page', 'Job Seeker Registration [separator] [sitename]', 'Job Seeker Registration [separator] [sitename]', 'jobseeker', '[separator],[sitename]', 1),
          (41, '".$slugprefix."user-register', 'user-register', 'userregister', 'slug for user register page', 'User Registration [separator] [sitename]', 'User Registration [separator] [sitename]', 'wpjobportal', '[separator],[sitename]', 1),
          (42, '".$slugprefix."add-resume', 'add-resume', 'addresume', 'slug for add resume page', 'Add Resume [separator] [sitename]', 'Add Resume [separator] [sitename]', 'resume', '[separator],[sitename]', 1),
          (43, '".$slugprefix."my-resumes', 'my-resumes', 'myresumes', 'slug for my resumes page', 'My Resumes [separator] [sitename]', 'My Resumes [separator] [sitename]', 'resume', '[separator],[sitename]', 1),
          (45, '".$slugprefix."companies', 'companies', 'companies', 'slug for companies page', 'Companies [separator] [sitename]', 'Companies [separator] [sitename]', 'company', '[separator],[sitename]', 1),
          (46, '".$slugprefix."my-applied-jobs', 'my-applied-jobs', 'myappliedjobs', 'slug for my applied jobs page', 'My Applied Jobs [separator] [sitename]', 'My Applied Jobs [separator] [sitename]', 'jobapply', '[separator],[sitename]', 1),
          (47, '".$slugprefix."job-applied-resume', 'job-applied-resume', 'jobappliedresume', 'slug for job applied resume page', 'Job Applied Resume [separator] [sitename]', 'Job Applied Resume [separator] [sitename]', 'jobapply', '[separator],[sitename]', 1),
          (49, '".$slugprefix."job-search', 'job-search', 'jobsearch', 'slug for job search page', 'Job Search [separator] [sitename]', 'Job Search [separator] [sitename]', 'jobsearch', '[separator],[sitename]', 1),
          (50, '".$slugprefix."job-save-searches', 'job-save-searches', 'jobsavesearch', 'slug for job save search page', 'Job Save Searches [separator] [sitename]', 'Job Save Searches [separator] [sitename]', 'jobsearch', '[separator],[sitename]', 1),
          (51, '".$slugprefix."job-alert', 'job-alert', 'jobalert', 'slug for job alert page', 'Job Alert [separator] [sitename]', 'Job Alert [separator] [sitename]', 'jobalert', '[separator],[sitename]', 1),
          (52, '".$slugprefix."job-rss', 'job-rss', 'jobrss', 'slug for job rss page', 'Job Rss [separator] [sitename]', 'Job Rss [separator] [sitename]', 'rss', '[separator],[sitename]', 1),
          (53, '".$slugprefix."shortlisted-jobs', 'shortlisted-jobs', 'shortlistedjobs', 'slug for shortlisted jobs page', 'Shortlisted Jobs [separator] [sitename]', 'Shortlisted Jobs [separator] [sitename]', 'shortlistedjobs', '[separator],[sitename]', 1),
          (54, '".$slugprefix."jobseeker-purchase-history', 'jobseeker-purchase-history', 'jobseekerpurchasehistory', 'slug for job seeker purchase history page', 'Job Seeker Purchase History [separator] [sitename]', 'Job Seeker Purchase History [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (55, '".$slugprefix."jobseeker-rate-list', 'jobseeker-rate-list', 'ratelistjobseeker', 'slug for rate list job seeker page', 'Job Seeker Rate List [separator] [sitename]', 'Job Seeker Rate List [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (56, '".$slugprefix."employer-rate-list', 'employer-rate-list', 'ratelistemployer', 'slug for rate list employer page', 'Employer Rate List [separator] [sitename]', 'Employer Rate List [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (57, '".$slugprefix."jobseeker-credits-log', 'jobseeker-credits-log', 'jobseekercreditslog', 'slug for job seeker credits log page', 'Job Seeker Credits Log [separator] [sitename]', 'Job Seeker Credits Log [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (58, '".$slugprefix."employer-credits-log', 'employer-credits-log', 'employercreditslog', 'slug for employer credits log page', 'Employer Credits Log [separator] [sitename]', 'Employer Credits Log [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (59, '".$slugprefix."job-categories', 'job-categories', 'jobsbycategories', 'slug for jobs by categories page', 'Job By Categories [separator] [sitename]', 'Job By Categories [separator] [sitename]', 'category', '[separator],[sitename]', 1),
          (60, '".$slugprefix."newest-jobs', 'newest-jobs', 'newestjobs', 'slug for newest jobs page', 'Newest Jobs [separator] [sitename]', 'Newest Jobs [separator] [sitename]', 'job', '[separator],[sitename]', 1),
          (61, '".$slugprefix."job-by-types', 'job-by-types', 'jobsbytypes', 'slug for jobs by types page', 'Job By Types [separator] [sitename]', 'Job By Types [separator] [sitename]', 'job', '[separator],[sitename]', 1),
          (64, '".$slugprefix."jobs-by-cities', 'jobs-by-cities', 'jobsbycities', 'slug for jobs by cities page', 'Jobs By Cities [separator] [sitename]', 'Jobs By Cities [separator] [sitename]', 'job', '[separator],[sitename]', 1),
          (65, '".$slugprefix."resume-pdf', 'resume-pdf', 'pdf', 'slug for employer resume pdf', 'Resume PDF [separator] [sitename]', 'Resume PDF [separator] [sitename]', 'resume', '[separator],[sitename]', 1),
          (67, '".$slugprefix."my-invoices', 'my-invoices', 'myinvoices', 'slug for new in wp job portal page', 'My Invoices [separator] [sitename]', 'My Invoices [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (69, '".$slugprefix."my-packages', 'my-packages', 'purchasehistory', 'slug for new in wp job portal page', 'My Packages [separator] [sitename]', 'My Packages [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (70, '".$slugprefix."packages', 'packages', 'packages', 'slug for new in wp job portal page', 'Packages [separator] [sitename]', 'Packages [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (71, '".$slugprefix."my-subscriptions', 'my-subscriptions', 'mysubscriptions', 'slug for new in wp job portal page', 'My Subscriptions [separator] [sitename]', 'My Subscriptions [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (72, '".$slugprefix."edit-profile', 'edit-profile', 'formprofile', 'Slug for edit Profile', 'Edit Profile [separator] [sitename]', 'Edit Profile [separator] [sitename]', 'wpjobportal', '[separator],[sitename]', 1),
          (75, '".$slugprefix."resume-print', 'resume-print', 'printresume', '', 'Resume Print [separator] [sitename]', 'Resume Print [separator] [sitename]', 'resume', '[separator],[sitename]', NULL),
          (78, '".$slugprefix."company-payment', 'company-payment', 'paycompany', '', 'Company Payment [separator] [sitename]', 'Company Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (80, '".$slugprefix."featuredcompany-payment', 'featuredcompany-payment', 'payfeaturedcompany', '', 'Featured Company Payment [separator] [sitename]', 'Featured Company Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (81, '".$slugprefix."department-payment', 'department-payment', 'paydepartment', '', 'Department Payment [separator] [sitename]', 'Department Payment [separator] [sitename]', 'credits', '[separator],[sitename]', 1),
          (82, '".$slugprefix."featuredjob-payment', 'featuredjob-payment', 'payfeaturedjob', '', 'Featured Job Payment [separator] [sitename]', 'Featured Job Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (83, '".$slugprefix."job-payment', 'job-payment', 'payjob', '', 'Job Payment [separator] [sitename]', 'Job Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (84, '".$slugprefix."featuredresume-payment', 'featuredresume-payment', 'payfeaturedresume', '', 'Featured Resume Payment [separator] [sitename]', 'Featured Resume Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (85, '".$slugprefix."jobapply-payment', 'jobapply-payment', 'payjobapply', '', 'Job Apply Payment [separator] [sitename]', 'Job Apply Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (86, '".$slugprefix."resume-payment', 'resume-payment', 'payresume', '', 'Resume Payment [separator] [sitename]', 'Resume Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (87, '".$slugprefix."newest-jobs', 'newest-jobs', 'newestjobs', '1', 'Newest Jobs [separator] [sitename]', 'Newest Jobs [separator] [sitename]', 'job', '[separator],[sitename]', NULL),
          (88, '".$slugprefix."resumesavesearch-payment', 'resumesavesearch-payment', 'payresumesearch', '', 'Resume Save Search Payment [separator] [sitename]', 'Resume Save Search Payment [separator] [sitename]', 'credits', '[separator],[sitename]', NULL),
          (89, '".$slugprefix."my-coverletters', 'my-coverletters', 'mycoverletters', 'slug for my coverletters page', 'My Cover Letters [separator] [sitename]', 'My Cover Letters [separator] [sitename]', 'coverletter', '[separator],[sitename]', 1),
          (90, '".$slugprefix."add-coverletter', 'add-coverletter', 'addcoverletter', 'slug for add coverletter page', 'Add Cover Letter [separator] [sitename]', 'Add Cover Letter [separator] [sitename]', 'coverletter', '[separator],[sitename]', 1),
          (91, '".$slugprefix."coverletter', 'coverletter', 'viewcoverletter', 'slug for view coverletter page', 'Cover Letter Information [separator] [sitename]', 'Cover Letter Information [separator] [sitename]', 'coverletter', '[separator],[sitename]', 1),
          (92, '".$slugprefix."coverletter-payment', 'coverletter-payment', 'paycoverletter', '', 'Cover Letter Payment [separator] [sitename]', 'Cover Letter Payment [separator] [sitename]', 'credits', '[separator],[sitename]', 1);

          ";
          wpjobportal::$_db->query($query);
          $query = "CREATE TABLE IF NOT EXISTS `" . wpjobportal::$_db->prefix . "wj_portal_jswjsessiondata` (
                      `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                      `usersessionid` char(64) NOT NULL,
                      `sessionmsg` text CHARACTER SET utf8 NOT NULL,
                      `sessionexpire` bigint(32) NOT NULL,
                      `sessionfor` varchar(255) NOT NULL,
                      `msgkey`varchar(255) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
          wpjobportal::$_db->query($query);

        }
      }
    }
?>
