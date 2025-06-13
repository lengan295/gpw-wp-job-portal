<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALCareerlevelModel {

    function getJobCareerLevelbyId($id) {
        if (is_numeric($id) == false)
            return false;

        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_careerlevels WHERE id = " . esc_sql($id);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);

        return;
    }

    function getAllCareerLevels() {
        // Filter
        $title = wpjobportal::$_search['careerlevel']['title'];
        $status = wpjobportal::$_search['careerlevel']['status'];
        $pagesize = absint(WPJOBPORTALrequest::getVar('pagesize'));
        $formsearch = WPJOBPORTALrequest::getVar('WPJOBPORTAL_form_search', 'post');
        if ($formsearch == 'WPJOBPORTAL_SEARCH') {
            update_option( 'wpjobportal_page_size', $pagesize);
        }
        if(get_option( 'wpjobportal_page_size', '' ) != ''){
            $pagesize = get_option( 'wpjobportal_page_size');
        }

        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            $inquery .= esc_sql($clause) . "title LIKE '%".esc_sql($title)."%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .=esc_sql($clause) . " status = " . esc_sql($status);

        wpjobportal::$_data['filter']['title'] = $title;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['pagesize'] = $pagesize;
        //pagination
        if($pagesize){
            WPJOBPORTALpagination::setLimit($pagesize);
        }
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_careerlevels ";
        $query .= $inquery;
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);
        //data
        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_careerlevels";
        $query .= $inquery;
        $query .= " ORDER BY ordering ASC LIMIT " . WPJOBPORTALpagination::$_offset . " , " . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        return;
    }

    function updateIsDefault($id) {
        //DB class limitations
        if (!is_numeric($id))
            return false;
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` SET isdefault = 0 WHERE id != " . esc_sql($id);
        wpjobportaldb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCareerlevelExist($data['title']);
            if ($result == true) {
                return WPJOBPORTAL_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . wpjobportal::$_db->prefix . "wj_portal_careerlevels";
                $data['ordering'] = wpjobportaldb::get_var($query);
            }

            if ($data['status'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if ($data['isdefault'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['wpjobportal_isdefault'] == 1) {
                $data['isdefault'] = 1;
                $data['status'] = 1;
            } else {
                if ($data['status'] == 0) {
                    $data['isdefault'] = 0;
                } else {
                    if ($data['isdefault'] == 1) {
                        $canupdate = true;
                    }
                }
            }
        }
        return $canupdate;
    }

    function storeCareerLevel($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === WPJOBPORTAL_ALREADY_EXIST)
            return WPJOBPORTAL_ALREADY_EXIST;

        $row = WPJOBPORTALincluder::getJSTable('careerlevels');
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data = WPJOBPORTALincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if ($canupdate) {
            $this->updateIsDefault($row->id);
        }

        return WPJOBPORTAL_SAVED;
    }

    function deleteCareerLevels($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('careerlevels');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->careerLevelCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
                }
            } else {
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

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = WPJOBPORTALincluder::getJSTable('careerlevels');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->careerLevelCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                } else {
                    $total += 1;
                }
            }
        }
        if ($total == 0) {
            WPJOBPORTALMessages::$counter = false;
            if ($status == 1)
                return WPJOBPORTAL_PUBLISHED;
            else
                return WPJOBPORTAL_UN_PUBLISHED;
        }else {
            WPJOBPORTALMessages::$counter = $total;
            if ($status == 1)
                return WPJOBPORTAL_PUBLISH_ERROR;
            else
                return WPJOBPORTAL_UN_PUBLISH_ERROR;
        }
    }

    function careerLevelCanUnpublish($careerlevelid) {
        if (is_numeric($careerlevelid) == false)
            return false;
        $query = " SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` WHERE id = " . esc_sql($careerlevelid) . " AND isdefault = 1 ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function careerLevelCanDelete($careerlevelid) {
        if (is_numeric($careerlevelid) == false)
            return false;

        $query = " SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE careerlevel = " . esc_sql($careerlevelid) . ")
                    + ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` WHERE id = " . esc_sql($careerlevelid) . " AND isdefault = 1)
                    AS total ";

        $total = wpjobportaldb::get_var($query);


        if ($total > 0)
            return false;
        else
            return true;
    }

    function getCareerLevelsForCombo() {
        $query = "SELECT id, title AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` WHERE status = 1 ORDER BY ordering ASC ";
        $careerlevels = wpjobportaldb::get_results($query);
        return $careerlevels;
    }

    function getDefaultCareerlevelId() {
        $query = "SELECT id FROM " . wpjobportal::$_db->prefix . "wj_portal_careerlevels WHERE `isdefault` = 1";
        $id = wpjobportaldb::get_var($query);

        return $id;
    }

    function isCareerlevelExist($title) {
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_careerlevels WHERE title ='$title'";
        $result = wpjobportaldb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getTitleByid($id) {
        if(!is_numeric($id)){
            return false;
        }
        $query = "SELECT title FROM " . wpjobportal::$_db->prefix . "wj_portal_careerlevels WHERE id =". esc_sql($id);
        $result = wpjobportaldb::get_var($query);
        return $result;
    }

    function getMessagekey(){
        $key = 'careerlevel';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


    //WE will Save the Ordering system in this Function
    function storeOrderingFromPage($data) {//
        if (empty($data)) {
            return false;
        }
        $sorted_array = array();
        wpjobportalphplib::wpJP_parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){
            $row = WPJOBPORTALincluder::getJSTable('careerlevels');
            $ordering_coloumn = 'ordering';
        }
        $page_multiplier = 0;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        if (isset($pagenum)) {
            $page_multiplier = $pagenum - 1;
        }
        $pagesize = get_option( 'wpjobportal_page_size');
        if ($pagesize == 0) {
            $pagesize = wpjobportal::$_configuration['pagination_default_page_size'];
        }
        $page_multiplier = $pagesize * $page_multiplier;
        for ($i=0; $i < count($sorted_array) ; $i++) {
            $row->update(array('id' => $sorted_array[$i], $ordering_coloumn => $page_multiplier + $i));
        }
        WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Ordering updated', 'wp-job-portal')), 'updated', $this->getMessagekey());
        return ;
    }
    // End Function

    function getSearchFormDataCareerLevel(){
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = WPJOBPORTALrequest::getVar('title');
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status');
        $jsjp_search_array['search_from_careerlevel'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedCareerLevel(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data, true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_careerlevel']) && $wpjp_search_cookie_data['search_from_careerlevel'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableCareerLevel($jsjp_search_array){
        wpjobportal::$_search['careerlevel']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : null;
        wpjobportal::$_search['careerlevel']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
    }
}

?>
