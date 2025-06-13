<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALHighesteducationModel {

    function getHighestEducationbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_heighesteducation WHERE id = " . esc_sql($c_id);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        return;
    }

    function getAllHighestEducations() {
        // Filter
        $title = wpjobportal::$_search['knowledge']['title'];
        $status = wpjobportal::$_search['knowledge']['status'];
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
            $inquery .= esc_sql($clause) . "title LIKE '%" . esc_sql($title) . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .=esc_sql($clause) . " isactive = " . esc_sql($status);
        wpjobportal::$_data['filter']['title'] = $title;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['pagesize'] = $pagesize;
        //Pagination
        if($pagesize){
           WPJOBPORTALpagination::setLimit($pagesize);
        }
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_heighesteducation";
        $query .= $inquery;
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_heighesteducation $inquery ORDER BY ordering ASC";
        $query .=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        //DB class limitations
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` SET isdefault = 0 WHERE id != " . esc_sql($id);
        wpjobportaldb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isHighestEducationExist($data['title']);
            if ($result == true) {
                return WPJOBPORTAL_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . wpjobportal::$_db->prefix . "wj_portal_heighesteducation";
                $data['ordering'] = wpjobportaldb::get_var($query);
            }

            if ($data['isactive'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if ($data['isdefault'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['wpjobportal_isdefault'] == 1) {
                $data['isdefault'] = 1;
                $data['isactive'] = 1;
            } else {
                if ($data['isactive'] == 0) {
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

    function storeHighestEducation($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === WPJOBPORTAL_ALREADY_EXIST)
            return WPJOBPORTAL_ALREADY_EXIST;

        $row = WPJOBPORTALincluder::getJSTable('highesteducation');
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data = wpjobportal::$_common->stripslashesFull($data);// remove slashes with quotes.
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

    function deleteHighestEducations($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('highesteducation');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->highestEducationCanDelete($id) == true) {
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

        $row = WPJOBPORTALincluder::getJSTable('highesteducation');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'isactive' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->highestEducationCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'isactive' => $status))) {
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

    function highestEducationCanUnpublish($educationid) {
        if (!is_numeric($educationid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` WHERE id = " . esc_sql($educationid) . " AND isdefault =1)
                    AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function highestEducationCanDelete($educationid) {
        if (!is_numeric($educationid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE heighestfinisheducation = " . esc_sql($educationid) . " OR educationid = " . esc_sql($educationid) . ")
                    + ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` WHERE id = " . esc_sql($educationid) . " AND isdefault =1)
                    AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getHighestEducationForCombo() {
        $query = "SELECT id, title AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` WHERE isactive = 1";
        $query.= " ORDER BY ordering ASC ";
        $heighesteducation = wpjobportaldb::get_results($query);
        if (wpjobportal::$_db->last_error != null) {
            return false;
        }
        return $heighesteducation;
    }

    function getDefaultEducationId() {
        $query = "SELECT id FROM " . wpjobportal::$_db->prefix . "wj_portal_heighesteducation WHERE `isdefault` = 1";
        $id = wpjobportaldb::get_var($query);

        return $id;
    }

    function isHighestEducationExist($title) {
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_heighesteducation WHERE title = '" . esc_sql($title) . "'";
        $result = wpjobportaldb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }
    function getMessagekey(){
        $key = 'highesteducation';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    // WE will Save the Ordering system in this Function
    function storeOrderingFromPage($data) {//
        if (empty($data)) {
            return false;
        }
        $sorted_array = array();
        wpjobportalphplib::wpJP_parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){
            $row = WPJOBPORTALincluder::getJSTable('highesteducation');
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
    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = WPJOBPORTALrequest::getVar("title");
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar("status");
        $jsjp_search_array['search_from_knowledge'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = filter_var($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_knowledge']) && $wpjp_search_cookie_data['search_from_knowledge'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        wpjobportal::$_search['knowledge']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : '';
        wpjobportal::$_search['knowledge']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
    }
}

?>
