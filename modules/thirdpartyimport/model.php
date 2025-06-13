<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALthirdpartyimportModel {

    private $_params_flag;
    private $_params_string;


    // job manager import data

    private $job_manager_company_custom_fields = array();
    private $job_manager_job_custom_fields = array();
    private $job_manager_resume_custom_fields = array();

    private $job_manager_users_array = array();

    private $job_manager_company_ids = array();
    private $job_manager_job_ids = array();
    private $job_manager_resume_ids = array();
    private $job_manager_jobapply_ids = array();
    private $job_manager_user_ids = array();
    private $job_manager_jobtype_ids = array();
    private $job_manager_category_ids = array();
    private $job_manager_tag_ids = array();

    // values for counts
    private $job_manager_import_count = [];

    function __construct() {
        $this->_params_flag = 0;
        $this->job_manager_import_count =
            [
                'company' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],
                'job' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],
                'resume' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],
                'user' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],
                'field' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],

                'jobtype' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],
                'category' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],
                'tag' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ],
                'jobapply' => [
                    'imported' => 0,
                    'skipped'  => 0,
                    'failed'   => 0,
                ]
            ];
    }


    // function getJobManagerDataStats() {
    //     $entity_counts = [];


    //     // Users ?? need to process further
    //     $user_query = new WP_User_Query(['count_total' => true]);
    //     $entity_counts['users'] = $user_query->get_total();

    //     // Jobs ?? status
    //     $entity_counts['jobs'] = wp_count_posts('job_listing')->publish;

    //     // Companies (if WP Job Manager Companies addon)
    //     if (post_type_exists('company')) {
    //         $entity_counts['companies'] = wp_count_posts('company')->publish;
    //     }

    //     // Resumes (if Resume Manager addon)
    //     if (post_type_exists('resume')) {
    //         $entity_counts['resumes'] = wp_count_posts('resume')->publish;
    //     }

    //     // Job Applications (if using Applications addon)
    //     if (post_type_exists('job_application')) {
    //         $entity_counts['job_applications'] = wp_count_posts('job_application')->publish;
    //     }

    //     // 6. Categories (job_listing_category taxonomy)
    //     $categories = get_terms([
    //         'taxonomy'   => 'job_listing_category',
    //         'hide_empty' => false,
    //         'fields'     => 'ids'
    //     ]);
    //     $entity_counts['categories'] = is_array($categories) ? count($categories) : 0;

    //     // 7. Job Types (job_listing_type taxonomy)
    //     $job_types = get_terms([
    //         'taxonomy'   => 'job_listing_type',
    //         'hide_empty' => false,
    //         'fields'     => 'ids'
    //     ]);
    //     $entity_counts['job_types'] = is_array($job_types) ? count($job_types) : 0;

    //     // 8. Tags
    //     $tags = get_terms([
    //         'taxonomy'   => 'job_listing_tag',
    //         'hide_empty' => false,
    //     ]);
    //     $entity_counts['tags'] = is_array($tags) ? count($tags) : 0;

    //     return $entity_counts;
    // }

    function getPostConutByType ( $post_type ) {
        $counts = wp_count_posts( $post_type );
        return isset( $counts->publish ) ? (int) $counts->publish : 0;
    }

   function getJobManagerDataStats($count_for) {
        // $count_for handles different plugins

    //  at the moment only 1 is supported/ 1 is for wp job manager
    if($count_for != 1){
        return;
    }

        // Make sure WP Job Manager is active
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ( ! is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) ) {
            //return;
            //return new WP_Error( 'wpjm_inactive', 'WP Job Manager is not active.' );
        }

        $entity_counts = [];

        // Users
        // $user_query = new WP_User_Query( [ 'count_total' => true ] );
        // $entity_counts['users'] = (int) $user_query->get_total();

        // Jobs
        $entity_counts['jobs'] = $this->getPostConutByType( 'job_listing' );

        // Companies
        $entity_counts['companies'] = post_type_exists( 'company' ) ? $this->getPostConutByType( 'company' ) : 0;

        // Resumes
        $entity_counts['resumes'] = post_type_exists( 'resume' ) ? $this->getPostConutByType( 'resume' ) : 0;

        // Job Applications
        $entity_counts['job_applies'] = post_type_exists( 'job_application' ) ? $this->getPostConutByType( 'job_application' ) : 0;

        // Categories (job_listing_category taxonomy)
        $categories = get_terms([
            'taxonomy'   => 'job_listing_category',
            'hide_empty' => false,
            'fields'     => 'ids'
        ]);
        $entity_counts['job_categories'] = is_array($categories) ? count($categories) : 0;

        // Job Types (job_listing_type taxonomy)
        $job_types = get_terms([
            'taxonomy'   => 'job_listing_type',
            'hide_empty' => false,
            'fields'     => 'ids'
        ]);
        $entity_counts['job_types'] = is_array($job_types) ? count($job_types) : 0;

        // Tags
        // $tags = get_terms([
        //     'taxonomy'   => 'job_listing_tag',
        //     'hide_empty' => false,
        // ]);

        $query = "SELECT taxonomy.*, terms.*
                    FROM `" . wpjobportal::$_db->prefix . "term_taxonomy` AS taxonomy
                    JOIN `" . wpjobportal::$_db->prefix . "terms` AS terms ON terms.term_id = taxonomy.term_id
                    WHERE taxonomy.taxonomy = 'job_listing_tag';";
        $tags = wpjobportal::$_db->get_results($query);
        $entity_counts['tags'] = is_array($tags) ? count($tags) : 0;

        wpjobportal::$_data['entity_counts'] = $entity_counts;

        return;
    }

    // delete data only for development

    function deletejobmanagerimporteddata(){

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE id >3;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` WHERE id >200;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE id > 9;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE id > 10;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` WHERE id > 9;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id > 9;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE id > 9;";
        wpjobportal::$_db->query($query);
        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` WHERE id > 1;";
        wpjobportal::$_db->query($query);
        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` WHERE id > 1;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE id > 1;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` WHERE id > 3;";
        wpjobportal::$_db->query($query);

        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` WHERE id > 238;";
        wpjobportal::$_db->query($query);
        // $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_tags` WHERE id > 0;";
        // wpjobportal::$_db->query($query);

    }


    function importJobManagerData(){

        // only for delvelopment remove it befoore live
        //$this->deletejobmanagerimporteddata();

        // removing ids from options
        update_option('job_portal_jm_data_users','');
        update_option('job_portal_jm_data_companies','');
        update_option('job_portal_jm_data_jobs','');
        update_option('job_portal_jm_data_resumes','');


        // import users to wp job portal table
        $this->importUsers();
        $this->importJobFields();
        $this->importCompanies(); // logo missing

        $this->importCategories();
        $this->importJobTypes();
        if(in_array('tag', wpjobportal::$_active_addons)){
            $this->importTags();
        }

        $this->importJobs();
        $this->importResume();
        $this->importJobApplied();
        // echo '<pre>';print_r($this->job_manager_job_custom_fields);
        // echo '<pre>';print_r($this->job_manager_import_count);
        // die('here in job manager import 1817');
        return;
    }

    function setUserRoleByContent($user_id) {
        if (empty($user_id)) {
            return null;
        }

        // post types per user role
        $employer_types = array('job_listing', 'company');
        $job_seeker_types = array('resume', 'job_application');

        // number f records
        $counts = array( 'employer' => 0,'job_seeker' => 0);

        // dates for latest posts
        $latest_post_dates = array('employer' => null,'job_seeker' => null);

        // Check all wp job manafer post types
        $full_types_array = array_merge($employer_types, $job_seeker_types);
        foreach ( $full_types_array as $post_type) {
            // fetch posts by type
            $posts = get_posts([
                'post_type'      => $post_type,
                'author'         => $user_id,
                'posts_per_page' => -1, // all will be fetched
                'post_status'    => ['publish', 'pending', 'draft'],
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if (!empty($posts)) {
                $is_employer = in_array($post_type, $employer_types); // if current post is from employer array
                $key = $is_employer ? 'employer' : 'job_seeker'; // current post type employer or job seeker
                $counts[$key] += count($posts); // total posts by current type

                // latest post date record
                $latest_date = get_the_date('U', $posts[0]);
                if (empty($latest_post_dates[$key]) || $latest_date > $latest_post_dates[$key]) {
                    $latest_post_dates[$key] = $latest_date;
                }
            }
        }

        // Determine role based on counts and date
        if ($counts['employer'] > 0 && $counts['job_seeker'] === 0) { // if employer count > zero but job seeker count xero
            return 'employer';
        } elseif ($counts['job_seeker'] > 0 && $counts['employer'] === 0) {// if job seeker count > zero but employer count xero
            return 'job_seeker';
        } elseif ($counts['employer'] > 0 && $counts['job_seeker'] > 0) { ////  if both counts more then zero
            // Mixed usage: compare counts or latest activity
            if ($counts['employer'] > ($counts['job_seeker'] * 2)) { //  emplopyer entity count is more then 2 times
                return 'employer';
            } elseif ($counts['job_seeker'] > ($counts['employer'] * 2)) { //  emplopyer entity count is more then 2 times
                return 'job_seeker';
            } else { // Compare by latest post dates assign role based on latest post
                if ($latest_post_dates['employer'] > $latest_post_dates['job_seeker']) {
                    return 'employer';
                } else {
                    return 'job_seeker';
                }
            }
        }

        return null; // No entities found
    }

    function importUsers(){
        $users = get_users();

        // check if user already processed for import
        $imported_users = array();
        $imported_users_json = get_option('job_portal_jm_data_users');
        if(!empty($imported_users_json)){
            $imported_users = json_decode($imported_users_json,true);
        }

        foreach ($users as $user) {
            // check already imported
            if(!empty( $imported_users ) && in_array($user->ID, $imported_users) ){ // if user id already in array skip it
                $this->job_manager_import_count['user']['skipped'] += 1;
                continue;
            }
            // check if user is already in system (uid dupicate check)
            $user_object = WPJOBPORTALincluder::getJSModel('user')->getUserIDByWPUid($user->ID);
            if(!empty($user_object)){ // not empty means it will contain id for corresponding uid
                continue;
            }

            $data = array();
            $data['uid'] = $user->ID;
            $data['emailaddress'] = $user->user_email;

            // user role
            $role = $user->roles;
            if($role == 'company'){
                $data['roleid'] = 1;
            }elseif($role == 'employer'){
                $data['roleid'] = 1;
            }elseif($role == 'candidate'){
                $data['roleid'] = 2;
            }else{ // if any other role
                // handling the case of no role or wordpress dedfault role
                $role_string = $this->setUserRoleByContent($user->ID);
                if($role_string == 'employer'){
                    $data['roleid'] = 1;
                }elseif($role_string == 'job_seeker'){
                    $data['roleid'] = 2;
                }elseif($role_string == 'job_seeker'){
                    $data['roleid'] = 5;
                }
            }

            $data['first_name'] = get_user_meta( $user->ID, 'first_name', true );
            $data['last_name']  = get_user_meta( $user->ID, 'last_name', true );
            $data['status'] = 1;
            $data['created'] = $user->user_registered;
            $data = wpjobportal::wpjobportal_sanitizeData($data);
            $row = WPJOBPORTALincluder::getJSTable('users');

            if(!($row->bind($data) && $row->check() && $row->store())){
                $this->job_manager_import_count['user']['failed'] += 1;
                continue; // move on to next post if store fialed
            }else{
                // if no error then
                $this->job_manager_users_array[$user->ID] = $row->id; // create an array of uid and ids to use in record insertion
                $this->job_manager_user_ids[] = $user->ID; // create an array of user ids to store
                $this->job_manager_import_count['user']['imported'] += 1;
            }
        }
        if(!empty($this->job_manager_user_ids)){
            update_option('job_portal_jm_data_users', wp_json_encode($this->job_manager_user_ids) );
        }
    }


    function importCompanies(){
        $args = array('post_type' => 'company_listings');
        $companies = get_posts($args);

        // check if company already processed for import
        $imported_companies = array();
        $imported_companies_json = get_option('job_portal_jm_data_companies');
        if(!empty($imported_companies_json)){
            $imported_companies = json_decode($imported_companies_json,true);
        }

        foreach($companies as $company){
            // check already imported
            if(!empty( $imported_companies ) && in_array($company->ID, $imported_companies) ){ // if id already in array skip it
                $this->job_manager_import_count['company']['skipped'] += 1;
                continue;
            }
            echo "<br>".$company->ID;
            $args2 = array('post_parent' => $company->ID);
            $companies_details = get_posts($args2);

            $logo = '';
            $logoisfile = '';
            $logo_url = get_the_post_thumbnail_url( $company->ID, 'full' );
            if($logo_url != ''){
                $logo =basename($logo_url);
                $logoisfile = 1;
            }

            $post_meta = get_post_meta($company->ID);
            // echo "<br>";
            // print_r($company);
            // print_r($post_meta);
            //exit;
            $featured = 0;
            if($post_meta["_company_email"][0]) $email = $post_meta["_company_email"][0]; else $email = "";
            if($post_meta["_company_location"][0]) $address = $post_meta["_company_location"][0]; else $address = "";
            if($post_meta["_company_website"][0]) $website = $post_meta["_company_website"][0]; else $website = "";
            if($post_meta["_company_twitter"][0]) $twitter = $post_meta["_company_twitter"][0]; else $twitter = "";

            $end_featured_date = '';
            if(in_array('featuredcompany', wpjobportal::$_active_addons)){
                if(isset($post_meta["_featured"][0])) $featured = $post_meta["_featured"][0];
                if($featured == 1){
                    $end_featured_date = gmdate('Y-m-d H:i:s',strtotime(" +30 days"));
                }
            }

            $alias = wpjobportal::$_common->stringToAlias($company->post_title);

            $uid = $this->getUserIDFromAuthorID($company->post_author);

            $comapnyparams = $this->getParamsForCustomFields($this->job_manager_company_custom_fields,$post_meta);

            $data = [
                "id" => "",
                "uid" => $uid,
                "name" => $company->post_title,
                "alias" => $alias,
                "url" => $website,
                "logofilename" => $logo,
                "logoisfile" => $logoisfile,
                "smalllogofilename" => "",
                "smalllogoisfile" => "",
                "smalllogo" => "",
                "contactemail" => $email,
                "description" => $company->post_content,
                "city" => "",
                "address1" => $address,
                "address2" => "",
                "created" => $company->post_date,
                "price" => "",
                "modified" => $company->post_modified,
                "hits" => "",
                "tagline" => "",
                "status" => "1",
                "isfeaturedcompany" => $featured,
                "startfeatureddate" => "",
                "endfeatureddate" => $end_featured_date,
                "serverstatus" => "",
                "userpackageid" => "",
                "serverid" => "",
                "params" => $comapnyparams,
                "twiter_link" => $twitter,
                "linkedin_link" => "",
                "youtube_link" => "",
                "facebook_link" => ""
            ];
            $row = WPJOBPORTALincluder::getJSTable('company');
            print_r($data);

            if(!($row->bind($data) && $row->check() && $row->store())){
                $this->job_manager_import_count['company']['failed'] += 1;
                continue; // move on to next post if store fialed
            }else{
                // if no error then
                $this->job_manager_company_ids[] = $company->ID; // create an array of company ids to store
                $this->job_manager_import_count['company']['imported'] += 1;
            }
            // handle logo file upload to wp job portal uploads
            //$logo_url
            $this->handleUploadFile(1, $row->id, $logo_url);
        }
        //print_r($companies);
        if(!empty($this->job_manager_company_ids)){
            update_option('job_portal_jm_data_companies', wp_json_encode($this->job_manager_company_ids) );
        }
    }

    function ensureFilePathValid($full_path, $datadirectory) {

        if($full_path == '' || $datadirectory == ''){
            return;
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        // emove trailing slashes
        $full_path = untrailingslashit($full_path);
        $datadirectory = untrailingslashit($datadirectory);

        // Only proceed if path includes datadirectory makeing sure no other path gets proccessed
        $pos = strpos($full_path, $datadirectory);
        if ($pos === false) return;

        // Get segments from datadirectory onward
        $relative = substr($full_path, $pos);
        $base = substr($full_path, 0, $pos);
        $current = untrailingslashit($base);

        $relative_paths_segments = explode('/', $relative);

        foreach ($relative_paths_segments as $segment) {
            $current .= '/' . $segment;
            if ( ! $wp_filesystem->is_dir($current) ) {
                $wp_filesystem->mkdir($current, FS_CHMOD_DIR, true);
            }
            $index = $current . '/index.html';
            if ( ! $wp_filesystem->exists($index) ) {
                $wp_filesystem->put_contents($index, '', FS_CHMOD_FILE);
            }
        }
    }


    function handleUploadFile($uploadfor, $enitity_id, $upload_file){
        // basic validation
        if(is_numeric($uploadfor) && is_numeric($enitity_id) && $upload_file !=''){
            $datadirectory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
            $upload_dir = wp_upload_dir();
            $base_path = $upload_dir['basedir'];
            $path = $datadirectory . '/data';

            if($uploadfor == 1){ // company logo
                $folder_path = $upload_dir['basedir'].'/'.$path."/employer/comp_".$enitity_id."/logo";
            }elseif($uploadfor == 3){ // resume photo
                $folder_path = $upload_dir['basedir'].'/'.$path."/jobseeker/resume_".$enitity_id."/photo";
            }elseif($uploadfor == 4){ // resume files
                $folder_path = $upload_dir['basedir'].'/'.$path."/jobseeker/resume_".$enitity_id."/resume";
            }

            // set up direcotiers and index files
            $this->ensureFilePathValid($folder_path,$datadirectory);

            // Move uploaded file
            require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
            $filesystem = new WP_Filesystem_Direct( true );

            // remote path to relative path conversion
            $file_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $upload_file );
            $file_name = basename($upload_file);

            $source = $file_path;
            $destination = $folder_path . "/". $file_name;

            // echo "<br>s: ".$source;
            // echo "<br>d: ".$destination;

            // Make sure file exists before moving
            if ( file_exists( $source ) ) {
                $result = $filesystem->copy($source, $destination, true);
             //   var_dump($result);
            }
            //  else {
            //     echo "<br><strong>Source file not found:</strong> $source";
            // }
        }
    }



    function getParamsForCustomFields($customfields,$post_meta){
        $params = array();
        foreach($customfields as $custom_field){
            $meta_key = '_' . $custom_field['name'];
            if (!isset( $post_meta[ $meta_key ])) { // of meta for current field not set ignore it
                continue;
            }

            $custom_field_value = $post_meta[ $meta_key ][0] ?? '';
            if($custom_field_value == ''){ // if no value ignore current field
                continue;
            }
            $vardata = "";
            switch ( $custom_field['type'] ) { // to handle different type of fields seprately
                case 'date':
                    $vardata = gmdate("Y-m-d", wpjobportalphplib::wpJP_strtotime($custom_field_value));
                break;
                case 'checkbox':
                case 'combo':
                case 'file':
                    $vardata = maybe_unserialize($custom_field_value);
                    //$vardata = unserialize($custom_field_value);
                break;
                default:
                    $vardata = $custom_field_value;
                break;
            }
            if($vardata != ''){ //  only add value to params if its not empty
                if(is_array($vardata)){
                    $vardata = implode(', ', array_filter($vardata));
                }
                $params[$custom_field["jp_filedorderingfield"]] = wpjobportalphplib::wpJP_htmlspecialchars($vardata);
            }
        }
        // echo '<pre>';print_r($params);echo '</pre>';
        if(!empty($params)){
            return html_entity_decode(wp_json_encode($params, JSON_UNESCAPED_UNICODE));
        }else{
            return '';
        }
    }


    function importJobs(){

        // check if job already processed for import
        $imported_jobs = array();
        $imported_jobs_json = get_option('job_portal_jm_data_jobs');
        if(!empty($imported_jobs_json)){
            $imported_jobs = json_decode($imported_jobs_json,true);
        }

        $jobs = get_posts([
            'post_type'      => 'job_listing',
            'post_status'    => 'any', // will include all except 'auto-draft'
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'numberposts'    => -1, // fetch all posts
            'exclude'        => wp_list_pluck(
                get_posts([
                    'post_type'   => 'job_listing',
                    'post_status'=> 'auto-draft',
                    'fields'     => 'ids',
                    'numberposts'=> -1
                ]),
                null
            ),
        ]);

        // print_r($jobs);

        foreach($jobs as $job){
            // check already imported
            if(!empty( $imported_jobs ) && in_array($job->ID, $imported_jobs) ){ // if id already in array skip it
                $this->job_manager_import_count['job']['skipped'] += 1;
                continue;
            }
            $post_meta = get_post_meta($job->ID);
            // print_r($job);
            // print_r($post_meta);

            if(isset($post_meta["_company_id"][0])){ // company is already added (company is a seprate entitity)
                $companyid = $this->getCompanyIdByJobManagerId($post_meta["_company_id"][0]);
            }else{ // add company (compnay data is in job data) (no company addon)
                $companyid = $this->createJPCompany($job);
            }

            $stoppublishing = "";
            if(isset($post_meta["_job_expires"][0])){
                $stoppublishing = $post_meta["_job_expires"][0];
            }
            if(!$stoppublishing){
                $stoppublishing = gmdate('Y-m-d H:i:s',strtotime(" +30 days"));
            }
            //echo "<br>ex: ".$stoppublishing;
            $featured = $hits = $job_salary = 0;
            $salary_currency = $address = "";
            if(isset($post_meta["_wpjms_visits_total"][0])) $hits = $post_meta["_wpjms_visits_total"][0];

            // salary for the job
            $salary_array = $this->parseJobManagerSalaryData($job->ID);

            // Extract and assign values
            $job_salary_currency = isset($salary_array['currency']) ? $salary_array['currency'] : '';
            $job_salary_duration_type = isset($salary_array['type']) ? $salary_array['type'] : '';
            $job_salary_min = isset($salary_array['min']) ? floatval($salary_array['min']) : null;
            $job_salary_max = isset($salary_array['max']) ? floatval($salary_array['max']) : null;
            $salaryfixed = '';

            $job_salary_duration = $this->getSalaryDuration($job_salary_duration_type);

            // Determine range type
            if (!is_null($job_salary_min) && !is_null($job_salary_max)) {
                $salaryrangetype = 3; // min and max
            } elseif (!is_null($job_salary_min) && is_null($job_salary_max)) {
                $salaryrangetype = 2; // only one of min (to hanlde fixed salary case)
                $salaryfixed = $job_salary_min; // set fixed salary variable
                $job_salary_min = null; // un set min varioables
            } else {
                $salaryrangetype = 1; // neither set
            }


            if(isset($post_meta["_job_location"][0])){
                $city = $post_meta["_job_location"][0];
                $city_arr = explode(",",$city);
                if(count($city_arr) > 1){
                    $cityid = $this->getCityId($city);
                    //if(!$cityid) $address = $city; // if no city case
                }else{
                    //$address = $city; // if no city case
                }
            }

            // custom fields handing
            $jobparams = $this->getParamsForCustomFields($this->job_manager_job_custom_fields,$post_meta);

            // tags are still being fetched by query
            // possible issue

            $job_manager_tags = array();
            if(in_array('tag', wpjobportal::$_active_addons)){
                $query = "SELECT taxonomy.*, relationships.*, terms.*
                            FROM `" . wpjobportal::$_db->prefix . "term_taxonomy` AS taxonomy
                            JOIN `" . wpjobportal::$_db->prefix . "term_relationships` AS relationships ON relationships.term_taxonomy_id = taxonomy.term_taxonomy_id
                            JOIN `" . wpjobportal::$_db->prefix . "terms` AS terms ON terms.term_id = taxonomy.term_id
                            WHERE relationships.object_id = ".$job->ID.";";
                $taxonomy = wpjobportal::$_db->get_results($query);
                $job_manager_job_type = "";
                $job_manager_categories = array();
                foreach($taxonomy as $tax){
                    if($tax->taxonomy == "job_listing_tag"){
                        $job_manager_tags[] = $tax->name;
                    }
                }
            }

            $categories = get_the_terms( $job->ID, 'job_listing_category' );

            if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
                foreach ( $categories as $category ) {
                    $job_manager_categories[] = $category->name;
                }
            }

            $jobtypes = get_the_terms( $job->ID, 'job_listing_type' );
            if ( ! is_wp_error( $jobtypes ) && ! empty( $jobtypes ) ) {
                foreach ( $jobtypes as $jobtype ) {
                    $job_manager_job_type = $jobtype->name;
                }
            }

            $jobtype = "";
            if($job_manager_job_type){
                $jobtype = $this->getJobTypeByTitle($job_manager_job_type);
            }

            $jobcategory = "";
            if(isset($job_manager_categories[0])){
                $jobcategory = $this->getJobCategoriesByTitle($job_manager_categories[0]);
            }
            //echo var_dump($jobcategory);

            $tags = "";
            if(!empty($job_manager_tags)){
                $tags = $this->getJobTagsByTitle($job_manager_tags);
            }

            $alias = wpjobportal::$_common->stringToAlias($job->post_title);

            $jobid = WPJOBPORTALincluder::getJSModel('job')->getJobId();
            if(!$stoppublishing){
                $expiry = "2 years";
                $curdate = date_i18n('Y-m-d');
                $stoppublishing = gmdate('Y-m-d H:i:s',strtotime($curdate.'+'.$expiry));
            }

            $uid = $this->getUserIDFromAuthorID($job->post_author);

            $end_featured_date = '';
            if(in_array('featuredjob', wpjobportal::$_active_addons)){
                if(isset($post_meta["_featured"][0])) $featured = $post_meta["_featured"][0];
                if($featured == 1){
                    $end_featured_date = gmdate('Y-m-d H:i:s',strtotime(" +30 days"));
                }
            }

            $data = [
                "id" => '',
                "uid" => $uid,
                "companyid" => $companyid,
                "title" => $job->post_title,
                "alias" => $alias,
                "jobcategory" => $jobcategory,
                "jobtype" => $jobtype,
                "jobstatus" => '1',
                "hidesalaryrange" => '',
                "description" => $job->post_content,
                "qualifications" => '',
                "prefferdskills" => '',
                "applyinfo" => '',
                "company" => '',
                "city" => '',
                "address1" => '',
                "address2" => '',
                "companyurl" => '',
                "contactname" => '',
                "contactphone" => '',
                "contactemail" => '',
                "showcontact" => '',
                "noofjobs" => '1',
                "reference" => '',
                "duration" => '',
                "heighestfinisheducation" => '',
                "created" => $job->post_date,
                "created_by" => $job->post_author,
                "modified" => $job->post_modified,
                "modified_by" => '',
                "hits" => $hits,
                "experience" => '',
                "startpublishing" => $job->post_date,
                "stoppublishing" => $stoppublishing,
                "departmentid" => '',
                "sendemail" => '',
                "metadescription" => '',
                "metakeywords" => '',
                "ordering" => '',
                "aboutjobfile" => '',
                "status" => '1',
                "degreetitle" => '',
                "careerlevel" => '',
                "educationid" => '',
                "map" => '',
                "salarytype" => $salaryrangetype,
                "salaryfixed" => $salaryfixed,
                "salarymin" => $job_salary_min,
                "salarymax" => $job_salary_max,
                "salaryduration" => $job_salary_duration,
                "subcategoryid" => '',
                "currency" => $job_salary_currency,
                "jobid" => $jobid,
                "longitude" => '',
                "latitude" => '',
                "raf_degreelevel" => '',
                "raf_education" => '',
                "raf_category" => '',
                "raf_subcategory" => '',
                "raf_location" => '',
                "isfeaturedjob" => $featured,
                "serverstatus" => '',
                "serverid" => '',
                "joblink" => '',
                "jobapplylink" => '',
                "tags" => $tags,
                "params" => $jobparams,
                "userpackageid" => '',
                "price" => '',
                // log error
                "startfeatureddate" => '',
                "endfeatureddate" => $end_featured_date,
            ];
            $row = WPJOBPORTALincluder::getJSTable('job');

            if(!($row->bind($data) && $row->check() && $row->store())){
                $this->job_manager_import_count['job']['failed'] += 1;
                continue; // move on to next post if store fialed
            }else{
                // if no error then
                $this->job_manager_job_ids[] = $job->ID; // create an array of job ids to store
                $this->job_manager_import_count['job']['imported'] += 1;
            }

            $jobid = $row->id;

            if($cityid){
                $data = [
                    "id" => '',
                    "jobid" => $jobid,
                    "cityid" => $cityid
                ];
                $row = WPJOBPORTALincluder::getJSTable('jobcities');
                if (!$row->bind($data)) {
                    //$error[] = wpjobportal::$_db->last_error;
                }
                if (!$row->store()) {
                    //$error[] = wpjobportal::$_db->last_error;
                    echo "<br> error job city store----------";
                }

            }
        }
        //print_r($jobs);
        if(!empty($this->job_manager_job_ids)){
            update_option('job_portal_jm_data_jobs', wp_json_encode($this->job_manager_job_ids) );
        }
    }

    function getUserIDFromAuthorID($wordpres_uid){
        if(!is_numeric($wordpres_uid)){
            return 0;
        }
        if(isset($this->job_manager_users_array[$wordpres_uid])){
            $uid = $this->job_manager_users_array[$wordpres_uid];
        }else{
            $uid = WPJOBPORTALincluder::getJSModel('user')->getUserIDByWPUid($wordpres_uid);
        }

        // to handle edge (error) case
        if(!is_numeric($uid)){
            $uid = 0;
        }
        return $uid;
    }

    function createJPCompany($company){
        $uid = $this->getUserIDFromAuthorID($company->post_author);
        $post_meta = get_post_meta($company->ID);
        if(isset($post_meta["_company_name"][0])){
            $name = $post_meta["_company_name"][0];
            $query = "SELECT company.id
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                        WHERE LOWER(company.name) = '".strtolower($name)."'
                        AND company.uid = ".$uid.";";
                        //echo "<br>".$query;
            $jpcompany = wpjobportaldb::get_row($query);
            if(!empty($jpcompany)){
                return $jpcompany->id;
            }else{
                echo "<br>";
                print_r($company);
                print_r($post_meta);
                if($post_meta["_company_website"][0]) $website = $post_meta["_company_website"][0]; else $website = "";
                if($post_meta["_company_twitter"][0]) $twitter = $post_meta["_company_twitter"][0]; else $twitter = "";

                $alias = wpjobportal::$_common->stringToAlias($name);

                $companyparams = $this->getParamsForCustomFields($this->job_manager_company_custom_fields,$post_meta);

                $data = [
                    "id" => "",
                    "uid" => $uid,
                    "name" => $name,
                    "alias" => $alias,
                    "url" => $website,
                    "logofilename" => "",
                    "logoisfile" => "",
                    "logo" => "",
                    "smalllogofilename" => "",
                    "smalllogoisfile" => "",
                    "smalllogo" => "",
                    "contactemail" => "",
                    "description" => "",
                    "city" => "",
                    "address1" => "",
                    "address2" => "",
                    "created" => $company->post_date,
                    "price" => "",
                    "modified" => $company->post_modified,
                    "hits" => "",
                    "tagline" => "",
                    "status" => "1",
                    "isfeaturedcompany" => "",
                    "startfeatureddate" => "",
                    "endfeatureddate" => "",
                    "serverstatus" => "",
                    "userpackageid" => "",
                    "serverid" => "",
                    "params" => "",
                    "twiter_link" => $twitter,
                    "linkedin_link" => "",
                    "youtube_link" => "",
                    "params" => $companyparams,
                    "facebook_link" => ""
                ];
                $row = WPJOBPORTALincluder::getJSTable('company');
                // print_r($data);

                if(!($row->bind($data) && $row->check() && $row->store())){
                    $this->job_manager_import_count['company']['failed'] += 1;
                }else{
                    // if no error then
                    $this->job_manager_company_ids[] = $company->ID; // create an array of company ids to store
                    $this->job_manager_import_count['company']['imported'] += 1;
                }
            }
        }
    }

    function getCompanyIdByJobManagerId($post_id){
        $query = "SELECT post.*
                    FROM `" . wpjobportal::$_db->prefix . "posts` AS post
                    WHERE post.post_type = 'company_listings'
                    AND post.id = ".$post_id.";";
                    //echo "<br>".$query;


        $jmcompany = wpjobportaldb::get_row($query);
        if($jmcompany){
            $query = "SELECT company.id
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                        WHERE LOWER(company.name) = '".strtolower($jmcompany->post_title)."'
                        AND company.uid = ".$jmcompany->post_author.";";
                        //echo "<br>".$query;
            $jpcompany = wpjobportaldb::get_row($query);
            if($jpcompany)
                return $jpcompany->id;
        }
        return;
    }

    function importResume(){

        // check if resume already processed for import
        $imported_resumes = array();
        $imported_resumes_json = get_option('job_portal_jm_data_resumes');
        if(!empty($imported_resumes_json)){
            $imported_resumes = json_decode($imported_resumes_json,true);
        }

        $resumes = get_posts( array(
                'post_type'      => 'resume',
                'post_status'    => array_diff( get_post_stati(), array( 'auto-draft' ) ),
                'numberposts'    => -1, // get all
                'orderby'        => 'ID',
                'order'          => 'ASC',
            ) );
        foreach($resumes as $resume){
            // check already imported
            if(!empty( $imported_resumes ) && in_array($resume->ID, $imported_resumes) ){ // if id already in array skip it
                $this->job_manager_import_count['resume']['skipped'] += 1;
                continue;
            }

            $post_meta = get_post_meta($resume->ID);

            $featured = 0;
            if($post_meta["_candidate_title"][0]) $candidate_title = $post_meta["_candidate_title"][0]; else $candidate_title = "";
            if(!empty($post_meta["_candidate_photo"][0])) $candidate_photo_url = $post_meta["_candidate_photo"][0]; else $candidate_photo_url = "";
            if($post_meta["_candidate_email"][0]) $candidate_email = $post_meta["_candidate_email"][0]; else $candidate_email = "";
            if($post_meta["_candidate_location"][0]) $candidate_location = $post_meta["_candidate_location"][0]; else $candidate_location = "";

            if(in_array('featureresume', wpjobportal::$_active_addons)){
                if(isset($post_meta["_featured"][0])) $featured = $post_meta["_featured"][0];
                $end_featured_date = '';
                if($featured == 1){
                    $end_featured_date = gmdate('Y-m-d H:i:s',strtotime(" +30 days"));
                }
            }


            $candidate_photo = "";
            if($candidate_photo_url != ''){
                $candidate_photo = basename($candidate_photo_url);
            }

            // if name not set use post title // first_name system fielf (required)
            if(!empty($post_meta["_candidate_name"][0])) $candidate_name = $post_meta["_candidate_name"][0]; else $candidate_name = $resume->post_title;

            // skills field
            if(!empty($post_meta["_resume_skills"][0])) $candidate_skill = $post_meta["_resume_skills"][0]; else $candidate_skill = $resume->post_title;

            // job_cateogry column resume
            $categories = get_the_terms( $resume->ID, 'resume_category' );

            if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
                foreach ( $categories as $category ) {
                    $job_manager_categories[] = $category->name;
                }
            }
            $resumecategory = "";
            if(isset($job_manager_categories[0])){
                $resumecategory = $this->getJobCategoriesByTitle($job_manager_categories[0]);
            }

            $resumeparams = $this->getParamsForCustomFields($this->job_manager_resume_custom_fields,$post_meta);

            $uid = $this->getUserIDFromAuthorID($resume->post_author);

            if($candidate_title != ''){ // use possible application title value for alias
                $alias = wpjobportal::$_common->stringToAlias($candidate_title);
            }else{
                $alias = wpjobportal::$_common->stringToAlias($resume->post_title);
            }

            if(!empty($post_meta["_resume_file"][0])) $resume_file = $post_meta["_resume_file"][0]; else $resume_file = "";

            // unused
            //$resume->post_content

            $data = [
                "id" => "",
                "uid" => $uid,
                "application_title" => $candidate_title,
                "alias" => $alias,
                "first_name" => $candidate_name,
                "last_name" => "",
                "email_address" => $candidate_email,
                "searchable" => "1",
                "photo" => $candidate_photo,
                "status" => "1",
                "resume" => "",
                "skills" => $candidate_skill,
                "isfeaturedresume" => $featured,
                "created" => $resume->post_date,
                "last_modified" => $resume->post_modified,
                "published" => "1",
                "job_category" => $resumecategory,
                "params" => $resumeparams
            ];
            $row = WPJOBPORTALincluder::getJSTable('resume');
            print_r($data);
            if(!($row->bind($data) && $row->check() && $row->store())){
                $this->job_manager_import_count['resume']['failed'] += 1;
                continue; // move on to next post if store fialed
            }else{
                // if no error then
                $this->job_manager_resume_ids[] = $resume->ID; // create an array of resume ids to store
                $this->job_manager_import_count['resume']['imported'] += 1;
            }
            $resumeid = $row->id;
            if($candidate_photo_url != ''){ // if photo exisits
                $this->handleUploadFile(3, $resumeid, $candidate_photo_url); // move the file to wpjobportal uploads
            }
            if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                //resume file
                if($resume_file !=''){
                    $resume_file_size = '';
                    $resume_file_type = '';
                    $filename = basename($resume_file);
                    $upload_dir = wp_get_upload_dir();
                    $file_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $resume_file );
                    if ( file_exists( $file_path ) ) {
                        $file_type_array = wp_check_filetype( $file_path );
                        $resume_file_type = $file_type_array['type'];
                        $resume_file_size = filesize( $file_path );
                    }
                    // store resume file record
                    $row = WPJOBPORTALincluder::getJSTable('resumefile');
                    $cols = array();
                    $cols['resumeid'] = $resumeid;
                    $cols['filename'] = $filename;
                    $cols['filetype'] = $resume_file_type;
                    $cols['filesize'] = $resume_file_size;
                    $cols['created'] = $resume->post_date;
                    $cols = wpjobportal::wpjobportal_sanitizeData($cols);
                    print_r($cols);
                    if($row->bind($cols) && $row->store()) { // if record inserted in table
                        $this->handleUploadFile(4, $resumeid, $resume_file); // move the file to wpjobportal uploads
                    }
                }

                // address section
                $address = '';
                if($candidate_location){
                    $row = WPJOBPORTALincluder::getJSTable('resumeaddresses');
                    $city_arr = explode(",",$candidate_location);
                    if(count($city_arr) > 1){
                        $cityid = $this->getCityId($candidate_location);
                        if(!$cityid) $address = $candidate_location;
                    }else{
                        $address = $candidate_location;
                    }
                    $data = [
                        "id" => "",
                        "resumeid" => $resumeid,
                        "address" => $address,
                        "address_city" => $cityid,
                        "created" => $resume->post_date
                    ];
                    if(!($row->bind($data) && $row->check() && $row->store())){
                        echo "<br> error --- ";
                        return false;
                    }
                    print_r($data);
                }
                // education section
                if($post_meta["_candidate_education"][0]) {
                    $educations = unserialize($post_meta["_candidate_education"][0]);
                    $row = WPJOBPORTALincluder::getJSTable('resumeinstitutes');
                    foreach($educations as $education){
                        $data = [
                            "id" => "",
                            "resumeid" => $resumeid,
                            "institute" => $education["location"],
                            "institute_certificate_name" => $education["qualification"],
                            "institute_study_area" => $education["date"]."\n".$education["notes"],
                            "fromdate" => "",
                            "todate" => "",
                            "created" => $resume->post_date,
                        ];
                        print_r($data);
                        if(!($row->bind($data) && $row->check() && $row->store())){
                            echo "<br> error --- ";
                            return false;
                        }
                    }
                    //print_r($educations);
                }
                // employer section
                if($post_meta["_candidate_experience"][0]) {
                    $experiences = unserialize($post_meta["_candidate_experience"][0]);
                    $row = WPJOBPORTALincluder::getJSTable('resumeemployers');
                    foreach($experiences as $experience){
                        $data = [
                            "id" => "",
                            "resumeid" => $resumeid,
                            "employer" => $experience["employer"],
                            "employer_position" => $experience["job_title"],
                            "employer_address" => $experience["date"]."\n".$experience["notes"],
                            "fromdate" => "",
                            "todate" => "",
                            "created" => $resume->post_date,
                        ];
                        if(!($row->bind($data) && $row->check() && $row->store())){
                            echo "<br> error --- ";
                            return false;
                        }
                        print_r($data);
                    }
                    print_r($experiences);
                }
            }
        }
        //print_r($companies);
        if(!empty($this->job_manager_resume_ids)){
            update_option('job_portal_jm_data_resumes', wp_json_encode($this->job_manager_resume_ids) );
        }
    }

    function importJobApplied(){
        $job_applications = get_posts( [
            'post_type'      => 'job_application',
            'post_status'    => 'any', // includes all except 'auto-draft'
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'numberposts'    => -1, // get all
            'exclude'        => get_posts([
                'post_type'   => 'job_application',
                'post_status'=> 'auto-draft',
                'fields'      => 'ids',
            ]),
        ] );

        // check if resume already processed for import
        $imported_resumes = array();
        $imported_resumes_json = get_option('job_portal_jm_data_resumes');
        if(!empty($imported_resumes_json)){
            $imported_resumes = json_decode($imported_resumes_json,true);
        }
        foreach($job_applications as $job_application){ // store all job applies
            //echo '<pre>';print_r($job_application);echo '</pre>';
            $post_meta = get_post_meta($job_application->ID);
            //echo '<pre>';print_r($post_meta);echo '</pre>';
            //die("asd 2730");
            if($job_application->post_parent){
                $jobid = $this->getJobPortalJobIdByPost($job_application->post_parent);
                $uid = $this->getUserIDFromAuthorID($job_application->post_author);
                if($jobid){
                    /// check already imported
                    if(!empty( $imported_resumes ) && in_array($job_application->ID, $imported_resumes) ){ // if id already in array skip it
                        $this->job_manager_import_count['jobapply']['skipped'] += 1;
                        continue;
                    }
                    $resume_file = "";
                    if($post_meta["_job_applied_for"][0]) $job_applied_for = $post_meta["_job_applied_for"][0]; else $job_applied_for = "";
                    if($post_meta["_candidate_email"][0]) $candidate_email = $post_meta["_candidate_email"][0]; else $candidate_email = "";
                    if($post_meta["Message"][0]) $message = $post_meta["Message"][0]; else $message = "";
                    if($post_meta["Full name"][0]) $full_name = $post_meta["Full name"][0]; else $full_name = "";
                    $filename = '';
                    if($post_meta["_attachment_file"][0]){
                        $attachment_file = unserialize($post_meta["_attachment_file"][0]);
                        $resume_file = $attachment_file[0];
                    }
                    $alias = wpjobportal::$_common->stringToAlias($full_name);

                    $data = [
                        "id" => "",
                        "uid" => $uid,
                        "first_name" => $full_name,
                        "email_address" => $candidate_email,
                        "alias" => $alias,
                        "status" => "1",
                        "quick_apply" => "1",
                        "last_modified" => $job_application->post_date,
                        "created" => $job_application->post_date
                    ];
                    $row = WPJOBPORTALincluder::getJSTable('resume');
                    if(!($row->bind($data) && $row->check() && $row->store())){
                        $this->job_manager_import_count['resume']['failed'] += 1;
                        continue; // move on to next post if store fialed
                    }else{
                        // if no error then
                        $this->job_manager_resume_ids[] = $job_application->ID; // create an array of resume ids to store
                        $this->job_manager_import_count['resume']['imported'] += 1;
                    }
                    $resumeid = $row->id;

                    // handle resume file if any
                    if($resume_file !=''){
                        $resume_file_size = '';
                        $resume_file_type = '';
                        $filename = basename($resume_file);
                        $upload_dir = wp_get_upload_dir();
                        $file_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $resume_file );
                        if ( file_exists( $file_path ) ) {
                            $file_type_array = wp_check_filetype( $file_path );
                            $resume_file_type = $file_type_array['type'];
                            $resume_file_size = filesize( $file_path );
                        }
                        // store resume file record
                        $row = WPJOBPORTALincluder::getJSTable('resumefile');
                        $cols = array();
                        $cols['resumeid'] = $resumeid;
                        $cols['filename'] = $filename;
                        $cols['filetype'] = $resume_file_type;
                        $cols['filesize'] = $resume_file_size;
                        $cols['created'] = $job_application->post_date;
                        $cols = wpjobportal::wpjobportal_sanitizeData($cols);
                        print_r($cols);
                        if($row->bind($cols) && $row->store()) { // if record inserted in table
                            $this->handleUploadFile(4, $resumeid, $resume_file); // move the file to wpjobportal uploads
                        }
                    }

                    $data = [
                        "id" => "",
                        "jobid" => $jobid,
                        "cvid" => $resumeid,
                        "apply_date" => $job_application->post_date,
                        "action_status" => "1",
                        "status" => "1",
                        "quick_apply" => "1",
                        "apply_message" => $message,
                        "params" => "",
                        "created" => $job_application->post_date
                    ];
                    print_r($data);
                    $row = WPJOBPORTALincluder::getJSTable('jobapply');
                    if(!($row->bind($data) && $row->check() && $row->store())){
                        $this->job_manager_import_count['jobapply']['failed'] += 1;
                        continue; // move on to next post if store fialed
                    }else{
                        // if no error then
                        $this->job_manager_jobapply_ids[] = $job_application->ID; // create an array of jobapply ids to store
                        $this->job_manager_import_count['jobapply']['imported'] += 1;
                    }
                }
            }
        }
        if(!empty($this->job_manager_resume_ids)){
            update_option('job_portal_jm_data_resumes', wp_json_encode($this->job_manager_resume_ids) );
        }
    }

    function getJobPortalJobIdByPost($postid){
        $job = get_post( $postid );

        if(!empty($job)){
            $uid = $this->getUserIDFromAuthorID($job->post_author);
            // if($uid == 0){
            //     return false;
            // }
            $query = "SELECT job.id
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                        WHERE job.uid = ".$uid."
                        AND LOWER(job.title) ='".strtolower($job->post_title)."';";
            $jpjob_job_id = wpjobportaldb::get_var($query);
            if(is_numeric($jpjob_job_id) && $jpjob_job_id > 0){
                return $jpjob_job_id;
            }
        }
        return false;
    }

    function getCityId($city){
        $city_name = $state_name = $country_name = "";
        $city_arr = explode(",",$city);
        $city_name = $city_arr[0];
        if(count($city_arr) == 2){
            $country_name = $city_arr[1];
        }
        if(count($city_arr) == 3){
            $state_name = $city_arr[1];
            $country_name = $city_arr[2];
        }
        if($country_name){
            $query = "SELECT country.id
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country
                        WHERE LOWER(country.name) = '".strtolower(trim($country_name))."' OR LOWER(country.shortCountry) = '".strtolower($country_name)."';";
                        //echo "<br>".$query;
            $jpcountry = wpjobportaldb::get_row($query);
            if($jpcountry){
                if($state_name){
                    $query = "SELECT state.id
                                FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state
                                WHERE LOWER(state.name) = '".strtolower(trim($state_name))."' OR LOWER(state.shortRegion) = '".strtolower($state_name)."'
                                AND state.countryid = ".$jpcountry->id.";";
                                //echo "<br>".$query;
                    $jpstate = wpjobportaldb::get_row($query);
                    if($jpstate){
                        $query = "SELECT city.id
                                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                                    WHERE (LOWER(city.name) = '".strtolower(trim($city_name))."' OR LOWER(city.localname) = '".strtolower(trim($city_name))."' OR LOWER(city.internationalname) = '".strtolower(trim($city_name))."')
                                    AND city.countryid = ".$jpcountry->id."
                                    AND city.stateid = ".$jpstate->id." ;";
                                    //echo "<br>".$query;
                        $jpcity = wpjobportaldb::get_row($query);
                        if($jpcity)
                            return $jpcity->id;
                    }else{
                        $query = "SELECT city.id
                                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                                    WHERE (LOWER(city.name) = '".strtolower(trim($city_name))."' OR LOWER(city.localname) = '".strtolower(trim($city_name))."' OR LOWER(city.internationalname) = '".strtolower(trim($city_name))."')
                                    AND city.countryid = ".$jpcountry->id.";";
                                    //echo "<br>".$query;
                        $jpcity = wpjobportaldb::get_row($query);
                        if($jpcity)
                            return $jpcity->id;
                    }
                }else{
                        $query = "SELECT city.id
                                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                                    WHERE (LOWER(city.name) = '".strtolower(trim($city_name))."' OR LOWER(city.localname) = '".strtolower(trim($city_name))."' OR LOWER(city.internationalname) = '".strtolower(trim($city_name))."')
                                    AND city.countryid = ".$jpcountry->id.";";
                                    //echo "<br>".$query;
                        $jpcity = wpjobportaldb::get_row($query);
                        if($jpcity)
                            return $jpcity->id;
                }
            }
        }
        return;

    }
    function getSalaryDuration($duration){
        if($duration == ''){
            return '';
        }
        $title = "Per ".$duration;
        $query = "SELECT type.id
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS type
                    WHERE LOWER(type.title) = '".strtolower($title)."';";
                    //echo "<br>".$query;
        $jptitle = wpjobportaldb::get_row($query);
        if($jptitle){
            return $jptitle->id;
        }else{

            $query = "SELECT MAX(sal_type.ordering)
                  FROM `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS sal_type";
                $ordering = (int) wpjobportal::$_db->get_var($query);

            $data = [
                "id" => "",
                "title" => $title,
                "status" => "1",
                "isdefault" => "",
                "ordering" => ++$ordering
            ];
            $row = WPJOBPORTALincluder::getJSTable('salaryrangetype');

            if(!($row->bind($data) && $row->check() && $row->store())){
                return false;
            }else{
                return $row->id;
            }

        }

    }

    function getJobTypeByTitle($title) {
        $job_type = '';
        // Fetch all job types
        $query = "SELECT jobtype.id, jobtype.title
                  FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype";
        $jpjobtypes = wpjobportal::$_db->get_results($query);

        $jp_job_types_array = [];

        // make array for comparison
        foreach ($jpjobtypes as $jobtype) {
            $jp_job_types_array[$this->cleanStringForCompare($jobtype->title)] = $jobtype->id;
        }

        $compare_name = $this->cleanStringForCompare($title);

        // Check if the cleaned title exists as a key in the array and retrieve the corresponding ID
        if (isset($jp_job_types_array[$compare_name])) {
            $job_type = $jp_job_types_array[$compare_name];
        }

        return $job_type;
    }

    function getJobCategoriesByTitle($title) {
        // Fetch all categories
        $query = "SELECT category.id, category.cat_title
                  FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category";
        $categories = wpjobportal::$_db->get_results($query);

        $jp_category_names_array = [];

        foreach ($categories as $category) {
            $jp_category_names_array[$this->cleanStringForCompare($category->cat_title)] = $category->id;
        }

        $compare_name = $this->cleanStringForCompare($title);

        // Check if the cleaned category title exists as a
        if (isset($jp_category_names_array[$compare_name])) {
            return $jp_category_names_array[$compare_name];
        }
        return '';
    }

    function getJobTagsByTitle($tags){
        $tags_title = "";
        foreach($tags as $tag){
            $query = "SELECT tag.tag
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_tags` AS tag
                        WHERE LOWER(tag.tag) = '".strtolower($tag)."';";
                        //echo "<br>".$query;
            $jptags = wpjobportaldb::get_row($query);
            if($jptags){
                if($tags_title) $tags_title = $tags_title.", ".$jptags->tag;
                else $tags_title = $jptags->tag;
            }
        }
        return $tags_title;
    }

    function importCategories() {
        $job_categories = get_terms([
            'taxonomy'   => 'job_listing_category',
            'hide_empty' => false,
        ]);

        $resume_categories = get_terms([
            'taxonomy'   => 'resume_category',
            'hide_empty' => false,
        ]);

        if(is_array($resume_categories)){
            $categories = array_merge($job_categories, $resume_categories);
        }else{
            $categories = $job_categories;
        }

        // Get max ordering from existing categories table
        $query = "SELECT MAX(category.ordering)
                  FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category";
        $ordering = (int) wpjobportal::$_db->get_var($query);

        $jp_category_names = [];

        // Fetch all existing category names
        $query = "SELECT id,cat_title FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories`";
        $existing_categories = wpjobportal::$_db->get_results($query);

        foreach ($existing_categories as $existing_category) {
            // sanitized for comparison
            $jp_category_names[$existing_category->id] = $this->cleanStringForCompare($existing_category->cat_title);
        }

        if (!is_wp_error($categories) && !empty($categories)) {
            foreach ($categories as $category) {
                $parent_category_id = '';
                $name = $category->name;
                $compare_name = $this->cleanStringForCompare($name);

                if (!empty($jp_category_names) && in_array($compare_name, $jp_category_names)) {
                    $this->job_manager_import_count['category']['skipped'] += 1;
                    continue;
                }

                $alias = wpjobportal::$_common->stringToAlias($name);

                // Handle parent ID lookup using WP functions
                if ($category->parent) {
                    $parent_term = get_term($category->parent);
                    if ($parent_term && !is_wp_error($parent_term)) {
                        $parent_compare_name = $this->cleanStringForCompare($parent_term->name);

                        $parent_id = array_search($parent_compare_name, $jp_category_names);
                        if ($parent_id !== false) {
                            // Parent category found, assign the id
                            $parent_category_id = $parent_id;
                        }
                    }
                }

                $row = WPJOBPORTALincluder::getJSTable('categories');
                $updated = date_i18n('Y-m-d H:i:s');
                $created = date_i18n('Y-m-d H:i:s');

                $data = [];
                $data['id']         = '';
                $data['cat_value']  = '';
                $data['cat_title']  = $name;
                $data['alias']      = $alias;
                $data['isactive']   = '1';
                $data['isdefault']  = '0';
                $data['ordering']   = $ordering;
                $data['parentid']   = $parent_category_id;

                if (!($row->bind($data) && $row->check() && $row->store())) {
                    $this->job_manager_import_count['category']['failed'] += 1;
                    continue;
                } else {
                    $this->job_manager_category_ids[] = $category->term_id;
                    $this->job_manager_import_count['category']['imported'] += 1;
                }
                $ordering++;
            }
        }
    }

    function cleanStringForCompare($string){
        if($string == ''){
            return $string;
        }
        // already null checked so no need for         wpjobportalphplib::wpJP_ functions
        $string = str_replace(' ', '', $string);
        $string = str_replace('-', '', $string);
        $string = str_replace('_', '', $string);
        $string = trim($string);
        $string = strtolower($string);
        return $string;
    }

    function importJobTypes(){
        $jobtypes = get_terms( [
                'taxonomy'   => 'job_listing_type',
                'hide_empty' => false, // set to true if you only want terms with posts
            ] );
        // max ordering from table
        $query = "SELECT MAX(jobtype.ordering)
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ";
        $ordering = (int) wpjobportal::$_db->get_var($query);
        // $ordering = 25;

        $jp_job_types_array = [];
        if ( ! is_wp_error( $jobtypes ) && ! empty( $jobtypes ) ) {

            // to compare job portal job types with wp job manager job types
            $query = "SELECT jobtype.id, jobtype.title
                            FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ";
            $jpjobtypes = wpjobportal::$_db->get_results($query);
            foreach ($jpjobtypes as $jobtype) {
                $jp_job_types_array[] = $this->cleanStringForCompare($jobtype->title);
            }

            // colors for new job types
            $colors = ['#3E4095','#ED3237','#EC268F','#A8518A','#F58634','#84716B','#48887B','#6E4D8B'];
            $colorIndex = 0;

            foreach($jobtypes AS $jobtype){
                $parent_category_id = "";
                $name = $jobtype->name;
                $compare_name = $this->cleanStringForCompare($name);

                if(!empty($jp_job_types_array) && in_array($compare_name, $jp_job_types_array) ){ // try and match job type title
                    $this->job_manager_import_count['jobtype']['skipped'] += 1;
                    continue; // ignore current job type if it mathces
                }
                $alias = wpjobportal::$_common->stringToAlias($name);

                $row = WPJOBPORTALincluder::getJSTable('jobtype');
                $updated = date_i18n('Y-m-d H:i:s');
                $created = date_i18n('Y-m-d H:i:s');

                $data = []; // reset object
                $data['id'] = '';
                $data['title'] = $name;
                // handle tangent ccase
                if($colorIndex > 7){
                    $colorIndex = 0;
                }
                $data['color'] = $colors[$colorIndex];
                $colorIndex++;
                $data['alias'] = $alias;
                $data['isactive'] = "1";
                $data['isdefault'] = '0';
                $data['ordering'] = $ordering;
                $data['status'] = "1";

                if(!($row->bind($data) && $row->check() && $row->store())){
                    $this->job_manager_import_count['jobtype']['failed'] += 1;
                    continue; // move on to next post if store fialed
                }else{
                    // if no error then
                    //$this->job_manager_jobtype_ids[] = $jobtype->id; // create an array of job type ids to store
                    $this->job_manager_import_count['jobtype']['imported'] += 1;
                }
                $ordering = $ordering + 1;
            }
        }
    }

    function importTags(){
        // problem case
        $query = "SELECT taxonomy.*, terms.*
                    FROM `" . wpjobportal::$_db->prefix . "term_taxonomy` AS taxonomy
                    JOIN `" . wpjobportal::$_db->prefix . "terms` AS terms ON terms.term_id = taxonomy.term_id
                    WHERE taxonomy.taxonomy = 'job_listing_tag';";
        $tags = wpjobportal::$_db->get_results($query);

        if($tags){
            foreach($tags AS $tag){
                $name = $tag->name;

                $query = "SELECT tag.*
                            FROM `" . wpjobportal::$_db->prefix . "wj_portal_tags` AS tag
                            WHERE LOWER(tag.tag) = '".strtolower($name)."'";
                $jptag = wpjobportal::$_db->get_row($query);

                if(!$jptag){ // not exists
                    $alias = wpjobportal::$_common->stringToAlias($name);

                    $row = WPJOBPORTALincluder::getJSTable('tag');
                    $updated = date_i18n('Y-m-d H:i:s');
                    $created = date_i18n('Y-m-d H:i:s');

                    $data['id'] = '';
                    $data['tag'] = $name;
                    $data['alias'] = $alias;
                    $data['tagfor'] = "1";
                    $data['status'] = "1";
                    $data['created'] = $created;
                    $data['createdby'] = "";
                    //print_r($data);

                    if(!($row->bind($data) && $row->check() && $row->store())){
                        $this->job_manager_import_count['tag']['failed'] += 1;
                        continue; // move on to next post if store fialed
                    }else{
                        // if no error then
                        $this->job_manager_import_count['tag']['imported'] += 1;
                    }
                }else{ // if record matched then ignore
                    $this->job_manager_import_count['tag']['skipped'] += 1;
                }
            }
        }
    }

    function importJobFields(){
        $all_custom_fields = get_option("_transient_jmfe_fields_custom"); // job custom fields
        //if(isset($custom_fields["job"]))$custom_fields_job = $custom_fields["job"];
        //print_r($all_custom_fields);
        //die();

        if(empty($all_custom_fields)){ // to handle error
            return;
        }

        //print_r($custom_fields_job);
        foreach($all_custom_fields as $key=>$value){
            foreach($value AS $custom_field){
                switch ($custom_field["type"]){
                    case "text":
                        $fieldtype = "text"; break;
                    case "select":
                        $fieldtype = "combo"; break;
                    case "radio":
                        $fieldtype = "radio"; break;
                    case "checklist":
                        $fieldtype = "checkbox"; break;
                    case "textarea":
                        $fieldtype = "textarea"; break;
                    case "number":
                        $fieldtype = "text"; break;
                    case "range":
                        $fieldtype = "text"; break;
                    case "email":
                        $fieldtype = "email"; break;
                    case "url":
                        $fieldtype = "text"; break;
                    case "tel":
                        $fieldtype = "text"; break;
                    case "wp-editor":
                        $fieldtype = "textarea"; break;
                    case "file":
                        $fieldtype = "file"; break;
                    case "date":
                        $fieldtype = "date"; break;
                    case "fpdate":
                        $fieldtype = "date"; break;
                    case "fptime":
                        $fieldtype = "date"; break;
                    case "phone":
                        $fieldtype = "text"; break;
                    case "checkbox":
                        $fieldtype = "checkbox"; break;
                    case "multiselect":
                        $fieldtype = "combo"; break;

                }
                $fieldfor = "";
                $section = "";
                if($key == "company") $fieldfor = 1;
                elseif($key == "job") $fieldfor = 2;
                elseif($key == "resume_fields"){ $fieldfor = 3; $section = 1; }
                // check if field already exsists

                $query = "SELECT id,field FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` WHERE isuserfield = 1 AND LOWER(fieldtitle) ='".esc_sql(strtolower($custom_field["label"]))."' AND userfieldtype ='".esc_sql($fieldtype)."' AND fieldfor = " . esc_sql($fieldfor);
                $field_record = wpjobportaldb::get_row($query);

                if(!empty($field_record)){ // this will make sure
                    // set field to array so that i can be used for importing entities (job,company,resume)
                    if($fieldfor == 1){
                        $this->job_manager_company_custom_fields[] = array("name" => $custom_field["meta_key"], "type" =>$fieldtype, "jp_filedorderingid" => $field_record->id, "jp_filedorderingfield" => $field_record->field);
                    }elseif($fieldfor == 2){
                        $this->job_manager_job_custom_fields[] = array("name" => $custom_field["meta_key"], "type" =>$fieldtype, "jp_filedorderingid" => $field_record->id, "jp_filedorderingfield" => $field_record->field);
                    }elseif($fieldfor == 3){
                        $this->job_manager_resume_custom_fields[] = array("name" => $custom_field["meta_key"], "type" =>$fieldtype, "jp_filedorderingid" => $field_record->id, "jp_filedorderingfield" => $field_record->field);
                    }

                    $this->job_manager_import_count['field']['skipped'] += 1;
                    continue;
                }

                $option_values = "";
                if(isset($custom_field["options"])){
                    foreach($custom_field["options"] as $opt_key => $opt_value){
                        if(empty($option_values)) $option_values = $opt_value;
                        else $option_values = $option_values ."\n ". $opt_value;
                    }
                }
                $required = 0;
                if(isset($custom_field["required"])){
                    if($custom_field["required"] == "yes") $required = 1;
                }
                    //die();
                    //echo "<br> set";
                    $fieldOrderingData = [
                        "id" =>"",
                        "field" => $custom_field["meta_key"],
                        "fieldtitle" => $custom_field["label"],
                        "placeholder" => $custom_field["placeholder"],
                        "description" => $custom_field["description"],
                        "ordering" => "",
                        "section" => $section,
                        "fieldfor" => $fieldfor,
                        "published" => "1",
                        "isvisitorpublished" => "1",
                        "sys" => "0",
                        "cannotunpublish" => "0",
                        "required" => $required,
                        "cannotsearch" => "0",
                        "search_ordering" => "",
                        "isuserfield" => "1",
                        "userfieldtype" => $fieldtype,
                        "options" => $option_values,
                        "search_user" => "0",
                        "search_visitor" => "0",
                        "showonlisting" => "0",
                        "cannotshowonlisting" => "0",
                        "depandant_field" => "",
                        "j_script" => "",
                        "size" => "",
                        "maxlength" => "255",
                        "cols" => "",
                        "rows" => "",
                        "readonly" => "",
                        "is_section_headline" => "",
                        "visible_field" => "",
                        "visibleparams" => "",
                    ];
                    //echo "<br>key: ".$key."<br>";
                    //print_r($fieldOrderingData);
                    //echo "<br>cf: ".$fieldtype;
                    //print_r($custom_field);

                    //die();
                    // WPJOBPORTAL_SAVE_ERROR


                    $record_saved =  WPJOBPORTALincluder::getJSModel('fieldordering')->storeUserField($fieldOrderingData);
                    if($record_saved == WPJOBPORTAL_SAVED){
                        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` ORDER BY id DESC LIMIT 0, 1";
                        //echo "<br> ".$query;
                        $latest_record = wpjobportal::$_db->get_row($query);
                        if($fieldfor == 1){
                            $this->job_manager_company_custom_fields[] = array("name" => $custom_field["meta_key"], "type" =>$fieldtype, "jp_filedorderingid" => $latest_record->id, "jp_filedorderingfield" => $latest_record->field);
                        }elseif($fieldfor == 2){
                            $this->job_manager_job_custom_fields[] = array("name" => $custom_field["meta_key"], "type" =>$fieldtype, "jp_filedorderingid" => $latest_record->id, "jp_filedorderingfield" => $latest_record->field);
                        }elseif($fieldfor == 3){
                            $this->job_manager_resume_custom_fields[] = array("name" => $custom_field["meta_key"], "type" =>$fieldtype, "jp_filedorderingid" => $latest_record->id, "jp_filedorderingfield" => $latest_record->field);
                        }
                        $this->job_manager_import_count['field']['imported'] += 1;
                    }else{
                        $this->job_manager_import_count['field']['failed'] += 1;
                        continue;
                    }
            }
        }
    }

    function parseJobManagerSalaryData($post_id) {
        $salary_raw = get_post_meta($post_id, '_job_salary');
        $salary_min = get_post_meta($post_id, '_job_salary_min', true);
        $salary_max = get_post_meta($post_id, '_job_salary_max', true);
        $salary_currency = get_post_meta($post_id, '_job_salary_currency', true);
        $salary_type = get_post_meta($post_id, '_job_salary_unit', true); // e.g., yearly, monthly, etc.

        // return array
        $result = array(
            'currency' => null,
            'type'     => null,
            'min'      => null,
            'max'      => null,
        );

        // Normalize and sanitize input
        $type = is_string($salary_type) ? strtolower(trim($salary_type)) : '';
        $currency_code = is_string($salary_currency) ? strtoupper(trim($salary_currency)) : '';

        $currency_map = array(
                '€' => 'EUR',
                '$' => 'USD',
                '£' => 'GBP',
                '₹' => 'INR',
                '¥' => 'JPY',
                '₽' => 'RUB',
                '₩' => 'KRW',
                'AED' => 'AED',
                'Rs' => 'PKR',
            );

        if (!empty($salary_min) || !empty($salary_max)) {
            $result['min'] = !empty($salary_min) ? $this->normalize_salary_value($salary_min) : null;
            $result['max'] = !empty($salary_max) ? $this->normalize_salary_value($salary_max) : null;
            $result['type'] = !empty($type) ? $type : null;
            $result['currency'] = !empty($currency_code) ? $currency_code : null;
            // Parse currency
            foreach ($currency_map as $symbol => $code) {
                if (strpos($currency_code, $symbol) !== false || stripos($currency_code, $code) !== false) {
                    $result['currency'] = $symbol;
                    break;
                }
            }
        } elseif (!empty($salary_raw)) {
            // Convert array to string if needed
            if (is_array($salary_raw)) {
                $flattened = [];
                foreach ($salary_raw as $item) {
                    if (is_array($item)) {
                        $flattened = array_merge($flattened, $item);
                    } else {
                        $flattened[] = $item;
                    }
                }
                $salary_raw = implode(' - ', $flattened);
            }

            // Now apply regex
            preg_match_all('/[\$€£₹¥₽₩]?\s*(\d{1,3}(?:[.,]?\d{3})*|\d+)(k)?/i', $salary_raw, $matches);

            $numbers = [];
            if (!empty($matches[1])) {
                foreach ($matches[1] as $index => $number) {
                    $value = floatval(str_replace([',', ' '], '', $number));
                    $is_k = isset($matches[2][$index]) && strtolower($matches[2][$index]) === 'k';

                    if ($is_k) {
                        $value *= 1000;
                    }

                    $numbers[] = $value;
                }
            }
            if (count($numbers) === 1) {
                $result['min'] = $numbers[0];
            } elseif (count($numbers) >= 2) {
                $result['min'] = min($numbers);
                $result['max'] = max($numbers);
            }

            //Parse currency
            foreach ($currency_map as $symbol => $code) {
                if (strpos($salary_raw, $symbol) !== false || stripos($salary_raw, $code) !== false) {
                    $result['currency'] = $symbol;
                    break;
                }
            }

            if(empty($salary_type)){
                //Parse salary type
                $raw = strtolower($salary_raw);
                if (strpos($raw, 'year') !== false || strpos($raw, 'annum') !== false) {
                    $result['type'] = 'Year';
                } elseif (strpos($raw, 'month') !== false) {
                    $result['type'] = 'Month';
                } elseif (strpos($raw, 'week') !== false) {
                    $result['type'] = 'Week';
                } elseif (strpos($raw, 'day') !== false) {
                    $result['type'] = 'Day';
                } elseif (strpos($raw, 'hour') !== false) {
                    $result['type'] = 'Hour';
                }
            }else{
                $result['type'] = is_string($salary_type) ? trim($salary_type) : '';
            }
        }
        return $result;
    }

    function getMessagekey(){
        $key = 'thirdpartyimport';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}

?>