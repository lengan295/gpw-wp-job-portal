<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALhandlesearchcookies {
    public $_jsjp_search_array;
    public $_callfrom;
    public $_setcookies;

    function __construct( ) {
        $this->_jsjp_search_array = array();
        $this->_callfrom = 3; // 3 means cookies will be reset
        $this->_setcookies = false;
        $this->init();
    }

    function init(){
        // set/remove any transients in cookies
        $this->setCookiesFromTransientData();
        $this->removeCookiesFromTransientData();

        $isadmin = wpjobportal::$_common->wpjp_isadmin();
        $jstlay = '';
        $page = WPJOBPORTALrequest::getVar('page');
        $wpjobportallt = WPJOBPORTALrequest::getVar('wpjobportallt');
        $wpjobportallay = WPJOBPORTALrequest::getVar('wpjobportallay');
        if($page != '' ){ // page is for admin case
            $jstlay = $page;
        }elseif($wpjobportallt !=''){// for layouts
            $jstlay = $wpjobportallt;
        }elseif($wpjobportallay !=''){ // is for search, pagiantion and top sorting case
            $jstlay = $wpjobportallay;
        }

        $layoutname = wpjobportalphplib::wpJP_explode("wpjobportal_", $jstlay);// admin page has wpjobportal_ prefix
        if(isset($layoutname[1])){
            $jstlay = $layoutname[1];
        }

        $from_search = WPJOBPORTALrequest::getVar('WPJOBPORTAL_form_search');
        $job_portal_search = WPJOBPORTALrequest::getVar('from_search');

        if( $from_search != '' && $from_search == 'WPJOBPORTAL_SEARCH'){ // search form is submitted set callfrom =1 to set values in cookie
            $this->_callfrom = 1;
        }elseif( $job_portal_search != '' && $job_portal_search == 'WPJOBPORTAL_SEARCH'){ // search form is submitted set callfrom =1 to set values in cookie
            $this->_callfrom = 1;
        }
        elseif(WPJOBPORTALrequest::getVar('pagenum', 'get', null) != null){ // pagination case
            $this->_callfrom = 2;
        }

        // to handle the case of sorting not working on layouts
        if($this->_callfrom == 3){
            $sorton = WPJOBPORTALrequest::getVar('sorton','post',0);
            if(is_numeric($sorton) && $sorton > 0){
                $this->_callfrom = 1;
            }else{
                $sortby = WPJOBPORTALrequest::getVar('sortby','post',0);
                if(is_numeric($sortby) && $sortby > 0){
                    $this->_callfrom = 1;
                }
            }
        }
      
    if($jstlay == ''){ // to handle the case of theme pages with SEF URLs
        global $post;
      
        $current_url = add_query_arg(array(), get_permalink());

        // Get the post ID from the URL
        $post_id = url_to_postid($current_url);
          
        $content = get_post_field('post_content', $post_id);

        $shortcode = $content;

        // Define the regular expression pattern to extract the page attribute
        $pattern = '/\[jp_job_portal_theme_pages\s+([^\]]+)\]/';

        // Match the pattern in the shortcode
        preg_match($pattern, $shortcode, $matches);

        // Check if the matches are found
        if (isset($matches[1])) {
            $jp_job_attributes = shortcode_parse_atts($matches[1]);

            // Extract the 'page' attribute value
            $page_value = isset($jp_job_attributes['page']) ? $jp_job_attributes['page'] : '';

            // Output or use the extracted 'page' value
        	if($page_value != '' && is_numeric($page_value)){
              $jstlay = $this->getLayoutValueFromPageNum($page_value);
            }
        }
    }
      
    switch($jstlay){
            case 'jobs':
            case 'job':
                $this->searchdataforjobs();
            break;
            case 'myresume':
            case 'resumes':
            case 'resume':
                $this->searchFormDataForResume($jstlay);
            break;
            case 'appliedjobs': // for jobseeker case
            case 'myjobs': // For employer case
            case 'activitylog': // For activity log
            // to handle the sorting and search on these pages.
            case 'myappliedjobs': // for jobseeker case
                $this->searchFormDataForCommonData($jstlay);
            break;
            // case 'mycompany': // For employer case
            // case 'company': // For admin case
            //     $this->searchFormDataForCompanies();
            // break;
            case 'careerlevel':
                if(is_admin())
                    $this->searchFormDataForCareerLevel();
            break;
            case 'category':
                if(is_admin())
                    $this->searchFormDataForCategory();
            break;
            case 'city':
                if(is_admin())
                    $this->searchFormDataForCity();
            break;
            case 'country':
                if(is_admin())
                    $this->searchFormDataForCountry();
            break;
            case 'currency':
            case 'fieldordering':
            case 'highesteducation':
            case 'user':
            case 'state':
            case 'slug':
            case 'salaryrangetype':
            case 'jobstatus':
            case 'jobtype':
                if(is_admin()){
                    $this->setSearchFormData($jstlay);
                }
            break;
            case 'departments':
            case 'jobapply':
            case 'coverletter':
            case 'invoice':
            case 'purchasehistory':
            case 'folder':
            case 'jobalert':
            case 'message':
            case 'company':
            case 'mycompany':
            case 'tag':
            case 'jobappliedresume': //there was a duplicate in the above code
             case 'companies':
            case 'controlpanel':
                    $this->setSearchFormDataAdminListing();
            break;

            default:
                if($jstlay != '' ){ // avoid deleting cookies for wordpress internal call
                    wpjobportal::removeusersearchcookies();
                }
            break;
        }

        if($this->_setcookies){
            wpjobportal::wpjobportal_setusersearchcookies($this->_setcookies,$this->_jsjp_search_array);
        }
    }

    private function searchdataforjobs(){
        $search_userfields = array();
        // $search_userfields = JSSTincluder::getObjectClass('customfields')->userFieldsForSearch(1);
        if($this->_callfrom == 1 || $this->_callfrom == 3){ //  3 for theme
            if(is_admin()){
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('job')->getAdminJobSearchFormData($search_userfields);
            }else{
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('job')->getFrontSideJobSearchFormData($search_userfields);
            }
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('job')->getCookiesSavedSearchDataJob($search_userfields);
        }
        WPJOBPORTALincluder::getJSModel('job')->setSearchVariableForJob($this->_jsjp_search_array,$search_userfields);
    }

    private function searchFormDataForResume($layout){
        if($this->_callfrom == 1 || $this->_callfrom == 3){ // 3 for theme
            if(is_admin()){
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('resume')->getAdminResumeSearchFormData();
            }else{
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('resume')->getMyResumeSearchFormData($layout);
            }
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('resume')->getResumeSavedCookiesData($layout);
        }
        if(is_admin()){
            WPJOBPORTALincluder::getJSModel('resume')->setSearchVariableForAdminResume($this->_jsjp_search_array,$layout);
        }else{
            WPJOBPORTALincluder::getJSModel('resume')->setSearchVariableForMyResume($this->_jsjp_search_array,$layout);
        }
    }

    private function searchFormDataForCommonData($jstlay){
        if($this->_callfrom == 1){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('common')->getSearchFormDataOnlySort($jstlay);
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('common')->getCookiesSavedOnlySortandOrder();
        }
        WPJOBPORTALincluder::getJSModel('common')->setSearchVariableOnlySortandOrder($this->_jsjp_search_array,$jstlay);
    }

    private function searchFormDataForCompanies(){
        if($this->_callfrom == 1){
            if(is_admin()){
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('company')->getSearchFormAdminCompanyData();
            }else{
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('company')->getSearchFormDataMyCompany();
            }
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            if(is_admin()){
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('company')->getAdminCompanySavedCookies();
            }else{
                $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('company')->getCookiesSavedMyCompany();
            }
        }
        if(is_admin()){
            WPJOBPORTALincluder::getJSModel('company')->setAdminCompanySearchVariable($this->_jsjp_search_array);
        }else{
            WPJOBPORTALincluder::getJSModel('company')->setSearchVariableMyCompany($this->_jsjp_search_array);
        }
    }

    private function searchFormDataForCareerLevel(){
        if($this->_callfrom == 1){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('careerlevel')->getSearchFormDataCareerLevel();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('careerlevel')->getCookiesSavedCareerLevel();
        }
        WPJOBPORTALincluder::getJSModel('careerlevel')->setSearchVariableCareerLevel($this->_jsjp_search_array);
    }

    private function searchFormDataForCategory(){
        if($this->_callfrom == 1){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('category')->getSearchFormDataCategory();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('category')->getCookiesSavedCategory();
        }
        WPJOBPORTALincluder::getJSModel('category')->setSearchVariableCategory($this->_jsjp_search_array);
    }

    private function searchFormDataForCity(){
        if($this->_callfrom == 1){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('city')->getSearchFormDataCity();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('city')->getCookiesSavedCity();
        }
        WPJOBPORTALincluder::getJSModel('city')->setSearchVariableCity($this->_jsjp_search_array);
    }

    private function searchFormDataForCountry(){
        if($this->_callfrom == 1){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('country')->getCountrySearchFormData();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('country')->getCountrySavedCookiesData();
        }
        WPJOBPORTALincluder::getJSModel('country')->setCountrySearchVariable($this->_jsjp_search_array);
    }

    private function setSearchFormData($module){
        if($this->_callfrom == 1){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel($module)->getSearchFormData();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel($module)->getSavedCookiesDataForSearch();
        }
        WPJOBPORTALincluder::getJSModel($module)->setSearchVariableForSearch($this->_jsjp_search_array);
    }

    private function setSearchFormDataAdminListing(){
        if($this->_callfrom == 1){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('common')->getSearchFormDataAdmin();
            $this->_setcookies = true;
        }elseif($this->_callfrom == 2){
            $this->_jsjp_search_array = WPJOBPORTALincluder::getJSModel('common')->getCookiesSavedAdmin();
        }
        WPJOBPORTALincluder::getJSModel('common')->setSearchVariableAdmin($this->_jsjp_search_array);
    }

    private function setCookiesFromTransientData(){
        $user_data  =  get_transient( 'wpjobportal-social-login-data');
        //echo 'printing tranient data from handlecookies class 248 <pre>';print_r($user_data);echo '</pre>';
        if( $user_data !== FALSE){ // it will be false if transient does not exsist
            if($user_data != '' && is_array($user_data) && !empty($user_data)){
                if (!isset($_COOKIE['wpjobportal-socialid'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialid' , $user_data['socialid'] , time() + 1209600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                        wpjobportalphplib::wpJP_setcookie('wpjobportal-socialid' , $user_data['socialid'] , time() + 1209600 , SITECOOKIEPATH);
                    }
                }
                if (!isset($_COOKIE['wpjobportal-socialfirstname'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialfirstname' , $user_data['socialfirstname'] , time() + 1209600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                       wpjobportalphplib::wpJP_setcookie('wpjobportal-socialfirstname' , $user_data['socialfirstname'] , time() + 1209600 , SITECOOKIEPATH);
                    }
                }
                if (!isset($_COOKIE['wpjobportal-sociallastname'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-sociallastname' , $user_data['sociallastname'], time() + 1209600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                       wpjobportalphplib::wpJP_setcookie('wpjobportal-sociallastname' , $user_data['sociallastname'], time() + 1209600 , SITECOOKIEPATH);
                    }
                }
                if (!isset($_COOKIE['wpjobportal-socialemail'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialemail' , $user_data['socialemail'], time() + 1209600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialemail' , $user_data['socialemail'], time() + 1209600 , SITECOOKIEPATH);
                    }
                }
                if (!isset($_COOKIE['wpjobportal-socialmedia'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialmedia' , $user_data['socialmedia'], time() + 1209600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialmedia' , $user_data['socialmedia'], time() + 1209600 , SITECOOKIEPATH);
                    }
                }
                delete_transient('wpjobportal-social-login-data');// removing transient to avoid re creating cookie on every call
            }
        }
    }

    private function removeCookiesFromTransientData(){
        $remove_coookies  =  get_transient( 'wpjobportal-social-login-logout-cookies');
        if( $remove_coookies !== FALSE){ // it will be false if transient does not exsist
            if($remove_coookies != '' && !empty($remove_coookies) && $remove_coookies == 'remove-cookies' ){

                if(isset($_COOKIE['wpjobportal-socialid'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialid' , '' , time() - 3600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                        wpjobportalphplib::wpJP_setcookie('wpjobportal-socialid' , '' , time() - 3600 , SITECOOKIEPATH);
                    }
                    unset($_COOKIE['wpjobportal-socialid']);
                }

                if(isset($_COOKIE['wpjobportal-socialfirstname'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialfirstname' , '' , time() - 3600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                       wpjobportalphplib::wpJP_setcookie('wpjobportal-socialfirstname' , '' , time() - 3600 , SITECOOKIEPATH);
                    }
                    unset($_COOKIE['wpjobportal-socialfirstname']);
                }

                if(isset($_COOKIE['wpjobportal-sociallastname'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-sociallastname' , '', time() - 3600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                       wpjobportalphplib::wpJP_setcookie('wpjobportal-sociallastname' , '', time() - 3600 , SITECOOKIEPATH);
                    }
                    unset($_COOKIE['wpjobportal-sociallastname']);
                }

                if(isset($_COOKIE['wpjobportal-socialemail'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialemail' , '', time() - 3600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                        wpjobportalphplib::wpJP_setcookie('wpjobportal-socialemail' , '', time() - 3600 , SITECOOKIEPATH);
                    }
                    unset($_COOKIE['wpjobportal-socialemail']);
                }

                if(isset($_COOKIE['wpjobportal-socialmedia'])){
                    wpjobportalphplib::wpJP_setcookie('wpjobportal-socialmedia' , '', time() - 3600 , COOKIEPATH);
                    if ( SITECOOKIEPATH != COOKIEPATH ){
                        wpjobportalphplib::wpJP_setcookie('wpjobportal-socialmedia' , '', time() - 3600 , SITECOOKIEPATH);

                    }
                    unset($_COOKIE['wpjobportal-socialmedia']);
                }

                delete_transient('wpjobportal-social-login-logout-cookies');// removing transient to avoid re creating cookie on every call
            }
        }
    }
  
  	public function getLayoutValueFromPageNum($pagenum){
        switch($pagenum){
            case 1: // jobseeker control panel
                $module = 'jobseeker';
                $layout = 'controlpanel';
            break;
            case 2: // newest job
                $module = 'job';
                $layout = 'jobs';
            break;
            case 3: // job search
                $module = 'jobsearch';
                $layout = 'jobsearch';
            break;
            case 4: // jobs by category
                $module = 'job';
                $layout = 'jobsbycategories';
            break;
            case 5: // shortlited jobs
                $module = 'shortlist';
                $layout = 'shortlistedjobs';
            break;
            case 6: // add resume
                $module = in_array('multiresume', wpjobportal::$_active_addons) ? 'multiresume' : 'resume';
                $layout = 'addresume';
            break;
            case 7: // my resume
                $module = in_array('multiresume', wpjobportal::$_active_addons) ? 'multiresume' : 'resume';
                $layout = 'myresumes';
            break;
            case 8: // my applied jobs
                $module = 'jobapply';
                $layout = 'myappliedjobs';
            break;
            case 9: // job alert
                $module = 'jobalert';
                $layout = 'jobalert';
            break;
            case 10: // company list
                    $module = in_array('multicompany', wpjobportal::$_active_addons) ? 'multicompany' : 'company';
                    $layout = 'companies';
            break;
            case 11: // jobseeker messages
                $module = 'message';
                $layout = 'jobseekermessages';
            break;
            case 12: // jobseeker registration
                $module = 'user';
                $layout = 'regjobseeker';
            break;
            case 13: // employer controlpanel
                $module = 'employer';
                $layout = 'controlpanel';
            break;
            case 14: // add company
                $module = in_array('multicompany', wpjobportal::$_active_addons) ? 'multicompany' : 'company';
                $layout = 'addcompany';
            break;
            case 15: // my companies
                $module = in_array('multicompany', wpjobportal::$_active_addons) ? 'multicompany' : 'company';
                $layout = 'mycompanies';
            break;
            case 16: // add job
                $module = 'job';
                $layout = 'addjob';
            break;
            case 17: // my jobs
                $module = 'job';
                $layout = 'myjobs';
            break;
            case 18: // resume list
                $module = 'resumesearch';
                $layout = 'resumes';
            break;
            case 19: // resume search
                $module = 'resumesearch';
                $layout = 'resumesearch';
            break;
            case 20: // resume save search
                $module = 'resumesearch';
                $layout = 'resumesavesearch';
            break;
            case 21: // resume by category
                $module = 'resume';
                $layout = 'resumebycategory';
            break;
            case 22: // employer messages
                $module = 'message';
                $layout = 'employermessages';
            break;
            case 23: // employer registration
                $module = 'user';
                $layout = 'regemployer';
            break;
            case 24: // login
                $module = 'wpjobportal';
                $layout = 'login';
            break;

            case 25: // featured jobs
                $module = 'featuredjob';
                $layout = 'featuredjobs';
            break;
            case 26: // feauted resume
                $module = 'featureresume';
                $layout = 'featuredresumes';
            break;
            case 27: // feauted companies
                $module = 'featuredcompany';
                $layout = 'featuredcompanies';
            break;
            case 28: // all companies
                $module = 'allcompanies';
                $layout = 'companies';
            break;
            case 29: // all resumes
                $module = 'allresumes';
                $layout = 'resumes';
            break;

            // default:
            //     $module = 'job';
            //     $layout = 'jobs';
            break;
        }
      return $layout;
    }
}
?>
