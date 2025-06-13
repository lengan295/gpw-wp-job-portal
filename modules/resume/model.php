<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALResumeModel {


    function getResumePercentage( $resumeid ){
        if(!is_numeric($resumeid))  return false;
        // get published sections first
        $list = $this->getPublishedSectionsList();
        $sections_status = array();
        foreach ($list as $key => $value) {
            $field = $value->field;
            $field = wpjobportalphplib::wpJP_explode('_', $field);
            $sections_status[$value->section] = array('name' => $field[1] , 'id' => $value->section, 'status' => 0);
        }
        // percentage fo personal section
        $percentage = 40;
        $number_of_sections = (int) count($list);
        if($number_of_sections == 0){
            $section_percentage = 0;
            $percentage = 100;
        }else{
            // how much percnetage will a section reprsent
            $section_percentage = 60 / $number_of_sections;
        }

        foreach ($sections_status as $key => $section) {
            if($section['id'] == 5 || $section['id'] == 6){
                $field = 'skills';
                if($section['id'] == 6) $field = 'resume';
                $query = "SELECT $field FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE `id` = ".esc_sql($resumeid);
                $result = wpjobportal::$_db->get_var($query);
                if($result !=''){
                    $sections_status[$key]['status'] = 1;
                    $percentage = $percentage + $section_percentage;// section is filled add the section percentage to total
                }else{
                    // check their params now
                    $query = "SELECT params FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE `id` = ".esc_sql($resumeid);
                    $result = wpjobportal::$_db->get_var($query);
                    if($result != '' ){
                        $params = json_decode($result , true);
                        $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor( 3 , $section['id']);
                        foreach($fields AS $field){
                            if(isset($params[$field->field]) && $params[$field->field] != ''){ // only add value for a section once. not for all its custom fields
                                $sections_status[$key]['status'] = 1;
                                $percentage = $percentage + $section_percentage;// section is filled add the section percentage to total
                                break; // get out of this loop only counting it once. not for every custom field of a section
                            }
                        }
                    }
                }
            }else{
                $table_name = 'resume' . $section['name'] . 's';
                if ($section['id'] == 2)
                    $table_name = 'resume' . $section['name'] . 'es';
                // section name in field ordering education, table name is still institutes
                if($section['name'] == 'education'){
                    $table_name = 'resume' .'institutes';
                }
                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_".esc_sql($table_name)."` WHERE `resumeid` = ".esc_sql($resumeid);
                $count = wpjobportal::$_db->get_var($query);
                if($count != '' && $count > 0){
                    $sections_status[$key]['status'] = 1;
                    $percentage = $percentage + $section_percentage;// section is filled add the section percentage to total
                }
            }
        }
        //$filled_sections = 0;

/*
    // functionality of this code is handled above now.
        foreach ($sections_status as $key => $value) {
            if($value['status'] == 1)
                $filled_sections += 1;
        }
        if(empty($sections_status)){
            $total = 0;
        }else{
            $total = count($sections_status);
        }
        if($total > 0){
            $others = 75 / $total;
            $total_fill = 0;
            for ($i=1; $i < $filled_sections; $i++) {
                $total_fill += $others;
            }
            if($total_fill > 0){
                $percentage = 25 + $total_fill;
                $percentage = round($percentage);
            }else{
                $percentage = 25;
            }
        }else{
            $percentage = 100;
        }
*/

        $sections_status['percentage'] = (int) round($percentage);
        return $sections_status;
    }
    function getPublishedSectionsList(){
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        if ($uid != 0)
            $published = '`published` = 1';
        else
            $published = '`isvisitorpublished` = 1';
        //'section_institute','section_skills', 'section_language'
        // section_institute has been changed to section_education in database table
        if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
            $frp = " ,'section_education','section_skills', 'section_language'";
        }else{
            $frp = "";
        }
        $query = "SELECT field , section FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` WHERE `field` IN('section_address',  'section_employer'$frp) AND ".esc_sql($published)." AND `fieldfor` = 3";
        $fields = wpjobportal::$_db->get_results($query);
        return $fields;
    }

    /* new code for resume start */

    function storeResume($data,$uid=''){
        if (empty($data)) return false;
        if (!$this->captchaValidate()) {
            WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Incorrect Captcha code', 'wp-job-portal')), 'error',$this->getMessagekey());
            $array = wp_json_encode(array('html' => 'error'));
            return $array;
        }
        if(isset($data) && !empty($data['id']) && !wpjobportal::$_common->wpjp_isadmin()){
            if ($this->getIfResumeOwner($data['id']) == false) {
                return false;
            }
        }
        // check to make sure quick apply is not edited
        if (isset($data['quick_apply']) && $data['quick_apply'] == 1 ) {
            return false;
        }


        $resumeid = $data['id'];
        $data['sec_1']['id'] = $resumeid; // because id is not in any section to put for sections
        if(!current_user_can('manage_options')){ // if not admin then get current user is
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        }else{ // if admin then use the uid submitted on the form
            $uid = $data['uid'];
        }
        $data['sec_1']['uid'] = $uid;

        $resumedata = $data['sec_1'];
        $resumedata['resume_logo_deleted'] = $data['resume_logo_deleted'];

        $resume = $this->storePersonalSection($resumedata); // store persnal section
        if($resume === false) return false;
        if(isset($resume[0])) $filestatus = $resume[0];
        $resumeid = $resume[1];
        $resumealiasid = $resume[2].'-'.$resumeid;
        if (wpjobportal::$_common->wpjp_isadmin()) {
            $resumealiasid = $resumeid;
        }
        $sections =
            array(
                1 => array('name' => 'address' , 'id' => 2),
                2 => array('name' => 'institute' , 'id' => 3),
                3 => array('name' => 'employer' , 'id' => 4),
                4 => array('name' => 'skills' , 'id' => 5),
                5 => array('name' => 'editor' , 'id' => 6),
                6 => array('name' => 'reference' , 'id' => 7),
                7 => array('name' => 'language' , 'id' => 8),
            );
        $doremove = false;
        foreach ($sections as $sec) {
            $sec_id = 'sec_'.$sec['id'];
            // get sections's data object vise
            $row = array();
            $total = isset($data[$sec_id]) ? count($data[$sec_id]['id']) : 0; // only published sections will be considred
            // check if empty section submitted
            $is_filled = false;
            for ($i = 0; $i < $total; $i++) {
                $doremove = false;
                foreach ($data[$sec_id] as $key => $arr) {
                    $row[$key] = isset($arr[$i]) ? $arr[$i] : '';
                    if($key == 'deletethis' AND $arr[$i] == 1){
                        $doremove = true;
                    }
                    if( ! empty($arr[$i])){
                        $is_filled = true;
                    }
                }
                $row['resumeid'] = $resumeid;
                wpjobportal::$_data['id'] = $resumeid;
                if($doremove){
                    //var_dump('do remove sec '.$sec);
                    $result = $this->removeResumeSection( $row, $sec);
                }else{
                    if($sec['id'] == 5 || $sec['id'] == 6){
                        $is_filled = true;
                    }
                    if( $is_filled ){
                        $result = $this->storeResumeSection( $row , $sec , $i); // i is use for geting custom files
                        if($result==false) return false;
                    }
                }
            }
        }
        // visitor apply
        if (isset($_COOKIE['wpjobportal_apply_visitor'])) {
            if (!is_user_logged_in()) {
                $url = apply_filters('wpjobportal_addons_applyjob_visitor',false);
                wp_redirect($url);
                exit;
            }
        }

        // action hook for add resume
        if(empty($data['id'])){ // changed the if to handle problem case (it will handle unset and null set value case)
            $data['id'] = $row['resumeid']; // $row is not stc class object of table class
        }
        do_action('wpjobportal_after_store_resume_hook',$data);

        return WPJOBPORTAL_SAVED;
    }

    private function getSaveSearchForView($search) {
       if (!is_numeric($search))
            return false;
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumesearches` WHERE id = " . esc_sql($search);
        $result = wpjobportal::$_db->get_row($query);
        $inquery = "";
        $params = array();
        if ( isset($result->searchparams) && $result->searchparams != null) {
            $params = json_decode($result->searchparams, true);
        }
        if (isset($params['application_title'])) {
            wpjobportal::$_data['filter']['application_title'] = $params['application_title'];
            $inquery .= " AND resume.application_title LIKE '%" . esc_sql($params['application_title']) . "%' ";
        }
        if (isset($params['first_name'])) {
            wpjobportal::$_data['filter']['first_name'] = $params['first_name'];
            $inquery .= " AND resume.first_name LIKE '%" . esc_sql($params['first_name']) . "%'";
        }
        if (isset($params['middle_name'])) {
            wpjobportal::$_data['filter']['middle_name'] = $params['middle_name'];
            $inquery .= " AND resume.middle_name LIKE '%" . esc_sql($params['middle_name']) . "%'";
        }
        if (isset($params['last_name'])) {
            wpjobportal::$_data['filter']['last_name'] = $params['last_name'];
            $inquery .= " AND resume.last_name LIKE '%" .esc_sql( $params['last_name']) . "%'";
        }
        if (isset($params['nationality']) && is_numeric($params['nationality'])) {
            wpjobportal::$_data['filter']['nationality'] = $params['nationality'];
            $inquery .= " AND resume.nationality = " . esc_sql($params['nationality']);
        }
        if (isset($params['gender'])) {
            wpjobportal::$_data['filter']['gender'] = $params['gender'];
            $inquery .= " AND resume.gender = '" . esc_sql($params['gender']) . "' ";
        }
        if (isset($params['category']) && is_numeric($params['category'])) {
            wpjobportal::$_data['filter']['category'] = $params['category'];
            $inquery .= " AND resume.job_category = " . esc_sql($params['category']) . " ";
        }
        if (isset($params['jobtype']) && is_numeric($params['jobtype'])) {
            wpjobportal::$_data['filter']['jobtype'] = $params['jobtype'];
            $inquery .= " AND resume.jobtype = " . esc_sql($params['jobtype']) . " ";
        }
        if (isset($params['salaryrangetype'])) {
            wpjobportal::$_data['filter']['salaryrangetype'] = $params['salaryrangetype'];
            $inquery .= " AND salaryrangetype.title LIKE '%" . esc_sql($params['salaryrangetype']) . "%' ";
        }
        if (isset($params['tags'])) {
            wpjobportal::$_data['filter']['tags'] = $params['tags'];
            $res = $this->makeQueryFromArray('tags', $params['tags']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($params['city'])) {
            wpjobportal::$_data['filter']['city'] = $params['city'];
            $_SESSION['wpjobportal-searchresume-form']['city'] = $params['city'];
        }
        //custom field code
        $inquery2 = '';
        if (isset($result->params) && $result->params != null) {
            $data = wpjobportal::$_wpjpcustomfield->userFieldsData(3);
            $or = '';
            if (!empty($data)) {
                $inquery2 .= " AND (";
                $valarray = json_decode($result->params);

                foreach ($data as $uf) {
                    $fieldname = $uf->field;
                    if (isset($valarray->$fieldname) && $valarray->$fieldname != null) {
                        switch ($uf->userfieldtype) {
                            case 'text':
                            case 'email':
                                //$inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"%' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '%"%\' ';
                                $inquery2 .= esc_sql($or) . ' resume.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                $or = " OR ";
                                break;
                            case 'combo':
                                $inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                $or = " OR ";
                                break;
                            case 'depandant_field':
                                $inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                $or = " OR ";
                                break;
                            case 'radio':
                                $inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                $or = " OR ";
                                break;
                            case 'checkbox':
                                //$inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars(implode(", ",$valarray->$fieldname)) . '%\' ';
                                $inquery2 .= esc_sql($or) . ' resume.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars(implode(", ",$valarray->$fieldname)) . '.*"\' ';
                                $or = " OR ";
                                break;
                            case 'date':
                                $inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                $or = " OR ";
                                break;
                            case 'editor':
                                $inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                $or = " OR ";
                                break;
                            case 'textarea':
                                // $inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '"%\' ';
                                $inquery2 .= esc_sql($or) . ' resume.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($valarray->$fieldname) . '.*"\' ';
                                $or = " OR ";
                                break;
                            case 'multiple':
                                // $inquery2 .= esc_sql($or) . ' resume.params LIKE \'%"' . esc_sql($uf->field) . '":[';
                                // $icomma = '';
                                // for ($i = 0; $i < count($valarray->$fieldname); $i++) {
                                //     $multiplevals = $valarray->$fieldname;
                                //     $inquery2 .=$icomma . '"' . wpjobportalphplib::wpJP_htmlspecialchars($multiplevals[$i]) . '"';
                                //     $icomma = ',';
                                // }
                                // $inquery2 .=']%\' ';



                                $finalvalue = '';
                                foreach($valarray->$fieldname AS $value){
                                    if($value){
                                        $finalvalue .= $value.'.*';
                                    }
                                }
                                if($finalvalue){
                                    $inquery2 .= esc_sql($or) . ' resume.params REGEXP \'%"' . esc_sql($uf->field) . '":"[^"]*'.wpjobportalphplib::wpJP_htmlspecialchars($finalvalue).'"\' ';
                                }
                                $or = " OR ";
                                break;
                        }
                        //to convert an std class object to array
                        if (!empty($valarray)) {
                            $valarray = wp_json_encode($valarray);
                            $valarray = json_decode($valarray, true);
                        }
                        wpjobportal::$_data['filter']['params'] = $valarray;
                    }
                }
                $inquery2 .= " ) ";
            }
        }
        //patch
        if ($inquery2 == ' AND ( ) ') {
            $inquery2 = '';
        }
        //end
        $inquery .= $inquery2;
        return $inquery;
    }
    function storeResumeSection( $formdata, $section , $i) { // i is the index of A section have multi forms
        if(empty($section)) return false;
        $sectionid = $section['id'];
        $datafor = $section['name'];

        // store skills/editor sections data
        if($sectionid == 5 || $sectionid == 6){
            $result = $this->storeSkillsAndResumeSection($formdata , $section, $i);
            return $result;
        }
        if ($sectionid == 2) {
            $table_name = 'resume' . $datafor . 'es';
        } else {
            $table_name = 'resume' . $datafor . 's';
        }

       $row = WPJOBPORTALincluder::getJSTable($table_name);
        $return_cf = $this->makeResumeTableParams($formdata,$sectionid,$i);
        $params = array();
        $par = json_decode($return_cf['params'],true);
        if(is_array($par)){
            foreach($par AS $key => $value){
                $params[$key] = $value;
            }
        }
        $resumeid = $formdata['resumeid'];

        //check whether form data array is empty;
            $check_array = $formdata;
            unset($check_array['resumeid']);
            $empty_flag = (count(array_filter($check_array)) == 0) ? 1 : 0;
            if($empty_flag == 1){
                return true;
            }
        //

        // moved this code below to avoid saving empty sections because there date fields are filled.
        if($section['id']==4){
            if(!isset($formdata['employer_current_status'])){
             $formdata['employer_current_status']=0;
            }
            if(isset($formdata['employer_from_date']) && $formdata['employer_from_date'] != ''){
              $formdata['employer_from_date']= gmdate('Y-m-d',strtotime($formdata['employer_from_date']));
            }else{
              $formdata['employer_from_date'] = '1970-01-01';
            }
            if(isset($formdata['employer_to_date']) && $formdata['employer_to_date'] !='' ){
              $formdata['employer_to_date'] = gmdate('Y-m-d',strtotime($formdata['employer_to_date']));
            }else{
              $formdata['employer_to_date'] = '1970-01-01';
            }
        }
        // to handle the case of education section

        if($datafor == 'institute'){
            if(isset($formdata['todate']) && $formdata['todate'] != ''){
              $formdata['todate']= gmdate('Y-m-d',strtotime($formdata['todate']));
            }else{
              $formdata['todate'] = '1970-01-01';
            }
            if(isset($formdata['fromdate']) && $formdata['fromdate'] !='' ){
              $formdata['fromdate'] = gmdate('Y-m-d',strtotime($formdata['fromdate']));
            }else{
              $formdata['fromdate'] = '1970-01-01';
            }
        }

        // set created date
        if( ! is_numeric($formdata['id'])){
            $formdata['created'] = gmdate('Y-m-d H:i:s');
        }

        if(WPJOBPORTALincluder::getJSModel('common')->checkLanguageSpecialCase()){
            $formdata = wpjobportal::$_common->stripslashesFull($formdata);// remove slashes with quotes.
        }

        if($params){
            $formdata['params'] = wp_json_encode($params);
        }else{
            $formdata['params'] = '';
        }
        // custom field code end
        $formdata = wpjobportal::wpjobportal_sanitizeData($formdata);

        if (!$row->bind($formdata)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }


        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }
    function storeSkillsAndResumeSection($formdata , $section, $i){
        if(empty($section)) return '';
        $sectionid = $section['id'];
        $datafor = $section['name'];

        $row = WPJOBPORTALincluder::getJSTable('resume');

        $formdata['id'] = $formdata['resumeid'];
        $resumeid = $formdata['resumeid'];
        if(!is_numeric($resumeid)){
            return '';
        }
        unset($formdata['resumeid']);
        if ($sectionid == 6) { // editor
            //$formdata['resume'] = JRequest::getVar('resumeeditor', '', 'post', 'string', JREQUEST_ALLOWHTML );
            // RESUME Resume CUSTOM FIELD
            //$params = $this->getDataForParams(6, $data);
            $return_cf = $this->makeResumeTableParams($formdata, $sectionid, $i);
            $params = $return_cf['params'];

            $pquery = "SELECT params FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($resumeid);
            $parmsvar = wpjobportal::$_db->get_var($pquery);
            $parray = array();
            if (isset($parmsvar) && $parmsvar != '') {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && $params != '') {
                $params = json_decode($params);
            }
            if(!empty($parray)){
                $params = (object) array_merge((array) $params, (array) $parray);
            }
            if(is_object($params) && !empty($params)){
                $params = wp_json_encode($params);
                $queryparams = " , params='" . $params . "' ";
            }else{
                $queryparams = "";
            }
            //END
            $resume = WPJOBPORTALrequest::getVar('resume_edit_val');
            if($resume == ''){
                $resume = WPJOBPORTALrequest::getVar('resume');
            }
            $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_resume` SET resume='" . esc_sql($resume) . "' " .$queryparams." WHERE id = ".esc_sql($resumeid);
            wpjobportal::$_db->query($query);

        }elseif($sectionid==5){
            $skills = WPJOBPORTALrequest::getVar('skills');
            $skills = sanitize_text_field( $skills );
            // RESUME SKILL CUSTOM FIELD
            //$params = $this->getDataForParams(5, $data);
            $return_cf = $this->makeResumeTableParams($formdata, $sectionid, $i);
            $params = $return_cf['params'];
            $pquery = "SELECT params FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($resumeid);
            $parmsvar = wpjobportal::$_db->get_var($pquery);

            $parray = array();
            if (isset($parmsvar) && $parmsvar !='' ) {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && $params != '') {
                $params = json_decode($params);
            }
            if(!empty($parray)){
                $params = (object) array_merge((array) $parray, (array) $params); // in case of edit/update of field old values were presistent
            }
            if(is_object($params) && !empty($params)){
                $params = wp_json_encode($params);
                $queryparams = " , params='" . $params . "' ";
            }else{
                $queryparams = "";
            }
            //END
            $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_resume` SET skills='" . esc_sql($skills) . "' " . $queryparams . " WHERE id = ".esc_sql($resumeid);
            wpjobportal::$_db->query($query);

        }
        return true;
        $return_cf = $this->makeResumeTableParams($formdata, $sectionid, $i);
        $formdata['params'] = $return_cf['params'];
        if (!$row->bind($formdata)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        //retain last state of below vars in edit
        if (is_numeric($formdata['id']) ){
            unset($row->isfeaturedresume);
        }

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    function removeResumeSection( $formdata, $section ){
        if($formdata['deletethis'] != 1){
            return;
        }
        if($formdata['id'] == '' || !isset($formdata['id'])){
            return;
        }
        //exit;
        if(empty($section)) return false;
        $sec_id = $section['id'];
        $datafor = $section['name'];

        $resumeid = $formdata['resumeid'];
        $sectionid = $formdata['id'];

        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        if(!is_numeric($resumeid)) return false;
        if(!is_numeric($sectionid)) return false;

        if ( ! current_user_can( 'manage_options' ) ) { // user is not admin check perform
            if( ! WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = ".esc_sql($resumeid)." AND uid =". esc_sql($uid);
                $result = wpjobportal::$_db->get_var($query);
                if($result == 0){
                    return false; // not your resume
                }
            }
        }

        if ($sec_id == 2) {
            $table_name = 'resume' . $datafor . 'es';
        } else {
            $table_name = 'resume' . $datafor . 's';
        }

        if($sec_id == 5 || $sec_id == 6){ //skill,editor
            return true;
        }else{
            $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_".esc_sql($table_name)."` WHERE id = ".esc_sql($sectionid);
            if (wpjobportaldb::query($query)) {
                return true;
            }else{
                return false;
            }
        }
    }

    function storePersonalSection($data){
       if(empty($data)) return false;
        if(isset($data['id']) && $data['id'] == 0 ) $data['id'] = '';
        $id = (int) $data['id'];
        $isnew = !$id;
        $row = WPJOBPORTALincluder::getJSTable('resume');
        $submission_type = wpjobportal::$_config->getConfigValue('submission_type') ;
        $user = WPJOBPORTALincluder::getObjectClass('user');
        if (empty($data['id'])) {
            if(isset($data['application_title'])){
                $data['alias'] = wpjobportalphplib::wpJP_str_replace(' ', '-', $data['application_title']);
            }else{
                $alias_string = $data['first_name'];;
                if(isset($data['middle_name'])){// min field issue
                    $alias_string .= ' '.$data['middle_name'];
                }
                $alias_string .= ' '.$data['last_name'];
                $data['alias'] = wpjobportalphplib::wpJP_str_replace(' ', '-', $alias_string);
            }
            $data['alias'] = wpjobportalphplib::wpJP_str_replace('_', '-', $data['alias']);
            $data['created'] = gmdate('Y-m-d H:i:s');
            $visitorcanapply = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_apply_to_job');
            $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
            if((in_array('credits', wpjobportal::$_active_addons) && $isguest && $visitorcanapply != 1) || (in_array('credits', wpjobportal::$_active_addons) && !$isguest)){
                if($submission_type == 1){
                    $data['status'] = wpjobportal::$_config->getConfigurationByConfigName('empautoapprove');
                }elseif ($submission_type == 2) {
                    // in case of per listing submission mode
                    $price_check = WPJOBPORTALincluder::getJSModel('credits')->checkIfPriceDefinedForAction('add_resume');
                    if($price_check == 1){ // if price is defined then status 3
                        $data['status'] = 3;
                    }else{ // if price not defined then status set to auto approve configuration
                        $data['status'] = wpjobportal::$_config->getConfigValue('empautoapprove');
                    }
                }elseif ($submission_type == 3) {
                        $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                        $package = apply_filters('wpjobportal_addons_userpackages_permodule',false,$upakid,$user->uid(),'remresume');
                        if( !$package ){
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                        if( $package->expired ){
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                        //if Department are not unlimited & there is no remaining left
                        if( $package->resume!=-1 && !$package->remresume ){ //-1 = unlimited
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                        #user packae id--
                        $data['status'] = wpjobportal::$_config->getConfigValue('empautoapprove');
                        $data['userpackageid'] = $upakid;
                }
            }else{
                $data['status'] = wpjobportal::$_config->getConfigValue('empautoapprove');
            }
        } else {
            if(current_user_can('manage_options')){
                $data['status'] = $data['status'];
            }else{
                $row->load($data['id']);
                $data['status'] = $row->status;
            }
        }
        /*$query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE field =  'searchable' AND fieldfor =3";
        $record = wpjobportal::$_db->get_row($query);
        if($record->published == 0 AND is_user_logged_in()){
            $data['searchable'] = 1;
        }elseif($record->isvisitorpublished == 0){
            $data['searchable'] = 1;
        }*/

        $data['last_modified'] = gmdate('Y-m-d H:i:s');
        $section = 1;

        $data = wpjobportal::wpjobportal_sanitizeData($data);

        $data = wpjobportal::$_common->stripslashesFull($data);// remove slashes with quotes.
        $return_cf = $this->makeResumeTableParams($data,$section);
        $data['params'] = $return_cf['params'];

        // storing  custom section values
        $resume_custom_sections = WPJOBPORTALincluder::getJSModel('fieldordering')->getResumeCustomSections();

        if(!empty($resume_custom_sections)){
            $main_params = json_decode($data['params'],true); // decoding main paras
            $sec_data = WPJOBPORTALrequest::get('post'); // fetching POST to extract custom section field values
            $section_parms_array = array();
            foreach ($resume_custom_sections as $resume_section) {
                //$resume_section_data = $this->makeResumeTableParams($sec_data,$resume_section->section); // create and fetch parms for custom section fields using $_POST data and fielf ordering section value
                $resume_section_data = $this->getDataForParamsResume($resume_section->section , $sec_data);

                $resume_section_data_params = json_decode($resume_section_data['params'],true);// convert custom section json to php array
                //$main_params = array_merge($main_params,$resume_section_data_params); // merge php arrays main(personal section params) and custom section filds params
                $section_parms_array = array_merge($section_parms_array,$resume_section_data_params); // merge php arrays main(personal section params) and custom section filds params
            }
            if(!is_array($main_params)){// to handle empty params case
                $main_params = array();
            }
            $main_params = array_merge($main_params,$section_parms_array);
            $data['params'] = wp_json_encode($main_params);// inset new updated params php array as json to data object to stored as params
        }

        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        $objectid = $row->id;
        $resumeid = $row->id;
        // to handle log error of resume_logo_deleted not set in array
        if(isset($data['resume_logo_deleted']) && $data['resume_logo_deleted'] == 1){
            $this->deleteResumeLogoModel($resumeid);
        }
        if (isset($_FILES['photo']['size']) && $_FILES['photo']['size'] > 0) {
            if(isset($data['resume_logo_deleted']) && $data['resume_logo_deleted'] != 1){
                $this->deleteResumeLogoModel($resumeid);
            }
            $this->uploadPhoto($objectid);
        }

        if (isset($_FILES['resumefiles'])) {
            $filereturnvalue = $this->uploadResume($objectid);
        }
        // upload custom files

        /*
        $return_cf['customflagforadd'] = $customflagforadd;
        $return_cf['customflagfordelete'] = $customflagfordelete;
        $return_cf['custom_field_namesforadd'] = $custom_field_namesforadd;
        $return_cf['custom_field_namesfordelete'] = $custom_field_namesfordelete;
        */
        if(is_array($return_cf) && !empty($return_cf)){
            $this->storeCustomUploadFile($resumeid,$return_cf);
        }

        // Save resumeid in session in case of visitor add resume is allowed
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $visitor_can_add_resume = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_add_resume');
            if ($visitor_can_add_resume == 1) {
                $_SESSION['wp-wpjobportal']['resumeid'] = $resumeid;
            }
        }
        //Update credits log in case of new resume
        if ($data['id'] == '') {
            if(empty($data['id'])){
                WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3,1,$resumeid); // 3 for resume,1 for add new resume
            }
        }

        if(in_array('credits', wpjobportal::$_active_addons)){
            if(!WPJOBPORTALincluder::getObjectClass('user')->isguest() && !wpjobportal::$_common->wpjp_isadmin() && empty($data['id']) && $submission_type == 3){
                do_action('wpjobportal_addons_user_transactionlog',$row,'resume',$upakid,$user->uid(),$isnew);
            }
        }

        $return = array();
        if(isset($filereturnvalue)) $return[0] = $filereturnvalue;
        $return[1] = $row->id;
        $return[2] = $row->alias;
        return $return;
    }

/*

        $return['customflagforadd'] = $customflagforadd;
        $return['customflagfordelete'] = $customflagfordelete;
        $return['custom_field_namesforadd'] = $custom_field_namesforadd;
        $return['custom_field_namesfordelete'] = $custom_field_namesfordelete;

*/
    function storeCustomUploadFile($resumeid,$data_array){
        if(!is_numeric($resumeid)){
            return;
        }

        $entity_for = 'resume';

        //removing custom field attachments
        if(isset($data_array['customflagfordelete']) && $data_array['customflagfordelete'] == true){
            foreach ($data_array['custom_field_namesfordelete'] as $key) {
               $res = wpjobportal::$_wpjpcustomfield->removeFileCustom($resumeid,$key,$entity_for);
            }
        }

        //storing custom field attachments
        if(isset($data_array['customflagforadd']) && $data_array['customflagforadd'] == true){
            foreach ($data_array['custom_field_namesforadd'] as $key) {
                if (isset($_FILES[$key])) {
                    if ($_FILES[$key]['size'] > 0) { // logo
                       $res = wpjobportal::$_wpjpcustomfield->uploadFileCustom($resumeid,$key,$entity_for);
                    }
                }
            }
        }
    }


    function makeResumeTableParams($formdata,$sectionid,$i=0){

        $return_cf = $this->getDataForParamsResume($sectionid , $formdata, $i);

        $params_new = $return_cf['params'];

        if(is_numeric($formdata['id'])){
            $params_new = json_decode($params_new, true);
            $query = "SELECT params FROM `". wpjobportal::$_db->prefix ."wj_portal_resume` WHERE id = ".esc_sql($formdata['id']);
            $oParams = wpjobportaldb::get_var($query);
            if(!empty($oParams)){
                $oParams = json_decode($oParams,true);
                $unpublihsedFields =/*apply_filters('wpjobportal_addons_customFields_unpublish',false,3,1);*/ WPJOBPORTALincluder::getJSModel('customfield')->getUnpublishedFieldsFor(3,1);
                foreach($unpublihsedFields AS $field){
                    if(isset($oParams[$field->field]) && !empty($oParams[$field->field])){
                        $params_new[$field->field] = $oParams[$field->field];
                    }
                }
                $sectionfields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3,$sectionid);
                foreach($sectionfields AS $cfield){
                    if(isset($oParams[$cfield->field]))
                        unset($oParams[$cfield->field]);
                }

                foreach($oParams AS $key => $value){
                    $params_new[$key] = $value;
                }
            }
            if($params_new){
                $params_new = wp_json_encode($params_new);
            }
        }
        $return_cf['params'] = $params_new;
        //fix for resume only
        if($return_cf['params'] == null || $return_cf['params'] == 'null'){
            $return_cf['params'] = '';
        }
        return $return_cf;
    }

    // custom field code start
    function getDataForParamsResume($sectionid, $data , $i = 0) {
        $userfieldforresume = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3, $sectionid);
        $customflagforadd = false;
        $customflagfordelete = false;
        $custom_field_namesforadd = array();
        $custom_field_namesfordelete = array();
        $params = array();
        foreach ($userfieldforresume AS $ufobj) {
            $vardata = '';
            if ($ufobj->userfieldtype == 'date') {
                $vardata = (isset($data[$ufobj->field]) && $data[$ufobj->field] !='')  ? gmdate('Y-m-d H:i:s',strtotime($data[$ufobj->field])) : '';
            } elseif($ufobj->userfieldtype == 'file'){
                // if(isset($_POST[$ufobj->field.'_1']) && $_POST[$ufobj->field.'_1'] == 0 && isset($_POST[$ufobj->field.'_2'])){
                //     $vardata = $data[$ufobj->field.'_2'];
                $ufield_1 = WPJOBPORTALrequest::getVar($ufobj->field.'_1','post','');
                $ufield_2 = WPJOBPORTALrequest::getVar($ufobj->field.'_2','post','');
                if($ufield_1 == 0 && $ufield_2 != ''){
                    $vardata = sanitize_file_name($ufield_2);
                }else{
                    // if($sectionid == 1){
                    //     $section_id = 'sec_'.$sectionid;
                    //     $vardata = isset($_FILES[$section_id]['name'][$ufobj->field]) ? sanitize_file_name($_FILES[$section_id]['name'][$ufobj->field]) : '';
                    // }else{
                        //$section_id = 'sec_'.$sectionid;
                        if(isset($_FILES[$ufobj->field])){
                            $vardata = isset($_FILES[$ufobj->field]['name']) ? sanitize_file_name($_FILES[$ufobj->field]['name']) : '';
                        }
                    //}
                }
                $customflagforadd = true;
                $custom_field_namesforadd[] = $ufobj->field;
            }else{
                $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            }
            if(isset($data[$ufobj->field.'_1']) && $data[$ufobj->field.'_1'] == 1){
                $customflagfordelete = true;
                $custom_field_namesfordelete[]= $data[$ufobj->field.'_2'];
            }
            if($vardata != ''){
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = wpjobportalphplib::wpJP_htmlspecialchars($vardata);
            }else{ // to handle edit case filled issue
                $params[$ufobj->field] = '';
            }
        }
        $params = wp_json_encode($params);

        $return = array();
        $return['params'] = $params;
        $return['customflagforadd'] = $customflagforadd;
        $return['customflagfordelete'] = $customflagfordelete;
        $return['custom_field_namesforadd'] = $custom_field_namesforadd;
        $return['custom_field_namesfordelete'] = $custom_field_namesfordelete;

        return $return;
    }
    // custom field code End

    function getResumeDataBySection($resumeid, $sectionName){
        if(!is_numeric($resumeid)) return false;

        switch ($sectionName) {
            case 'personal': $section = 1; break;
            case 'address': $section = 2; break;
            case 'institute': $section = 3; break;
            case 'employer': $section = 4; break;
            case 'skills': $section = 5; break;
            case 'editor': $section = 6; break;
            case 'reference': $section = 7; break;
            case 'language': $section = 8; break;
            case 'default':
                return false;
        }
        $data = array();
        if ($sectionName == 'personal') {
            $results = $this->getResumeBySection($resumeid, $sectionName);
            //$resumelists = $this->getResumeListsForForm($results);
            //wpjobportal::$_data[2]=$resumelists;
        } else {
            $sectionData = array();
            if ($sectionName == "skills" OR $sectionName == "editor") {
                $results = $this->getResumeBySection($resumeid, $sectionName);
            } else {
                $results = $this->getResumeBySection($resumeid, $sectionName);
            }
        }
        $custom_fields =WPJOBPORTALincluder::getObjectClass('customfields')->formCustomFields($field, 1, 1);
        $resume_section_fields = WPJOBPORTALincluder::getJSModel('customfield')->getResumeFieldsOrderingBySection($section);
        wpjobportal::$_data[0] = $results;
        return;
    }

    function getResumeBySection($resumeid, $sectionName ) {
        if (!is_numeric($resumeid)) {
            return false;
        }
        if (empty($sectionName)) {
            return false;
        }
        $resume = '';
        if ($sectionName == 'personal') {
            $query = "SELECT resume.id,resume.driving_license,resume.tags AS viewtags , resume.tags AS resumetags,resume.uid,resume.application_title, resume.first_name, resume.last_name, resume.cell, resume.email_address, resume.nationality AS nationalityid, resume.photo, resume.gender, resume.job_category, resume.experienceid, resume.home_phone, resume.work_phone, resume.date_of_birth,
                , resume.jobsalaryrangetype, resume.skills, resume.keywords, resume.searchable, resume.iamavailable, cat.cat_title AS categorytitle, jobtype.title AS jobtypetitle, resume.date_start,resume.jobtype
                , resume.resume, saltype.title AS rangetype,nationality.name AS nationality
                ,resume.params,resume.status
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = resume.job_category
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS saltype ON saltype.id = resume.jobsalaryrangetype
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS nationality ON nationality.id = resume.nationality
                        WHERE resume.id = " . esc_sql($resumeid);

            $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
            $iswpjobportaluser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPORTALUser();
            // uid was undefined
            // if(! $isguest && $iswpjobportaluser){
            //     if (!current_user_can( 'manage_options' ) && $uid) {
            //         //$query .= " AND resume.uid  = " . esc_sql($uid);
            //     }
            // }
            $resume = wpjobportaldb::get_row($query);
        } elseif ($sectionName == 'skills') {
            $query = "SELECT id,uid,skills,params FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($resumeid);
            $resume = wpjobportaldb::get_row($query);
        } elseif ($sectionName == 'editor') {
            $query = "SELECT id,uid,resume,params FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($resumeid);
            $resume = wpjobportaldb::get_row($query);
        } elseif ($sectionName == 'language') {
            $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumelanguages` WHERE resumeid = " . esc_sql($resumeid);
            $resume = wpjobportaldb::get_results($query);
        } elseif ($sectionName == 'address') {
            $query = "SELECT address.*,
                        cities.id AS cityid,
                        cities.name AS city,
                        states.name AS state,
                        countries.name AS country
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS cities ON address.address_city = cities.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS states ON cities.stateid = states.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS countries ON cities.countryid = countries.id
                        WHERE address.resumeid = " . esc_sql($resumeid);
            $resume = wpjobportaldb::get_results($query);
        } else {
            $query = "SELECT " . esc_sql($sectionName) . ".*,
                        cities.id AS cityid,
                        cities.name AS city,
                        states.name AS state,
                        countries.name AS country
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume" . esc_sql($sectionName) . "s` AS " . esc_sql($sectionName) . "
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS cities ON " . esc_sql($sectionName) . "." . esc_sql($sectionName) . "_city = cities.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS states ON cities.stateid = states.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS countries ON cities.countryid = countries.id
                        WHERE " . esc_sql($sectionName) . ".resumeid = " . esc_sql($resumeid);
            $resume = wpjobportaldb::get_results($query);
        }
        return $resume;
    }
    // joomla code in this function
    // function getResumeListsForForm($application) {
    //     $resumelists = array();
    //     $nationality_required = '';
    //     $license_country_required = '';
    //     $gender_required = '';
    //     $driving_license_required = '';
    //     $category_required = '';
    //     $subcategory_required = '';
    //     $salary_required = '';
    //     $workpreference_required = '';
    //     $education_required = '';
    //     $expsalary_required = '';

    //     // explicit use of site model in case form admin resume
    //     //$fieldsordering = $this->getJSSiteModel('customfields')->getResumeFieldsOrderingBySection(1);
    //     $fieldsordering = wpjobportal::$_wpjpcustomfield->getResumeFieldsOrderingBySection(1);
    //     foreach ($fieldsordering AS $fo) {
    //         switch ($fo->field) {
    //             case "nationality":
    //                 $nationality_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "license_country":
    //                 $license_country_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "gender":
    //                 $gender_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "driving_license":
    //                 $driving_license_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "job_category":
    //                 $category_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "job_subcategory":
    //                 $subcategory_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "salary":
    //                 $salary_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "jobtype":
    //                 $workpreference_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "heighestfinisheducation":
    //                 $education_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "desired_salary":
    //                 $expsalary_required = ($fo->required ? 'required' : '');
    //                 break;
    //             case "total_experience":
    //                 $experienceid_required = ($fo->required ? 'required' : '');
    //                 break;
    //         }
    //     }
    //     // since common is already executed form admin


    //     $gender = WPJOBPORTALincluder::getJSModel('common')->getGender();

    //     $defaultCategory = WPJOBPORTALincluder::getJSModel('category')->getDefaultCategoryId();
    //     $defaultJobtype = WPJOBPORTALincluder::getJSModel('jobtype')->getDefaultJobTypeId();
    //     $yesno=WPJOBPORTALincluder::getJSModel('common')->getYesNo();
    //     $job_type = WPJOBPORTALincluder::getJSModel('jobtype')->getJobTypeForCombo();
    //     $job_categories = WPJOBPORTALincluder::getJSModel('category')->getCategoryForCombobox('');
    //     $countries = WPJOBPORTALincluder::getJSModel('country')->getCountriesForCombo();
    //     if (isset($application)) {
    //         $resumelists['nationality'] = JHTML::_('select.genericList', $countries, 'sec_1[nationality]', 'class="inputbox ' . $nationality_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->nationality);
    //         $resumelists['license_country'] = JHTML::_('select.genericList', $countries, 'sec_1[license_country]', 'class="inputbox ' . $license_country_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->license_country);

    //         $resumelists['gender'] = JHTML::_('select.genericList', $gender, 'sec_1[gender]', 'class="inputbox ' . $gender_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->gender);
    //         $resumelists['driving_license'] = JHTML::_('select.genericList', $driving_license, 'sec_1[driving_license]', 'class="inputbox ' . $driving_license_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->driving_license);

    //         $resumelists['job_category'] = JHTML::_('select.genericList', $job_categories, 'sec_1[job_category]', 'class="inputbox ' . $category_required . ' wpjobportal-cbo" ' . 'onChange="return fj_getsubcategories(\'job_subcategory\', this.value)"', 'value', 'text', $application->job_category);
    //         if(!empty($job_subcategories))
    //             $resumelists['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'sec_1[job_subcategory]', 'class="inputbox ' . $subcategory_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->job_subcategory);
    //         else
    //             $resumelists['job_subcategory'] = JHTML::_('select.genericList', array(), 'sec_1[job_subcategory]', 'class="inputbox ' . $subcategory_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->job_subcategory);

    //         $resumelists['jobtype'] = JHTML::_('select.genericList', $job_type, 'sec_1[jobtype]', 'class="inputbox ' . $workpreference_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->jobtype);
    //         $resumelists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'sec_1[jobsalaryrange]', 'class="inputbox ' . $salary_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->jobsalaryrange);
    //         $resumelists['desired_salary'] = JHTML::_('select.genericList', $job_salaryrange, 'sec_1[desired_salary]', 'class="inputbox ' . $expsalary_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $application->desired_salary);
    //         $resumelists['jobsalaryrangetypes'] = JHTML::_('select.genericList', $job_salaryrangetype, 'sec_1[jobsalaryrangetype]', 'class="inputbox wpjobportal-cbo" ' . '', 'value', 'text', $application->jobsalaryrangetype);
    //         $resumelists['djobsalaryrangetypes'] = JHTML::_('select.genericList', $job_salaryrangetype, 'sec_1[djobsalaryrangetype]', 'class="inputbox wpjobportal-cbo" ' . '', 'value', 'text', $application->djobsalaryrangetype);
    //         $resumelists['currencyid'] = JHTML::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'sec_1[currencyid]', 'class="inputbox wpjobportal-cbo" ' . '', 'value', 'text', $application->currencyid);
    //         $resumelists['dcurrencyid'] = JHTML::_('select.genericList', $this->getJSModel('currency')->getCurrency(), 'sec_1[dcurrencyid]', 'class="inputbox wpjobportal-cbo" ' . '', 'value', 'text', $application->dcurrencyid);
    //         $resumelists['experienceid'] = JHTML::_('select.genericList', $experiences, 'sec_1[experienceid]', 'class="inputbox wpjobportal-cbo" ' . '', 'value', 'text', $application->experienceid);
    //     } else {
    //         $resumelists['license_country'] = JHTML::_('select.genericList', $countries, 'sec_1[license_country]', 'class="inputbox ' . $license_country_required . ' wpjobportal-cbo" ' . '', 'value', 'text', '');
    //         $resumelists['nationality'] = JHTML::_('select.genericList', $countries, 'sec_1[nationality]', 'class="inputbox ' . $nationality_required . ' wpjobportal-cbo" ' . '', 'value', 'text', '');
    //         $resumelists['gender'] = JHTML::_('select.genericList', $gender, 'sec_1[gender]', 'class="inputbox ' . $gender_required . ' wpjobportal-cbo" ' . '', 'value', 'text', '');
    //         $resumelists['driving_license'] = JHTML::_('select.genericList', $driving_license, 'sec_1[driving_license]', 'class="inputbox ' . $driving_license_required . ' wpjobportal-cbo" ' . '', 'value', 'text', '');

    //         $resumelists['job_category'] = JHTML::_('select.genericList', $job_categories, 'sec_1[job_category]', 'class="inputbox ' . $category_required . ' wpjobportal-cbo" ' . 'onChange="fj_getsubcategories(\'job_subcategory\', this.value)"', 'value', 'text', $defaultCategory);
    //         $resumelists['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'sec_1[job_subcategory]', 'class="inputbox ' . $subcategory_required . ' wpjobportal-cbo" ' . '', 'value', 'text', '');

    //         $resumelists['jobtype'] = JHTML::_('select.genericList', $job_type, 'sec_1[jobtype]', 'class="inputbox ' . $workpreference_required . ' wpjobportal-cbo" ' . '', 'value', 'text', $defaultJobtype);

    //     }
    //     return $resumelists;
    // }
/* new code for resume start */

 function getAllEmpApps() {

        $this->sorting();
        //Filter
        $searchtitle = wpjobportal::$_search['resumes']['searchtitle'];
        $searchname = wpjobportal::$_search['resumes']['searchname'];
        $searchjobcategory = wpjobportal::$_search['resumes']['searchjobcategory'];
        $searchjobtype = wpjobportal::$_search['resumes']['searchjobtype'];
        $searchjobsalaryrange = wpjobportal::$_search['resumes']['searchjobsalaryrange'];
        $status = wpjobportal::$_search['resumes']['status'];
        $datestart = wpjobportal::$_search['resumes']['datestart'];
        $dateend = wpjobportal::$_search['resumes']['dateend'];
        $featured = wpjobportal::$_search['resumes']['featured'];

        wpjobportal::$_data['filter']['searchtitle'] = $searchtitle;
        wpjobportal::$_data['filter']['searchname'] = $searchname;
        wpjobportal::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        wpjobportal::$_data['filter']['searchjobtype'] = $searchjobtype;
        wpjobportal::$_data['filter']['searchjobsalaryrange'] = $searchjobsalaryrange;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['datestart'] = $datestart;
        wpjobportal::$_data['filter']['dateend'] = $dateend;
        wpjobportal::$_data['filter']['featured'] = $featured;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobsalaryrange)
            if (is_numeric($searchjobsalaryrange) == false)
                return false;

        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(app.application_title) LIKE '%" . esc_sql($searchtitle) . "%'";
        if ($searchname) {
            $inquery .= " AND (";
            $inquery .= " LOWER(app.first_name) LIKE '%" . esc_sql($searchname) . "%'";
            $inquery .= " OR LOWER(app.last_name) LIKE '%" . esc_sql($searchname) . "%'";
            // $inquery .= " OR LOWER(app.middle_name) LIKE '%" . esc_sql($searchname) . "%'";
            $inquery .= " )";
        }
        if (is_numeric($searchjobcategory))
            $inquery .= " AND app.job_category = " . esc_sql($searchjobcategory);
        if (is_numeric($searchjobtype))
            $inquery .= " AND app.jobtype = " . esc_sql($searchjobtype);
        if (is_numeric($searchjobsalaryrange)){
            $inquery .= " AND (SELECT rangestart FROM `".wpjobportal::$_db->prefix."wj_portal_salaryrange` WHERE id = ".esc_sql($searchjobsalaryrange).") >= salarystart.rangestart AND (SELECT rangestart FROM `".wpjobportal::$_db->prefix."wj_portal_salaryrange` WHERE id = ".esc_sql($searchjobsalaryrange).") <= salarystart.rangeend";
        }
        if ($status != null) {
            if (is_numeric($status)) {
                $inquery .= " AND app.status = " . esc_sql($status);
            }
        }
        if ($datestart != null) {
            $datestart = gmdate('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(app.created) >=  '" . esc_sql($datestart) . "' ";
        }

        if ($dateend != null) {
            $dateend = gmdate('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(app.created) <=  '" . esc_sql($dateend) . "'";
        }
        $curdate = gmdate('Y-m-d');
        if ($featured != null) {
            $inquery .= " AND app.isfeaturedresume = 1 AND DATE(app.startfeatureddate) <= '".esc_sql($curdate)."' AND DATE(app.endfeatureddate) >= '".esc_sql($curdate)."'";
        }
        //Pagination
        $query = "SELECT COUNT(app.id) FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS app
                WHERE app.status <> 0 AND app.quick_apply <> 1 ";
        $query.=$inquery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT app.uid,app.id,app.endfeatureddate, app.application_title,app.first_name, app.last_name,app.jobtype,app.photo,app.salaryfixed,app.created, app.status, cat.cat_title,app.id AS resumeid
                , jobtype.title AS jobtypetitle,app.isfeaturedresume,city.id as city,jobtype.color
            FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS app
            LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_categories AS cat ON app.job_category = cat.id
            LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_jobtypes AS jobtype    ON app.jobtype = jobtype.id
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = app.id ORDER BY id DESC LIMIT 1)
            WHERE app.status <> 0  AND app.quick_apply <> 1 ";
        $query.=$inquery;
        $query.=" ORDER BY " . wpjobportal::$_data['sorting'];
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(3);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('resume');
        return;
    }


    function sortingrescat() {
        // $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        // wpjobportal::$_data['sorton'] = isset(wpjobportal::$_search['myresume']['sorton']) ? wpjobportal::$_search['myresume']['sorton'] : 6;
        // wpjobportal::$_data['sortby'] = isset(wpjobportal::$_search['myresume']['sortby']) ? wpjobportal::$_search['myresume']['sortby'] : 2;
        wpjobportal::$_data['sorton'] = WPJOBPORTALrequest::getVar('sorton', 'post', 6);
        wpjobportal::$_data['sortby'] = WPJOBPORTALrequest::getVar('sortby', 'post', 2);

        switch (wpjobportal::$_data['sorton']) {
            case 1: // appilcation title
                wpjobportal::$_data['sorting'] = ' resume.application_title ';
                break;
            case 2: // first name
                wpjobportal::$_data['sorting'] = ' resume.first_name ';
                break;
            case 3: // category
                wpjobportal::$_data['sorting'] = ' category.cat_title ';
                break;
            case 4: // job type
                wpjobportal::$_data['sorting'] = ' resume.jobtype ';
                break;
            case 5: // location
                wpjobportal::$_data['sorting'] = ' city.name ';
                break;
            case 6: // created
                wpjobportal::$_data['sorting'] = ' resume.created ';
                break;
            case 7: // status
                wpjobportal::$_data['sorting'] = ' resume.status ';
                break;
        }
        if (wpjobportal::$_data['sortby'] == 1) {
            wpjobportal::$_data['sorting'] .= ' ASC ';
        } else {
            wpjobportal::$_data['sorting'] .= ' DESC ';
        }
        wpjobportal::$_data['combosort'] = wpjobportal::$_data['sorton'];
    }


    function sorting() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        wpjobportal::$_data['sorton'] = wpjobportal::$_search['resumes']['sorton'];
        wpjobportal::$_data['sortby'] = wpjobportal::$_search['resumes']['sortby'];
        switch (wpjobportal::$_data['sorton']) {
            case 1: // appilcation title
                wpjobportal::$_data['sorting'] = ' app.application_title ';
                break;
            case 2: // first name
                wpjobportal::$_data['sorting'] = ' app.first_name ';
                break;
            case 3: // category
                wpjobportal::$_data['sorting'] = ' cat.cat_title ';
                break;
            case 4: // job type
                wpjobportal::$_data['sorting'] = ' app.jobtype ';
                break;
            case 5: // location
                wpjobportal::$_data['sorting'] = ' city.name ';
                break;
            case 6: // created
                wpjobportal::$_data['sorting'] = ' app.created ';
                break;
            case 7: // status
                wpjobportal::$_data['sorting'] = ' app.status ';
                break;
        }
        if (wpjobportal::$_data['sortby'] == 1) {
            wpjobportal::$_data['sorting'] .= ' ASC ';
        } else {
            wpjobportal::$_data['sorting'] .= ' DESC ';
        }
        wpjobportal::$_data['combosort'] = wpjobportal::$_data['sorton'];
    }

    function getAllUnapprovedEmpApps() {
        $this->sorting();
        //Filter
        $searchtitle = wpjobportal::$_search['resumes']['searchtitle'];
        $searchname = wpjobportal::$_search['resumes']['searchname'];
        $searchjobcategory = wpjobportal::$_search['resumes']['searchjobcategory'];
        $searchjobtype = wpjobportal::$_search['resumes']['searchjobtype'];
        $searchjobsalaryrange = wpjobportal::$_search['resumes']['searchjobsalaryrange'];
        $status = wpjobportal::$_search['resumes']['status'];
        $datestart = wpjobportal::$_search['resumes']['datestart'];
        $dateend = wpjobportal::$_search['resumes']['dateend'];
        $featured = wpjobportal::$_search['resumes']['featured'];

        wpjobportal::$_data['filter']['searchtitle'] = $searchtitle;
        wpjobportal::$_data['filter']['searchname'] = $searchname;
        wpjobportal::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        wpjobportal::$_data['filter']['searchjobtype'] = $searchjobtype;
        wpjobportal::$_data['filter']['searchjobsalaryrange'] = $searchjobsalaryrange;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['datestart'] = $datestart;
        wpjobportal::$_data['filter']['dateend'] = $dateend;
        wpjobportal::$_data['filter']['featured'] = $featured;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobsalaryrange)
            if (is_numeric($searchjobsalaryrange) == false)
                return false;

        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(app.application_title) LIKE '%" . esc_sql($searchtitle) . "%'";
        if ($searchname) {
            $inquery .= " AND (";
            $inquery .= " LOWER(app.first_name) LIKE '%" . esc_sql($searchname) . "%'";
            $inquery .= " OR LOWER(app.last_name) LIKE '%" . esc_sql($searchname) . "%'";
            //$inquery .= " OR LOWER(app.middle_name) LIKE '%" . esc_sql($searchname) . "%'";
            $inquery .= " )";
        }
        if (is_numeric($searchjobcategory))
            $inquery .= " AND app.job_category = " . esc_sql($searchjobcategory);
        if (is_numeric($searchjobtype))
            $inquery .= " AND app.jobtype = " . esc_sql($searchjobtype);
        if (is_numeric($searchjobsalaryrange))
            $inquery .= " AND app.jobsalaryrangetype = " . esc_sql($searchjobsalaryrange);
        if ($status != null) {
            if (is_numeric($status))
                $inquery .= " AND app.status = " . esc_sql($status);
        }

        if ($datestart != null) {
            $datestart = gmdate('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(app.created) >=  '" . esc_sql($datestart) . "' ";
        }

        if ($dateend != null) {
            $dateend = gmdate('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(app.created) <=  '" . esc_sql($dateend) . "'";
        }
        
        $curdate = gmdate('Y-m-d');
        if ($featured != null) {
            $inquery .= " AND app.isfeaturedresume = 1 AND DATE(app.startfeatureddate) <= '".esc_sql($curdate)."' AND DATE(app.endfeatureddate) >= '".esc_sql($curdate)."'";
        }

        //Pagination
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS app
                WHERE (app.status = 0) AND app.quick_apply <> 1";
        $query.=$inquery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT app.photo,app.id,app.salaryfixed as salaryfixed, app.application_title,app.first_name, app.last_name, app.jobtype,
                app.created, app.status, app.isfeaturedresume,app.endfeatureddate, cat.cat_title,jobtype.title AS jobtypetitle,city.id as city,jobtype.color as color
            FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS app
            LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_categories AS cat ON app.job_category = cat.id
            LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_jobtypes AS jobtype    ON app.jobtype = jobtype.id
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = app.id ORDER BY id DESC LIMIT 1)

            WHERE (app.status = 0 OR app.isfeaturedresume = 0 ) AND app.quick_apply <> 1 ";
        $query.=$inquery;
        $query.=" ORDER BY " . wpjobportal::$_data['sorting'];
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(3);
        return;
    }

    function getUserStatsResumes($resumeuid) {
        if (is_numeric($resumeuid) == false)
            return false;
        //pagination
        $query = "SELECT COUNT(resume.id) FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume WHERE resume.uid =" . esc_sql($resumeuid);
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT resume.id,resume.application_title,resume.first_name,resume.last_name,cat.cat_title,resume.created,resume.status
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume
                    LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_categories AS cat ON cat.id=resume.job_category
                    WHERE resume.uid = " . esc_sql($resumeuid);
        $query .= " ORDER BY resume.first_name";
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        return;
    }

    function getResumeSearch() {
        //Filters
        $title = WPJOBPORTALrequest::getVar('title');
        $name = WPJOBPORTALrequest::getVar('name');
        $nationality = WPJOBPORTALrequest::getVar('nationality');
        $gender = WPJOBPORTALrequest::getVar('gender');
        $iamavailable = WPJOBPORTALrequest::getVar('iamavailable', 0); // b/c when checkbox is unchecked it remain get its last value
        $jobcategory = WPJOBPORTALrequest::getVar('jobcategory');
        $jobtype = WPJOBPORTALrequest::getVar('jobtype');
        $education = WPJOBPORTALrequest::getVar('heighestfinisheducation');
        $currency = WPJOBPORTALrequest::getVar('currency');
        $zipcode = WPJOBPORTALrequest::getVar('zipcode');
        $jobstatus = WPJOBPORTALrequest::getVar('jobstatus');

        wpjobportal::$_data['filter']['title'] = $title;
        wpjobportal::$_data['filter']['name'] = $name;
        wpjobportal::$_data['filter']['nationality'] = $nationality;
        wpjobportal::$_data['filter']['gender'] = $gender;
        wpjobportal::$_data['filter']['iamavailable'] = $iamavailable;
        wpjobportal::$_data['filter']['jobcategory'] = $jobcategory;
        wpjobportal::$_data['filter']['jobtype'] = $jobtype;
        wpjobportal::$_data['filter']['heighestfinisheducation'] = $education;
        wpjobportal::$_data['filter']['currency'] = $currency;
        wpjobportal::$_data['filter']['zipcode'] = $zipcode;
        wpjobportal::$_data['filter']['jobstatus'] = $jobstatus;

        if ($gender != '')
            if (is_numeric($gender) == false)
                return false;
        if ($iamavailable != '')
            if (is_numeric($iamavailable) == false)
                return false;
        if ($jobcategory != '')
            if (is_numeric($jobcategory) == false)
                return false;
        if ($jobtype != '')
            if (is_numeric($jobtype) == false)
                return false;
        if ($jobsalaryrange != '')
            if (is_numeric($jobsalaryrange) == false)
                return false;
        if ($education != '')
            if (is_numeric($education) == false)
                return false;

        if ($currency != '')
            if (is_numeric($currency) == false)
                return false;
        if ($zipcode != '')
            if (is_numeric($zipcode) == false)
                return false;

        $wherequery = '';
        if ($title != '')
            $wherequery .= " AND resume.application_title LIKE '%" . esc_sql(wpjobportalphplib::wpJP_str_replace("'", "", $title)) . "%'";
        if ($name != '') {
            $wherequery .= " AND (";
            $wherequery .= " LOWER(resume.first_name) LIKE '%" . esc_sql($name) . "%'";
            $wherequery .= " OR LOWER(resume.last_name) LIKE '%" . esc_sql($name) . "%'";
            //$wherequery .= " OR LOWER(resume.middle_name) LIKE '%" . esc_sql($name) . "%'";
            $wherequery .= " )";
        }

        if ($nationality != '' && is_numeric($nationality))
            $wherequery .= " AND resume.nationality = '" . esc_sql($nationality) . "'";
        if ($gender != '' && is_numeric($gender))
            $wherequery .= " AND resume.gender = " . esc_sql($gender);
        if ($iamavailable != '' && is_numeric($iamavailable))
            $wherequery .= " AND resume.iamavailable = " . esc_sql($iamavailable);
        if ($jobcategory != '' && is_numeric($jobcategory))
            $wherequery .= " AND resume.job_category = " . esc_sql($jobcategory);
        if ($jobtype != '' && is_numeric($jobtype))
            $wherequery .= " AND resume.jobtype = " . esc_sql($jobtype);
        if ($jobsalaryrange != '' && is_numeric($jobsalaryrange))
            $wherequery .= " AND resume.jobsalaryrange = " . esc_sql($jobsalaryrange);

        //Pagination
        $query = "SELECT count(resume.id)
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON resume.job_category = cat.id
                WHERE resume.status = 1 AND resume.searchable = 1 ";
        $query .= $wherequery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT resume.*, cat.cat_title, jobtype.title AS jobtypetitle
                , salary.rangestart, salary.rangeend , currency.symbol
                ,salarytype.title AS salarytype
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON resume.job_category = cat.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_currencies` AS currency ON currency.id = resume.currencyid
               ";
        $query .= "WHERE resume.status = 1 AND resume.searchable = 1 ";
        $query .= $wherequery;
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        return;
    }

    function rejectQueueAllResumesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;
        $result = $this->rejectQueueResumeModel($id);
        return $result;
    }

    function approveQueueAllResumesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;
        $result = $this->approveQueueResumeModel($id);
        return $result;
    }

    function rejectQueueResumeModel($id) {
        if (is_numeric($id) == false) return false;
        $row = WPJOBPORTALincluder::getJSTable('resume');
        if($row->load($id)){
            $row->columns['status'] = -1;
            if(!$row->store()){
                return WPJOBPORTAL_REJECT_ERROR;
            }
        }else{
            return WPJOBPORTAL_REJECT_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, 2, $id); //3 for resume. 2 for resume approve or reject
        return WPJOBPORTAL_REJECTED;
    }

    function rejectQueueFeatureResumeModel($id) {
        if (is_numeric($id) == false) return false;
        $row = WPJOBPORTALincluder::getJSTable('resume');
        if($row->load($id)){
            $row->columns['isfeaturedresume'] = -1;
            if(!$row->store()){
                return WPJOBPORTAL_REJECT_ERROR;
            }
        }else{
            return WPJOBPORTAL_REJECT_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, 4, $id); //3 for resume. 4 for feature resume approve or reject
        return WPJOBPORTAL_REJECTED;
    }

    function approveQueueResumeModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = WPJOBPORTALincluder::getJSTable('resume');
        if($row->load($id)){
            $row->columns['status'] = 1;
            if(!$row->store()){
                return WPJOBPORTAL_APPROVE_ERROR;
            }
        }else{
            return WPJOBPORTAL_APPROVE_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, 2, $id); //3 for resume. 3 for resume approve or reject
        return WPJOBPORTAL_APPROVED;
    }

    function approveQueueFeatureResumeModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = WPJOBPORTALincluder::getJSTable('resume');
        if($row->load($id)){
            $row->columns['isfeaturedresume'] = 1;
            if(!$row->store()){
                return WPJOBPORTAL_APPROVE_ERROR;
            }
        }else{
            return WPJOBPORTAL_APPROVE_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, 4, $id); //3 for resume. 4 for feature resume approve or reject
        return WPJOBPORTAL_APPROVED;
    }

    function getResumes_Widget($resumetype, $noofresumes) {
        if ((!is_numeric($resumetype)) || ( !is_numeric($noofresumes)))
            return false;

        if ($resumetype == 1) { //newest
            $inquery = ' ORDER BY resume.created DESC ';
        } elseif ($resumetype == 2) { //top
            $inquery = ' ORDER BY resume.hits DESC ';
        } else {
            return '';
        }

        $id = "resume.id AS id";
        $alias = ",CONCAT(resume.alias,'-',resume.id) AS resumealiasid ";
        $query = "SELECT resume.id AS resumeid,
                $id, resume.application_title AS applicationtitle, CONCAT(resume.first_name,' ', resume.last_name) AS name, resume.photo,jobtype.color as jobtypecolor
                ,resume.created AS created , cat.cat_title, jobtype.title AS jobtypetitle,nationality.name AS nationalityname
                $alias,(SELECT address.address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address WHERE address.resumeid = resume.id LIMIT 1) AS city,resume.email_address

                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON resume.job_category = cat.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS nationality ON nationality.id=resume.nationality
                WHERE resume.status = 1 ";
        $query .= $inquery;
        if ($noofresumes != -1 && is_numeric($noofresumes))
            $query .=" LIMIT " . esc_sql($noofresumes);

        $results = wpjobportaldb::get_results($query);
        foreach ($results as $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
        }
        return $results;
    }

     function isYoursResume($id, $uid) {
        if (!is_numeric($id))
            return false;
        if (current_user_can( 'manage_options' )){
            return true;
        }
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $conflag = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_add_resume');
            if ($conflag == 1) {
                if (isset($_SESSION['wp-wpjobportal']) && isset($_SESSION['wp-wpjobportal']['resumeid'])) {
                    if ($id == $_SESSION['wp-wpjobportal']['resumeid']) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        if (!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($id) . " AND uid = ". esc_sql($uid);
        $result = wpjobportaldb::get_var($query);
        if ($result == 0)
            return false;
        else
            return true;
    }

    function cancelResumeSectionAjax() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'cancel-resume-section-ajax') ) {
            die( 'Security check Failed' );
        }
        $section = WPJOBPORTALrequest::getVar('section');
        $data = WPJOBPORTALrequest::get('post');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $data['uid'] = $uid;
        $resumeid = $data['resumeid'];
        $objectid = $data['sectionid'];
        if ($section != 'skills' && $section != 'resume' && $section != 'personal')
            if ($objectid)
                if (!is_numeric($objectid))
                    return false;
        $result = null;
        $resumelayout = WPJOBPORTALincluder::getObjectClass('resumeformlayout');
        $fieldsordering = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(3); // resume fields
        wpjobportal::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            wpjobportal::$_data[2][$field->section][$field->field] = $field->required;
        }
        switch ($section) {
            case 'addresses':
                if (is_numeric($objectid))
                    wpjobportal::$_data[0]['address_section'][0] = $this->getResumeAddressSection($resumeid, $uid, $objectid);
                else
                    wpjobportal::$_data[0]['address_section'][0] = '';
                $result = $resumelayout->getAddressesSection(0, 1);
                break;
            case 'institutes':
                if (is_numeric($objectid))
                    wpjobportal::$_data[0]['institute_section'][0] = apply_filters('wpjobportal_addons_getResume_action_ajx_adm',false,'getResumeInstituteSection',$resumeid,$uid,$sectionid);
                else
                    wpjobportal::$_data[0]['institute_section'][0] = '';
                $result = apply_filters('wpjobportal_addons_view_resume_by_section_resume',false,'getEducationSection');
                break;
            case 'employers':
                if (is_numeric($objectid))
                    wpjobportal::$_data[0]['employer_section'][0] = $this->getResumeEmployerSection($resumeid, $uid, $objectid);
                else
                    wpjobportal::$_data[0]['employer_section'][0] = '';
                $result = $resumelayout->getEmployerSection(0, 1);
                break;
            case 'languages':
                if (is_numeric($objectid))
                    wpjobportal::$_data[0]['language_section'][0] = apply_filters('wpjobportal_addons_getResume_action_ajx_adm',false,'getResumeLanguageSection',$resumeid,$uid,$objectid);
                else
                    wpjobportal::$_data[0]['language_section'][0] = '';
                $result = apply_filters('wpjobportal_addons_view_resume_by_section_resume',false,'getLanguageSection');
                break;
            case 'skills':
                    if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                        wpjobportal::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                        $result = apply_filters('wpjobportal_addons_view_resume_by_section_resume',false,'getSkillSection');
                    }
                break;
            case 'personal':
                wpjobportal::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                wpjobportal::$_data[0]['file_section'] = $this->getResumeFilesSection($resumeid, $uid);
                wpjobportal::$_data['resumecontactdetail'] = true;
                $result = $resumelayout->getPersonalTopSection(1, 0);
                $result .= '<div class="resume-section-title personal"><img class="heading-img" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/personal-info.png" />' . esc_html(__('Personal information', 'wp-job-portal')) . '</div>';
                $result .= $resumelayout->getPersonalSection(0);
                break;
        }
        if ($section != 'skills' && $section != 'resume' && $section != 'personal') {
            $canadd = $this->canAddMoreSection($uid, $resumeid, $section);
            $anchor = '<a class="add" data-section="' . $section . '"> + ' . esc_html(__('Add New', 'wp-job-portal')) . ' ' . wpjobportal::wpjobportal_getVariableValue($section) . '</a>';
        } else {
            $canadd = 0;
            $anchor = '';
        }
        $array = wp_json_encode(array('html' => $result, 'canadd' => $canadd, 'anchor' => $anchor));
        return $array;
    }

    function captchaValidate() {
        if (!is_user_logged_in()) {
            $config_array = wpjobportal::$_config->getConfigByFor('captcha');
            if ($config_array['resume_captcha'] == 1) {
                if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                    $gresponse = WPJOBPORTALrequest::getVar('g-recaptcha-response','post');
                    $resp = googleRecaptchaHTTPPost($config_array['recaptcha_privatekey'] , $gresponse);

                    if ($resp) {
                        return true;
                    } else {
                        wpjobportal::$_data['google_captchaerror'] = esc_html(__("Invalid captcha",'wp-job-portal'));
                        return false;
                    }

                } else { // own captcha
                    $captcha = new WPJOBPORTALcaptcha;
                    $result = $captcha->checkCaptchaUserForm();
                    if ($result == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function getDataForParams($section, $data) {
        //custom field code start
        $userfieldforjob = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3, $section);
        $params = array();
        foreach ($userfieldforjob AS $ufobj) {
            $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
            if($vardata != ''){
                if($ufobj->userfieldtype == 'multiple'){
                    $vardata = wpjobportalphplib::wpJP_explode(',', $vardata[0]); // fixed index
                }
                if(is_array($vardata)){
                    $vardata = implode(', ', $vardata);
                }
                $params[$ufobj->field] = wpjobportalphplib::wpJP_htmlspecialchars($vardata);
            }
        }
        if (!empty($params)) {
            $params = wp_json_encode($params);
            return $params;
        } else {
            return false;
        }
        //custom field code end
    }

    function saveResumeSectionAjax() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-resume-section-ajax') ) {
            die( 'Security check Failed' );
        }
        $section = WPJOBPORTALrequest::getVar('section');
        $data = WPJOBPORTALrequest::get('post');
        if(!current_user_can('manage_options')){
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            $data['uid'] = $uid;
        }else{
			$uid = $this->getUidByResumeId($data['resumeid']);
			$data['uid'] = $uid;
		}
        $resumeid = $data['resumeid'];
        $row = null;
        switch ($section) {
            case 'personal':
                $row = WPJOBPORTALincluder::getJSTable('resume');
                $data['id'] = $resumeid;
                $params = $this->getDataForParams(1, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'addresses':
                $row = WPJOBPORTALincluder::getJSTable('resumeaddress');
                $params = $this->getDataForParams(2, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'institutes':
                $row = WPJOBPORTALincluder::getJSTable('resumeinstitute');
                $params = $this->getDataForParams(3, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            case 'employers':
                $row = WPJOBPORTALincluder::getJSTable('resumeemployer');
                $params = $this->getDataForParams(4, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
            // case 'references':
            //     $row = WPJOBPORTALincluder::getJSTable('resumereference');
            //     $params = $this->getDataForParams(7, $data);
            //     $data['params'] = $params == false ? '' : $params;
            //     break;
            case 'languages':
                $row = WPJOBPORTALincluder::getJSTable('resumelanguage');
                $params = $this->getDataForParams(8, $data);
                $data['params'] = $params == false ? '' : $params;
                break;
        }
        if ($row != null) {
            if ($section == 'personal') { // b/c of form ajax loop we have to unset the photo field if no photo selected
                if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                    //empty here to make it simple to understand
                } else {
                    unset($data['photo']);
                }
                if (empty($data['id'])) {
                    $data['alias'] = wpjobportalphplib::wpJP_str_replace(' ', '-', $data['application_title']);
                    $data['created'] = gmdate('Y-m-d H:i:s');
                    $data['status'] = wpjobportal::$_config->getConfigurationByConfigName('empautoapprove');
                } else {
                    if(current_user_can('manage_options')){
                        $data['status'] = $data['status'];
                    }else{
                        $row = WPJOBPORTALincluder::getJSTable('resume');
                        $row->load($data['id']);
                        $data['status'] = $row->status;
                    }
                }
                if(!empty($data['date_of_birth']))
                    $data['date_of_birth'] = gmdate('Y-m-d H:i:s',strtotime($data['date_of_birth']));
                if(!empty($data['date_start']))
                    $data['date_start'] = gmdate('Y-m-d H:i:s',strtotime($data['date_start']));
				$query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE field =  'searchable' AND fieldfor =3";
				$record = wpjobportal::$_db->get_row($query);
				if($record->published == 0 AND is_user_logged_in()){
					$data['searchable'] = 1;
				}elseif($record->isvisitorpublished == 0){
					$data['searchable'] = 1;
				}
                if (!$this->captchaValidate()) {
                    WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Incorrect Captcha code', 'wp-job-portal')), 'error',$this->getMessagekey());
                    $array = wp_json_encode(array('html' => 'error'));
                    return $array;
                }
            }
            $data = wpjobportal::wpjobportal_sanitizeData($data);
            if (!$row->bind($data)) {
                return WPJOBPORTAL_SAVE_ERROR;
            }
            if (!$row->store()) {
                return WPJOBPORTAL_SAVE_ERROR;
            }
            $objectid = $row->id;
            if ($section == 'personal') {
                $resumeid = $row->id;
            }
            //Check for the resume photo && files upload
            if ($section == 'personal') {
                if (isset($_FILES['photo'])) {
                    $this->uploadPhoto($objectid);
                }
                if (isset($_FILES['resumefiles'])) {
                    $this->uploadResume($objectid);
                }
                // Save resumeid in session in case of visitor add resume is allowed
                if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                    $visitor_can_add_resume = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_add_resume');
                    if ($visitor_can_add_resume == 1) {
                        $_SESSION['wp-wpjobportal']['resumeid'] = $resumeid;
                    }
                }
                //Update credits log in case of new resume
                if ($data['resumeid'] == '') {
                    $actionid = $data['creditid'];
                }
            }
        } elseif ($section == 'skills') {
            $skills = WPJOBPORTALrequest::getVar('skills');
// RESUME SKILL CUSTOM FIELD
            $params = $this->getDataForParams(5, $data);
            if(!is_numeric($resumeid)){
                return false;
            }
            $pquery = "SELECT params FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($resumeid);
            $parmsvar = wpjobportal::$_db->get_var($pquery);
            $parray = array();
            if (isset($parmsvar) && !empty($parmsvar)) {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && !empty($params)) {
                $params = json_decode($params);
            }
            $params = (object) array_merge((array) $params, (array) $parray);
            $params = wp_json_encode($params);
            $queryparams = " , params='" . $params . "' ";
//END
            $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_resume` SET skills='" . esc_sql($skills) . "' " . $queryparams . " WHERE id = ".esc_sql($resumeid);
            wpjobportal::$_db->query($query);
        } elseif ($section == 'resume') {
// RESUME SKILL CUSTOM FIELD
            $params = $this->getDataForParams(6, $data);
            $pquery = "SELECT params FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($resumeid);
            $parmsvar = wpjobportal::$_db->get_var($pquery);
            $parray = array();
            if (isset($parmsvar) && !empty($parmsvar)) {
                $parray = json_decode($parmsvar);
            }
            if (isset($params) && !empty($params)) {
                $params = json_decode($params);
            }
            $params = (object) array_merge((array) $params, (array) $parray);
            $params = wp_json_encode($params);
            $queryparams = " , params='" . $params . "' ";
//END
            $resume = WPJOBPORTALrequest::getVar('resume');
            $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_resume` SET resume='" . esc_sql($resume) . "' " .$queryparams." WHERE id = ".esc_sql($resumeid);
            wpjobportal::$_db->query($query);
        }
        $result = null;
        $resumelayout = WPJOBPORTALincluder::getObjectClass('resumeformlayout');
        $fieldsordering = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(3); // resume fields
        wpjobportal::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            wpjobportal::$_data[2][$field->section][$field->field] = $field->required;
        }
        switch ($section) {
            case 'addresses':
                wpjobportal::$_data[0]['address_section'][0] = $this->getResumeAddressSection($resumeid, $uid, $objectid);
                $result = $resumelayout->getAddressesSection(0, 1);
                break;
            case 'institutes':
                wpjobportal::$_data[0]['institute_section'][0] = apply_filters('wpjobportal_addons_getResume_action_ajx_adm',false,'getResumeInstituteSection',$resumeid,$uid,$objectid);
                $result = apply_filters('wpjobportal_addons_view_resume_by_section_resume',false,'getEducationSection');
                break;
            case 'employers':
                wpjobportal::$_data[0]['employer_section'][0] = $this->getResumeEmployerSection($resumeid, $uid, $objectid);
                $result = $resumelayout->getEmployerSection(0, 1);
                break;
            case 'languages':
                wpjobportal::$_data[0]['language_section'][0] = apply_filters('wpjobportal_addons_getResume_action_ajx_adm',false,'getResumeLanguageSection',$resumeid,$uid,$objectid);
                $result = apply_filters('wpjobportal_addons_view_resume_by_section_resume',false,'getLanguageSection');
                break;
            case 'skills':
                if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                    wpjobportal::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                    $result = apply_filters('wpjobportal_addons_view_resume_by_section_resume',false,'getSkillSection');
                }
                break;
            case 'resume':
                wpjobportal::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                $result = $resumelayout->getResumeSection(0, 1);
                break;
            case 'personal':
                wpjobportal::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                wpjobportal::$_data[0]['file_section'] = $this->getResumeFilesSection($resumeid, $uid);
                wpjobportal::$_data['resumecontactdetail'] = true;
                $result = $resumelayout->getPersonalTopSection(1, 0);
                $result .= '<div class="resume-section-title personal"><img class="heading-img" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/personal-info.png" />' . esc_html(__('Personal information', 'wp-job-portal')) . '</div>';
                $result .= $resumelayout->getPersonalSection(0);
                break;
        }
        if ($section != 'skills' && $section != 'resume' && $section != 'personal') {
            $canadd = $this->canAddMoreSection($uid, $resumeid, $section);
            $anchor = '<a class="add" data-section="' . $section . '"> + ' . esc_html(__('Add New', 'wp-job-portal')) . ' ' . wpjobportal::wpjobportal_getVariableValue($section) . '</a>';
        } else {
            $canadd = 0;
            $anchor = '';
        }
        //send email

        if($section == 'personal' && empty($data['id'])){
            WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3,1,$resumeid); // 3 for resume,1 for add new resume
        }
        $array = wp_json_encode(array('html' => $result, 'canadd' => $canadd, 'anchor' => $anchor, 'resumeid' => $resumeid));
        return $array;
    }

    function deleteResumeSectionAjax() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-resume-section-ajax') ) {
            die( 'Security check Failed' );
        }
        $section = WPJOBPORTALrequest::getVar('section');
        $data = WPJOBPORTALrequest::get('post');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $data['uid'] = $uid;
        $resumeid = $data['resumeid'];
        $row = null;
        switch ($section) {
            case 'languages':
                $row = WPJOBPORTALincluder::getJSTable('resumelanguage');
                break;
            // case 'references':
            //     $row = WPJOBPORTALincluder::getJSTable('resumereference');
            //     break;
            case 'employers':
                $row = WPJOBPORTALincluder::getJSTable('resumeemployer');
                break;
            case 'institutes':
                $row = WPJOBPORTALincluder::getJSTable('resumeinstitute');
                break;
            case 'addresses':
                $row = WPJOBPORTALincluder::getJSTable('resumeaddress');
                break;
        }
        $msg = esc_html(__('Section has been deleted', 'wp-job-portal'));
        $result = 1;
        if ($this->isYoursResume($resumeid, $uid)) {
            if (!$row->delete($data['sectionid'])) {
                $msg = esc_html(__('Error deleting section', 'wp-job-portal'));
                $result = 0;
            }
        }
        $canadd = $this->canAddMoreSection($uid, $resumeid, $section);
        $anchor = '<a class="add" data-section="' . $section . '"> + ' . esc_html(__('Add New', 'wp-job-portal')) . ' ' . wpjobportal::wpjobportal_getVariableValue($section) . '</a>';
        $array = wp_json_encode(array('canadd' => $canadd, 'msg' => $msg, 'result' => $result, 'anchor' => $anchor));
        return $array;
    }

    function canAddMoreSection($uid, $resumeid, $section) {
        $config_array = wpjobportal::$_config->getConfigByFor('resume');
        if (!is_numeric($resumeid))
            return false;
        if (!is_numeric($uid))
            return false;
        switch ($section) {
            case 'languages':
                $tablename = 'wj_portal_resumelanguages';
                $count = $config_array['max_resume_languages'];
                break;
            // case 'references':
            //     // $tablename = 'wj_portal_resumereferences';
            //     // $count = $config_array['max_resume_references'];
            //     break;
            case 'employers':
                $tablename = 'wj_portal_resumeemployers';
                $count = $config_array['max_resume_employers'];
                break;
            case 'institutes':
                $tablename = 'wj_portal_resumeinstitutes';
                $count = $config_array['max_resume_institutes'];
                break;
            case 'addresses':
                $tablename = 'wj_portal_resumeaddresses';
                $count = $config_array['max_resume_addresses'];
                break;
        }
        $query = "SELECT COUNT(sec.id)
                    FROM `" . wpjobportal::$_db->prefix . $tablename . "` AS sec
                    JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume ON resume.id = sec.resumeid
                    WHERE sec.resumeid = " . esc_sql($resumeid);
        $visallowed = 0;
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            if ($config_array['visitor_can_add_resume'] == 1) {
                $visallowed = 1;
            }
        }
        if ($uid && $visallowed = 0) {
            $query .= " AND resume.uid = ". esc_sql($uid);
        }
        $total = wpjobportal::$_db->get_var($query);
        if ($count > $total) {
            return 1;
        } else {
            return 0;
        }
    }

    function getResumeSectionAjax() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-resume-section-ajax') ) {
            die( 'Security check Failed' );
        }
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $section = WPJOBPORTALrequest::getVar('section');
        $sectionid = WPJOBPORTALrequest::getVar('sectionid');
        $resumeid = WPJOBPORTALrequest::getVar('resumeid');
        $resumelayout = WPJOBPORTALincluder::getObjectClass('resumeformlayout');
        $fieldsordering = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(3); // resume fields
        wpjobportal::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            wpjobportal::$_data[2][$field->section][$field->field] = $field->required;
        }

        $data = '';
        switch ($section) {
            case 'addresses':
                wpjobportal::$_data[0]['address_section'] = $this->getResumeAddressSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getAddressesSection(1, 1);
                break;
            case 'institutes':
                wpjobportal::$_data[0]['institute_section'] = apply_filters('wpjobportal_addons_getResume_action_ajx_adm',false,'getResumeInstituteSection',$resumeid,$uid,$sectionid);
                $data = apply_filters('wpjobportal_addons_view_resume_by_section_resume_ajx',false,'getEducationSection');
                break;
            case 'employers':
                wpjobportal::$_data[0]['employer_section'] = $this->getResumeEmployerSection($resumeid, $uid, $sectionid);
                $data = $resumelayout->getEmployerSection(1, 1);
                break;
            case 'languages':
                wpjobportal::$_data[0]['language_section'] = apply_filters('wpjobportal_addons_getResume_action_ajx_adm',false,'getResumeLanguageSection',$resumeid,$uid,$sectionid);
                $data = apply_filters('wpjobportal_addons_view_resume_by_section_resume_ajx',false,'getLanguageSection');
                break;
            case 'skills':
                if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                    wpjobportal::$_data[0]['personal_section'] =$this->getResumePersonalSection($resumeid, $uid, $sectionid);
                    $data = apply_filters('wpjobportal_addons_view_resume_by_section_resume_ajx',false,'getSkillSection');
                }
                break;
            case 'personal':
                wpjobportal::$_data[0]['personal_section'] = $this->getResumePersonalSection($resumeid, $uid);
                wpjobportal::$_data[0]['file_section'] = $this->getResumeFilesSection($resumeid, $uid);
                $data = $resumelayout->getPersonalSection(1);
                break;
        }
        return $data;
    }

    private function getUidByResumeId($id) {
        if (!is_numeric($id)) return false;
        $query = "SELECT uid FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($id);
        $uid = wpjobportal::$_db->get_var($query);
        return $uid;
    }

    public function getResumeTitle($id) {
        if (!is_numeric($id)) return false;
        $query = "SELECT application_title FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($id);
        $uid = wpjobportal::$_db->get_var($query);
        return $uid;
    }

    function getResumeById($id) {
        if (WPJOBPORTALincluder::getObjectClass('user')->isemployer() || current_user_can( 'manage_options' )) { // Current user is employer
            $uid = $this->getUidByResumeId($id);
        } else {
			$userobject = WPJOBPORTALincluder::getObjectClass('user');
			if($userobject->isguest() || !$userobject->isWPJOBPORTALUser()){
                $uid = $this->getUidByResumeId($id);
            }else{
                $uid = $userobject->uid();
			}
        }
        if(isset($_COOKIE['wpjobportal_apply_visitor']) && is_numeric($_COOKIE['wpjobportal_apply_visitor']) && !is_user_logged_in()){
            $query = "SELECT job.id as id,job.endfeatureddate,job.id,job.uid,job.title,job.isfeaturedjob,job.serverid,job.noofjobs,job.city,job.status,job.currency,
                CONCAT(job.alias,'-',job.id) AS jobaliasid,job.created,job.serverid,company.name AS companyname,company.id AS companyid,company.logofilename,CONCAT(company.alias,'-',company.id) AS compnayaliasid,job.salarytype,job.salarymin,job.salarymax,salaryrangetype.title AS salarydurationtitle,
                cat.cat_title, jobtype.title AS jobtypetitle,salaryrangetype.title AS srangetypetitle,
                (SELECT count(jobapply.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply
                 WHERE jobapply.jobid = job.id) AS resumeapplied ,job.params,job.startpublishing,job.stoppublishing
                 ,LOWER(jobtype.title) AS jobtypetit,jobtype.color as jobtypecolor
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salarytype WHERE job.id = " . sanitize_key($_COOKIE['wpjobportal_apply_visitor']);
            wpjobportal::$_data['jobinfo'] = wpjobportaldb::get_row($query);
            if(wpjobportal::$_data['jobinfo'] != ''){
                wpjobportal::$_data['jobinfo']->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView(wpjobportal::$_data['jobinfo']->city);
            }
            wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2);
            wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('job');
        }

        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {

            // $guestallowed = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_add_resume'); old code// problem
			$guestallowed = wpjobportal::$_config->getConfigurationByConfigName('visitorview_emp_viewresume');
            if ($guestallowed == 0)
                return false;
        }else {
            if($uid)
            if (is_numeric($uid) == false)
                return false;
        }
            if (($id != '') && ($id != 0)) {
                if (is_numeric($id) == false)
                    return false;
                global $job_portal_theme_options;
                // getting personal section
                wpjobportal::$_data[0]['personal_section'] = $this->getResumePersonalSection($id, $uid);
                // getting address section
                wpjobportal::$_data[0]['address_section'] = $this->getResumeAddressSection($id, $uid);
                // getting employer section
                wpjobportal::$_data[0]['employer_section'] = $this->getResumeEmployerSection($id, $uid);
                // getting institutes section
                wpjobportal::$_data[0]['institute_section'] = apply_filters('wpjobportal_addons_resume_by_user_adv',false,'getResumeInstituteSection',$id,$uid);
                // getting languages section
                wpjobportal::$_data[0]['language_section'] = apply_filters('wpjobportal_addons_resume_by_user_adv',false,'getResumeLanguageSection',$id,$uid);
               // getting file section
                wpjobportal::$_data[0]['file_section'] = $this->getResumeFilesSection($id, $uid);
                $theme = wp_get_theme();
            $layout = WPJOBPORTALrequest::getVar('wpjobportallt');
            $finalresume = array();
            if($layout == 'viewresume' && !wpjobportal::$_common->wpjp_isadmin() && !WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()){ // hiding related resumes from jobseeker. no point in showing those to job seeker
                if(wpjobportal::$theme_chk != 0){
                    // Related Resumes data
                    $max = $job_portal_theme_options['maximum_relatedresume'];
                    if(!is_numeric($max)){
                        $max = 5;
                    }
                    $finalresume = array();
                    $relatedresume=array();
                    $layout =WPJOBPORTALrequest::getVar("wpjobportallt");
                    if ($layout != 'printresume') {
                        //var_dump($job_portal_theme_options['relatedresume_criteria_sorter']['enabled']);
                        foreach($job_portal_theme_options['relatedresume_criteria_sorter']['enabled'] AS $key => $value){
                            $inquery = '';
                            switch($key){
                                case 'category':
                                    if(wpjobportal::$_data[0]['personal_section']->job_category != '' && is_numeric(wpjobportal::$_data[0]['personal_section']->job_category)){

                                        $inquery = ' resume.job_category = ' . esc_sql(wpjobportal::$_data[0]['personal_section']->job_category);
                                    }
                                break;
                                case 'jobtype':
                                    if(wpjobportal::$_data[0]['personal_section']->jobtype != '' && is_numeric(wpjobportal::$_data[0]['personal_section']->jobtype)){
                                        $inquery = ' resume.jobtype = ' . esc_sql(wpjobportal::$_data[0]['personal_section']->jobtype);
                                    }
                                break;
                            }
                            if(!empty($inquery)){
                                $query = "SELECT resume.id,resume.uid,resume.application_title, resume.first_name, resume.last_name,resume.photo,resume.job_category, cat.cat_title AS categorytitle, jobtype.title AS jobtypetitle, resume.jobtype
                                        ,resume.params,resume.status,resume.created,LOWER(jobtype.title) AS jobtypetit
                                        ,resumeaddress.address_city, resumeaddress.address, resumeaddress.longitude, resumeaddress.latitude
                                        ,city.name AS cityname, state.name AS statename, country.name AS countryname ,resumeaddress.params
                                        ,resume.salaryfixed as salary,LOWER(jobtype.title) AS jobtypetit,jobtype.color as jobtypecolor,resume.params
                                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = resume.job_category
                                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS resumeaddress ON resumeaddress.resumeid = resume.id
                                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = resumeaddress.address_city
                                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
                                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                                        WHERE 1=1 AND ".$inquery." AND resume.id != ".esc_sql($id)." AND resume.quick_apply <> 1 GROUP BY resume.id LIMIT ".esc_sql($max);
                                        $result = wpjobportaldb::get_results($query);
                                        $relatedresume = array_merge($relatedresume, $result);
                                        $relatedresume = array_map('unserialize', array_unique(array_map('serialize', $relatedresume)));
                                        if(COUNT($relatedresume) >= $max){
                                            break;
                                        }
                            }
                        }
                    }
                    if(!empty($relatedresume)){
                        foreach ($relatedresume AS $d) {
                            $d->location = WPJOBPORTALincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
                            //$d->salary = WPJOBPORTALincluder::getJSModel('common')->getSalaryRangeView($d->rangestart, $d->rangeend, $d->rangetype,$d->total_experience);
                            $finalresume[] = $d;
                        }
                    }
                    wpjobportal::$_data['relatedresume'] = $finalresume;
                }
                wpjobportal::$_data['relatedresume'] = $finalresume;
            }
        }
        wpjobportal::$_data['resumecontactdetail'] = true;
          if (WPJOBPORTALincluder::getObjectClass('user')->isguest() || (isset(wpjobportal::$_data[0]['personal_section']->uid) && wpjobportal::$_data[0]['personal_section']->uid != WPJOBPORTALincluder::getObjectClass('user')->uid())) {
                //$result = WPJOBPORTALincluder::getJSModel('credits')->getMinimumCreditIDByAction('view_resume_contact_detail');
                if (in_array('credits', wpjobportal::$_active_addons)) {
                    $subType = wpjobportal::$_config->getConfigValue('submission_type');
                    if($subType == 1){
                        wpjobportal::$_data['resumecontactdetail'] = true;
                    }elseif ($subType == 2 || $subType == 3) {
                        $contantdetail_paid = 1;
                        if($subType == 2){
                            if(!wpjobportal::$_config->getConfigValue('job_viewresumecontact_price_perlisting') > 0){
                                $contantdetail_paid = 0;
                            }
                        }
                        if($contantdetail_paid == 1){
                            wpjobportal::$_data['resumecontactdetail'] = $this->checkAlreadyViewResumeContactDetail($id);
                        }else{
                            wpjobportal::$_data['resumecontactdetail'] = true;
                        }
                    }
                }else{
                    wpjobportal::$_data['resumecontactdetail'] = true;
                }
                if(is_numeric($id) && $id > 0){
                    // if resume owner not viewing it then count the resume views, its shown on view resume page
                    $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_resume` SET hits = hits + 1 WHERE id = " . esc_sql($id);
                    wpjobportal::$_db->query($query);
                }
            }
             if(in_array('credits', wpjobportal::$_active_addons)){
                wpjobportal::$_data['paymentconfig'] = wpjobportal::$_wpjppaymentconfig->getPaymentConfigFor('paypal,stripe,woocommerce',true);
            }
            // code to show next back on view resume in case of job applied applitcation layout
            $jobapplyid = WPJOBPORTALrequest::getVar('jobapplyid');
            if(is_numeric($jobapplyid) && $jobapplyid > 0){

                // getting jobid and action_status to use in next query to get resumes with same data
                $query = "SELECT jobapply.jobid, jobapply.action_status
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply
                WHERE jobapply.id = " . esc_sql($jobapplyid);

                $job_apply_record = wpjobportaldb::get_row($query);
                if(!empty($job_apply_record) && is_numeric($job_apply_record->jobid) && is_numeric($job_apply_record->action_status)){
                    $query = "SELECT jobapply.cvid
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply
                        WHERE jobapply.jobid = " . esc_sql($job_apply_record->jobid) ." AND jobapply.action_status = ". esc_sql($job_apply_record->action_status)." ORDER BY jobapply.id DESC";
                    // cv ids that have same jobid and action_status
                    $job_apply_records_cvids = wpjobportal::$_db->get_col($query);

                    // index of current cv id to get next and back
                    $current_resume_index = array_search($id, $job_apply_records_cvids);
                    // the above function may return 0 as index value
                    if($current_resume_index !== FALSE){
                        wpjobportal::$_data['jobapply_resume_next'] = isset($job_apply_records_cvids[$current_resume_index + 1]) ? $job_apply_records_cvids[$current_resume_index + 1] : FALSE ;
                        wpjobportal::$_data['jobapply_resume_prev'] = isset($job_apply_records_cvids[$current_resume_index - 1]) ? $job_apply_records_cvids[$current_resume_index - 1] : FALSE ;
                        wpjobportal::$_data['jobapply_resume_jobapplyid'] = $jobapplyid;
                    }

                }

            }
        return;
    }

    function getResumePersonalSection($id, $uid) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        $query = "SELECT resume.id,resume.salaryfixed, resume.tags AS viewtags , resume.tags AS resumetags ,resume.uid,resume.application_title, resume.first_name, resume.last_name, resume.cell, resume.email_address, resume.nationality AS nationalityid, resume.photo, resume.gender, resume.job_category
                    , resume.skills, resume.keywords, cat.cat_title AS categorytitle, jobtype.title AS jobtypetitle,resume.jobtype
                    , resume.resume,nationality.name AS nationality
                    ,resume.params,resume.status,resume.hits AS resumehits ,resume.created,resume.searchable,LOWER(jobtype.title) AS jobtypetit,jobtype.color AS jobtypecolor,resume.quick_apply,resume.isfeaturedresume,resume.endfeatureddate
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = resume.job_category
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS nationality ON nationality.id = resume.nationality
                    WHERE resume.id = " . esc_sql($id);
        $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
        $iswpjobportaluser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPORTALUser();
        if(!$isguest && $iswpjobportaluser){
            if (!current_user_can( 'manage_options' ) && $uid) {
                $query .= " AND resume.uid = " . esc_sql($uid);
            }
        }
        $result = wpjobportaldb::get_row($query);
        if(!empty($result)){
            $result->resumetags = WPJOBPORTALincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn($result->resumetags);
        }
        return $result;
    }

    function getResumeAddressSection($id, $uid, $sectionid = null) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT resumeaddress.id, resumeaddress.address_city, resumeaddress.address
                        , city.name AS cityname, state.name AS statename, country.name AS countryname ,resumeaddress.params,resumeaddress.longitude,resumeaddress.latitude
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` resumeaddress
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = resumeaddress.address_city
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                    WHERE resumeaddress.resumeid = " . esc_sql($id);
        if ($sectionid != null) {
            if (!is_numeric($sectionid))
                return false;
            $query .= ' AND resumeaddress.id = ' . esc_sql($sectionid);
            $result = wpjobportaldb::get_row($query);
        }else {
            $result = wpjobportaldb::get_results($query);
        }

        return $result;
    }

    function getResumeEmployerSection($id, $uid, $sectionid = null) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT employer.id, employer.employer, employer.employer_current_status,employer.employer_from_date, employer.employer_to_date, employer.employer_city,employer.employer_position
                    , employer.employer_phone, employer.employer_address
                    , city.name AS cityname,employer.params, state.name AS statename, country.name AS countryname,city.latitude,city.longitude
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` AS employer
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = employer.employer_city
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                    WHERE employer.resumeid = " . esc_sql($id);
        if ($sectionid != null) {
            if (!is_numeric($sectionid))
                return false;
            $query .= ' AND employer.id = ' . esc_sql($sectionid);
            $result = wpjobportaldb::get_row($query);
        }else {
            $result = wpjobportaldb::get_results($query);
        }
        return $result;
    }

    function getResumeFilesSection($id, $uid) {
        if (!is_numeric($id))
            return false;
        if ($uid)
            if (!is_numeric($uid))
                return false;
        if (!$this->isYoursResume($id, $uid))
            return false;
        $query = "SELECT *
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles`
                    WHERE resumeid = " . esc_sql($id);
        $result = wpjobportaldb::get_results($query);
        return $result;
    }

    function getResumeFiles() {
        $resumeid = (int) WPJOBPORTALrequest::getVar('resumeid');
        if(!is_numeric($resumeid)){
            return false;
        }
        $data_directory = wpjobportal::$_config->getConfigValue('data_directory');
        $files = array();
        $totalFilesQry = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` WHERE resumeid=" . esc_sql($resumeid);
        $filesFound = wpjobportaldb::get_results($totalFilesQry);
        if ($filesFound > 0) {
            $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` WHERE resumeid = " . esc_sql($resumeid);
            $files = wpjobportaldb::get_results($query);
        }
        // resume form layout class
        include_once(WPJOBPORTAL_PLUGIN_PATH . '/includes/resumeformlayout.php');
        $resumeformlayout = new WPJOBPORTALResumeformlayout();
        $data = $resumeformlayout->getResumeFilesLayout($files, $data_directory);
        return $data;
    }

    function uploadResume($id) {
        if (is_numeric($id) == false)
            return false;
        WPJOBPORTALincluder::getObjectClass('uploads')->uploadResumeFiles($id);
        return;
    }

    function uploadPhoto($id) {
        if (is_numeric($id) == false)
            return false;
        WPJOBPORTALincluder::getObjectClass('uploads')->uploadResumePhoto($id);
        return;
    }

    function deleteResume($ids) {
        if (empty($ids))
            return false;
        $notdeleted = 0;
        $row = WPJOBPORTALincluder::getJSTable('resume');
        foreach ($ids as $id) {
            if(!is_numeric($id)){
                continue;
            }
            if ($this->resumeCanDelete($id) == true) {
                //code for preparing data for delete resume email
                $resultforsendmail = WPJOBPORTALincluder::getJSModel('resume')->getResumeInfoForEmail($id);
                $username = $resultforsendmail->firstname . '' . $resultforsendmail->lastname;
                if ($username == '') {
                    $username = $resultforsendmail->username;
                }
                $email = $resultforsendmail->useremailfromresume;
                if ($email == '') {
                    $email = $resultforsendmail->useremail;
                }
                $resumetitle = $resultforsendmail->resumetitle;
                $mailextradata = array();
                $mailextradata['resumetitle'] = $resumetitle;
                $mailextradata['jobseekername'] = $username;
                $mailextradata['useremail'] = $email;

                if (!$row->delete($id)) {
                    $notdeleted += 1;
                }
                $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = " . esc_sql($id);
                wpjobportaldb::query($query);
                $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` WHERE resumeid = " . esc_sql($id);
                wpjobportaldb::query($query);

                $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` WHERE resumeid = " . esc_sql($id);
                wpjobportaldb::query($query);
                $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` WHERE resumeid = " . esc_sql($id);
                wpjobportaldb::query($query);

                $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumelanguages` WHERE resumeid = " . esc_sql($id);
                wpjobportaldb::query($query);
                $wpdir = wp_upload_dir();
                $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
                array_map('wp_delete_file', glob($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id."/resume/*.*"));//deleting files
                array_map('wp_delete_file', glob($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id."/photo/*.*"));//deleting files
                if ( ! function_exists( 'WP_Filesystem' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                global $wp_filesystem;
                if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
                    $creds = request_filesystem_credentials( site_url() );
                    wp_filesystem( $creds );
                }


                if ($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id.'/resume')) {
                    @$wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id.'/resume');
                }
                if ($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id.'/photo')) {
                    @$wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id.'/photo');
                }
                if ($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id)) {
                    @$wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$id);
                }
                WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, 6, $id,$mailextradata); // 3 for resume,6 for DELETE resume
                // action hook for delete resume
                do_action('wpjobportal_after_delete_resume_hook',$id);
            }else{
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            WPJOBPORTALMessages::$counter = false;
            return WPJOBPORTAL_DELETED;
        } else {
            WPJOBPORTALMessages::$counter = $notdeleted;
            return WPJOBPORTAL_DELETE_ERROR;
        }
    }

    function resumeCanDelete($resumeid) {
        if (!is_numeric($resumeid))
            return false;
        if(!wpjobportal::$_common->wpjp_isadmin()){
            if(!$this->getIfResumeOwner($resumeid)){
                return false;
            }
        }
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE cvid = " . esc_sql($resumeid);
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function resumeEnforceDelete($resumeid, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($resumeid) == false)
            return false;

        $juid = 0; // jobseeker uid
        $query = "DELETE  resume,apply,resumeaddress,resumeemployers,resumefiles
                            ,resumeinstitutes,resumelanguages";
        if(in_array('folder', wpjobportal::$_active_addons)){
            $query .= ",resumefolder";
        }
        if(in_array('message', wpjobportal::$_active_addons)){
            $query .= ",message";
        }

        $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS apply ON resume.id=apply.cvid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS resumeaddress ON resume.id=resumeaddress.resumeid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` AS resumeemployers ON resume.id=resumeemployers.resumeid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` AS resumefiles ON resume.id=resumefiles.resumeid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` AS resumeinstitutes ON resume.id=resumeinstitutes.resumeid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumelanguages` AS resumelanguages ON resume.id=resumelanguages.resumeid";
        if(in_array('folder', wpjobportal::$_active_addons)){
            $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_folderresumes` AS resumefolder ON 
                    resume.id=resumefolder.resumeid";
        }
        if(in_array('message', wpjobportal::$_active_addons)){
            $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_messages` AS message ON
                    resume.id = message.resumeid ";
        }
        $query .= " WHERE resume.id = " . esc_sql($resumeid);
            //code for preparing data for delete resume email
                $resultforsendmail = WPJOBPORTALincluder::getJSModel('resume')->getResumeInfoForEmail($resumeid);
                $username = $resultforsendmail->firstname . ' ' . $resultforsendmail->lastname;
                if ($username == '') {
                    $username = $resultforsendmail->username;
                }
                $email = $resultforsendmail->useremailfromresume;
                if ($email == '') {
                    $email = $resultforsendmail->useremail;
                }
                $resumetitle = $resultforsendmail->resumetitle;

                $mailextradata['resumetitle'] = $resumetitle;
                $mailextradata['jobseekername'] = $username;
                $mailextradata['useremail'] = $email;

        if (!wpjobportaldb::query($query)) {
            return WPJOBPORTAL_DELETE_ERROR; //error while delete resume
        }

        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        array_map('wp_delete_file', glob($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$resumeid."/resume/*.*"));//deleting files
        array_map('wp_delete_file', glob($wpdir['basedir'] . '/'. $data_directory . "/data/jobseeker/resume_".$resumeid."/photo/*.*"));//deleting files
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }


        if($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$resumeid.'/resume')) {
            $wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$resumeid.'/resume');
        }
        if($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$resumeid.'/photo')) {
            $wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$resumeid.'/photo');
        }
        if($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$resumeid)) {
            $wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/jobseeker/resume_".$resumeid);
        }

        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, 6, $resumeid,$mailextradata); // 3 for resume,6 for DELETE resume
        // action hook for delete resume
        do_action('wpjobportal_after_delete_resume_hook',$resumeid);
        return WPJOBPORTAL_DELETED;
    }

    function getResumeInfoForEmail($resumeid) {
        if ((is_numeric($resumeid) == false))
            return false;
        // changed join to left join to get mail data for visitor reumes
        $query = 'SELECT resume.application_title AS resumetitle, CONCAT(user.first_name," ",user.last_name) AS username
                        ,resume.email_address AS useremailfromresume
                        ,resume.first_name AS firstname, resume.last_name AS lastname
                        , resume.email_address AS useremail
                        FROM `' . wpjobportal::$_db->prefix . 'wj_portal_resume` AS resume
                        LEFT JOIN `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS user ON user.id = resume.uid
                        WHERE resume.id = '.esc_sql($resumeid);
        $return_value = wpjobportaldb::get_row($query);
        return $return_value;
    }

    function empappReject($app_id) {
        if (is_numeric($app_id) == false)
            return false;

        $row = WPJOBPORTALincluder::getJSTable('resume');
        if(! $row->update(array('id' => $app_id , 'status' => -1))){
            return WPJOBPORTAL_DELETE_ERROR;
        }

        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, -1, $app_id);
        return WPJOBPORTAL_REJECTED;
    }

    function canAddResume($uid,$actionname='') {
        #User authentication submission
        if (!is_numeric($uid))
            return false;
        if(in_array('credits', wpjobportal::$_active_addons)){
            $credits = apply_filters('wpjobportal_addons_userpackages_module_wise',false,$uid,$actionname);
            if($credits){
                return true;
            }else{
                return false;
            }
        }else{
            return $this->checkAlreadyadd($uid);
        }
    }

    function getResumeTitleById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT resume.application_title FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume WHERE resume.id = " . esc_sql($id);
        $jobname = wpjobportal::$_db->get_var($query);
        return $jobname;
    }

    function getResumes($vars,$show_only_featured = 0,$ignore_serachable = 0) {
        //die('abc');
        $inquery = '';
        $jsformresumesearch = WPJOBPORTALrequest::getVar('jsformresumesearch');
        if (isset($jsformresumesearch) AND $jsformresumesearch == 1) {
            wpjobportal::$_data['issearchform'] = 1;
            wpjobportal::$_data['filter'] =  array();
        }

        if (isset($vars['category']) AND $vars['category'] != '') {
            $categoryid = $vars['category'];
            if (!is_numeric($categoryid))
                return false;
            $wpjp_query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` WHERE parentid = ". esc_sql($categoryid);
            $wpjp_cats = wpjobportaldb::get_results($wpjp_query);
            $wpjp_ids = [];
            foreach ($wpjp_cats as $wpjp_cat) {
                $wpjp_ids[] = $wpjp_cat->id;
            }
            $wpjp_ids[] = $categoryid;
            $wpjp_ids = implode(",",$wpjp_ids);
            $inquery = " AND resume.job_category IN(".$wpjp_ids.")";
            wpjobportal::$_data['filter']['category'] = $categoryid;
        }
        if (isset($vars['searchid']) AND $vars['searchid'] != '') {
            $search = $vars['searchid'];
            if (!is_numeric($search))
                return false;
            $inquery = $this->getSaveSearchForView($search);
            wpjobportal::$_data['filter']['search'] = $search;
        }
        if (isset($vars['tags']) AND $vars['tags'] != '') {
            wpjobportal::$_data['fromtags'] = $vars['tags'];
            $tags = $vars['tags'];
            $inquery = " AND resume.tags LIKE '%" . esc_sql($tags) . "%'";
            // to populate tags fields on the search form.
            wpjobportal::$_data['filter']['tag'] = WPJOBPORTALincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn($tags);
        }

        // ai resume search

        // variable to handle the resme data prepration overide
        $dont_prep_data = 0;

        $airesumesearcch = isset(wpjobportal::$_search['resumes']['airesumesearcch']) ? wpjobportal::$_search['resumes']['airesumesearcch'] : '';
        if ($airesumesearcch != '') {
        // AI search
            do_action('wpjobportal_addons_airesumesearch_query');
            if( !empty(wpjobportal::$_data['ai_resume_data_set']) ){
                $dont_prep_data = 1; // ignore the below code data is preped.
            }
        }

        // ai resume search

        // echo '<pre>';print_r(wpjobportal::$_search['resumes']);echo '</pre>';
        // echo '<pre>';print_r($vars);echo '</pre>';
        // variable to handle the resme data prepration overide
        if (isset($vars['aisuggestedresumes_job']) AND $vars['aisuggestedresumes_job'] != '') {
            do_action('wpjobportal_addons_aisuggestedresumes_resumes',$vars['aisuggestedresumes_job']);
            if( !empty(wpjobportal::$_data['ai_resume_data_set']) ){
                $dont_prep_data = 1; // ignore the below code data is preped.
            }
        }

        $this->sortingrescat();
        //variables form search form
            $title = isset(wpjobportal::$_search['resumes']['application_title']) ? wpjobportal::$_search['resumes']['application_title'] : '';
            if ($title != '') {
                $inquery .= ' AND resume.application_title LIKE "%' . esc_sql($title) . '%" ';
                wpjobportal::$_data['filter']['application_title'] = $title;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $firstName = isset(wpjobportal::$_search['resumes']['first_name']) ? wpjobportal::$_search['resumes']['first_name'] : '';
            if ($firstName != '') {
                $inquery .= ' AND resume.first_name LIKE "%' . esc_sql($firstName) . '%" ';
                wpjobportal::$_data['filter']['first_name'] = $firstName;
                wpjobportal::$_data['issearchform'] = 1;
            }
            $middle_name = isset(wpjobportal::$_search['resumes']['middle_name']) ? wpjobportal::$_search['resumes']['middle_name'] : '';
            $lastName = isset(wpjobportal::$_search['resumes']['last_name']) ? wpjobportal::$_search['resumes']['last_name'] : '';
            if ($lastName != '') {
                $inquery .= ' AND resume.last_name LIKE "%' . esc_sql($lastName) . '%" ';
                wpjobportal::$_data['filter']['last_name'] = $lastName;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $nationality = isset(wpjobportal::$_search['resumes']['nationality']) ? wpjobportal::$_search['resumes']['nationality'] : '';
            if ($nationality != '' && is_numeric($nationality)) {
                $inquery .= ' AND resume.nationality =' . esc_sql($nationality) . '';
                wpjobportal::$_data['filter']['nationality'] = $nationality;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $gender = isset(wpjobportal::$_search['resumes']['gender']) ? wpjobportal::$_search['resumes']['gender'] : '';
            if ($gender != '') {
                $inquery .= ' AND resume.gender LIKE "%' . esc_sql($gender) . '%" ';
                wpjobportal::$_data['filter']['gender'] = $gender;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $salaryfixed = isset(wpjobportal::$_search['resumes']['salaryfixed']) ? wpjobportal::$_search['resumes']['salaryfixed'] : '';
            if ($salaryfixed != '' && is_numeric($salaryfixed)) {
                $inquery .= ' AND resume.salaryfixed = "' . esc_sql($salaryfixed).'"';// non numric value casuing query error without quotes
                wpjobportal::$_data['filter']['salaryfixed'] = $salaryfixed;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $jobType = isset(wpjobportal::$_search['resumes']['jobtype']) ? wpjobportal::$_search['resumes']['jobtype'] : '';
            if ($jobType != '' && is_numeric($jobType)) {
                $inquery .= ' AND resume.jobtype = ' . esc_sql($jobType) . ' ';
                wpjobportal::$_data['filter']['jobtype'] = $jobType;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $salaryRangeType = isset(wpjobportal::$_search['resumes']['salaryrangetype']) ? wpjobportal::$_search['resumes']['salaryrangetype'] : '';
            if ($salaryRangeType != '' && is_numeric($salaryRangeType)) {
                $inquery .= ' AND resume.jobsalaryrangetype = ' . esc_sql($salaryRangeType) . '  ';
                wpjobportal::$_data['filter']['salaryrangetype'] = $salaryRangeType;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $category = isset(wpjobportal::$_search['resumes']['category']) ? wpjobportal::$_search['resumes']['category'] : '';
            if ($category != '' && is_numeric($category)) {
                $inquery .= ' AND resume.job_category = ' . esc_sql($category) . ' ';
                wpjobportal::$_data['filter']['category'] = $category;
                wpjobportal::$_data['issearchform'] = 1;
            }

            $zipCode = isset(wpjobportal::$_search['resumes']['zipcode']) ? wpjobportal::$_search['resumes']['zipcode'] : '';
            if ($zipCode) {
                wpjobportal::$_data['filter']['zipcode'] = $zipCode;
            }

            $keywords = isset(wpjobportal::$_search['resumes']['keywords']) ? wpjobportal::$_search['resumes']['keywords'] : '';
            if ($keywords) {
                $res = $this->makeQueryFromArray('keywords', $keywords);
                if ($res)
                    $inquery .= " AND ( " . $res . " )";
                wpjobportal::$_data['filter']['keywords'] = $keywords;
            }

            //Custom field search
            //start
            $data = wpjobportal::$_wpjpcustomfield->userFieldsData(3);/*apply_filters('wpjobportal_addons_customFields_user',false,3,'userFieldsData')*/;
            $valarray = array();
            if (!empty($data)) {
                foreach ($data as $uf) {
                    $session_userfield = isset(wpjobportal::$_search['resume_custom_fields'][$uf->field]) ? wpjobportal::$_search['resume_custom_fields'][$uf->field] : '';

                    $valarray[$uf->field] = $session_userfield;
                    if (isset($valarray[$uf->field]) && $valarray[$uf->field] != null && $valarray[$uf->field] !="" ) {
                        switch ($uf->userfieldtype) {
                            case 'text':
                            case 'email':
                                $inquery .= ' AND resume.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                                break;
                            case 'combo':
                                $inquery .= ' AND resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                                $or = " OR ";
                                break;
                            case 'depandant_field':
                                $inquery .= ' AND resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                                break;
                            case 'radio':
                                $inquery .= ' AND resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                                break;
                            case 'checkbox':
                                $finalvalue = '';
                                foreach($valarray[$uf->field] AS $value){
                                    $finalvalue .= $value.'.*';
                                }
                                $inquery .= ' AND resume.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($finalvalue) . '.*"\' ';
                                break;
                            case 'date':
                                if (isset($valarray[$uf->field]) && $valarray[$uf->field] != '') {
                                    $valarray[$uf->field] = gmdate('Y-m-d H:i:s',strtotime($valarray[$uf->field]));
                                }
                                $inquery .= ' AND resume.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                                break;
                            case 'textarea':
                                $inquery .= ' AND resume.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                                break;
                            case 'multiple':
                                $finalvalue = '';
                                foreach($valarray[$uf->field] AS $value){
                                    if($value){
                                        $finalvalue .= $value.'.*';
                                    }
                                }
                                if($finalvalue){
                                    $inquery .= ' AND resume.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*'.wpjobportalphplib::wpJP_htmlspecialchars($finalvalue).'"\'';
                                }
                                break;
                        }
                        wpjobportal::$_data['filter']['params'] = $valarray;
                        wpjobportal::$_data['issearchform'] = 1;
                    }
                }
            }
            //end
            $tags = WPJOBPORTALrequest::getVar('tags');
            if ($tags) {
                wpjobportal::$_data['filter']['tag'] = WPJOBPORTALincluder::getJSModel('common')->makeFilterdOrEditedTagsToReturn($tags);
                $res = $this->makeQueryFromArray('tags', $tags);
                if ($res)
                    $inquery .= " AND ( " . $res . " )";
            }
            $city = isset(wpjobportal::$_search['resumes']['city']) ? wpjobportal::$_search['resumes']['city'] : '';
            if ($city != '') {
                wpjobportal::$_data['filter']['city'] = WPJOBPORTALincluder::getJSModel('common')->getCitiesForFilter($city);
                $res = $this->makeQueryFromArray('city', $city);
                if ($res)
                    $inquery .= " AND ( " . $res . " )";
            }

            if($show_only_featured == 1){
                $inquery .= " AND resume.isfeaturedresume = 1 AND DATE(resume.endfeatureddate) >= CURDATE() ";
            }

        // shortcode options
        $noofresumes = '';
        $module_name = WPJOBPORTALrequest::getVar('wpjobportalme');
        if($module_name == 'allresumes'){
            //shortcode attribute proceesing (filter,ordering,no of resume)
            $attributes_query = $this->processShortcodeAttributesResume();
            if($attributes_query != ''){
                $inquery .= $attributes_query;
            }
            if(isset(wpjobportal::$_data['shortcode_option_no_of_resumes']) && wpjobportal::$_data['shortcode_option_no_of_resumes'] > 0){
                $noofresumes = wpjobportal::$_data['shortcode_option_no_of_resumes'];
            }
        }
        if($dont_prep_data == 0){
            //Pagination
            if($noofresumes == ''){
                $query = "SELECT COUNT(resume.id) AS total
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category ";
                    if($zipCode != ''){
                        $query .= " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address1 ON (address1.resumeid = resume.id AND address1.address_zipcode = '".esc_sql($zipCode)."' ) ";
                    }elseif ($city != '') {
                        $query .= " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address1 ON address1.resumeid = resume.id ";
                    }elseif (strstr($inquery, 'address1.address_city')) { // to handle shortcode option case for locations
                        $query .= " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address1 ON address1.resumeid = resume.id ";
                    }
                    $query .= " WHERE resume.status = 1 AND resume.quick_apply <> 1 ";
                    if($ignore_serachable == 0){
                        $query .= " AND resume.searchable = 1 ";
                    }
                $query .= $inquery;
                $total = wpjobportaldb::get_var($query);
                wpjobportal::$_data['total'] = $total;
                wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total,'resumes');
            }

            //Data
            $query = "SELECT resume.id,CONCAT(resume.alias,'-',resume.id) AS resumealiasid ,resume.first_name
                    ,resume.last_name,resume.application_title as applicationtitle,resume.email_address,category.cat_title
                    ,resume.created,jobtype.title AS jobtypetitle,resume.photo,
                    resume.isfeaturedresume,resume.endfeatureddate
                    ,resume.status,city.name AS cityname
                    ,state.name AS statename,resume.params,resume.salaryfixed as salary
                    ,resume.last_modified,LOWER(jobtype.title) AS jobtypetit,jobtype.color as jobtypecolor,country.name AS countryname,resume.id as resumeid,resume.skills
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category ";
                if($zipCode != ''){
                    $query .= " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address1 ON (address1.resumeid = resume.id AND address1.address_zipcode = '".esc_sql($zipCode)."' ) ";
                }elseif ($city != '') {
                    $query .= " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address1 ON address1.resumeid = resume.id ";
                }elseif (strstr($inquery, 'address1.address_city')) { // to handle shortcode option case for locations
                    $query .= " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address1 ON address1.resumeid = resume.id ";
                }
                $query .= "
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid ";
                    $query .= " WHERE resume.status = 1 AND resume.quick_apply <> 1";
                    if($ignore_serachable == 0){
                        $query .= " AND resume.searchable = 1 ";
                    }
            $query .= $inquery;
            $query .= " GROUP BY resume.id ";
            $query.= " ORDER BY " . wpjobportal::$_data['sorting'];
            if($noofresumes == ''){
                $query .=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
            }elseif(is_numeric($noofresumes)){
                $query .=" LIMIT " . esc_sql($noofresumes);
            }

            $results = wpjobportal::$_db->get_results($query);
            $data = array();
            foreach ($results AS $d) {
                //  updated the query select to select 'name' as cityname
                $d->location = WPJOBPORTALincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
                $data[] = $d;
            }
            wpjobportal::$_data[0] = $data;
        }

        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('resume');
        wpjobportal::$_data[2] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforSearch(3);
        wpjobportal::$_data['listingfields'] = wpjobportal::$_wpjpfieldordering->getFieldsForListing(3);
        // not counting the resume searches in transaction log
        return;
    }
    ///

    public function makeQueryFromArray($for, $array) {
        if (empty($array))
            return false;
        $qa = array();
        switch ($for) {
            case 'keywords':
                $array = wpjobportalphplib::wpJP_explode(",", $array);
                $total = count($array);
                for ($i = 0; $i < $total; $i++) {
                    $qa[] = "resume.keywords LIKE '%" . esc_sql(wpjobportalphplib::wpJP_trim($array[$i])) . "%'";
                }
                break;
            case 'tags':
                $array = wpjobportalphplib::wpJP_explode(',', $array);
                foreach ($array as $item) {
                    $qa[] = "resume.tags LIKE '%" . esc_sql($item) . "%'";
                }
                break;
            case 'city':
                $array = wpjobportalphplib::wpJP_explode(',', $array);
                foreach ($array as $item) {
                    if(is_numeric($item)){
                        $qa[] = " address1.address_city = " . esc_sql($item);
                    }
                }
                break;
        }
        $query = implode(" OR ", $qa);
        return $query;
    }

    function getAllResumeFiles() {
        $resumeid = WPJOBPORTALrequest::getVar('resumeid');
        // $resumeid = WPJOBPORTALincluder::getJSModel('common')->decodeIdForDownload($resumeid_string);

        if(!is_numeric($resumeid)){
            return false;
        }
        do_action('wpjobportal_load_wp_pcl_zip');
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $path = WPJOBPORTAL_PLUGIN_PATH . $data_directory;

        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        if (!$wp_filesystem->exists($path)) {
            WPJOBPORTALincluder::getJSModel('common')->makeDir($path);
        }
        $path .= '/zipdownloads';
        if (!$wp_filesystem->exists($path)) {
            WPJOBPORTALincluder::getJSModel('common')->makeDir($path);
        }
        $randomfolder = $this->getRandomFolderName($path);
        $path .= '/' . $randomfolder;
        if (!$wp_filesystem->exists($path)) {
            WPJOBPORTALincluder::getJSModel('common')->makeDir($path);
        }
        $archive = new PclZip($path . '/allresumefiles.zip');
        $wpdir = wp_upload_dir();
        $directory = $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resumeid . '/resume/';
        //$scanned_directory = array_diff(scandir($directory), array('..', '.'));// code seems reduntant but showing error on deleted resume file downloads
        $filelist = '';
        $query = "SELECT filename FROM `".wpjobportal::$_db->prefix."wj_portal_resumefiles` WHERE resumeid = ".esc_sql($resumeid);
        $files = wpjobportal::$_db->get_results($query);
        foreach ($files AS $file) {
            $filelist .= $directory . '/' . $file->filename . ',';
        }
        $filelist = wpjobportalphplib::wpJP_substr($filelist, 0, wpjobportalphplib::wpJP_strlen($filelist) - 1);
        $v_list = $archive->create($filelist, PCLZIP_OPT_REMOVE_PATH, $directory);
        if ($v_list == 0) {
            die("Error : '" . wp_kses($archive->errorInfo(),WPJOBPORTAL_ALLOWED_TAGS) . "'");
        }
        $file = $path . '/allresumefiles.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . wpjobportalphplib::wpJP_basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();//this was commented and causing problems
        flush();
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }
        echo $wp_filesystem->get_contents($file);
        @wp_delete_file($file);
        $path = WPJOBPORTAL_PLUGIN_PATH . $data_directory;
        $path .= '/zipdownloads';
        $path .= '/' . $randomfolder;
        @wp_delete_file($path . '/index.html');


        $wp_filesystem->rmdir($path);
        exit();
    }

    function getResumeFileDownloadById($fileid) {

        //$fileid = WPJOBPORTALincluder::getJSModel('common')->decodeIdForDownload($fileid_string);

        if (!is_numeric($fileid))
            return false;
        $query = "SELECT filename,resumeid FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` WHERE id = " . esc_sql($fileid);
        $object = wpjobportal::$_db->get_row($query);
        if(empty($object) || !is_numeric($object->resumeid)){ // if the file record does not exsists.(was logging error on accesing properties for null/empty object)
            exit;
        }
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $file =  $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $object->resumeid . '/resume/' . $object->filename;

        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        if (!$wp_filesystem->exists($file)) {// if file does not exsit then stop executing code(handling log errors)
            exit;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . wpjobportalphplib::wpJP_basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
//        ob_clean();
        flush();
        echo $wp_filesystem->get_contents($file);
        exit();
    }

    function getRandomFolderName($path) {
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        $match = '';
        do {
            $rndfoldername = "";
            $length = 5;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
            $maxlength = wpjobportalphplib::wpJP_strlen($possible);
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            $i = 0;
            while ($i < $length) {
                $char = wpjobportalphplib::wpJP_substr($possible, wp_rand(0, $maxlength - 1), 1);
                if (!wpjobportalphplib::wpJP_strstr($rndfoldername, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $rndfoldername .= $char;
                            $i++;
                        }
                    } else {
                        $rndfoldername .= $char;
                        $i++;
                    }
                }
            }
            $folderexist = $path . '/' . $rndfoldername;
            if ($wp_filesystem->exists($folderexist))
                $match = 'Y';
            else
                $match = 'N';
        }while ($match == 'Y');

        return $rndfoldername;
    }

    function getResumenameById($resumeid) {
        if (!is_numeric($resumeid))
            return false;
        $query = "SELECT resume.application_title FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume WHERE resume.id = " . esc_sql($resumeid);
        $resumename = wpjobportal::$_db->get_var($query);
        return $resumename;
    }

    function addViewContactDetail($resumeid, $uid) {
        $profileid = 0;
        if(wpjobportalphplib::wpJP_strstr($resumeid, 'jssc-')){
            $array = wpjobportalphplib::wpJP_explode('-', $resumeid);
            $profileid = $array[1];
            $resumeid = 0;
        }
        if (!is_numeric($profileid))
            return false;
        if (!is_numeric($resumeid))
            return false;
        if (!is_numeric($uid))
            return false;
        $curdate = gmdate('Y-m-d H:i:s');
        $row = WPJOBPORTALincluder::getJSTable('employerviewresume');
        $data = array();
        if(in_array('credits', wpjobportal::$_active_addons)){
            #Submission Type
            $subType = wpjobportal::$_config->getConfigValue('submission_type');
            if ($subType == 3) {
                #Membershipe Code for Featured Resume
                $packageid = WPJOBPORTALrequest::getVar('wpjobportal_packageid');
                # Package Filter's
                $package = apply_filters('wpjobportal_addons_userpackages_perfeaturemodule',false,$packageid,'remresumecontactdetail');
                if($package && !$package->expired && ($package->resumecontactdetail==-1 || $package->resumecontactdetail)){ //-1 = unlimited
                    #Data For Featured Company Member
                    $data['uid'] = $uid;
                    $data['resumeid'] = $resumeid;
                    $data['status'] = 1;
                    $data['created'] = $curdate;
                    $data['profileid'] = $profileid;
                    $data['userpackageid'] = $package->packageid;
                    $data = wpjobportal::wpjobportal_sanitizeData($data);
                    #Job sekker Company View
                    if($this->checkAlreadyViewResumeContactDetail($resumeid) == false){
                        if($row->bind($data)){
                            if($row->store()){
                                # Company Contact View Resume Transactio Log Entries--
                                $trans = WPJOBPORTALincluder::getJSTable('transactionlog');
                                $arr = array();
                                $arr['userpackageid'] = $package->id;
                                $arr['uid'] = $uid;
                                $arr['recordid'] = $resumeid;
                                $arr['type'] = 'resumecontactdetail';
                                $arr['created'] = current_time('mysql');
                                $arr['status'] = 1;
                                $trans->bind($arr);
                                $trans->store();
                                WPJOBPORTALmessages::setLayoutMessage(esc_html(__('You can view Resume Contact Detail Now','wp-job-portal')), 'updated',$this->getMessagekey());
                                return true;
                            }else{
                                return false;
                            }
                        }
                    }else{
                        return false;
                    }
                }else{
                    WPJOBPORTALmessages::setLayoutMessage(esc_html(__("There was some problem performing action",'wp-job-portal')), 'error',$this->getMessagekey());
                    return false;
                }
            }elseif ($subType == 2) {
                # Paid Perlisting
                $data['status']  == 3;
            }elseif ($status == 1) {
                # Free
                $data['status'] == 1;
            }

        }
        $data['uid'] = $uid;
        $data['resumeid'] = $resumeid;
        if(!isset($data['status'])){
            $data['status'] = 1;
        }
        $data['created'] = $curdate;
        $data['profileid'] = $profileid;
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        if (!$row->bind($data)) {
            return false;
        }

        if($row->store()){
            return true;
        }else{
            return false;
        }
    }

    function getOrdering() {
        $sort = WPJOBPORTALrequest::getVar('sortby', '', null);
        if ($sort == null) {
            $id = WPJOBPORTALrequest::getVar('wpjobportalid');
            if ($id != null) {
                $array = wpjobportalphplib::wpJP_explode('_', $id);
                if ($array[1] == '14') {
                    $sort = $array[0];
                }
            }
        }else{
            $array = wpjobportalphplib::wpJP_explode('_', $sort);
            if (isset($array[1]) && $array[1] == '14') {
                $sort = $array[0];
            }
        }
        if ($sort == null) {
            $sort = 'posteddesc';
        }

        $this->getListOrdering($sort);
        $this->getListSorting($sort);
    }

    function getListOrdering($sort) {
        switch ($sort) {
            case "titledesc":
                wpjobportal::$_ordering = "resume.application_title DESC";
                wpjobportal::$_sorton = "title";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "titleasc":
                wpjobportal::$_ordering = "resume.application_title ASC";
                wpjobportal::$_sorton = "title";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "jobtypedesc":
                wpjobportal::$_ordering = "jobtype.title DESC";
                wpjobportal::$_sorton = "jobtype";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "jobtypeasc":
                wpjobportal::$_ordering = "jobtype.title ASC";
                wpjobportal::$_sorton = "jobtype";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "salarydesc":
                wpjobportal::$_ordering = "salaryrangestart.rangestart DESC";
                wpjobportal::$_sorton = "salary";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "salaryasc":
                wpjobportal::$_ordering = "salaryrangestart.rangestart ASC";
                wpjobportal::$_sorton = "salary";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "posteddesc":
                wpjobportal::$_ordering = "resume.created DESC";
                wpjobportal::$_sorton = "posted";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "postedasc":
                wpjobportal::$_ordering = "resume.created ASC";
                wpjobportal::$_sorton = "posted";
                wpjobportal::$_sortorder = "ASC";
                break;
            default: wpjobportal::$_ordering = "resume.created DESC";
        }
        return;
    }

    function getSortArg($type, $sort) {
        $mat = array();
        if (wpjobportalphplib::wpJP_preg_match("/(\w+)(asc|desc)/i", $sort, $mat)) {
            if ($type == $mat[1]) {
                return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
            } else {
                return $type . $mat[2];
            }
        }
        return "iddesc";
    }

    function getListSorting($sort) {
        wpjobportal::$_sortlinks['title'] = $this->getSortArg("title", $sort);
        wpjobportal::$_sortlinks['salary'] = $this->getSortArg("salary", $sort);
        wpjobportal::$_sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        wpjobportal::$_sortlinks['posted'] = $this->getSortArg("posted", $sort);
        return;
    }

    function removeResumeFileById() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'remove-resume-file-by-id') ) {
            die( 'Security check Failed' );
        }
        $id = WPJOBPORTALrequest::getVar('id');
        if (!is_numeric($id))
            return false;
        if(current_user_can('manage_options')){
            $uid = ' resume.uid ';
        }else{
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        }
        $query = "SELECT COUNT(file.id) AS file, resume.id AS resumeid, file.filename
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                    JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` AS file ON file.resumeid = resume.id
                    WHERE resume.uid = ". esc_sql($uid)." AND file.id = " . esc_sql($id);
        $file = wpjobportal::$_db->get_row($query);
        if ($file->file > 0) { // You are the owner
            $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` WHERE id = " . esc_sql($id);
            wpjobportal::$_db->query($query);
            $wpdir = wp_upload_dir();
            $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
            $file = $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $file->resumeid . '/resume/' . $file->filename;
            @wp_delete_file($file);
            return true;
        }
        return false;
    }

    function getRssResumes() {
        $resume_rss = wpjobportal::$_config->getConfigurationByConfigName('resume_rss');
        if ($resume_rss == 1) {
            $curdate = date_i18n('Y-m-d H:i:s');
            $query = "SELECT resume.id,resume.application_title,resume.photo,resume.first_name,resume.last_name,
                        resume.email_address,cat.cat_title,resume.gender,
                        CONCAT(resume.alias,'-',resume.id) AS resumealiasid
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON resume.job_category = cat.id
                        ";
            $result = wpjobportal::$_db->get_results($query);
            foreach ($result AS $rs) {
                if(!is_numeric($rs->id)){
                    continue;
                }
                $query = "SELECT filename,filetype,filesize FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` WHERE resumeid = " . esc_sql($rs->id);
                $rs->filename = wpjobportal::$_db->get_results($query);
            }
            return $result;
        }
        var_dump($query);
        //return false;
    }
    function makeResumeSeo($resume_seo , $wpjobportalid){
        if(empty($resume_seo))
            return '';

        $common = WPJOBPORTALincluder::getJSModel('common');
        $id = $common->parseID($wpjobportalid);
        if(! is_numeric($id))
            return '';

        $result = '';
        $resume_seo = wpjobportalphplib::wpJP_str_replace( ' ', '', $resume_seo);
        $resume_seo = wpjobportalphplib::wpJP_str_replace( '[', '', $resume_seo);
        $array = wpjobportalphplib::wpJP_explode(']', $resume_seo);

        $total = count($array);
        if($total > 3)
            $total = 3;

        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'title':
                    $query = "SELECT application_title AS col FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($id);
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category
                        WHERE resume.id = " . esc_sql($id);
                break;
                case 'location':
                    $locationquery = "SELECT ra.address_city AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS ra
                        JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume ON resume.id = ra.resumeid
                        WHERE resume.id = " . esc_sql($id);
                break;
            }

            if($array[$i] == 'location'){
                $rows = wpjobportaldb::get_results($locationquery);
                $location = '';
                foreach ($rows as $row) {
                    if($row->col != '' && is_numeric($row->col)){
                        $query = "SELECT name FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` WHERE id = ". esc_sql($row->col);
                        $cityname = wpjobportaldb::get_row($query);
                        if(isset($cityname->name)){
                            if($location == '')
                                $location .= $cityname->name;
                            else
                                $location .= ' '.$cityname->name;
                        }
                    }
                }
                $location = $common->removeSpecialCharacter($location);
                // if url encoded string is different from the orginal string dont add it to url
                $val = $location;
                $test_val = urlencode($val);
                if($val != $test_val){
                    continue;
                }
                if($location != ""){
                    if($result == '')
                        $result .= wpjobportalphplib::wpJP_str_replace(' ', '-', $location);
                    else{
                        $result .= '-'.wpjobportalphplib::wpJP_str_replace(' ', '-', $location);
                    }
                }
            }else{
                if($query){
                    $data = wpjobportaldb::get_row($query);
                    if(isset($data->col)){
                        $val = $common->removeSpecialCharacter($data->col);
                        // if url encoded string is different from the orginal string dont add it to url
                        $test_val = urlencode($val);
                        if($val != $test_val){
                            continue;
                        }
                        if($result == '')
                            $result .= wpjobportalphplib::wpJP_str_replace(' ', '-', $val);
                        else
                            $result .= '-'.wpjobportalphplib::wpJP_str_replace(' ', '-', $val);
                    }
                }
            }
        }
        if($result != ''){
            $result = wpjobportalphplib::wpJP_str_replace('_', '-', $result);
        }
        return $result;
    }


    function makeResumeSeoDocumentTitle($resume_seo , $wpjobportalid){
        if(empty($resume_seo))
            return '';

        $common = wpjobportal::$_common;
        $id = $common->parseID($wpjobportalid);
        if(! is_numeric($id))
            return '';
        $result = '';

        $application_title = '';
        $category_title = '';
        $jobtype_title = '';
        $resume_location = '';

        $query = "SELECT resume.application_title, category.cat_title AS resumecategory,jobtype.title AS resumejobtype
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                        WHERE resume.id = " . esc_sql($id);


        $data = wpjobportaldb::get_row($query);

        if(!empty($data)){
            $resume_location = '';
            $query = "SELECT city.name AS cityname
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` resumeaddress
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = resumeaddress.address_city
                WHERE resumeaddress.resumeid = " . esc_sql($id);
                $city_data = wpjobportaldb::get_results($query);
            if(!empty($city_data)){
                //  updated the query select to select 'name' as cityname
                foreach ($city_data as $key => $city) {
                    if($resume_location == ''){
                        $resume_location .= $city->cityname;
                    }else{
                        $resume_location .= ', '.$city->cityname;
                    }
                }
            }
            $application_title = $data->application_title;
            $category_title = $data->resumecategory;
            $jobtype_title = $data->resumejobtype;
            $matcharray = array(
                '[applicationtitle]' => $application_title,
                '[jobcategory]' => $category_title,
                '[jobtype]' => $jobtype_title,
                '[location]' => $resume_location,
                '[separator]' => '-',
                '[sitename]' => get_bloginfo( 'name', 'display' )
            );
            $result = $this->replaceMatches($resume_seo,$matcharray);

            //echo var_dump($result);die("3499");

        }

        return $result;
    }


    function replaceMatches($string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = wpjobportalphplib::wpJP_str_replace($find, $replace, $string);
        }
        return $string;
    }


    //getAllRoleLessUsersAjax

   function getMyResumes($uid) {
        if (!is_numeric($uid))
            return false;
        $this->sortingrescat();
        //$this->getOrdering();
        $query = "SELECT COUNT(resume.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category
                WHERE resume.uid =". esc_sql($uid)." AND resume.quick_apply <> 1 ";
        $total = wpjobportaldb::get_var($query);
        if(!in_array('multiresume', wpjobportal::$_active_addons) && $total > 1){
            $total = 1;
        }
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total,'myresume');

        $query = "SELECT resume.id,resume.first_name,resume.last_name,resume.application_title as applicationtitle,CONCAT(resume.alias,'-',resume.id) resumealiasid,resume.email_address,category.cat_title,resume.created,jobtype.title AS jobtypetitle,resume.photo,resume.salaryfixed as salary,
                resume.isfeaturedresume,resume.status,city.name AS cityname,state.name AS statename,country.name AS countryname,resume.id as resumeid,resume.endfeatureddate,resume.params,resume.last_modified,LOWER(jobtype.title) AS jobtypetit,jobtype.color as jobtypecolor,resume.skills
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                WHERE resume.uid = ". esc_sql($uid)." AND resume.quick_apply <> 1 ";
        if(in_array('multiresume', wpjobportal::$_active_addons)){
            $query.= " ORDER BY " . wpjobportal::$_data['sorting'];
            $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        }else{
            $query.=" ORDER BY resume.id ASC LIMIT 0,1 ";
        }
        $results = wpjobportal::$_db->get_results($query);
        $data = array();
        foreach ($results AS $d) {//  updated the query select to select 'name' as cityname
            $d->location = wpjobportal::$_common->getLocationForView($d->cityname, $d->statename, $d->countryname);
            $data[] = $d;
        }
        wpjobportal::$_data['fields'] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsOrderingforView(3);
        wpjobportal::$_data[0] = $data;
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('resume');
        wpjobportal::$_data['listingfields'] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsForListing(3);
        // to handle left menu/ my resume page add resume link case
        $query = "SELECT resume.id as resumeid
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume
                    WHERE `uid`='".esc_sql($uid)."' AND resume.quick_apply <> 1
                    GROUP BY resume.id  ORDER BY resume.id ASC LIMIT 0,1 "; // made the condition same as to remove inconsistency
        wpjobportal::$_data['resumeid'] = wpjobportaldb::get_var($query);
        return;
    }


    function getResumeByCategory() {
        $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid,category.id AS categoryid
            ,(SELECT count(resume.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                where resume.job_category = category.id AND resume.status = 1 AND resume.searchable = 1 AND resume.quick_apply <> 1)  AS totaljobs
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
            WHERE category.isactive = 1 AND category.parentid = 0 ORDER BY category.ordering ASC";
        $categories = wpjobportaldb::get_results($query);
        $config_array = wpjobportal::$_config->getConfigByFor('category');

        $subcategory_limit = 3;
        if($config_array['subcategory_limit'] != ''){ // to handle float value in configuration
            $subcategory_limit = ceil($config_array['subcategory_limit']);
        }

        foreach($categories AS $category){
            $total = 0;
            if(!is_numeric($category->categoryid)){
                continue;
            }
            $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                ,(SELECT count(resume.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                where resume.job_category = category.id AND resume.status = 1 AND resume.searchable = 1 AND resume.quick_apply <> 1)  AS totaljobs
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
                WHERE category.isactive = 1 AND category.parentid = ".esc_sql($category->categoryid)." ORDER BY category.ordering ASC ";
            $subcats = wpjobportal::$_db->get_results($query);
            $i = 0;
            foreach ($subcats as $id => $scat) {
                $total += $scat->totaljobs;
                if($subcategory_limit <= $i){
                    unset($subcats[$id]);
                }
                $i++;
            }
            $category->subcat = $subcats;
            $category->total_sub_jobs = $total;
        }


        if(wpjobportal::$_configuration['job_resume_show_all_categories'] == 0){//conifguration based
            $final_arr = array();
            foreach ($categories as $job_category) {
                if($job_category->totaljobs != 0 || $job_category->total_sub_jobs != 0){
                    $final_arr[] = $job_category;
                }
            }
            $categories = $final_arr;
        }
        wpjobportal::$_data[0] = $categories;
        wpjobportal::$_data['config'] =  wpjobportal::$_config->getConfigByFor('category');
        return;
    }

    //function for resume files in jobapply email
    function getResumeFilesByResumeId($resumeid) { // by resumeid because files are stored in seperate table
        if (!is_numeric($resumeid)) return false;
        $query = "SELECT COUNT(id) FROM `".wpjobportal::$_db->prefix."wj_portal_resumefiles` WHERE resumeid=" . esc_sql($resumeid);

        $filesFound = wpjobportaldb::get_var($query);
        if ($filesFound > 0) {
           $query = "SELECT * FROM `".wpjobportal::$_db->prefix."wj_portal_resumefiles` WHERE resumeid = " . esc_sql($resumeid);

           $files = wpjobportaldb::get_results($query);
           return $files;
        } else {
           return false;
        }
    }

    function getResumeExpiryStatus($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT resume.id
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
        WHERE resume.status = 1 AND resume.id =" . esc_sql($id);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    ///***To Add Only one Resume***///
    function checkAlreadyadd($uid='',$resumeid=''){
        if(wpjobportal::$_common->wpjp_isadmin()){
            return true;
        }else{
            if(!is_numeric($uid))
            return false;
        if(in_array('visitorapplyjob', wpjobportal::$_active_addons) || in_array('multiresume', wpjobportal::$_active_addons)){
            return true;
        }
        $query = "SELECT count(resume.id) as resume
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
        WHERE resume.status = 1 AND resume.uid =" . esc_sql($uid) ." AND resume.status!=-1 AND resume.id!='".esc_sql($resumeid)."'";
        $result = wpjobportal::$_db->get_var($query);
        $count = (int)$result;
        if($count > 0){
            return false;
         }else{
            return true;
            }
        }
    }

    public function checkAlreadyViewResumeContactDetail($resumeid,$data='') {
        if (!is_numeric($resumeid))
            return false;
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest() || !WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPORTALUser()) {
            return false;
        }
        if(WPJOBPORTALincluder::getObjectClass('user')->isemployer()){
            $employerid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            $query = "SELECT count(job.id)
                        FROM `".wpjobportal::$_db->prefix."wj_portal_jobs` AS job
                        JOIN `".wpjobportal::$_db->prefix."wj_portal_jobapply` AS ja ON ja.jobid = job.id
                        WHERE job.uid = ".esc_sql($employerid)." AND ja.cvid = ".esc_sql($resumeid);
            $result = wpjobportal::$_db->get_var($query);
            if($result > 0){
                return true;
            }
        }

        if(current_user_can('manage_options') && !isset($data['uid']) ){
            return true;
        }
        if (isset($_SESSION['wp-wpjobportal']) && isset($_SESSION['wp-wpjobportal']['resumeid'])) {
            if($_SESSION['wp-wpjobportal']['resumeid'] == $resumeid)
                return true;
        }
        if(isset($data['uid'])!=''){
            $uid = $data['uid'];
        }else{
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        }
        if(!is_numeric($uid)){
            return false;
        }
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_employer_view_resume` WHERE resumeid = ".esc_sql($resumeid)." AND uid =". esc_sql($uid);
        $result = wpjobportal::$_db->get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getIfResumeOwner($resumeid) {
        if (!is_numeric($resumeid))
            return false;
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        //extra code
        // if(in_array('multiresume', wpjobportal::$_active_addons)){
        //     $resumeid = $jobid;
        // }else{
        //     $query = "SELECT resume.id
        //             FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
        //             WHERE resume.uid = " . esc_sql($uid)." ORDER by resume.id DESC LIMIT 0,1";
        //     $result = wpjobportal::$_db->get_var($query);
        //     $resumeid = $jobid;
        // }
        // to handle visitor quick apply case
        if(!is_numeric($uid)){
            $uid = 0;
        }
        $query = "SELECT resume.id
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
        WHERE resume.uid = ". esc_sql($uid)."
        AND resume.id =" . esc_sql($resumeid);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getPackagePopupForResumeContactDetail(){

            $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'get-package-popup-for-resume-contact-detail') ) {
                die( 'Security check Failed' );
            }
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            $resumeid = WPJOBPORTALrequest::getVar('wpjobportalid');
            $subtype = wpjobportal::$_config->getConfigValue('submission_type');
            #submit type popup for Featured Resume --Listing(Membership)
            if( $subtype != 3 ){
                return false;
            }
            $userpackages = array();
            $pack = apply_filters('wpjobportal_addons_credit_get_Packages_user',false,$uid,'resumecontactdetail');
            $addonclass = '';
            if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled()){
                $addonclass = ' wjportal-elegant-addon-packages-popup ';
            }
            foreach($pack as $package){
                if($package->resumecontactdetail == -1 || $package->remresumecontactdetail > 0){ //-1 = unlimited
                    $userpackages[] = $package;
                }
            }
            if (wpjobportal::$theme_chk == 1) {
                $content = '
                <div id="wjportal-popup-background" style="display: none;"></div>
                <div id="package-popup" class="wjportal-popup-wrp wjportal-packages-popup">
                    <div class="wjportal-popup-cnt">
                        <img id="wjportal-popup-close-btn" alt="popup cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/popup-close.png">
                        <div class="wjportal-popup-title">
                            '.esc_html(__("Select Package",'wp-job-portal')).'
                            <div class="wjportal-popup-title3">
                                '.esc_html(__("Please select a package first",'wp-job-portal')).'
                            </div>
                        </div>
                        <div class="wjportal-popup-contentarea">
                            <div class="wjportal-packages-wrp">';
                                if(count($userpackages) == 0 || empty($userpackages)){
                                    $content .= WPJOBPORTALmessages::showMessage(esc_html(__("You do not have any View Resume Contact remaining",'wp-job-portal')),'error',1);
                                } else {
                                    foreach($userpackages as $package){
                                        #User Package For Selection in Popup Model --Views
                                        $content .= '
                                            <div class="wjportal-pkg-item" id="package-div-'.esc_attr($package->id).'" onclick="selectPackage('.esc_attr($package->id).');">
                                                <div class="wjportal-pkg-item-top">
                                                    <div class="wjportal-pkg-item-title">
                                                        '.esc_html($package->title).'
                                                    </div>
                                                </div>
                                                <div class="wjportal-pkg-item-btm">
                                                    <div class="wjportal-pkg-item-row">
                                                        <span class="wjportal-pkg-item-tit">
                                                            '.esc_html(__("View Contact Resume",'wp-job-portal')).' :
                                                        </span>
                                                        <span class="wjportal-pkg-item-val">
                                                            '.($package->resumecontactdetail==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->resumecontactdetail)).'
                                                        </span>
                                                    </div>
                                                    <div class="wjportal-pkg-item-row">
                                                        <span class="wjportal-pkg-item-tit">
                                                            '.esc_html(__("Remaining",'wp-job-portal')).' :
                                                        </span>
                                                        <span class="wjportal-pkg-item-val">
                                                            '.($package->resumecontactdetail==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->remresumecontactdetail)).'
                                                        </span>
                                                    </div>
                                                    <div class="wjportal-pkg-item-btn-row">
                                                    <a href="#" class="wjportal-pkg-item-btn">
                                                        '.esc_html(__("Select Package",'wp-job-portal')).'
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                                /*$content .= '<div class="wjportal-pkg-help-txt">
                                                '.esc_html(__("Click on package to select one",'wp-job-portal')).'
                                            </div>';*/
                            }
                            $content .= '</div>
                            <div class="wjportal-popup-msgs" id="wjportal-package-message"> </div>
                        </div>
                        <div class="wjportal-visitor-msg-btn-wrp">
                            <form action="'.esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume','action'=>'wpjobportaltask','task'=>'addviewresumedetail','wpjobportalid'=>$resumeid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_resume_nonce')).'" method="post">
                                <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">
                                <input type="submit" rel="button" id="jsre_featured_button" class="wjportal-visitor-msg-btn" value="'.esc_html(__('Show Company Contact','wp-job-portal')).'" disabled/>
                            </form>
                        </div>
                    </div>
                </div>';
            } else {
           $content = '
            <div id="wjportal-popup-background" style="display: none;"></div>
            <div id="package-popup" class="wjportal-popup-wrp wjportal-packages-popup '.$addonclass.'">
                <div class="wjportal-popup-cnt">
                    <img id="wjportal-popup-close-btn" alt="popup cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/popup-close.png">
                    <div class="wjportal-popup-title">
                        '.esc_html(__("Select Package",'wp-job-portal')).'
                        <div class="wjportal-popup-title3">
                            '.esc_html(__("Please select a package first",'wp-job-portal')).'
                        </div>
                    </div>
                    <div class="wjportal-popup-contentarea">
                        <div class="wjportal-packages-wrp">';
                            if(count($userpackages) == 0 || empty($userpackages)){
                                $content .= WPJOBPORTALmessages::showMessage(esc_html(__("You do not have any View Resume Contact remaining",'wp-job-portal')),'error',1);
                            } else {
                                foreach($userpackages as $package){
                                    #User Package For Selection in Popup Model --Views
                                    $content .= '
                                        <div class="wjportal-pkg-item" id="package-div-'.esc_attr($package->id).'" onclick="selectPackage('.esc_attr($package->id).');">
                                            <div class="wjportal-pkg-item-top">
                                                <div class="wjportal-pkg-item-title">
                                                    '.esc_html($package->title).'
                                                </div>
                                            </div>
                                            <div class="wjportal-pkg-item-btm">
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.esc_html(__("View Contact Resume",'wp-job-portal')).' :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->resumecontactdetail==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->resumecontactdetail)).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.esc_html(__("Remaining",'wp-job-portal')).' :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->resumecontactdetail==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->remresumecontactdetail)).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-btn-row">
                                                    <a href="#" class="wjportal-pkg-item-btn">
                                                        '.esc_html(__("Select Package",'wp-job-portal')).'
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                                /*$content .= '<div class="wjportal-pkg-help-txt">
                                                '.esc_html(__("Click on package to select one",'wp-job-portal')).'
                                            </div>';*/
                            }
                        $content .= '</div>
                        <div class="wjportal-popup-msgs" id="wjportal-package-message">&nbsp;</div>
                    </div>
                    <div class="wjportal-visitor-msg-btn-wrp">
                        <form action="'.esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume','action'=>'wpjobportaltask','task'=>'addviewresumedetail','wpjobportalid'=>$resumeid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_resume_nonce')).'" method="post">
                            <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">
                            <input type="submit" rel="button" id="jsre_featured_button" class="wjportal-visitor-msg-btn" value="'.esc_html(__('Show Resume Contact','wp-job-portal')).'" disabled/>
                        </form>
                    </div>
                </div>
            </div>';
            }

            echo wp_kses($content, WPJOBPORTAL_ALLOWED_TAGS);
            exit();
    }

    function UserCanAddResume($uid){
        if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
            return true;
        }
        # Check Whether Not More than one
        if(!is_numeric($uid)){
            return false;
        }
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE uid =". esc_sql($uid);
        $resume = wpjobportaldb::get_var($query);
        if($resume > 0){
            return false;
        }
        return true;
    }

    function getMyResumeSearchFormData($layout){
        $jsjp_search_array = array();
        if($layout == 'myresume'){
            $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton', 'post', 6);
            $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby', 'post', 2);
        }elseif($layout == 'resumes'){
            $customfields = wpjobportal::$_wpjpcustomfield->userFieldsData(3);
            $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton', 'post', 6);
            $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby', 'post', 2);
            $jsjp_search_array['application_title'] = WPJOBPORTALrequest::getVar('application_title');
            $jsjp_search_array['first_name'] = WPJOBPORTALrequest::getVar('first_name');
            $jsjp_search_array['middle_name'] = WPJOBPORTALrequest::getVar('middle_name');
            $jsjp_search_array['last_name'] = WPJOBPORTALrequest::getVar('last_name');
            $jsjp_search_array['nationality'] = WPJOBPORTALrequest::getVar('nationality');
            $jsjp_search_array['gender'] = WPJOBPORTALrequest::getVar('gender');
            $jsjp_search_array['salaryfixed'] = WPJOBPORTALrequest::getVar('salaryfixed');
            $jsjp_search_array['jobtype'] = WPJOBPORTALrequest::getVar('jobtype');
            $jsjp_search_array['salaryrangetype'] = WPJOBPORTALrequest::getVar('salaryrangetype');
            $jsjp_search_array['zipcode'] = WPJOBPORTALrequest::getVar('zipcode');
            $jsjp_search_array['keywords'] = WPJOBPORTALrequest::getVar('keywords');
            $jsjp_search_array['city'] = WPJOBPORTALrequest::getVar('city');
            $jsjp_search_array['airesumesearcch'] = WPJOBPORTALrequest::getVar('airesumesearcch');
            // if(WPJOBPORTALrequest::getVar('resume_filter')){
                $resume_filter = wpjobportalphplib::wpJP_safe_decoding(WPJOBPORTALrequest::getVar('resume_filter'));
                if($resume_filter !=''){
                    $resume_filter = json_decode($resume_filter, true );
                }
                if(isset($resume_filter['category'])){
                    $jsjp_search_array['category'] = $resume_filter['category'];
                }else{
                    $jsjp_search_array['category'] = WPJOBPORTALrequest::getVar('category');
                }
            // }
            if (!empty($customfields)) {
                foreach ($customfields as $uf) {
                    $jsjp_search_array['resume_custom_fields'][$uf->field] = WPJOBPORTALrequest::getVar($uf->field, 'post');
                }
            }
        }
        $jsjp_search_array['search_from_resumes'] = 1;
        return $jsjp_search_array;
    }

    function getAdminResumeSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchtitle'] = WPJOBPORTALrequest::getVar('searchtitle');
        $jsjp_search_array['searchname'] = WPJOBPORTALrequest::getVar('searchname');
        $jsjp_search_array['searchjobcategory'] = WPJOBPORTALrequest::getVar('searchjobcategory');
        $jsjp_search_array['searchjobtype'] = WPJOBPORTALrequest::getVar('searchjobtype');
        $jsjp_search_array['searchjobsalaryrange'] = WPJOBPORTALrequest::getVar('searchjobsalaryrange');
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status');
        $jsjp_search_array['datestart'] = WPJOBPORTALrequest::getVar('datestart');
        $jsjp_search_array['dateend'] = WPJOBPORTALrequest::getVar('dateend');
        $jsjp_search_array['featured'] = WPJOBPORTALrequest::getVar('featured');
        $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton', 'post', 6);
        $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby', 'post', 2);
        $jsjp_search_array['search_from_resumes'] = 1;
        return $jsjp_search_array;
    }

    function getResumeSavedCookiesData($layout){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_resumes']) && $wpjp_search_cookie_data['search_from_resumes'] == 1){
            if(wpjobportal::$_common->wpjp_isadmin()){
                $jsjp_search_array['searchtitle'] = $wpjp_search_cookie_data['searchtitle'];
                $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
                $jsjp_search_array['searchjobcategory'] = $wpjp_search_cookie_data['searchjobcategory'];
                $jsjp_search_array['searchjobtype'] = $wpjp_search_cookie_data['searchjobtype'];
                $jsjp_search_array['searchjobsalaryrange'] = $wpjp_search_cookie_data['searchjobsalaryrange'];
                $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
                $jsjp_search_array['datestart'] = $wpjp_search_cookie_data['datestart'];
                $jsjp_search_array['dateend'] = $wpjp_search_cookie_data['dateend'];
                $jsjp_search_array['featured'] = $wpjp_search_cookie_data['featured'];
                $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
                $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
            }else{
                if($layout == 'myresume'){
                    $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
                    $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
                }elseif($layout == 'resumes'){
                    $customfields = wpjobportal::$_wpjpcustomfield->userFieldsData(3);
                    $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
                    $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
                    $jsjp_search_array['application_title'] = $wpjp_search_cookie_data['application_title'];
                    $jsjp_search_array['first_name'] = $wpjp_search_cookie_data['first_name'];
                    $jsjp_search_array['middle_name'] = $wpjp_search_cookie_data['middle_name'];
                    $jsjp_search_array['last_name'] = $wpjp_search_cookie_data['last_name'];
                    $jsjp_search_array['nationality'] = $wpjp_search_cookie_data['nationality'];
                    $jsjp_search_array['gender'] = $wpjp_search_cookie_data['gender'];
                    $jsjp_search_array['salaryfixed'] = $wpjp_search_cookie_data['salaryfixed'];
                    $jsjp_search_array['jobtype'] = $wpjp_search_cookie_data['jobtype'];
                    $jsjp_search_array['salaryrangetype'] = $wpjp_search_cookie_data['salaryrangetype'];
                    $jsjp_search_array['category'] = $wpjp_search_cookie_data['category'];
                    $jsjp_search_array['zipcode'] = $wpjp_search_cookie_data['zipcode'];
                    $jsjp_search_array['keywords'] = $wpjp_search_cookie_data['keywords'];
                    $jsjp_search_array['city'] = $wpjp_search_cookie_data['city'];
                    $jsjp_search_array['airesumesearcch'] = $wpjp_search_cookie_data['airesumesearcch'];
                    if (!empty($customfields)) {
                        foreach ($customfields as $uf) {
                            $jsjp_search_array['resume_custom_fields'][$uf->field] = $wpjp_search_cookie_data['resume_custom_fields'][$uf->field];
                        }
                    }
                }
            }
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForMyResume($jsjp_search_array,$layout){
        wpjobportal::$_search['myresume']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : null;
        wpjobportal::$_search['myresume']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : null;
        if($layout == 'resumes'){
            $customfields = wpjobportal::$_wpjpcustomfield->userFieldsData(3);
            wpjobportal::$_search['resumes']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : 6;
            wpjobportal::$_search['resumes']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
            wpjobportal::$_search['resumes']['application_title'] = isset($jsjp_search_array['application_title']) ? $jsjp_search_array['application_title'] : null;
            wpjobportal::$_search['resumes']['first_name'] = isset($jsjp_search_array['first_name']) ? $jsjp_search_array['first_name'] : null;
            wpjobportal::$_search['resumes']['middle_name'] = isset($jsjp_search_array['middle_name']) ? $jsjp_search_array['middle_name'] : null;
            wpjobportal::$_search['resumes']['last_name'] = isset($jsjp_search_array['last_name']) ? $jsjp_search_array['last_name'] : null;
            wpjobportal::$_search['resumes']['nationality'] = isset($jsjp_search_array['nationality']) ? $jsjp_search_array['nationality'] : null;
            wpjobportal::$_search['resumes']['gender'] = isset($jsjp_search_array['gender']) ? $jsjp_search_array['gender'] : null;
            wpjobportal::$_search['resumes']['salaryfixed'] = isset($jsjp_search_array['salaryfixed']) ? $jsjp_search_array['salaryfixed'] : null;
            wpjobportal::$_search['resumes']['jobtype'] = isset($jsjp_search_array['jobtype']) ? $jsjp_search_array['jobtype'] : null;
            wpjobportal::$_search['resumes']['salaryrangetype'] = isset($jsjp_search_array['salaryrangetype']) ? $jsjp_search_array['salaryrangetype'] : null;
            wpjobportal::$_search['resumes']['category'] = isset($jsjp_search_array['category']) ? $jsjp_search_array['category'] : null;
            wpjobportal::$_search['resumes']['zipcode'] = isset($jsjp_search_array['zipcode']) ? $jsjp_search_array['zipcode'] : null;
            wpjobportal::$_search['resumes']['keywords'] = isset($jsjp_search_array['keywords']) ? $jsjp_search_array['keywords'] : null;
            wpjobportal::$_search['resumes']['city'] = isset($jsjp_search_array['city']) ? $jsjp_search_array['city'] : null;
            wpjobportal::$_search['resumes']['airesumesearcch'] = isset($jsjp_search_array['airesumesearcch']) ? $jsjp_search_array['airesumesearcch'] : null;
            if (!empty($customfields)) {
                foreach ($customfields as $uf) {
                    wpjobportal::$_search['resume_custom_fields'][$uf->field] = isset($jsjp_search_array['resume_custom_fields'][$uf->field]) ? $jsjp_search_array['resume_custom_fields'][$uf->field] : '';
                }
            }
        }
    }

    function setSearchVariableForAdminResume($jsjp_search_array){
        wpjobportal::$_search['resumes']['searchtitle']  = isset($jsjp_search_array['searchtitle']) ? $jsjp_search_array['searchtitle'] : null;
        wpjobportal::$_search['resumes']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : null;
        wpjobportal::$_search['resumes']['searchjobcategory'] = isset($jsjp_search_array['searchjobcategory']) ? $jsjp_search_array['searchjobcategory'] : null;
        wpjobportal::$_search['resumes']['searchjobtype'] = isset($jsjp_search_array['searchjobtype']) ? $jsjp_search_array['searchjobtype'] : null;
        wpjobportal::$_search['resumes']['searchjobsalaryrange'] = isset($jsjp_search_array['searchjobsalaryrange']) ? $jsjp_search_array['searchjobsalaryrange'] : null;
        wpjobportal::$_search['resumes']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
        wpjobportal::$_search['resumes']['datestart'] = isset($jsjp_search_array['datestart']) ? $jsjp_search_array['datestart'] : null;
        wpjobportal::$_search['resumes']['dateend'] = isset($jsjp_search_array['dateend']) ? $jsjp_search_array['dateend'] : null;
        wpjobportal::$_search['resumes']['featured'] = isset($jsjp_search_array['featured']) ? $jsjp_search_array['featured'] : null;
        wpjobportal::$_search['resumes']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : 6;
        wpjobportal::$_search['resumes']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
    }

    function deleteResumeLogo($resumeid = 0){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-resume-logo') ) {
            die( 'Security check Failed' );
        }
        if($resumeid == 0){
            $resumeid = WPJOBPORTALrequest::getVar('resumeid');
        }
        if(!is_numeric($resumeid)){
            return false;
        }
        $row = WPJOBPORTALincluder::getJSTable('resume');
        $data_directory = wpjobportal::$_config->getConfigValue('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resumeid . '/photo';
        $files = glob($path . '/*.*');
        array_map('wp_delete_file', $files);    // delete all file in the direcoty
        $query = "UPDATE `".wpjobportal::$_db->prefix."wj_portal_resume` SET photo = '' WHERE id = ".esc_sql($resumeid);
        wpjobportal::$_db->query($query);
        return true;
    }

    function deleteResumeLogoModel($resumeid = 0){

        if($resumeid == 0){
            $resumeid = WPJOBPORTALrequest::getVar('resumeid');
        }
        if(!is_numeric($resumeid)){
            return false;
        }
        $row = WPJOBPORTALincluder::getJSTable('resume');
        $data_directory = wpjobportal::$_config->getConfigValue('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resumeid . '/photo';
        $files = glob($path . '/*.*');
        array_map('wp_delete_file', $files);    // delete all file in the direcoty
        $query = "UPDATE `".wpjobportal::$_db->prefix."wj_portal_resume` SET photo = '' WHERE id = ".esc_sql($resumeid);
        wpjobportal::$_db->query($query);
        return true;
    }

    function getMessagekey(){
        $key = 'resume';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function getResumesForPageBuilderWidget($no_of_resumes){

        $query = "SELECT resume.id,CONCAT(resume.alias,'-',resume.id) AS resumealiasid ,resume.first_name
            ,resume.last_name,resume.application_title as applicationtitle,resume.email_address,category.cat_title
            ,resume.created,jobtype.title AS jobtypetitle,resume.photo,
            resume.isfeaturedresume,resume.endfeatureddate
            ,resume.status,city.name AS cityname
            ,state.name AS statename,resume.params,resume.salaryfixed as salary
            ,resume.last_modified,LOWER(jobtype.title) AS jobtypetit,jobtype.color as jobtypecolor,country.name AS countryname,resume.id as resumeid
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category ";
        $query .= " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS address1 ON address1.resumeid = resume.id ";
        $query .= "
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
            WHERE resume.status = 1  ";
        $query.= " ORDER BY created DESC ";
        //$query.=" LIMIT 4 " ;
        if(is_numeric($no_of_resumes)){
            $query.=" LIMIT " . esc_sql($no_of_resumes);
        }

        $results = wpjobportaldb::get_results($query);

        $data = array();
        foreach ($results AS $d) {
            //  updated the query select to select 'name' as cityname
            $d->location = WPJOBPORTALincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
            $data[] = $d;
        }
        return $data;
    }

    ///***Check if Quick Apply resume***///
    function checkQuickApply($resumeid){
        if(!is_numeric($resumeid))
            return false;

        $query = "SELECT count(resume.id) as resume_count
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
        WHERE resume.quick_apply = 1 AND resume.id = ".esc_sql($resumeid);
        $result = wpjobportal::$_db->get_var($query);
        $count = (int)$result;
        if($count > 0){
            return true;
         }else{
            return false;
        }
    }

    function processShortcodeAttributesResume(){
        $inquery = '';

        // cities
        $cities_list = WPJOBPORTALrequest::getVar('locations', 'shortcode_option', false);
        if ($cities_list && $cities_list !='' ) { // not empty check
            $city_array = wpjobportalphplib::wpJP_explode( ',' , $cities_list); // handle multi case
            $cityQuery = false;
            foreach($city_array as $city_id){ // loop over all ids
                if($city_id != ''){ // null check
                    $city_id = trim($city_id);
                }
                if(!is_numeric($city_id)){ // numric check
                    continue;
                }
                if($cityQuery){
                    $cityQuery .= " OR address1.address_city =  ".esc_sql($city_id);
                }else{
                    $cityQuery = " address1.address_city =  ".esc_sql($city_id);
                }
            }
            if($cityQuery){
                $inquery .= " AND ( $cityQuery ) ";
            }
        }

        // tags
        $tags_list = WPJOBPORTALrequest::getVar('tags', 'shortcode_option', false);
        if ($tags_list && $tags_list !='' ) { // not empty check
            $tag_array = wpjobportalphplib::wpJP_explode( ',' , $tags_list); // handle multi case
            $tagQuery = false;
            foreach($tag_array as $tag_id){ // loop over all ids
                if($tag_id != ''){ // null check
                    $tag_id = trim($tag_id);
                }
                if(!is_numeric($tag_id)){ // numric check
                    continue;
                }
                if($tagQuery){
                    $tagQuery .= " OR FIND_IN_SET('" . esc_sql($tag_id) . "', resume.tags) > 0 ";
                }else{
                    $tagQuery = " FIND_IN_SET('" . esc_sql($tag_id) . "', resume.tags) > 0 ";
                }
            }
            if($tagQuery){
                $inquery .= " AND ( $tagQuery ) ";
            }
        }

        // categories
        $category_list = WPJOBPORTALrequest::getVar('categories', 'shortcode_option', false);
        if ($category_list && $category_list !='' ) { // not empty check
            $category_array = wpjobportalphplib::wpJP_explode( ',' , $category_list); // handle multi case
            $categoryQuery = false;
            $wpjp_ids = array();
            foreach($category_array as $category_id){  // loop over all ids
                if($category_id != ''){ // null check
                    $category_id = trim($category_id);
                }
                if(!is_numeric($category_id)){ // numric check
                    continue;
                }
                // handle case of child categories of current category
                $wpjp_query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` WHERE parentid = ". esc_sql($category_id);
                $wpjp_cats = wpjobportaldb::get_results($wpjp_query);

                foreach ($wpjp_cats as $wpjp_cat) {
                    $wpjp_ids[] = $wpjp_cat->id;
                }
                $wpjp_ids[] = $category_id;

            }
            $wpjp_ids = implode(",",$wpjp_ids);
            $inquery .= " AND resume.job_category IN(".$wpjp_ids.")";
        }


        // types
        $jobtype_list = WPJOBPORTALrequest::getVar('types', 'shortcode_option', false);
        if ($jobtype_list && $jobtype_list !='' ) { // not empty check
            $jobtype_array = wpjobportalphplib::wpJP_explode( ',' , $jobtype_list); // handle multi case
            $jobtypeQuery = false;
            foreach($jobtype_array as $jobtype_id){  // loop over all ids
                if($jobtype_id != ''){ // null check
                    $jobtype_id = trim($jobtype_id);
                }
                if(!is_numeric($jobtype_id)){ // numric check
                    continue;
                }
                if($jobtypeQuery){
                    $jobtypeQuery .= " OR resume.jobtype  = " . esc_sql($jobtype_id);
                }else{
                    $jobtypeQuery = " resume.jobtype  =  " . esc_sql($jobtype_id);
                }
            }
            if($jobtypeQuery){
                $inquery .= " AND ( $jobtypeQuery ) ";
            }
        }

        // resume ids
        $resume_list = WPJOBPORTALrequest::getVar('ids', 'shortcode_option', false);
        if ($resume_list && $resume_list !='' ) { // not empty check
            $resume_array = wpjobportalphplib::wpJP_explode( ',' , $resume_list); // handle multi case
            $resumeQuery = false;
            foreach($resume_array as $resume_id){  // loop over all ids
                if($resume_id != ''){ // null check
                    $resume_id = trim($resume_id);
                }
                if(!is_numeric($resume_id)){ // numric check
                    continue;
                }
                if($resumeQuery){
                    $resumeQuery .= " OR resume.id  = " . esc_sql($resume_id);
                }else{
                    $resumeQuery = " resume.id  =  " . esc_sql($resume_id);
                }
            }
            if($resumeQuery){
                $inquery .= " AND ( $resumeQuery ) ";
            }
        }


        //handle attirbute for ordering
        $sorting = WPJOBPORTALrequest::getVar('sorting', 'shortcode_option', false);
        if($sorting && $sorting != ''){
            $this->makeOrderingQueryFromShortcodeAttributesResume($sorting);
        }

        //handle attirbute for no of jobs
        $no_of_resumes = WPJOBPORTALrequest::getVar('no_of_resumes', 'shortcode_option', false);
        if($no_of_resumes && $no_of_resumes != ''){
            wpjobportal::$_data['shortcode_option_no_of_resumes'] = (int) $no_of_resumes;
        }


        // handle visibilty of data based on shortcode
        $this->handleDataVisibilityByShortcodeAttributesResume();
        return $inquery;
    }


    function makeOrderingQueryFromShortcodeAttributesResume($sorting) {
        switch ($sorting) {
            //name
            case "name_desc":
                wpjobportal::$_data['sorting'] = " resume.first_name DESC ";
                break;
            case "name_asc":
                wpjobportal::$_data['sorting'] = " resume.first_name ASC ";
                break;
            //posted
            case "posted_desc":
                wpjobportal::$_data['sorting'] = " resume.created DESC ";
                break;
            case "posted_asc":
                wpjobportal::$_data['sorting'] = " resume.created ASC ";
                break;
            // category
            case 'category_asc':
                wpjobportal::$_data['sorting'] = ' category.cat_title ASC ';
                break;
            case 'category_desc':
                wpjobportal::$_data['sorting'] = ' category.cat_title DESC ';
                break;
            // jobtype
            case 'jobtype_asc':
                wpjobportal::$_data['sorting'] = ' jobtype.title ASC ';
                break;
            case 'jobtype_desc':
                wpjobportal::$_data['sorting'] = ' jobtype.title DESC ';
                break;
        }
        return;
    }

    function handleDataVisibilityByShortcodeAttributesResume() {

        //handle attirbute for hide company logo on job listing
        $hide_resume_photo = WPJOBPORTALrequest::getVar('hide_resume_photo', 'shortcode_option', false);
        if($hide_resume_photo && $hide_resume_photo != ''){
            wpjobportal::$_data['shortcode_option_hide_resume_photo'] = 1;
        }

        //handle attirbute for hide company name on job listing
        $hide_resume_location = WPJOBPORTALrequest::getVar('hide_resume_location', 'shortcode_option', false);
        if($hide_resume_location && $hide_resume_location != ''){
            wpjobportal::$_data['shortcode_option_hide_resume_location'] = 1;
        }

        //handle attirbute for hide company name on job listing
        $hide_resume_salary = WPJOBPORTALrequest::getVar('hide_resume_salary', 'shortcode_option', false);
        if($hide_resume_salary && $hide_resume_salary != ''){
            wpjobportal::$_data['shortcode_option_hide_resume_salary'] = 1;
        }
    }

    function getResumesForJobapply(){
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $resume_list = array();
        if( is_numeric($uid) && $uid > 0 ){
            $query = "SELECT id,application_title,first_name,last_name FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE status = 1 AND quick_apply <> 1 AND uid = ". esc_sql($uid);
            // code to handle unpublished application_title
            $resume_data = wpjobportal::$_db->get_results($query);
            $resume_list = array();
            foreach ($resume_data as $single_resume) {
                $resume_record = new stdClass();
                $resume_record->id = $single_resume->id;
                if($single_resume->application_title != ''){
                    $resume_record->text = $single_resume->application_title;
                }else{
                    $resume_record->text = $single_resume->first_name.' '.$single_resume->last_name;
                }
                $resume_list[] = $resume_record;
            }
        }
        return $resume_list;
    }

    function getResumesByResumeIds($resume_id_list){
        if($resume_id_list == ''){
            return false;
        }
       //Data
        $query = "SELECT resume.id,CONCAT(resume.alias,'-',resume.id) AS resumealiasid ,resume.first_name
                ,resume.last_name,resume.application_title as applicationtitle,resume.email_address,category.cat_title
                ,resume.created,jobtype.title AS jobtypetitle,resume.photo,
                resume.isfeaturedresume,resume.endfeatureddate
                ,resume.status,city.name AS cityname
                ,state.name AS statename,resume.params,resume.salaryfixed as salary
                ,resume.last_modified,LOWER(jobtype.title) AS jobtypetit,jobtype.color as jobtypecolor,country.name AS countryname,resume.id as resumeid,resume.skills
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category ";
            $query .= "
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid ";
                $query .= " WHERE resume.status = 1 AND resume.quick_apply <> 1";
                $query .= " AND resume.id IN (".esc_sql($resume_id_list).")";
        $query .= " GROUP BY resume.id ";

        $results = wpjobportal::$_db->get_results($query);
        $data = array();
        foreach ($results AS $d) {
            //  updated the query select to select 'name' as cityname
            $d->location = WPJOBPORTALincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
            $data[] = $d;
        }
        return $data;
    }


    function updateRecordsForAISearchResume(){

        $query = "SELECT resume.*
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume ";
                // not sure about this
                $query .= " WHERE resume.quick_apply <> 1";
                $query .= " ORDER BY resume.id ASC ";

        $results = wpjobportal::$_db->get_results($query);

        foreach ($results AS $resume) {
            $resume_data = json_decode(json_encode($resume),true); // main record is in personal section (mostly)
            $this->importAIStringDataForResume($resume_data);
        }
        return;
    }


    function prepareAIStringDataForResume($data){
        if(empty($data)){
            return;
        }
        if(!is_numeric($data['id'])){
            return;
        }

        $personal_section = $data['sec_1'];

        $resume_ai_string_main = '';

        if (!empty($personal_section['application_title'])) {
            $resume_ai_string_main .= wpjobportalphplib::wpJP_trim($personal_section['application_title']) . ' ';
        }


        // Job Category
        if (!empty($personal_section['job_category']) && is_numeric($personal_section['job_category'])) {
            $cat_id = $personal_section['job_category'];
            $cat_title = WPJOBPORTALincluder::getJSModel('category')->getTitleByCategory($cat_id);
            if ($cat_title) {
                $resume_ai_string_main .= $cat_title . ' ';
            }
        }

        // Job Type
        if(!isset(wpjobportal::$_data['ai']['resume']['jobtypes'])){
            wpjobportal::$_data['ai']['resume']['jobtypes'] = array();
        }
        if (!empty($personal_section['jobtype']) && is_numeric($personal_section['jobtype'])) {
            $type_id = $personal_section['jobtype'];
            $jobtype_title = WPJOBPORTALincluder::getJSModel('jobtype')->getTitleByid($type_id);

            if ($jobtype_title) {
                $resume_ai_string_main .= $jobtype_title . ' ';
            }
        }

        // Nationality (Country)
        if(!isset(wpjobportal::$_data['ai']['resume']['countries'])){
            wpjobportal::$_data['ai']['resume']['countries'] = array();
        }
        if (!empty($personal_section['nationality']) && is_numeric($personal_section['nationality'])) {
            $country_id = $personal_section['nationality'];
            $cuntry_name = WPJOBPORTALincluder::getJSModel('country')->getCountryName($country_id);
            if ($cuntry_name) {
                $resume_ai_string_main .= $cuntry_name . ' ';
            }
        }

        if (!empty($personal_section['gender']) && is_numeric($personal_section['gender'])) {
            $gender_title = '';
            if($personal_section['gender'] == 1){
                $gender_title = esc_html(__('Male', 'wp-job-portal'));
            }elseif($personal_section['gender'] == 2){
                $gender_title = esc_html(__('Female', 'wp-job-portal'));
            }
            if($gender_title != ''){
                $resume_ai_string_main .= $gender_title . ' ';
            }
        }

        if (!empty($personal_section['salaryfixed'])) {
            $resume_ai_string_main .= wpjobportalphplib::wpJP_trim($personal_section['salaryfixed']) . ' ';
        }

        if (!empty($personal_section['tags'])) {
            $resume_ai_string_main .= wpjobportalphplib::wpJP_trim($personal_section['tags']) . ' ';
        }

        // handle custom fields

        $custom_fields = WPJOBPORTALincluder::getJSModel('customfield')->getUserfieldsfor(3,1);// 3 fieldfor 1 section;
        // ignore these field types from current case
        $skip_types = ['file', 'email', 'textarea'];

        $text_area_field_values = '';

        foreach ($custom_fields as $single_field) {
            if(!in_array($single_field->userfieldtype, $skip_types)){ // check if type agaisnt array
                if (!empty($personal_section[$single_field->field])) { // check value exsists
                    if(is_array($personal_section[$single_field->field])){ // to handle multi select and check box case
                        $resume_ai_string_main .= implode(',', $personal_section[$single_field->field]) . ' ';
                    }else{
                        $resume_ai_string_main .= $personal_section[$single_field->field] . ' ';
                    }
                }
            }elseif ($single_field->userfieldtype == 'textarea'){ // text area value to be included in description column
                if (!empty($personal_section[$single_field->field])) { // check value exsists
                    $text_area_field_values .= $personal_section[$single_field->field] . ' ';
                }
            }
        }

        $resume_ai_string_main = trim($resume_ai_string_main); // Clean trailing space

        // SEOND LEVEL
        $resume_ai_string_desc = $resume_ai_string_main;
        $resume_ai_string_desc .= $text_area_field_values; // append personal section text area here

        foreach ($data as $section => $fields) {
            if ($section == 'sec_1' ||  $section == 'sec_6' ||  $section == 'sec_7') {
                continue; // avoid un expected cases & Skip personal, resume and refrence section
            }

            if(!is_array($fields) ){ // skip fields that are are not sections
                continue;
            }
            $num_entries = count(reset($fields)); // to handle multiple section instances of same section
            for ($i = 0; $i < $num_entries; $i++) {
                if (!empty($fields['deletethis'][$i]) && $fields['deletethis'][$i] == 1) { // ignore delete this indexes
                    continue;
                }

                $record_string = "";
                foreach ($fields as $key => $values) {
                    $value = $values[$i] ?? '';

                    // Collect address_city values separately
                    if ($key === 'address_city' && !empty($value)) { // handle cityids to get complete location name
                        $location_string = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($value);
                        if($location_string){ // the above function may return false
                            $value = $location_string;
                            $resume_ai_string_main .= $location_string." ";
                        }
                    }
                    if ($key === 'deletethis') { // ignore delete this case
                        continue;
                    }
                     // If value is a nested array, flatten it
                    if (is_array($value)) {
                        $value = implode(",", array_filter($value));
                    }
                    if (!empty($value)) {
                        $record_string .= $value." ";
                    }
                }
                $resume_ai_string_desc .= $record_string. " "; //add current section's current instance value to main string
            }
        }

        // handle custom sections

        $resumesections = WPJOBPORTALincluder::getJSModel('fieldordering')->getResumeSections();
        foreach ($resumesections as $section) {
            if($section->section <= 8){ // avoid the default resume sections
                continue;
            }
            // get fields for section
            $custom_fields = WPJOBPORTALincluder::getJSModel('customfield')->getUserfieldsfor(3,$section->section);// 3 fieldfor $section->section for section
            // ignore these field types from current case
            $skip_types = ['file', 'email'];

            foreach ($custom_fields as $single_field) {
                if(!in_array($single_field->userfieldtype, $skip_types)){ // check if type agaisnt array
                    if (!empty($data[$single_field->field])) { // check value exsists
                        if(is_array($data[$single_field->field])){ // to handle multi select and check box case
                            $resume_ai_string_desc .= implode(',', $data[$single_field->field]) . ' ';
                        }else{
                            $resume_ai_string_desc .= $data[$single_field->field] . ' ';
                        }
                    }
                }
            }
        }

        $row = WPJOBPORTALincluder::getJSTable('resume');
        if ($row->update(array('id'=>$data['id'], 'airesumesearchtext' => $resume_ai_string_main, 'airesumesearchdescription' => $resume_ai_string_desc))) {
            return;
        }
        return;
    }

    function importAIStringDataForResume($personal_section){
        if(empty($personal_section)){
            return;
        }
        if(!is_numeric($personal_section['id'])){
            return;
        }

        $resume_ai_string_main = '';

        if (!empty($personal_section['application_title'])) {
            $resume_ai_string_main .= wpjobportalphplib::wpJP_trim($personal_section['application_title']) . ' ';
        }

        // Initialize ai temp caches if not already exsist

        if(!isset(wpjobportal::$_data['ai'])){
            wpjobportal::$_data['ai'] = array();
        }
        // second level set to resume to avoid conflit with job records
        if(!isset(wpjobportal::$_data['ai']['resume'])){
            wpjobportal::$_data['ai']['resume'] = array();
        }

        if(!isset(wpjobportal::$_data['ai']['resume']['categories'])){
            wpjobportal::$_data['ai']['resume']['categories'] = array();
        }
        // Job Category
        if (!empty($personal_section['job_category']) && is_numeric($personal_section['job_category'])) {
            $cat_id = $personal_section['job_category'];
            if (!isset(wpjobportal::$_data['ai']['resume']['categories'][$cat_id])) {
                $cat_title = WPJOBPORTALincluder::getJSModel('category')->getTitleByCategory($cat_id);
                wpjobportal::$_data['ai']['resume']['categories'][$cat_id] = $cat_title;
            } else {
                $cat_title = wpjobportal::$_data['ai']['resume']['categories'][$cat_id];
            }

            if ($cat_title) {
                $resume_ai_string_main .= $cat_title . ' ';
            }
        }

        // Job Type
        if(!isset(wpjobportal::$_data['ai']['resume']['jobtypes'])){
            wpjobportal::$_data['ai']['resume']['jobtypes'] = array();
        }
        if (!empty($personal_section['jobtype']) && is_numeric($personal_section['jobtype'])) {
            $type_id = $personal_section['jobtype'];
            if (!isset(wpjobportal::$_data['ai']['resume']['jobtypes'][$type_id])) {
                $jobtype_title = WPJOBPORTALincluder::getJSModel('jobtype')->getTitleByid($type_id);
                wpjobportal::$_data['ai']['resume']['jobtypes'][$type_id] = $jobtype_title;
            } else {
                $jobtype_title = wpjobportal::$_data['ai']['resume']['jobtypes'][$type_id];
            }

            if ($jobtype_title) {
                $resume_ai_string_main .= $jobtype_title . ' ';
            }
        }

        // Nationality (Country)
        if(!isset(wpjobportal::$_data['ai']['resume']['countries'])){
            wpjobportal::$_data['ai']['resume']['countries'] = array();
        }
        if (!empty($personal_section['nationality']) && is_numeric($personal_section['nationality'])) {
            $country_id = $personal_section['nationality'];
            if (!isset(wpjobportal::$_data['ai']['resume']['countries'][$country_id])) {
                $cuntry_name = WPJOBPORTALincluder::getJSModel('country')->getCountryName($country_id);
                wpjobportal::$_data['ai']['resume']['countries'][$country_id] = $cuntry_name;
            } else {
                $cuntry_name = wpjobportal::$_data['ai']['resume']['countries'][$country_id];
            }

            if ($cuntry_name) {
                $resume_ai_string_main .= $cuntry_name . ' ';
            }
        }

        if (!empty($personal_section['gender']) && is_numeric($personal_section['gender'])) {
            $gender_title = '';
            if($personal_section['gender'] == 1){
                $gender_title = esc_html(__('Male', 'wp-job-portal'));
            }elseif($personal_section['gender'] == 2){
                $gender_title = esc_html(__('Female', 'wp-job-portal'));
            }
            if($gender_title != ''){
                $resume_ai_string_main .= $gender_title . ' ';
            }
        }

        if (!empty($personal_section['salaryfixed'])) {
            $resume_ai_string_main .= wpjobportalphplib::wpJP_trim($personal_section['salaryfixed']) . ' ';
        }

        if (!empty($personal_section['tags'])) {
            $resume_ai_string_main .= wpjobportalphplib::wpJP_trim($personal_section['tags']) . ' ';
        }

        // handle custom fields
        if(!isset(wpjobportal::$_data['ai']['customfields_user_3'])){
            wpjobportal::$_data['ai']['customfields_user_3'] = WPJOBPORTALincluder::getJSModel('customfield')->getUserfieldsfor(3,1);// 3 fieldfor 1 section;
        }

        $custom_fields = wpjobportal::$_data['ai']['customfields_user_3'];
        // $custom_fields = WPJOBPORTALincluder::getJSModel('customfield')->getUserfieldsfor(3,1);// 3 fieldfor 1 section
        // ignore these field types from current case
        $skip_types = ['file', 'email', 'textarea'];

        $text_area_field_values = '';

        foreach ($custom_fields as $single_field) {
            if(!in_array($single_field->userfieldtype, $skip_types)){ // check if type agaisnt array
                if (!empty($personal_section[$single_field->field])) { // check value exsists
                    if(is_array($personal_section[$single_field->field])){ // to handle multi select and check box case
                        $resume_ai_string_main .= implode(',', $personal_section[$single_field->field]) . ' ';
                    }else{
                        $resume_ai_string_main .= $personal_section[$single_field->field] . ' ';
                    }
                }
            }elseif ($single_field->userfieldtype == 'textarea'){
                if (!empty($personal_section[$single_field->field])) { // check value exsists
                    $text_area_field_values .= $personal_section[$single_field->field] . ' ';
                }
            }
        }

        $resume_ai_string_main = trim($resume_ai_string_main); // Clean trailing space

        // SEOND LEVEL
        $resume_ai_string_desc = $resume_ai_string_main;
        $resume_ai_string_desc .= $text_area_field_values; // append personal section text area here

        // handle custom sections

        $resumesections = WPJOBPORTALincluder::getJSModel('fieldordering')->getResumeSections();

        // resume sections
        // if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
        foreach ($resumesections AS $section) {
            switch ($section->field){
                case 'section_address':
                    $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3 , $section->section);// 3 for resume section for section
                    $section_data = $this->getAddressSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_main .= $section_data['location_names'];
                    $resume_ai_string_desc .= $section_data['address_string'];
                break;
                case 'section_education':
                    $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3 , $section->section);// 3 for resume section for section
                    $section_data = $this->getEducationSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data;
                    break;
                case 'section_employer':
                    $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3 , $section->section);// 3 for resume section for section
                    $section_data = $this->getEmployerSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data;
                    break;
                case 'section_skills':
                    $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3 , $section->section);// 3 for resume section for section
                    $section_data = $this->getSkillSectionDataForAI($personal_section['id'],$fields,$personal_section);
                    $resume_ai_string_desc .= $section_data;
                    break;
                case 'section_language':
                    $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3 , $section->section);// 3 for resume section for section
                    $section_data = $this->getLanguageSectionDataForAI($personal_section['id'],$fields);
                    $resume_ai_string_desc .= $section_data;
                    break;
                default:
                    if($section->is_section_headline == 1){ // to print resume custom sections
                        if($section->field != 'section_resume' && $section->section > 8){ // avoid resume editor section legacy code issue and anyother section
                            $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(3 , $section->section);// 3 for resume section for section
                            $section_data = $this->getCustomSectionDataForAI($personal_section['id'],$fields,$personal_section);
                            $resume_ai_string_desc .= $section_data;
                        }
                    }
                break;
            }
        }

        // echo var_dump($resume_ai_string_desc);
        // echo '<br>';
        // echo '<br>';
        // echo '<br>';
        // return;
        // foreach ($resumesections as $section) {
        //     if($section->section <= 8){ // avoid the default resume sections
        //         continue;
        //     }
        //     // get fields for section
        //     $custom_fields = WPJOBPORTALincluder::getJSModel('customfield')->getUserfieldsfor(3,$section->section);// 3 fieldfor $section->section for section
        //     // ignore these field types from current case
        //     $skip_types = ['file', 'email'];

        //     foreach ($custom_fields as $single_field) {
        //         if(!in_array($single_field->userfieldtype, $skip_types)){ // check if type agaisnt array
        //             if (!empty($data[$single_field->field])) { // check value exsists
        //                 if(is_array($data[$single_field->field])){ // to handle multi select and check box case
        //                     $resume_ai_string_desc .= implode(',', $data[$single_field->field]) . ' ';
        //                 }else{
        //                     $resume_ai_string_desc .= $data[$single_field->field] . ' ';
        //                 }
        //             }
        //         }
        //     }
        // }

        $row = WPJOBPORTALincluder::getJSTable('resume');
        if ($row->update(array('id'=>$personal_section['id'], 'airesumesearchtext' => $resume_ai_string_main, 'airesumesearchdescription' => $resume_ai_string_desc))) {
            return;
        }
        return;
    }

    // get resume address sections by resume id
    function getAddressSectionDataForAI($resumeid,$fields){
        if (!is_numeric($resumeid))
            return false;

        $return_data = array();

        $query = "SELECT resumeaddress.address_city, resumeaddress.address
                        ,resumeaddress.params,resumeaddress.longitude,resumeaddress.latitude
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` resumeaddress
                    WHERE resumeaddress.resumeid = " . esc_sql($resumeid);

        $results = wpjobportaldb::get_results($query);
        $resume_address_section_string = '';
        $main_string_location_names = '';
        $skip_types = ['file', 'email'];
        if(!empty($results)){
            foreach ($results as $address) {

                // address city
                if(!empty($address->address_city)){
                    $location_name = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($address->address_city);
                    if($location_name != ''){
                        $resume_address_section_string .= $location_name.' ';
                        $main_string_location_names .= $location_name.' ';
                    }
                }

                // address
                if(!empty($address->address)){
                    $resume_address_section_string .= $address->address.' ';
                }

                // latitude
                if(!empty($address->latitude)){
                    $resume_address_section_string .= $address->latitude.' ';
                }
                // longitude
                if(!empty($address->longitude)){
                    $resume_address_section_string .= $address->longitude.' ';
                }

                // params
                if(!empty($address->params)){
                    $params = json_decode($address->params,true);
                }


                // custom field for address section
                // $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor( 3 , 2);// 3 for resume 2 for address section
                foreach($fields AS $field){
                    if(!in_array($field->userfieldtype,$skip_types)){
                        if(isset($params[$field->field]) && $params[$field->field] != ''){ // only add value for a section once. not for all its custom fields
                            $resume_address_section_string .= $params[$field->field].' ';
                        }
                    }
                }
            }
        }

        $return_data['address_string'] = $resume_address_section_string;
        $return_data['location_names'] = $main_string_location_names;
        return $return_data;
    }

    // get resume institutes sections by resume id
    function getEducationSectionDataForAI($resumeid,$fields){
        if (!is_numeric($resumeid))
            return false;

        $return_data = '';

        $query = "SELECT resumeinstitute.institute, resumeinstitute.institute_certificate_name
                        ,resumeinstitute.params,resumeinstitute.institute_study_area,resumeinstitute.todate,resumeinstitute.fromdate
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` resumeinstitute
                    WHERE resumeinstitute.resumeid = " . esc_sql($resumeid);

        $results = wpjobportaldb::get_results($query);
        $resume_institute_section_string = '';
        $skip_types = ['file', 'email'];
        if(!empty($results)){
            foreach ($results as $resumeinstitute) {
                // institute
                if(!empty($resumeinstitute->institute)){
                    $resume_institute_section_string .= $resumeinstitute->institute.' ';
                }

                // institute_certificate_name
                if(!empty($resumeinstitute->institute_certificate_name)){
                    $resume_institute_section_string .= $resumeinstitute->institute_certificate_name.' ';
                }
                // institute_study_area
                if(!empty($resumeinstitute->institute_study_area)){
                    $resume_institute_section_string .= $resumeinstitute->institute_study_area.' ';
                }
                // todate
                if(!empty($resumeinstitute->todate)){
                    $resume_institute_section_string .= $resumeinstitute->todate.' ';
                }
                // fromdate
                if(!empty($resumeinstitute->fromdate)){
                    $resume_institute_section_string .= $resumeinstitute->fromdate.' ';
                }

                if(!empty($resumeinstitute->params)){
                    $params = json_decode($resumeinstitute->params,true);
                }

                // custom field for resumeinstitute section
                // $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor( 3 , 3);// 3 for resume 3 for institute section
                foreach($fields AS $field){
                    if(!in_array($field->userfieldtype,$skip_types)){
                        if(isset($params[$field->field]) && $params[$field->field] != ''){ // only add value for a section once. not for all its custom fields
                            $resume_institute_section_string .= $params[$field->field].' ';
                        }
                    }
                }
            }
        }

        $return_data = $resume_institute_section_string;
        return $return_data;
    }


    // get resume employer sections by resume id
    function getEmployerSectionDataForAI($resumeid,$fields){
        if (!is_numeric($resumeid))
            return false;

        $return_data = '';

        $query = "SELECT resumeemployer.employer, resumeemployer.employer_position, resumeemployer.employer_from_date
                    , resumeemployer.employer_current_status, resumeemployer.employer_to_date, resumeemployer.employer_phone
                    , resumeemployer.employer_address, resumeemployer.employer_city, resumeemployer.params
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` resumeemployer
                    WHERE resumeemployer.resumeid = " . esc_sql($resumeid);

        $results = wpjobportaldb::get_results($query);
        $resume_employer_section_string = '';
        $skip_types = ['file', 'email'];
        if(!empty($results)){
            foreach ($results as $resumeemployer) {

                // employer
                if(!empty($resumeemployer->employer)){
                    $resume_employer_section_string .= $resumeemployer->employer.' ';
                }

                // employer_position
                if(!empty($resumeemployer->employer_position)){
                    $resume_employer_section_string .= $resumeemployer->employer_position.' ';
                }

                // employer_from_date
                if(!empty($resumeemployer->employer_from_date)){
                    $resume_employer_section_string .= $resumeemployer->employer_from_date.' ';
                }

                // employer_current_status
                if(!empty($resumeemployer->employer_current_status)){
                    $resume_employer_section_string .= $resumeemployer->employer_current_status.' ';
                }

                // employer_to_date
                if(!empty($resumeemployer->employer_to_date)){
                    $resume_employer_section_string .= $resumeemployer->employer_to_date.' ';
                }

                // employer_phone
                if(!empty($resumeemployer->employer_phone)){
                    $resume_employer_section_string .= $resumeemployer->employer_phone.' ';
                }

                // employer_address
                if(!empty($resumeemployer->employer_address)){
                    $resume_employer_section_string .= $resumeemployer->employer_address.' ';
                }

                // address city
                if(!empty($resumeemployer->employer_city)){
                    $location_name = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($resumeemployer->employer_city);
                    if($location_name != ''){
                        $resume_employer_section_string .= $location_name.' ';
                    }
                }

                if(!empty($resumeemployer->params)){
                    $params = json_decode($resumeemployer->params,true);
                }

                // custom field for resumeemployer section
                // $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor( 3 , 3);// 3 for resume 3 for institute section
                foreach($fields AS $field){
                    if(!in_array($field->userfieldtype,$skip_types)){
                        if(isset($params[$field->field]) && $params[$field->field] != ''){ // only add value for a section once. not for all its custom fields
                            $resume_employer_section_string .= $params[$field->field].' ';
                        }
                    }
                }
            }
        }

        $return_data = $resume_employer_section_string;
        return $return_data;
    }


    // get resume skill sections by resume id
    function getSkillSectionDataForAI($resumeid,$fields,$data){
        if (!is_numeric($resumeid))
            return false;

        $return_data = '';
        $resume_skill_section_string = '';


        // skill
        if(!empty($data['skills'])){
            $resume_skill_section_string .= $data['skills'].' ';
        }

        if(!empty($data['params'])){
            $params = json_decode($data['params'],true);
        }

        // custom field for resumeemployer section
        // $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor( 3 , 3);// 3 for resume 3 for institute section
        foreach($fields AS $field){
            if(!in_array($field->userfieldtype,$skip_types)){
                if(isset($params[$field->field]) && $params[$field->field] != ''){ // only add value for a section once. not for all its custom fields
                    $resume_skill_section_string .= $params[$field->field].' ';
                }
            }
        }


        $return_data = $resume_skill_section_string;
        return $return_data;
    }

    // get resume language sections by resume id
    function getLanguageSectionDataForAI($resumeid,$fields){
        if (!is_numeric($resumeid))
            return false;

        $return_data = '';

        $query = "SELECT resumelanguage.language,  resumelanguage.params
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumelanguages` resumelanguage
                    WHERE resumelanguage.resumeid = " . esc_sql($resumeid);

        $results = wpjobportaldb::get_results($query);
        $resume_language_section_string = '';
        $skip_types = ['file', 'email'];
        if(!empty($results)){
            foreach ($results as $resumelanguage) {

                // language
                if(!empty($resumelanguage->language)){
                    $resume_language_section_string .= $resumelanguage->language.' ';
                }

                if(!empty($resumelanguage->params)){
                    $params = json_decode($resumelanguage->params,true);
                }

                // custom field for resumelanguage section
                // $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor( 3 , 3);// 3 for resume 3 for institute section
                foreach($fields AS $field){
                    if(!in_array($field->userfieldtype,$skip_types)){
                        if(isset($params[$field->field]) && $params[$field->field] != ''){ // only add value for a section once. not for all its custom fields
                            $resume_language_section_string .= $params[$field->field].' ';
                        }
                    }
                }
            }
        }

        $return_data = $resume_language_section_string;
        return $return_data;
    }


    // get resume skill sections by resume id
    function getCustomSectionDataForAI($resumeid,$fields,$data){
        if (!is_numeric($resumeid))
            return false;

        $return_data = '';
        $resume_custom_section_string = '';

        if(!empty($data['params'])){
            $params = json_decode($data['params'],true);
        }
        $skip_types = ['file', 'email'];
        // custom field for resumeemployer section
        // $fields = wpjobportal::$_wpjpfieldordering->getUserfieldsfor( 3 , 3);// 3 for resume 3 for institute section
        foreach($fields AS $field){
            if(!in_array($field->userfieldtype,$skip_types)){
                if(isset($params[$field->field]) && $params[$field->field] != ''){ // only add value for a section once. not for all its custom fields
                    $resume_custom_section_string .= $params[$field->field].' ';
                }
            }
        }


        $return_data = $resume_custom_section_string;
        return $return_data;
    }
}
?>
