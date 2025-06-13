<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALCommonModel {

    public $module_name = '';
    public $file_name = '';


    function removeSpecialCharacter($string) {
        $string = sanitize_title($string);
        return $string;
    }

    function stringToAlias($string){
        $string = $this->removeSpecialCharacter($string);
        $string = wpjobportalphplib::wpJP_strtolower(wpjobportalphplib::wpJP_str_replace(' ', '-', $string));
        $string = wpjobportalphplib::wpJP_strtolower(wpjobportalphplib::wpJP_str_replace('_', '-', $string));
        return $string;
    }

     function getTimeForView($time,$unit){
        // made the change to translate unit value(day,days,week,weeks etc)
        if($time > 1){
            $unit .= 's';
        }
        $text = $time.' '.wpjobportal::wpjobportal_getVariableValue(wpjobportalphplib::wpJP_ucfirst($unit));
        return $text;
    }

    function getGoogleMapApiAddress() {

        $filekey = wp_remote_get(esc_html(WPJOBPORTAL_PLUGIN_URL).'/includes/google-map-js.inc.php');
        if (is_wp_error($filekey)) {
            return '';
        }
        $file_key_string= $filekey['body'];
        //echo var_dump($file_key_string);die('comommm');

        $key =wpjobportal::$_configuration['google_map_api_key'];
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $matcharray = array(
            '{PROTOCOL}' => $protocol,
            '{KEY}' => $key,
        );
        foreach ($matcharray AS $find => $replace) {
            $file_key_string = wpjobportalphplib::wpJP_str_replace($find, $replace, $file_key_string);
        }
        return $file_key_string;
    }

    function getFancyPrice($price,$currentyid=0,$override_config=array()){
        $currency_align = isset($override_config['currency_align']) ? $override_config['currency_align'] : wpjobportal::$_configuration['currency_align'];
        $thousand_separator = isset($override_config['thousand_separator']) ? $override_config['thousand_separator'] : wpjobportal::$_configuration['thousand_separator'];
        $short_price = isset($override_config['short_price']) ? $override_config['short_price'] : wpjobportal::$_configuration['short_price'];
        if(isset($override_config['decimal_places'])){
            if($override_config['decimal_places'] === 'fit_to_currency' && $currentyid){
                $decimal_places = '';//wpjobportalphplib::wpJP_strlen(WPJOBPORTALincluder::getJSModel('currency')->getCurrencySmallestUnit($currentyid))-1;
            }else{
                $decimal_places = $override_config['decimal_places'];
            }
        }else{
            $decimal_places = wpjobportal::$_configuration['decimal_places'];
        }

        $text = '';
        if($currency_align == 1 && $currentyid ){//left
            $text .= WPJOBPORTALincluder::getJSModel('currency')->getCurrencySymbol($currentyid);
        }

        if( $short_price ){
            $text .= $this->getShortFormatPrice($price);
        }else{
            $text .= wpjobportalphplib::wpJP_number_format($price,$decimal_places,'.',$thousand_separator);
        }

        if($currency_align == 2 && $currentyid ){ //right
            $text .= WPJOBPORTALincluder::getJSModel('currency')->getCurrencySymbol($currentyid);
        }
        return $text;
    }

    function getShortFormatPrice($n, $precision = 1){
        if($n < 900) {
            // 0 - 900
            $n_format = wpjobportalphplib::wpJP_number_format($n, $precision);
            $suffix = '';
        }else if($n < 900000) {
            // 0.9k-850k
            $n_format = wpjobportalphplib::wpJP_number_format($n / 1000, $precision);
            $suffix = 'K';
        }else if($n < 900000000) {
            // 0.9m-850m
            $n_format = wpjobportalphplib::wpJP_number_format($n / 1000000, $precision);
            $suffix = 'M';
        }else if($n < 900000000000) {
            // 0.9b-850b
            $n_format = wpjobportalphplib::wpJP_number_format($n / 1000000000, $precision);
            $suffix = 'B';
        }else{
            // 0.9t+
            $n_format = wpjobportalphplib::wpJP_number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }
      // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
      // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if( $precision > 0 ) {
            $dotzero = '.' . wpjobportalphplib::wpJP_str_repeat( '0', $precision );
            $n_format = wpjobportalphplib::wpJP_str_replace( $dotzero, '', $n_format );
        }
        return $n_format . $suffix;
    }
    /**
    * @param wp job portal Function's
    * @param Get Status
    */
    function setDefaultForDefaultTable($id, $tablename) {
        if (is_numeric($id) == false)
            return false;

        switch ($tablename) {
            case "jobtypes":
            case "jobstatus":
            case "heighesteducation":
            case "careerlevels":
            case "experiences":
            case "currencies":
            case "salaryrangetypes":
            case "categories":
            case "subcategories":
                if (self::checkCanMakeDefault($id, $tablename)) {
                    if ($tablename == "currencies")
                        $column = "default";
                    else
                        $column = "isdefault";
                    //DB class limitations
                    $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($tablename) . "` AS t SET t." . esc_sql($column) . " = 0 ";
                    wpjobportaldb::query($query);
                    $query = "UPDATE  `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($tablename) . "` AS t SET t." . esc_sql($column) . " = 1 WHERE id=" . esc_sql($id);
                    if (!wpjobportaldb::query($query))
                        return WPJOBPORTAL_SET_DEFAULT_ERROR;
                    else
                        return WPJOBPORTAL_SET_DEFAULT;
                    break;
                }else {
                    return WPJOBPORTAL_UNPUBLISH_DEFAULT_ERROR;
                }
                break;
        }
    }

    function checkCanMakeDefault($id, $tablename) {
        if (!is_numeric($id))
            return false;
        switch ($tablename) {
            case 'jobtypes':
            case 'jobstatus':
            case 'heighesteducation':
            case 'categories':
                $column = "isactive";
                break;
            default:
                $column = "status";
                break;
        }
        $query = "SELECT " . esc_sql($column) . " FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($tablename) . "` WHERE id=" . esc_sql($id);
        $res = wpjobportaldb::get_var($query);
        if ($res == 1)
            return true;
        else
            return false;
    }

    function getTranskey($option_name){
         $query = "SELECT `option_value` FROM " . wpjobportal::$_wpprefixforuser . "options WHERE option_name = '".esc_sql($option_name)."'";
         $transactionKey = wpjobportaldb::get_var($query);
         return $transactionKey;
    }

      function getDefaultValue($table) {

        switch ($table) {
            case "categories":
            case "jobtypes":
            case "jobstatus":
            case "shifts":
            case "heighesteducation":
            case "ages":
            case "careerlevels":
            case "experiences":
            case "salaryrange":
            case "salaryrangetypes":
            case "subcategories":
                $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` WHERE isdefault=1";

                $default_id = wpjobportaldb::get_var($query);
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "`";

                    $min_id = wpjobportaldb::get_var($query);
                    return $min_id;
                }
            case "currencies":
                $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` WHERE `default`=1";

                $default_id = wpjobportaldb::get_var($query);
                if ($default_id)
                    return $default_id;
                else {
                    $query = "SELECT min(id) AS id FROM `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "`";

                    $min_id = wpjobportaldb::get_var($query);
                    return $min_id;
                }
                break;
        }
    }

    // function setOrderingUpForDefaultTable($field_id, $table) {
    //     if (is_numeric($field_id) == false)
    //         return false;
    //     //DB class limitations
    //     if($table == 'categories'){
    //         $parentid = wpjobportal::$_db->get_var("SELECT parentid FROM `".wpjobportal::$_db->prefix."wj_portal_categories` WHERE id = ".esc_sql($field_id));
    //         $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f1, `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f2
    //                     SET f1.ordering = f1.ordering + 1
    //                     WHERE f1.ordering = f2.ordering - 1 AND f1.parentid = ".esc_sql($parentid)."
    //                     AND f2.id = " . esc_sql($field_id);
    //     }else{
    //         $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f1, `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f2
    //                     SET f1.ordering = f1.ordering + 1
    //                     WHERE f1.ordering = f2.ordering - 1
    //                     AND f2.id = " . esc_sql($field_id);
    //     }
    //     if (false == wpjobportaldb::query($query)) {
    //         return WPJOBPORTAL_ORDER_UP_ERROR;
    //     }
    //     $query = " UPDATE " . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "
    //                 SET ordering = ordering - 1
    //                 WHERE id = " . esc_sql($field_id);

    //     if (false == wpjobportaldb::query($query)) {
    //         return WPJOBPORTAL_ORDER_UP_ERROR;
    //     }
    //     return WPJOBPORTAL_ORDER_UP;
    // }

    // function setOrderingDownForDefaultTable($field_id, $table) {
    //     if (is_numeric($field_id) == false)
    //         return false;
    //     //DB class limitations
    //     if($table == 'categories'){
    //         $parentid = wpjobportal::$_db->get_var("SELECT parentid FROM `".wpjobportal::$_db->prefix."wj_portal_categories` WHERE id = ".esc_sql($field_id));
    //         $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f1, `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f2
    //                     SET f1.ordering = f1.ordering - 1
    //                     WHERE f1.ordering = f2.ordering + 1 AND f1.parentid = ".esc_sql($parentid)."
    //                     AND f2.id = " . esc_sql($field_id);
    //     }else{
    //         $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f1, `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "` AS f2
    //                     SET f1.ordering = f1.ordering - 1
    //                     WHERE f1.ordering = f2.ordering + 1
    //                     AND f2.id = " . esc_sql($field_id);
    //     }

    //     if (false == wpjobportaldb::query($query)) {
    //         return WPJOBPORTAL_ORDER_DOWN_ERROR;
    //     }
    //     $query = " UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_" . esc_sql($table) . "`
    //                 SET ordering = ordering + 1
    //                 WHERE id = " . esc_sql($field_id);

    //     if (false == wpjobportaldb::query($query)) {
    //         return WPJOBPORTAL_ORDER_DOWN_ERROR;
    //     }
    //     return WPJOBPORTAL_ORDER_DOWN;
    // }

     function getMultiSelectEdit($id, $for) {
        if (!is_numeric($id))
            return false;
        $config = wpjobportal::$_config->getConfigByFor('default');
       $query = "SELECT city.id AS id, CONCAT(city.name";
        switch ($config['defaultaddressdisplaytype']) {
            case 'csc'://City, State, Country
                $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                break;
            case 'cs'://City, State
                $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                break;
            case 'cc'://City, Country
                $query .= " ,', ', country.name)";
                break;
            case 'c'://city by default select for each case
                $query .= ")";
                break;
        }
        $query .= " AS name ,city.latitude,city.longitude";
        switch ($for) {
            case 1:
                $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mcity";
                break;
            case 2:
                $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` AS mcity";
                break;
            case 3:
                $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobalertcities` AS mcity";
                break;
        }
        $query .=" JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city on city.id=mcity.cityid
                  JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country on city.countryid=country.id
                  LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state on city.stateid=state.id";
        switch ($for) {
            case 1:
                $query .= " WHERE mcity.jobid = ".esc_sql($id)." AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 2:
                $query .= " WHERE mcity.companyid = ".esc_sql($id)." AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 3:
                $query .= " WHERE mcity.alertid = ".esc_sql($id)." AND country.enabled = 1 AND city.enabled = 1";
                break;
        }
        $result = wpjobportaldb::get_results($query);
        $json_array = wp_json_encode($result);
        if (empty($json_array))
            return null;
        else
            return $json_array;
    }

    function getRequiredTravel() {
        $requiredtravel = array();
        $requiredtravel[] = (object) array('id' => 1, 'text' => esc_html(__('Not Required', 'wp-job-portal')));
        $requiredtravel[] = (object) array('id' => 2, 'text' => esc_html(__('25 Per', 'wp-job-portal')));
        $requiredtravel[] = (object) array('id' => 3, 'text' => esc_html(__('50 Per', 'wp-job-portal')));
        $requiredtravel[] = (object) array('id' => 4, 'text' => esc_html(__('75 Per', 'wp-job-portal')));
        $requiredtravel[] = (object) array('id' => 5, 'text' => esc_html(__('100 Per', 'wp-job-portal')));
        return $requiredtravel;
    }

    function getRequiredTravelValue($value) {
        switch ($value) {
            case '1': return esc_html(__('Not Required', 'wp-job-portal')); break;
            case '2': return esc_html(__('25 Per', 'wp-job-portal')); break;
            case '3': return esc_html(__('50 Per', 'wp-job-portal')); break;
            case '4': return esc_html(__('75 Per', 'wp-job-portal')); break;
            case '5': return esc_html(__('100 Per', 'wp-job-portal')); break;
        }
    }

    /**
    * @param wp job portal Function
    * @param Log Action's
    */

    function getLogAction($for) {
        $logaction = array();
        if ($for == 1) { //employer
            $logaction[] = (object) array('id' => 'add_company', 'text' => esc_html(__('New company', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'featured_company', 'text' => esc_html(__('Featured company', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'add_department', 'text' => esc_html(__('New department', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'add_job', 'text' => esc_html(__('New job', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'featured_job', 'text' => esc_html(__('Featured job', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'resume_save_search', 'text' => esc_html(__('Searched and saved resume', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'view_resume_contact_detail', 'text' => esc_html(__('Viewed resume contact details', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'featured_company_timeperiod', 'text' => esc_html(__('Featured company for time period', 'wp-job-portal')));
        }
        if ($for == 2) {
            $logaction[] = (object) array('id' => 'add_resume', 'text' => esc_html(__('New resume', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'featured_resume', 'text' => esc_html(__('Featured resume', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'add_cover_letter', 'text' => esc_html(__('New cover letter', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'job_alert_lifetime', 'text' => esc_html(__('Life time job alert', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'job_alert_time', 'text' => esc_html(__('Job alert', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'job_alert_timeperiod', 'text' => esc_html(__('Job alert for time', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'job_save_search', 'text' => esc_html(__('Saved a job search', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'shortlist_job', 'text' => esc_html(__('Job short listed', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'job_apply', 'text' => esc_html(__('Applied for job', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'view_job_apply_status', 'text' => esc_html(__('Viewed job status', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'view_company_contact_detail', 'text' => esc_html(__('Viewed company contact detail', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'tell_a_friend', 'text' => esc_html(__('Told a friend', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'job_save_filter', 'text' => esc_html(__('Saved a job filter', 'wp-job-portal')));
            $logaction[] = (object) array('id' => 'fb_share', 'text' => esc_html(__('Shared on social media', 'wp-job-portal')));
        }
        return $logaction;
    }

    function getMiniMax() {
        $minimax = array();
        $minimax[] = (object) array('id' => '1', 'text' => esc_html(__('Minimum', 'wp-job-portal')));
        $minimax[] = (object) array('id' => '2', 'text' => esc_html(__('Maximum', 'wp-job-portal')));
        return $minimax;
    }
    /**
    * @param wp job portal Function's
    * @param Get Yes Or No
    */

    function getYesNo() {
        $yesno = array();
        $yesno[] = (object) array('id' => '1', 'text' => esc_html(__('Yes', 'wp-job-portal')));
        $yesno[] = (object) array('id' => '0', 'text' => esc_html(__('No', 'wp-job-portal')));
        return $yesno;
    }

    /**
    * @param wp job portal Function's
    * @param Get gender
    */

    function getGender() {
        $gender = array();
        $gender[] = (object) array('id' => '1', 'text' => esc_html(__('Male', 'wp-job-portal')));
        $gender[] = (object) array('id' => '2', 'text' => esc_html(__('Female', 'wp-job-portal')));
        return $gender;
    }

    /**
    * @param wp job portal Function's
    * @param Get Status
    */

    function getStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => esc_html(__('Published', 'wp-job-portal')));
        $status[] = (object) array('id' => '0', 'text' => esc_html(__('Unpublished', 'wp-job-portal')));
        return $status;
    }

    function getTotalExp(&$resumeid){
        ///To get Total Experience From Resume Section's
        if(!is_numeric($resumeid)){
            return '';
        }
        $resume_id = $resumeid;
        $query ="SELECT resume.employer_from_date AS fromdate,resume.employer_to_date AS todate,resume.employer_current_status
                AS status FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` AS resume
                WHERE resumeid='".esc_sql($resume_id)."' ORDER BY ID ASC
                ";
        wpjobportal::$_data[3] = wpjobportaldb::get_results($query);
        $daystodate = wpjobportal::$_data[3];
        $totalYear = 0;
        $totalmonth = 0;
        $totaldays = 0;
        $html = '';
        for ($i=0; $i < count(wpjobportal::$_data[3]) ; $i++) {
            $status = $daystodate[$i]->status;
            $from = $daystodate[$i]->fromdate;
            $to   = $daystodate[$i]->todate;
            $diff = abs(strtotime($to) - strtotime($from));
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            $totalYear += $years;
            $totalmonth += $months;
            $totaldays += $days;
        }

        if(!empty($daystodate)){

            if($totalYear > 0){
                $html.= $totalYear;

            }
            if($totalYear > 0 && $totalmonth > 5){
                $html.= '.5+'.' '.'Years';
            }else if($totalYear>0 && $totalmonth<=5) {
                $html.= ' '.'+'.'Years';
            }else if ($totalYear<=0 && $totalmonth<5) {
                $html.='Less than 1 year';
            }
            else{
                $html.='Less than 1 year';
            }
        }
        else{
            $html.="Fresh";
        }
        return $html;
    }

    /**
    * @param wp job portal Function
    * @param Option's For Job Alert
    */

    function getOptionsForJobAlert() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => esc_html(__('Subscribed', 'wp-job-portal')));
        $status[] = (object) array('id' => '0', 'text' => esc_html(__('Unsubscribed', 'wp-job-portal')));
        return $status;
    }

    function getQueStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => esc_html(__('Approved', 'wp-job-portal')));
        $status[] = (object) array('id' => '-1', 'text' => esc_html(__('Rejected', 'wp-job-portal')));// rejected status is -1
        return $status;
    }

    /**
    * @param wp job portal
    * Roles For Combo
    */

    function getListingStatus() {
        $status = array();
        $status[] = (object) array('id' => '1', 'text' => esc_html(__('Approved', 'wp-job-portal')));
        $status[] = (object) array('id' => '-1', 'text' => esc_html(__('Rejected', 'wp-job-portal')));
        return $status;
    }

    /**
    * @param wp job portal
    * Roles For Combo
    */

    function getRolesForCombo() {
        $roles = array();
        $roles[] = (object) array('id' => '1', 'text' => esc_html(__('Employer', 'wp-job-portal')));
        $roles[] = (object) array('id' => '2', 'text' => esc_html(__('Job seeker', 'wp-job-portal')));
        return $roles;
    }

    /**
    * @param wp job portal
    * Fields Type's
    */

    function getFeilds() {
        $values = array();
        $values[] = (object) array('id' => 'text', 'text' => esc_html(__('Text Field', 'wp-job-portal')));
        $values[] = (object) array('id' => 'textarea', 'text' => esc_html(__('Text Area', 'wp-job-portal')));
        $values[] = (object) array('id' => 'checkbox', 'text' => esc_html(__('Check Box', 'wp-job-portal')));
        $values[] = (object) array('id' => 'date', 'text' => esc_html(__('Date', 'wp-job-portal')));
        $values[] = (object) array('id' => 'select', 'text' => esc_html(__('Drop Down', 'wp-job-portal')));
        $values[] = (object) array('id' => 'emailaddress', 'text' => esc_html(__('Email Address', 'wp-job-portal')));
        return $values;
    }

    /**
    * @param wp job portal
    * Radius Type
    */

    function getRadiusType() {
        $radiustype = array(
            (object) array('id' => '0', 'text' => esc_html(__('Select One', 'wp-job-portal'))),
            (object) array('id' => '1', 'text' => esc_html(__('Meters', 'wp-job-portal'))),
            (object) array('id' => '2', 'text' => esc_html(__('Kilometers', 'wp-job-portal'))),
            (object) array('id' => '3', 'text' => esc_html(__('Miles', 'wp-job-portal'))),
            (object) array('id' => '4', 'text' => esc_html(__('Nautical Miles', 'wp-job-portal'))),
        );
        return $radiustype;
    }

    /**
    * @param wp job portal
    * Rating
    */

     function getRating($rating){
        if(!is_numeric($rating)){
            $rating = 0;
        }
        $percent = ($rating/5)*100;
        $html ="<div class=\"wjportal-container-small\"" . ( " style=\"vertical-align:middle;display:inline-block;\"" ) . ">
            <ul class=\"wjportal-stars-small\">
                <li class=\"current-rating\" style=\"width:" . (int) $percent . "%;\"></li>
            </ul>
        </div>";
        return $html;
    }

       function getJobsStats_Widget($classname, $title, $showtitle, $employers, $jobseekers, $jobs, $companies, $activejobs, $resumes, $todaystats) {
        //listModuleJobs
        $curdate = gmdate('Y-m-d');
        $data = array();
        if ($employers == 1) {
            $query = "SELECT count(user.id) AS totalemployer
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                WHERE user.roleid = 1";
            $data['employer'] = wpjobportaldb::get_var($query);
        }
        if ($jobseekers == 1) {
            $query = "SELECT count(user.id) AS totaljobseeker
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                WHERE user.roleid = 2";
            $data['jobseeker'] = wpjobportaldb::get_var($query);
        }
        if ($jobs == 1) {
            $query = "SELECT count(job.id) AS totaljobs
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                WHERE job.status = 1 ";
            $data['totaljobs'] = wpjobportaldb::get_var($query);
        }
        if ($companies == 1) {
            $query = "SELECT count(company.id) AS totalcomapnies
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                WHERE company.status = 1 ";
            $data['totalcompanies'] = wpjobportaldb::get_var($query);
        }
        if ($activejobs == 1) {
            $query = "SELECT count(job.id) AS totalactivejobs
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                WHERE job.status = 1 AND DATE(job.startpublishing) <= " . esc_sql($curdate) . " AND DATE(job.stoppublishing) >= " . esc_sql($curdate);
            $data['tatalactivejobs'] = wpjobportaldb::get_var($query);
        }
        if ($resumes == 1) {
            $query = "SELECT count(resume.id) AS totalresume
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                WHERE resume.status = 1 ";
            $data['totalresume'] = wpjobportaldb::get_var($query);
        }

        if ($todaystats == 1) {
            if ($employers == 1) {
                $query = "SELECT count(user.id) AS todayemployer
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                    WHERE user.roleid = 1 AND DATE(user.created) = '" . esc_sql($curdate)."'";
                $data['todyemployer'] = wpjobportaldb::get_var($query);
            }
            if ($jobseekers == 1) {
                $query = "SELECT count(user.id) AS todayjobseeker
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
                    WHERE user.roleid = 2 AND DATE(user.created) = '" . esc_sql($curdate)."'";
                $data['todyjobseeker'] = wpjobportaldb::get_var($query);
            }
            if ($jobs == 1) {
                $query = "SELECT count(job.id) AS todayjobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) = '" . esc_sql($curdate)."'";

                $data['todayjobs'] = wpjobportaldb::get_var($query);
            }
            if ($companies == 1) {
                $query = "SELECT count(company.id) AS todaycomapnies
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                    WHERE company.status = 1 AND DATE(company.created) = '" . esc_sql($curdate)."'";

                $data['todaycompanies'] = wpjobportaldb::get_var($query);
            }
            if ($activejobs == 1) {
                $query = "SELECT count(job.id) AS todayactivejobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) = '" . esc_sql($curdate)."'";
                $data['todayactivejobs'] = wpjobportaldb::get_var($query);
            }
            if ($resumes == 1) {
                $query = "SELECT count(resume.id) AS todayresume
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                    WHERE resume.status = 1 AND DATE(resume.created) = '" . esc_sql($curdate)."'";
                $data['todayresume'] = wpjobportaldb::get_var($query);
            }
        }
        return $data;
    }

    /**
    * @param wp job portal
    * Image Extension's
    */

    function checkImageFileExtensions($file_name, $file_tmp, $image_extension_allow) {
        $allow_image_extension = wpjobportalphplib::wpJP_explode(',', $image_extension_allow);
        if ($file_name != "" AND $file_tmp != "") {
            $ext = $this->getExtension($file_name);
            $ext = wpjobportalphplib::wpJP_strtolower($ext);
            if (in_array($ext, $allow_image_extension))
                return true;
            else
                return false;
        }
    }

    function checkDocumentFileExtensions($file_name, $file_tmp, $document_extension_allow) {
        $allow_document_extension = wpjobportalphplib::wpJP_explode(',', $document_extension_allow);
        if ($file_name != '' AND $file_tmp != "") {
            $ext = $this->getExtension($file_name);
            $ext = wpjobportalphplib::wpJP_strtolower($ext);
            if (in_array($ext, $allow_document_extension))
                return true;
            else
                return false;
        }
    }

    function getExtension($str) {
        if($str == ''){
            return "";
        }
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = wpjobportalphplib::wpJP_strlen($str) - $i;
        $ext = wpjobportalphplib::wpJP_substr($str, $i + 1, $l);
        return $ext;
    }

    function makeDir($path) {
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        if (!$wp_filesystem->exists($path)) { // create directory
            $wp_filesystem->mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = $wp_filesystem->put_contents($ourFileName,'');
            if($ourFileHandle !== false){
            }else{
                die("can't open file (".esc_html($ourFileName).")");
            }

        }
    }
    function WPJPcheck_field() {
		$autfored = WPJOBPORTALincluder::getJSModel('wpjobportal')->WPJPcheck_autfored(); 
        $encrypted_field = openssl_encrypt('ed', 'AES-128-ECB', $autfored);
        $option_name = 'wjportal_ed';
        $stored_data = get_option($option_name);

        if ($stored_data) {
            if ($stored_data['encrypted_field_name'] === $encrypted_field) {
                return true; // Match found
            }
        }
        return false; // No match
    }

    function getJobtempModelFrontend() {
        $componentPath = JPATH_SITE . '/components/com_wpjobportal';
        require_once $componentPath . '/models/jobtemp.php';
        $jobtemp_model = new WPJOBPORTALModelJobtemp();
        return $jobtemp_model;
    }

    function getSalaryRangeView($type, $min, $max, $currency=""){
        $salary = '';
        $currencysymbol =  isset($currency) ? $currency : wpjobportal::$_config->getConfigValue('job_currency');
        $currency_align = wpjobportal::$_config->getConfigValue('currency_align');
        // $min = wpjobportalphplib::wpJP_number_format((float)$min,2);
        // $max = wpjobportalphplib::wpJP_number_format((float)$max,2);
    if($min){
      if(fmod($min, 1) !== 0.00){
            $min = wpjobportalphplib::wpJP_number_format((float)$min,2);
        }else{
            $min = wpjobportalphplib::wpJP_number_format((float)$min);
        }
    }
      if($max){
          if(fmod($max, 1) !== 0.00){
              $max = wpjobportalphplib::wpJP_number_format((float)$max,2);
          }else{
              $max = wpjobportalphplib::wpJP_number_format((float)$max);
          }
      }
      
        if($type == 1){
            $salary = esc_html(__("Negotiable",'wp-job-portal'));
        }else if($type == 2){
            if($currency_align == 1){ // Left align
                $salary = $currencysymbol . ' ' . $min;
            }else if($currency_align == 2) { // Right align
                $salary = $min . ' ' . $currencysymbol;
            }
        }else if($type == 3){
            if($currency_align == 1){ // Left align
                $salary = $currencysymbol . ' ' . $min . ' - ' . $max;
            }else if($currency_align == 2){ // Right align
                $salary = $min . ' - ' . $max . ' ' . $currencysymbol;
            }
        }

        if(!empty($salary)){
            return $salary;
        }
    }

    function getYearMonth($args=array()){
            $previousTimeStamp = gmdate('Y-m-d',strtotime($args['originalDate']));
            $lastTimeStamp = gmdate('Y-m-d',strtotime($args['currentDate']));
            $diff = abs(strtotime($previousTimeStamp) - strtotime($lastTimeStamp));
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            return array('month'=>$months,'days'=>$days,'years'=>$years);
    }

    function getLocationForView($cityname, $statename, $countryname) {
        $location = $cityname;
        $defaultaddressdisplaytype = wpjobportal::$_configuration['defaultaddressdisplaytype'];
        switch ($defaultaddressdisplaytype) {
            case 'csc':
                if ($statename)
                    $location .= ', ' . wpjobportal::wpjobportal_getVariableValue($statename);
                if ($countryname)
                    $location .= ', ' . wpjobportal::wpjobportal_getVariableValue($countryname);
                break;
            case 'cs':
                if ($statename)
                    $location .= ', ' . wpjobportal::wpjobportal_getVariableValue($statename);
                break;
            case 'cc':
                if ($countryname)
                    $location .= ', ' . wpjobportal::wpjobportal_getVariableValue($countryname);
                break;
        }
        return $location;
    }

    function getUidByObjectId($actionfor, $id) {
        if (!is_numeric($id))
            return false;
        switch ($actionfor) {
            case'company':
                $table = 'wj_portal_companies';
                break;
            case'job':
                $table = 'wj_portal_jobs';
                break;
            case'resume':
                $table = 'wj_portal_resume';
                break;
        }
        $query = "SELECT uid FROM `" . wpjobportal::$_db->prefix . $table . "`WHERE id = " . esc_sql($id);
        $result = wpjobportaldb::get_var($query);

        return $result;
    }

    public function makeFilterdOrEditedTagsToReturn($tags) {
        if (empty($tags))
            return null;
        $temparray = wpjobportalphplib::wpJP_explode(',', $tags);
        $array = array();
        for ($i = 0; $i < count($temparray); $i++) {
            $array[] = array('id' => $temparray[$i], 'name' => $temparray[$i]);
        }
        return wp_json_encode($array);
    }

    function saveNewInWPJOBPORTAL($data) {
        if (empty($data))
            return false;

        $allow_reg_as_emp = wpjobportal::$_config->getConfigurationByConfigName('showemployerlink');
        if($allow_reg_as_emp != 1){
            $data['roleid '] = 2;
        }
        if(isset($data['socialmedia']) && !empty($data['socialid'])){
            $data['uid'] = "";
            $data['socialmedia'] = $data['socialmedia'];
        } else {
            $currentuser = get_userdata(get_current_user_id());
            $data['socialid'] = '';
            $data['socialmedia'] = '';
            $data['first_name'] = $currentuser->first_name;
            $data['last_name'] = $currentuser->last_name;
            $data['emailaddress'] = $currentuser->user_email;
            $data['uid'] = $currentuser->ID;
        }
     
        $row = WPJOBPORTALincluder::getJSTable('users');
        $data['status'] = 1; // all user autoapprove when registered as WP Job Portal users
        $data['created'] = gmdate('Y-m-d H:i:s');
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->check()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

	if (isset($_COOKIE['first_name'])){
		wpjobportalphplib::wpJP_setcookie('first_name' , '' , time() + 0 , COOKIEPATH);
		if ( SITECOOKIEPATH != COOKIEPATH ){
		wpjobportalphplib::wpJP_setcookie('first_name' , '' , time() + 0 , SITECOOKIEPATH);
		}
	}
	if (isset($_COOKIE['last_name'])){
		wpjobportalphplib::wpJP_setcookie('last_name' , '', time() + 0 , COOKIEPATH);
		if ( SITECOOKIEPATH != COOKIEPATH ){
		wpjobportalphplib::wpJP_setcookie('last_name' , '', time() + 0 , SITECOOKIEPATH);
		}
	}
	if (isset($_COOKIE['email'])){
		wpjobportalphplib::wpJP_setcookie('email' , '', time() + 0 , COOKIEPATH);
		if ( SITECOOKIEPATH != COOKIEPATH ){
		wpjobportalphplib::wpJP_setcookie('email' , '', time() + 0 , SITECOOKIEPATH);
		}
	}
        return WPJOBPORTAL_SAVED;
    }

    function parseID($id){
        if(is_numeric($id)) return $id;
        // php 8 issue explod function
        if($id == ''){
            return $id;
        }
        $id = wpjobportalphplib::wpJP_explode('-', $id);
        $id = $id[count($id) -1];
        return $id;
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '') {
        if (!$senderName)
            $senderName = wpjobportal::$_configuration['title'];
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        add_filter('wp_mail_content_type', function(){return "text/html";});
        $body = wpjobportalphplib::wpJP_preg_replace('/\r?\n|\r/', '<br/>', $body);
        $body = wpjobportalphplib::wpJP_str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        $body = nl2br($body);
        $result = wp_mail($recevierEmail, $subject, $body, $headers, $attachments);
        return $result;
    }

    function jsMakeRedirectURL($module, $layout, $for, $cpfor = null){
        if(empty($module) AND empty($layout) AND empty($for))
            return null;

        $finalurl = '';
        if( $for == 1 ){ // login links
            $jsthisurl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$module, 'wpjobportallt'=>$layout));
            $jsthisurl = wpjobportalphplib::wpJP_safe_encoding($jsthisurl);
            $finalurl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'wpjobportal', 'wpjobportallt'=>'login', 'wpjobportalredirecturl'=>$jsthisurl));
        }

        return $finalurl;
    }

    function getCitiesForFilter($cities){
        if(empty($cities))
            return NULL;


        $cities = wpjobportalphplib::wpJP_explode(',', $cities);
        $result = array();

        $defaultaddressdisplaytype = wpjobportal::$_config->getConfigurationByConfigName('defaultaddressdisplaytype');

        foreach ($cities as $city) {
            if(!is_numeric($city)){
                continue;
            }
            $query = "SELECT city.id AS id, CONCAT(city.name";
            switch ($defaultaddressdisplaytype) {
                case 'csc'://City, State, Country
                    $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                    break;
                case 'cs'://City, State
                    $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                    break;
                case 'cc'://City, Country
                    $query .= " ,', ', country.name)";
                    break;
                case 'c'://city by default select for each case
                    $query .= ")";
                    break;
            }
            $query .= " AS name ";
            $query .= " FROM `".wpjobportal::$_db->prefix."wj_portal_cities` AS city
                        JOIN `".wpjobportal::$_db->prefix."wj_portal_countries` AS country on city.countryid=country.id
                        LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_states` AS state on city.stateid=state.id
                        WHERE country.enabled = 1 AND city.enabled = 1";
            $query .= " AND city.id =".esc_sql($city);


            $result[] = wpjobportaldb::get_row($query);
        }
        if(!empty($result)){
            return wp_json_encode($result);
        }else{
            return NULL;
        }
    }
    function getMessagekey(){
        $key = 'common';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function stripslashesFull($input){// testing this function/.
        if($input == ''){
            return $input;
        }
        if (is_array($input)) {
            $input = array_map(array($this,'stripslashesFull'), $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = stripslashesFull($v);
            }
        } else {
            $input = wpjobportalphplib::wpJP_stripslashes($input);
        }
        return $input;
    }

    function validateEmployerArea(){
        // first handle visitor case to show appropriate message to visitor
        $cuser = WPJOBPORTALincluder::getObjectClass('user');
        if($cuser->isguest()){
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            $layout = WPJOBPORTALrequest::getLayout('wpjobportallt',null,'');
            $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL($module, $layout, 1);
            $linktext = esc_html(__('Login','wp-job-portal'));
            throw new Exception( wp_kses(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1),WPJOBPORTAL_ALLOWED_TAGS) );
        }
        $employerAreaEnabled = wpjobportal::$_config->getConfigValue('disable_employer');
        if(!$employerAreaEnabled){
            throw new Exception( wp_kses(WPJOBPORTALLayout::setMessageFor(5,null,null,1),WPJOBPORTAL_ALLOWED_TAGS) );
        }
        if(!$cuser->isemployer()){
            if($cuser->isjobseeker()){
               throw new Exception( wp_kses(WPJOBPORTALLayout::setMessageFor(2,null,null,1),WPJOBPORTAL_ALLOWED_TAGS) );
            }
            if(!$cuser->isWPJOBPortalUser()){
                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal'));
                $linktext = esc_html(__('Select role','wp-job-portal'));
                throw new Exception( wp_kses(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1),WPJOBPORTAL_ALLOWED_TAGS) );
            }
        }
    }

    function getMessagesForAddMore($module){
        $linktext = esc_html(__('You are Not Allowed To Add More than One','wp-job-portal').'&nbsp;'.wpjobportal::wpjobportal_getVariableValue($module).' !'. __('Contact TO Adminstrator', 'wp-job-portal'));
        wpjobportal::$_error_flag = true;
        throw new Exception(wp_kses(WPJOBPORTALLayout::setMessageFor(16,'',$module,1),WPJOBPORTAL_ALLOWED_TAGS));
    }

    function getBuyErrMsg(){
        $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'package', 'wpjobportallt'=>'packages'));
        $linktext = esc_html(__('Buy Package', 'wp-job-portal'));
        wpjobportal::$_error_flag = true;
        wpjobportal::$_error_flag_message_for=15;
        throw new Exception(wp_kses(WPJOBPORTALLayout::setMessageFor(15,$link,$linktext,1),WPJOBPORTAL_ALLOWED_TAGS));
    }


    function getCalendarDateFormat(){
        static $js_scriptdateformat;
        if ($js_scriptdateformat) {
            return $js_scriptdateformat;
        }
        if (wpjobportal::$_configuration['date_format'] == 'm/d/Y' || wpjobportal::$_configuration['date_format'] == 'd/m/y' || wpjobportal::$_configuration['date_format'] == 'm/d/y' || wpjobportal::$_configuration['date_format'] == 'd/m/Y') {
            $dash = '/';
        } else {
            $dash = '-';
        }
        $dateformat = wpjobportal::$_configuration['date_format'];
        $firstdash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, 0);
        $firstvalue = wpjobportalphplib::wpJP_substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, $firstdash);
        $secondvalue = wpjobportalphplib::wpJP_substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = wpjobportalphplib::wpJP_substr($dateformat, $seconddash, wpjobportalphplib::wpJP_strlen($dateformat) - $seconddash);
        $js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
        $js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
        $js_scriptdateformat = wpjobportalphplib::wpJP_str_replace('Y', 'yy', $js_scriptdateformat);
        return $js_scriptdateformat;
    }

    function getProductDesc($id){
        $name = '';
        $parse = wpjobportalphplib::wpJP_explode('-', $id);
        $moduleid = $parse[1];
        $configname = $parse[0];
        if(is_array($parse) && !empty($parse)){
            if(!empty($id)){
                switch ($configname) {
                    case 'job_currency_department_perlisting':
                        //print_r(WPJOBPORTALincluder::getJSModel('departments')->getDepartmentById($moduleid));
                        break;
                    case 'company_price_perlisting':
                        break;
                    case 'company_feature_price_perlisting':
                        break;
                    case 'job_currency_price_perlisting':
                        break;
                    case 'jobs_feature_price_perlisting':
                        break;
                    case 'job_resume_price_perlisting':
                        break;
                    case 'job_featureresume_price_perlisting':
                        break;
                    case 'job_jobalert_price_perlisting':
                        break;
                    case 'job_resumesavesearch_price_perlisting':
                        $name = WPJOBPORTALincluder::getJSModel('resumesearch')->getResumeSearchName($moduleid);
                        # Resume Search Payment
                        break;
                   default:
                        # code...
                        break;
                }
                return $name;
            }
        }
    }

    function listModuleJobsStats($classname, $title, $showtitle, $employers, $jobseekers, $jobs, $companies, $activejobs, $resumes, $todaystats,$data){
        $my_html = '
            <div id="wpjobportals_mod_wrapper" class="wjportal-stats-mod"> ';
		$my_html .= '<div id="wpjobportals-mod-heading" class="wjportal-mod-heading"> ' . esc_html(__('Stats', 'wp-job-portal')) . '</div>';

        $my_html .='
                <div id="wpjobportals-data-wrapper" class="' . $classname . ' wjportal-stats">';
        $curdate = gmdate('Y-m-d');
        if ($employers == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Employer', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['employer'] . ')</span></div>';
        }
        if ($jobseekers == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Job seeker', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['jobseeker'] . ')</span></div>';
        }
        if ($jobs == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Jobs', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['totaljobs'] . ')</span></div>';
        }
        if ($companies == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Companies', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['totalcompanies'] . ')</span></div>';
        }
        if ($activejobs == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Active Jobs', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['tatalactivejobs'] . ')</span></div>';
        }
        if ($resumes == 1) {
            $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Resume', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['totalresume'] . ')</span></div>';
        }
        if ($todaystats == 1) {
			$my_html .= '</div> <div id="wpjobportals-mod-heading" class="wjportal-mod-heading"> ' . esc_html(__('Today Stats', 'wp-job-portal')) . '</div>';
            $my_html .='
                <div id="wpjobportals-data-wrapper" class="' . $classname . ' wjportal-stats">';
            if ($employers == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Employer', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['todyemployer'] . ')</span></div>';
            }
            if ($jobseekers == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Job seeker', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['todyjobseeker'] . ')</span></div>';
            }
            if ($jobs == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Jobs', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['todayjobs'] . ')</span></div>';
            }
            if ($companies == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Companies', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['todaycompanies'] . ')</span></div>';
            }
            if ($activejobs == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Active Jobs', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['todayactivejobs'] . ')</span></div>';
            }
            if ($resumes == 1) {
                $my_html .='<div class="wpjobportals-value wjportal-stats-data">' . esc_html(__('Resume', 'wp-job-portal')) . ' <span class="wjportal-stats-num">(' . $data['todayresume'] . ')</span></div>';
            }
            $my_html .= '</div>';
        }

        $my_html .= '</div>';
        return $my_html;
    }

    function getSearchFormDataOnlySort($jstlay){
        if($jstlay == 'activitylog'){
            $val1 = 4;
        }else{
            $val1 = 6;
        }
        $jsjp_search_array = array();
        $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton', 'post', $val1);
        $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby', 'post', 2);
        $jsjp_search_array['search_from_myapply_myjobs'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedOnlySortandOrder(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_myapply_myjobs']) && $wpjp_search_cookie_data['search_from_myapply_myjobs'] == 1){
            $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
            $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableOnlySortandOrder($jsjp_search_array,$jstlay){
        if($jstlay == 'activitylog'){
            $val1 = 4;
        }else{
            $val1 = 6;
        }
        wpjobportal::$_search['jobs']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : $val1;
        wpjobportal::$_search['jobs']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
    }

    function getServerProtocol(){
        static $protocol;
        if ($protocol) {
            return $protocol;
        }
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol;
    }

    function wpjp_isadmin(){
        if (current_user_can('manage_options')) {
            return true;
        } else {
            return false;
        }
    }

    function getSearchFormDataAdmin(){
        $jsjp_search_array = array();
        $jsjp_search_array['departmentname'] = WPJOBPORTALrequest::getVar('departmentname','','');
        $jsjp_search_array['companyname'] = WPJOBPORTALrequest::getVar('companyname','','');
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status','','');

        $jsjp_search_array['title'] = WPJOBPORTALrequest::getVar('title','','');
        $jsjp_search_array['name'] = WPJOBPORTALrequest::getVar('name','','');

        if($jsjp_search_array['title'] == ''){// to handle job applied resume case
            $jsjp_search_array['title'] = WPJOBPORTALrequest::getVar('application_title','','');
        }
        if($jsjp_search_array['name'] == ''){// to handle job applied resume case
            $jsjp_search_array['name'] = WPJOBPORTALrequest::getVar('applicantname','','');
        }


        $jsjp_search_array['ustatus'] = WPJOBPORTALrequest::getVar('ustatus','','');
        $jsjp_search_array['vstatus'] = WPJOBPORTALrequest::getVar('vstatus','','');
        $jsjp_search_array['required'] = WPJOBPORTALrequest::getVar('required','','');

        $jsjp_search_array['nationality'] = WPJOBPORTALrequest::getVar('nationality','','');
        $jsjp_search_array['jobcategory'] = WPJOBPORTALrequest::getVar('jobcategory','','');
        $jsjp_search_array['gender'] = WPJOBPORTALrequest::getVar('gender','','');
        $jsjp_search_array['jobtype'] = WPJOBPORTALrequest::getVar('jobtype','','');
        $jsjp_search_array['currency'] = WPJOBPORTALrequest::getVar('currency','','');
        $jsjp_search_array['jobsalaryrange'] = WPJOBPORTALrequest::getVar('jobsalaryrange','','');
        $jsjp_search_array['heighestfinisheducation'] = WPJOBPORTALrequest::getVar('heighestfinisheducation','','');

        $jsjp_search_array['coverlettertitle'] = WPJOBPORTALrequest::getVar('coverlettertitle','','');

        $jsjp_search_array['username'] = WPJOBPORTALrequest::getVar('username','','');
        //$jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status','','');
        $jsjp_search_array['currencyid'] = WPJOBPORTALrequest::getVar('currencyid','','');
        $jsjp_search_array['type'] = WPJOBPORTALrequest::getVar('type','','');


        $jsjp_search_array['foldername'] = WPJOBPORTALrequest::getVar('foldername','','');

        $jsjp_search_array['searchcompany'] = WPJOBPORTALrequest::getVar('searchcompany','','');
        $jsjp_search_array['searchcompcategory'] = WPJOBPORTALrequest::getVar('searchcompcategory','','');

        //$jsjp_search_array['searchcompany'] = WPJOBPORTALrequest::getVar('searchcompany','','');
        $jsjp_search_array['searchjobcategory'] = WPJOBPORTALrequest::getVar('searchjobcategory','','');
        $jsjp_search_array['datestart'] = WPJOBPORTALrequest::getVar('datestart','','');
        $jsjp_search_array['dateend'] = WPJOBPORTALrequest::getVar('dateend','','');
        $jsjp_search_array['featured'] = WPJOBPORTALrequest::getVar('featured','','');

        $jsjp_search_array['wpjobportal_city'] = WPJOBPORTALrequest::getVar('wpjobportal-city','','');
        $jsjp_search_array['wpjobportal_company'] = WPJOBPORTALrequest::getVar('wpjobportal-company','','');

        $jsjp_search_array['jobseeker'] = WPJOBPORTALrequest::getVar('jobseeker','','');
        $jsjp_search_array['employer'] = WPJOBPORTALrequest::getVar('employer','','');
        $jsjp_search_array['read'] = WPJOBPORTALrequest::getVar('read','','');
        $jsjp_search_array['company'] = WPJOBPORTALrequest::getVar('company','','');
        $jsjp_search_array['searchjobtitle'] = WPJOBPORTALrequest::getVar('searchjobtitle','','');
        $jsjp_search_array['searchresumetitle'] = WPJOBPORTALrequest::getVar('searchresumetitle','','');
        $jsjp_search_array['resumetitle'] = WPJOBPORTALrequest::getVar('resumetitle','','');
        $jsjp_search_array['jobtitle'] = WPJOBPORTALrequest::getVar('jobtitle','','');
        $jsjp_search_array['subject'] = WPJOBPORTALrequest::getVar('subject','','');
        $jsjp_search_array['searchsubject'] = WPJOBPORTALrequest::getVar('searchsubject','','');
        $jsjp_search_array['conflicted'] = WPJOBPORTALrequest::getVar('conflicted','','');

        //$jsjp_search_array['title'] = WPJOBPORTALrequest::getVar('title','','');
        $jsjp_search_array['email'] = WPJOBPORTALrequest::getVar('email','','');
        $jsjp_search_array['location'] = WPJOBPORTALrequest::getVar('location','','');
        $jsjp_search_array['category'] = WPJOBPORTALrequest::getVar('category','','');
        $jsjp_search_array['alertstatus'] = WPJOBPORTALrequest::getVar('alertstatus','','');
        //$jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status','','');

        $jsjp_search_array['searchname'] = WPJOBPORTALrequest::getVar('searchname','','');

        $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton','',3);
        $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby','',2);

        // job applied resume layout
        // $jsjp_search_array['application_title'] = WPJOBPORTALrequest::getVar('application_title','','');
        // $jsjp_search_array['applicantname'] = WPJOBPORTALrequest::getVar('applicantname','','');


        $jsjp_search_array['search_from_admin_listing'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedAdmin(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data, true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_admin_listing']) && $wpjp_search_cookie_data['search_from_admin_listing'] == 1){
            $jsjp_search_array['departmentname'] = $wpjp_search_cookie_data['departmentname'];
            $jsjp_search_array['companyname'] = $wpjp_search_cookie_data['companyname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];

            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['ustatus'] = $wpjp_search_cookie_data['ustatus'];
            $jsjp_search_array['vstatus'] = $wpjp_search_cookie_data['vstatus'];
            $jsjp_search_array['required'] = $wpjp_search_cookie_data['required'];

            //$jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['name'] = $wpjp_search_cookie_data['name'];
            $jsjp_search_array['nationality'] = $wpjp_search_cookie_data['nationality'];
            $jsjp_search_array['jobcategory'] = $wpjp_search_cookie_data['jobcategory'];
            $jsjp_search_array['gender'] = $wpjp_search_cookie_data['gender'];
            $jsjp_search_array['jobtype'] = $wpjp_search_cookie_data['jobtype'];
            $jsjp_search_array['currency'] = $wpjp_search_cookie_data['currency'];
            $jsjp_search_array['jobsalaryrange'] = $wpjp_search_cookie_data['jobsalaryrange'];
            $jsjp_search_array['heighestfinisheducation'] = $wpjp_search_cookie_data['heighestfinisheducation'];

            $jsjp_search_array['coverlettertitle'] = $wpjp_search_cookie_data['coverlettertitle'];
            //$jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];

            $jsjp_search_array['username'] = $wpjp_search_cookie_data['username'];
            //$jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['currencyid'] = $wpjp_search_cookie_data['currencyid'];
            $jsjp_search_array['type'] = $wpjp_search_cookie_data['type'];


            $jsjp_search_array['foldername'] = $wpjp_search_cookie_data['foldername'];

            $jsjp_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjp_search_array['searchcompcategory'] = $wpjp_search_cookie_data['searchcompcategory'];
            $jsjp_search_array['wpjobportal_city'] = $wpjp_search_cookie_data['wpjobportal_city'];

            //$jsjp_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjp_search_array['searchjobcategory'] = $wpjp_search_cookie_data['searchjobcategory'];
            $jsjp_search_array['datestart'] = $wpjp_search_cookie_data['datestart'];
            $jsjp_search_array['dateend'] = $wpjp_search_cookie_data['dateend'];
            $jsjp_search_array['featured'] = $wpjp_search_cookie_data['featured'];

            $jsjp_search_array['wpjobportal_company'] = $wpjp_search_cookie_data['wpjobportal_company'];

            $jsjp_search_array['jobseeker'] = $wpjp_search_cookie_data['jobseeker'];
            $jsjp_search_array['employer'] = $wpjp_search_cookie_data['employer'];
            $jsjp_search_array['read'] = $wpjp_search_cookie_data['read'];
            $jsjp_search_array['company'] = $wpjp_search_cookie_data['company'];
            $jsjp_search_array['jobtitle'] = $wpjp_search_cookie_data['jobtitle'];
            $jsjp_search_array['resumetitle'] = $wpjp_search_cookie_data['resumetitle'];
            $jsjp_search_array['subject'] = $wpjp_search_cookie_data['subject'];
            $jsjp_search_array['conflicted'] = $wpjp_search_cookie_data['conflicted'];

            $jsjp_search_array['searchjobtitle'] = $wpjp_search_cookie_data['searchjobtitle'];
            $jsjp_search_array['searchresumetitle'] = $wpjp_search_cookie_data['searchresumetitle'];
            $jsjp_search_array['searchsubject'] = $wpjp_search_cookie_data['searchsubject'];

            //$jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['email'] = $wpjp_search_cookie_data['email'];
            $jsjp_search_array['location'] = $wpjp_search_cookie_data['location'];
            $jsjp_search_array['category'] = $wpjp_search_cookie_data['category'];
            $jsjp_search_array['alertstatus'] = $wpjp_search_cookie_data['alertstatus'];
            //$jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];

            $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];


            // $jsjp_search_array['application_title'] = $wpjp_search_cookie_data['application_title'];
            // $jsjp_search_array['applicantname'] = $wpjp_search_cookie_data['applicantname'];


            $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
            $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableAdmin($jsjp_search_array){
        wpjobportal::$_search['search_filter']['departmentname'] = isset($jsjp_search_array['departmentname']) ? $jsjp_search_array['departmentname'] : null;
        wpjobportal::$_search['search_filter']['companyname'] = isset($jsjp_search_array['companyname']) ? $jsjp_search_array['companyname'] : null;
        wpjobportal::$_search['search_filter']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;

        wpjobportal::$_search['search_filter']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : null;
        wpjobportal::$_search['search_filter']['ustatus'] = isset($jsjp_search_array['ustatus']) ? $jsjp_search_array['ustatus'] : null;
        wpjobportal::$_search['search_filter']['vstatus'] = isset($jsjp_search_array['vstatus']) ? $jsjp_search_array['vstatus'] : null;
        wpjobportal::$_search['search_filter']['required'] = isset($jsjp_search_array['required']) ? $jsjp_search_array['required'] : null;

        //wpjobportal::$_search['search_filter']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : null;
        wpjobportal::$_search['search_filter']['name'] = isset($jsjp_search_array['name']) ? $jsjp_search_array['name'] : null;
        wpjobportal::$_search['search_filter']['nationality'] = isset($jsjp_search_array['nationality']) ? $jsjp_search_array['nationality'] : null;
        wpjobportal::$_search['search_filter']['jobcategory'] = isset($jsjp_search_array['jobcategory']) ? $jsjp_search_array['jobcategory'] : null;
        wpjobportal::$_search['search_filter']['gender'] = isset($jsjp_search_array['gender']) ? $jsjp_search_array['gender'] : null;
        wpjobportal::$_search['search_filter']['jobtype'] = isset($jsjp_search_array['jobtype']) ? $jsjp_search_array['jobtype'] : null;
        wpjobportal::$_search['search_filter']['currency'] = isset($jsjp_search_array['currency']) ? $jsjp_search_array['currency'] : null;
        wpjobportal::$_search['search_filter']['jobsalaryrange'] = isset($jsjp_search_array['jobsalaryrange']) ? $jsjp_search_array['jobsalaryrange'] : null;
        wpjobportal::$_search['search_filter']['heighestfinisheducation'] = isset($jsjp_search_array['heighestfinisheducation']) ? $jsjp_search_array['heighestfinisheducation'] : null;

        wpjobportal::$_search['search_filter']['coverlettertitle'] = isset($jsjp_search_array['coverlettertitle']) ? $jsjp_search_array['coverlettertitle'] : null;
        //wpjobportal::$_search['search_filter']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;

        wpjobportal::$_search['search_filter']['username'] = isset($jsjp_search_array['username']) ? $jsjp_search_array['username'] : null;
        // wpjobportal::$_search['search_filter']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
        wpjobportal::$_search['search_filter']['currencyid'] = isset($jsjp_search_array['currencyid']) ? $jsjp_search_array['currencyid'] : null;
        wpjobportal::$_search['search_filter']['type'] = isset($jsjp_search_array['type']) ? $jsjp_search_array['type'] : null;

        wpjobportal::$_search['search_filter']['name'] = isset($jsjp_search_array['name']) ? $jsjp_search_array['name'] : null;
        wpjobportal::$_search['search_filter']['foldername'] = isset($jsjp_search_array['foldername']) ? $jsjp_search_array['foldername'] : null;

        wpjobportal::$_search['search_filter']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : null;
        wpjobportal::$_search['search_filter']['searchcompcategory'] = isset($jsjp_search_array['searchcompcategory']) ? $jsjp_search_array['searchcompcategory'] : null;
        wpjobportal::$_search['search_filter']['wpjobportal_city'] = isset($jsjp_search_array['wpjobportal_city']) ? $jsjp_search_array['wpjobportal_city'] : null;

        //wpjobportal::$_search['search_filter']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : null;
        wpjobportal::$_search['search_filter']['searchjobcategory'] = isset($jsjp_search_array['searchjobcategory']) ? $jsjp_search_array['searchjobcategory'] : null;
        wpjobportal::$_search['search_filter']['datestart'] = isset($jsjp_search_array['datestart']) ? $jsjp_search_array['datestart'] : null;
        wpjobportal::$_search['search_filter']['dateend'] = isset($jsjp_search_array['dateend']) ? $jsjp_search_array['dateend'] : null;
        wpjobportal::$_search['search_filter']['featured'] = isset($jsjp_search_array['featured']) ? $jsjp_search_array['featured'] : null;

        wpjobportal::$_search['search_filter']['wpjobportal_company'] = isset($jsjp_search_array['wpjobportal_company']) ? $jsjp_search_array['wpjobportal_company'] : null;

        wpjobportal::$_search['search_filter']['jobseeker'] = isset($jsjp_search_array['jobseeker']) ? $jsjp_search_array['jobseeker'] : null;
        wpjobportal::$_search['search_filter']['employer'] = isset($jsjp_search_array['employer']) ? $jsjp_search_array['employer'] : null;
        wpjobportal::$_search['search_filter']['read'] = isset($jsjp_search_array['read']) ? $jsjp_search_array['read'] : null;
        wpjobportal::$_search['search_filter']['company'] = isset($jsjp_search_array['company']) ? $jsjp_search_array['company'] : null;
        wpjobportal::$_search['search_filter']['jobtitle'] = isset($jsjp_search_array['jobtitle']) ? $jsjp_search_array['jobtitle'] : null;
        wpjobportal::$_search['search_filter']['resumetitle'] = isset($jsjp_search_array['resumetitle']) ? $jsjp_search_array['resumetitle'] : null;
        wpjobportal::$_search['search_filter']['subject'] = isset($jsjp_search_array['subject']) ? $jsjp_search_array['subject'] : null;
        wpjobportal::$_search['search_filter']['conflicted'] = isset($jsjp_search_array['conflicted']) ? $jsjp_search_array['conflicted'] : null;

        wpjobportal::$_search['search_filter']['searchjobtitle'] = isset($jsjp_search_array['searchjobtitle']) ? $jsjp_search_array['searchjobtitle'] : null;
        wpjobportal::$_search['search_filter']['searchresumetitle'] = isset($jsjp_search_array['searchresumetitle']) ? $jsjp_search_array['searchresumetitle'] : null;
        wpjobportal::$_search['search_filter']['searchsubject'] = isset($jsjp_search_array['searchsubject']) ? $jsjp_search_array['searchsubject'] : null;

        //wpjobportal::$_search['search_filter']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : null;
        wpjobportal::$_search['search_filter']['email'] = isset($jsjp_search_array['email']) ? $jsjp_search_array['email'] : null;
        wpjobportal::$_search['search_filter']['location'] = isset($jsjp_search_array['location']) ? $jsjp_search_array['location'] : null;
        wpjobportal::$_search['search_filter']['category'] = isset($jsjp_search_array['category']) ? $jsjp_search_array['category'] : null;
        wpjobportal::$_search['search_filter']['alertstatus'] = isset($jsjp_search_array['alertstatus']) ? $jsjp_search_array['alertstatus'] : null;
        //wpjobportal::$_search['search_filter']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;

        wpjobportal::$_search['search_filter']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : null;

        wpjobportal::$_search['search_filter']['application_title'] = isset($jsjp_search_array['application_title']) ? $jsjp_search_array['application_title'] : null;
        wpjobportal::$_search['search_filter']['applicantname'] = isset($jsjp_search_array['applicantname']) ? $jsjp_search_array['applicantname'] : null;

        wpjobportal::$_search['search_filter']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : null;
        wpjobportal::$_search['search_filter']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : null;
    }

    function getDefaultImage($role){
        // job seeker deafult image
        if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled()){
            $url = ELEGANTDESIGN_PLUGIN_URL;
        } else {
            $url = WPJOBPORTAL_PLUGIN_URL;
        }
        $img_path = esc_url($url) . "includes/images/users.png";

        // employer default image
        if($role == 'employer'){
            $img_path = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/default_logo.png";
        }

        // admin set image
        if(!empty(wpjobportal::$_configuration['default_image'])){
            $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
            $wpdir = wp_upload_dir();
            $img_path = $wpdir['baseurl'] . '/' . $data_directory . '/data/default_image/' . wpjobportal::$_configuration['default_image'];
        }

        return $img_path;
    }

    function addWPSEOHooks($module_name,$file_name){
        $this->module_name = $module_name;
        $this->file_name = $file_name;
        add_filter( 'pre_get_document_title', array($this, 'WPJobPortalGetDocumentTitle'),99);
        //add_action("wp_head", array($this, "WPJobPortalMetaTags"));
    }

    function WPJobPortalGetDocumentTitle($title) {
        $module = $this->module_name;
        $layout = $this->file_name;
        // making sure our layout is being opened & making sure proper values are set
        if ($module != '' && $layout != '') {
            // get page title for current page
            $page_title = $this->getWPJobPortalDocumentTitleByPage($module, $layout);
            if($page_title != ''){
                $title = $page_title;
            }
        }
        return $title;
    }

    function getWPJobPortalDocumentTitleByPage($module='',$layout=''){
        if($module=='' && $layout==''){
            $layout = WPJOBPORTALrequest::getVar('wpjobportallt');
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
        }
        $title = '';
        if ($module != '' && $layout != '') {
            $title = $this->getPageTitleByFileName($layout,$module);
        }
        return $title;
    }

    function getPageTitleByFileName($layout,$module) {
        $page_title_val = '';
        $where_query = '';
        if($layout == 'controlpanel' || $layout == 'mystats'){
            $where_query = " AND modulename = '".esc_sql($module)."'";
        }

        $query = "SELECT pagetitle FROM `".wpjobportal::$_db->prefix."wj_portal_slug` WHERE filename = '".esc_sql($layout)."' ".$where_query;
        $page_title_val = wpjobportal::$_db->get_var($query);
        if($page_title_val != ''){
            // using switch to handle different layouts seprately
            switch ($layout) {
                case 'viewcompany':
                    $companyid = isset(wpjobportal::$_data[0]->id) ? wpjobportal::$_data[0]->id : '';
                    if($companyid == ''){
                        $companyid = WPJOBPORTALrequest::getVar('wpjobportalid');
                        $companyid = wpjobportal::$_common->parseID($companyid);
                    }
                    if(is_numeric($companyid) && $companyid > 0){
                        // below code is only here until the interface is not built properly
                        $company_title_options = get_option('wpjobportal_company_document_title_settings');
                        if(!empty($company_title_options)){
                            $page_title_val = $company_title_options;
                        }

                        $page_title_val = WPJOBPORTALincluder::getJSModel('company')->makeCompanySeoDocumentTitle($page_title_val , $companyid);
                    }
                break;
                case 'viewjob':
                    $jobid = isset(wpjobportal::$_data[0]->id) ? wpjobportal::$_data[0]->id : '';
                    if($jobid == ''){
                        $jobid = WPJOBPORTALrequest::getVar('wpjobportalid');
                        $jobid = wpjobportal::$_common->parseID($jobid);
                    }
                    if(is_numeric($jobid) && $jobid > 0){
                        // below code is only here until the interface is not built properly
                        $job_title_options = get_option('wpjobportal_job_document_title_settings');
                        if(!empty($job_title_options)){
                            $page_title_val = $job_title_options;
                        }

                        $page_title_val = WPJOBPORTALincluder::getJSModel('job')->makeJobSeoDocumentTitle($page_title_val , $jobid);
                    }
                break;
                case 'viewresume':
                    $resumeid = (!empty(wpjobportal::$_data[0]['personal_section']) && isset(wpjobportal::$_data[0]['personal_section']->id)) ? wpjobportal::$_data[0]['personal_section']->id : '';
                    if($resumeid == ''){
                        $resumeid = WPJOBPORTALrequest::getVar('wpjobportalid');
                        $resumeid = wpjobportal::$_common->parseID($resumeid);
                    }
                    if(is_numeric($resumeid) && $resumeid > 0){
                        // below code is only here until the interface is not built properly
                        $resume_title_options = get_option('wpjobportal_resume_document_title_settings');
                        if(!empty($resume_title_options)){
                            $page_title_val = $resume_title_options;
                        }

                        $page_title_val = WPJOBPORTALincluder::getJSModel('resume')->makeResumeSeoDocumentTitle($page_title_val , $resumeid);
                    }
                break;
                default: // for all other layouts
                    $matcharray = array(
                        '[separator]' => '|',
                        '[sitename]' => get_bloginfo( 'name', 'display' )
                    );
                    $page_title_val = $this->replaceMatches($page_title_val,$matcharray);
                    break;
            }
        }
        return $page_title_val;
    }

    function replaceMatches($string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = wpjobportalphplib::wpJP_str_replace($find, $replace, $string);
        }
        return $string;
    }

    function WPJobPortalMetaTags(){
        $module = $this->module_name;
        $layout = $this->file_name;
        // making sure our layout is being opened & making sure proper values are set
        if ($module != '' && $layout != '') {
            $description = $this->getWPJobPortalMetaDescriptionByPage($module, $layout);
            if(!empty($description)){
                echo '<meta name="description" content="'.esc_html($description).'"/>'."\n";
            }
        }
    }
	
    function isElegantDesignEnabled(){
        if(in_array('elegantdesign', wpjobportal::$_active_addons)){
            // check if addon is not properly installed
            if (!$this->WPJPcheck_field()) {
				if(!current_user_can('manage_options')){
					return false;
				}
            }
            return true;
        }

        return false;
    }

    function getWPJobPortalMetaDescriptionByPage($module,$layout){
        $description = '';
        if ($module != '' && $layout != '') {
            switch ($layout) {
                case 'controlpanel':
                    $description = esc_html(__('This Is Meta Description', 'wp-job-portal'));
                    break;
                }
        }
        return $description;
    }

    function checkLanguageSpecialCase(){
        $locale = get_locale();
        $locale = strtolower(substr($locale, 0,2));
        switch ($locale) {
            case 'ja':
            // case 'ja_JP':
                return false;
            break;
            case 'ko':
            // case 'ko_KR':
                return false;
            break;
            case 'es':
            // case 'es_ES':
                return false;
            break;
            case 'zh':
            // case 'zh_CN':
            // case 'zh_TW':
            // case 'zh_HK':
                return false;
            break;
            case 'el':
                return false;
            break;
            case 'de':
            //case 'de_DE':
                return false;
            break;

        }
        return true;
    }

    function encodeIdForDownload($resume_id){
        if( $resume_id == ''){
            return '';
        }
        $string_data = gmdate( 'Y-m-d H:i:s' )."Z".$resume_id;
        //$resume_id_string = (base64_encode($string_data));
        $resume_id_string = strtr(base64_encode($string_data), '+/', '-_');;
        return $resume_id_string;
    }

    function decodeIdForDownload($resume_id_string){
        if($resume_id_string == ''){
            return '';
        }

        $string_val = base64_decode($resume_id_string);
        $string_val = base64_decode(strtr($resume_id_string, '-_', '+/'));

        $string_array = explode('Z', $string_val);

        $date_time = $string_array[0];
        $current_time = gmdate( 'Y-m-d H:i:s' );

        $dateTime1 = new DateTime($date_time);
        $dateTime2 = new DateTime($current_time);

        // Calculate the difference
        // $interval = $dateTime1->diff($dateTime2);

        // Get the total difference in seconds
        $secondsDifference = abs($dateTime1->getTimestamp() - $dateTime2->getTimestamp());

        // Check if the difference is less than an hour (3600 seconds)
        if ($secondsDifference < 3600) {
            $resume_id = $string_array[1];
            return $resume_id;
        }

        return '';
    }


    function applyThresholdOnResults($results, $highest_score, $enitity_for) {
        if (empty($results)) {
            return $results; // Return early if no results
        }

        $threshold = 30; // Percentage threshold
        $highest_custom_score = $results[0]->custom_score ?? 0;

        // Calculate threshold values
        $custom_score_threshold_value = ($threshold / 100) * $highest_custom_score;
        $score_threshold_value = ($threshold / 100) * $highest_score;

        // Track highest scores for each jobid
        $unique_results = [];

        foreach ($results as $result) {
            // Skip results below the threshold (except the first result)
            if (
                ($result->custom_score <= $custom_score_threshold_value && $result !== $results[0]) &&
                ($result->score <= $score_threshold_value && $result !== $results[0])
            ) {
                continue;
            }

            if($result->custom_score == 0 && $result->score < 1.5) continue;
            // Ensure uniqueness by entitiy id, keeping the highest custom_score and then the highest score
            if($enitity_for == 1){
                $record_id = $result->jobid;
            }else{
                $record_id = $result->resumeid;
            }

            if (
                !isset($unique_results[$record_id]) ||
                $result->custom_score > $unique_results[$record_id]->custom_score ||
                ($result->custom_score === $unique_results[$record_id]->custom_score && $result->score > $unique_results[$record_id]->score)
            ) {
                $unique_results[$record_id] = $result;
            }

            if (!isset($unique_results[$record_id]) || $result->score > $unique_results[$record_id]->score) {
                $unique_results[$record_id] = $result;
            }
        }
        return array_values($unique_results); // Return reindexed array
    }

    function getUniqueIdForTransient() {
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        if ( is_numeric($uid) && $uid > 0 ) {
            $transient_id = 'user_' .$uid;
        } else { // Fallback: generate ID from IP + User Agent
            $ip = !empty($_SERVER['REMOTE_ADDR']) ? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) : '0.0.0.0';
            $useragent = !empty($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : 'unknown';
            $transient_id = $ip .'_'. $useragent;
        }
        $transient_id = base64_encode($transient_id);
        return  $transient_id;
    }

    function getRecordIdsForCurrentPage($job_ids_list, $page_num) {
        $current_records_to_show = '';
        if($job_ids_list !=''){
            if(!is_numeric($page_num) ){ // handle no page(first page)
                $page_num = 1;
            }
            // make an array
            $job_id_list_arrray = array_map('intval', explode(',', $job_ids_list)); // Convert to array of integers

            $pagination_size = wpjobportal::$_configuration['pagination_default_page_size'];; // How many per page

            // Calculate start index
            $page_num_offset = ($page_num - 1) * $pagination_size;

            // Get current slice of ids (array elements)
            $current_records_to_show_array = array_slice($job_id_list_arrray, $page_num_offset, $pagination_size);
            if(!empty($current_records_to_show_array)){
                $current_records_to_show = implode(',', $current_records_to_show_array); // create comma sperated string from array
            }
        }
        return $current_records_to_show;
    }

    function storeAIRecordsIDListTransient($job_ids_list, $transient_for) {
        switch ($transient_for) {
            case 1:
                $transient_string = 'ai_suggested_jobs_list_';
                break;
            case 2:
                $transient_string = 'ai_suggested_jobs_dashboard_';
                break;
            case 3:
                $transient_string = 'ai_websearch_jobs_list_';
                break;
            case 4:
                $transient_string = 'ai_suggested_resume_list_';
                break;
            case 5:
                $transient_string = 'ai_suggested_resume_dashboard_';
                break;
            case 6:
                $transient_string = 'ai_websearch_resume_list_';
                break;
            default:
                $transient_string = 'ai_suggested_jobs_list_';
                break;
        }
        // get unique transient id for current user/guesat
        $transient_id = WPJOBPORTALincluder::getJSModel('common')->getUniqueIdForTransient();
        // Store the data in a transient (expires in 1 hour)
        if($job_ids_list != '' &&  $transient_id != ''){ // making sure the data is not empty
            set_transient($transient_string.$transient_id, $job_ids_list, HOUR_IN_SECONDS);
        }
    }

    function getAIRecordsIdListFromTransient($transient_for){
        switch ($transient_for) {
            case 1:
                $transient_string = 'ai_suggested_jobs_list_';
                break;
            case 2:
                $transient_string = 'ai_suggested_jobs_dashboard_';
                break;
            case 3:
                $transient_string = 'ai_websearch_jobs_list_';
                break;
            case 4:
                $transient_string = 'ai_suggested_resume_list_';
                break;
            case 5:
                $transient_string = 'ai_suggested_resume_dashboard_';
                break;
            case 6:
                $transient_string = 'ai_websearch_resume_list_';
                break;
            default:
                $transient_string = 'ai_suggested_jobs_list_';
                break;
        }

        $result_list = '';
        // get unique transient id for current user/guesat
        $transient_id = WPJOBPORTALincluder::getJSModel('common')->getUniqueIdForTransient();
        // Store the data in a transient (expires in 1 hour)
        if($transient_id != ''){
            $result = get_transient($transient_string.$transient_id);
            if ($result !== false) { // if data is found
                $result_list = $result;
            }
        }
        return $result_list;
    }


    function updateRecordsForAISearch(){
       WPJOBPORTALincluder::getJSModel('job')->updateRecordsForAISearchJob();
        WPJOBPORTALincluder::getJSModel('resume')->updateRecordsForAISearchResume();
        update_option( 'wpjobportal_ai_search_data_sync_needed', 0,);
        return ;
    }

}
?>
