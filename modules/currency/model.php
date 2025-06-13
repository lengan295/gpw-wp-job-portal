<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALcurrencyModel {

    function getCurrencybyId($id) {
        if (is_numeric($id) == false)
            return false;

        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_currencies WHERE id = " . esc_sql($id);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        return;
    }

    function getCurrencyForCombo() {

        $query = "SELECT id, symbol AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` WHERE status = 1 ORDER BY ordering ASC";
        $allcurrency = wpjobportaldb::get_results($query);
        return $allcurrency;
    }

    function getDefaultCurrency() {

        $query = "SELECT currency.id FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` currency WHERE currency.default = 1 AND currency.status=1 ";
        $defaultValue = wpjobportaldb::get_row($query);
        if (!$defaultValue) {
            $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` WHERE status=1";
            $defaultValue = wpjobportaldb::get_results($query);
        }
        return $defaultValue;
    }

    function getCurrencySymbol($currencyid){
        if(!is_numeric($currencyid)){
            return false;
        }
        $query = " SELECT symbol FROM `" .wpjobportal::$_db->prefix. "wj_portal_currencies` WHERE id = ".esc_sql($currencyid);
        $defaultValue = wpjobportaldb::get_var($query);
        return $defaultValue;
    }

     function getCurrencySmallestUnit($currencyid){
        if(!is_numeric($currencyid)){
            return false;
        }
        $query = " SELECT `smallestunit` FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` WHERE id = ".esc_sql($currencyid);
        return wpjobportaldb::get_var($query);
    }

    function getAmountInSmallestUnit($amount,$currencyid){
        if(!is_numeric($currencyid)){
            return false;
        }
        $query = " SELECT `smallestunit` FROM `" .wpjobportal::$_db->prefix ."wj_portal_currencies` WHERE id = ".esc_sql($currencyid);
        $unit = wpjobportaldb::get_var($query);
        if(!$unit){
            $unit = 1;
        }
        return $amount * $unit;
    }


    function getAllCurrencies() {
        // Filter
        $title = wpjobportal::$_search['country']['title'];
        $status = wpjobportal::$_search['country']['status'];
        $code = wpjobportal::$_search['country']['code'];
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
        if (is_numeric($status)){
            $inquery .= esc_sql($clause) . " status = " . esc_sql($status);
            $clause = ' AND ';
        }
        if ($code != null){
            $inquery .= esc_sql($clause) . " code LIKE '%" . esc_sql($code) . "%'";
            $clause = ' AND ';
        }

        wpjobportal::$_data['filter']['title'] = $title;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['code'] = $code;
        wpjobportal::$_data['filter']['pagesize'] = $pagesize;
        //Pagination
        if($pagesize){
           WPJOBPORTALpagination::setLimit($pagesize);
        }
        $query = "SELECT count(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` ";
        $query .= $inquery;
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);
        //Data
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` $inquery ORDER BY ordering ASC ";
        $query .= " LIMIT " . WPJOBPORTALpagination::$_offset . ", " . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        return;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        //DB class limitations
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_currencies` AS cur SET cur.default = 0 WHERE cur.id != " . esc_sql($id);
        wpjobportaldb::query($query);
    }

    function validateFormData(&$data) {
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCurrencyExist($data['title']);
            if ($result == true) {
                return WPJOBPORTAL_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . wpjobportal::$_db->prefix . "wj_portal_currencies";
                $data['ordering'] = wpjobportaldb::get_var($query);
            }

            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ($data['default'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['status'] == 0) {
                $data['default'] = 0;
            } else {
                if ($data['default'] == 1) {
                    $canupdate = true;
                }
            }
        }
        return $canupdate;
    }

    function storeCurrency($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === WPJOBPORTAL_ALREADY_EXIST)
            return WPJOBPORTAL_ALREADY_EXIST;

        $row = WPJOBPORTALincluder::getJSTable('currency');
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data = WPJOBPORTALincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if ($row->default == 1) {
            $this->updateIsDefault($row->id);
        }
        return WPJOBPORTAL_SAVED;
    }

    function isCurrencyExist($title) {
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_currencies WHERE title = '" . esc_sql($title) . "'";
        $result = wpjobportaldb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function deleteCurrencies($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('currency');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->currencyCanDelete($id) == true) {
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

    function currencyCanDelete($currencyid) {
        if (is_numeric($currencyid) == false)
            return false;

        $query = " SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` AS cur WHERE cur.id = " . esc_sql($currencyid) . " AND cur.default =1)
                    AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = WPJOBPORTALincluder::getJSTable('currency');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'status' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->currencyCanUnpulish($id)) {
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

    function currencyCanUnpulish($currencyid) {
        if(!is_numeric($currencyid)){
            return false;
        }
        $query = " SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` AS cur WHERE cur.id = " . esc_sql($currencyid) . " AND cur.default = 1 ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getCurrencyResumeAppliedForCombo() {
        $query = "SELECT id, symbol AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_currencies` WHERE status = 1";
        $allcurrency = wpjobportaldb::get_results($query);
        return $allcurrency;
    }

    function getDefaultCurrencyId() {
        $query = "SELECT id FROM " . wpjobportal::$_db->prefix . "wj_portal_currencies WHERE `default` = 1";
        $id = wpjobportaldb::get_var($query);
        return $id;
    }

    function getDecimalPlaces($currencyid){
        if(!is_numeric($currencyid)){
            return wpjobportal::$_configuration['decimal_places'];
        }
        return wpjobportalphplib::wpJP_strlen($this->getCurrencySmallestUnit($currencyid))-1;
    }

    function getCurrencyCode($currencyid){
        if(!is_numeric($currencyid)){
            return false;
        }
        $query = " SELECT `code` FROM `" .wpjobportal::$_db->prefix."wj_portal_currencies` WHERE id = ".esc_sql($currencyid);
        return wpjobportaldb::get_var($query);
    }

    function getMessagekey(){
        $key = 'currency';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
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
            $row = WPJOBPORTALincluder::getJSTable('currency');
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
        WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Ordering updated', 'wp-job-portal')), 'updated',$this->getMessagekey());
        return ;
    }
    // End Function
    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = WPJOBPORTALrequest::getVar("title");
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar("status");
        $jsjp_search_array['code'] = WPJOBPORTALrequest::getVar("code");
        $jsjp_search_array['search_from_currency'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_currency']) && $wpjp_search_cookie_data['search_from_currency'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['code'] = $wpjp_search_cookie_data['code'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        wpjobportal::$_search['country']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : '';
        wpjobportal::$_search['country']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        wpjobportal::$_search['country']['code'] = isset($jsjp_search_array['code']) ? $jsjp_search_array['code'] : '';
    }

}

?>
