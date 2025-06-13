<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALStateModel {

    function getStatebyId($id) {
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_states WHERE id = " . esc_sql($id);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        return;
    }

    function getAllCountryStates($countryid) {
        if (!is_numeric($countryid))
            return false;
        //Filters
        $searchname = wpjobportal::$_search['state']['searchname'];
        $city = wpjobportal::$_search['state']['city'];
        $status = wpjobportal::$_search['state']['status'];

        $inquery = '';
        if ($searchname) {
            $inquery .= " AND name LIKE '%" . esc_sql($searchname) . "%'";
        }
        if (is_numeric($status)) {
            $inquery .= " AND state.enabled = " . esc_sql($status);
        }

        if ($city == 1) {
            $inquery .=" AND (SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city WHERE city.stateid = state.id) > 0 ";
        }

        wpjobportal::$_data['filter']['searchname'] = $searchname;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['city'] = $city;


        //Pagination
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state WHERE countryid = " . esc_sql($countryid);
        $query.=$inquery;
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state WHERE countryid = " . esc_sql($countryid);
        $query.=$inquery;
        $query.=" ORDER BY name ASC LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);

        return;
    }

    function storeState($data, $countryid) {
        if (empty($data))
            return false;

        $row = WPJOBPORTALincluder::getJSTable('state');
        $data['countryid'] = $countryid;

        if (!$data['id']) { // only for new
            $existvalue = $this->isStateExist($data['name'], $data['countryid']);
            if ($existvalue == true)
                return WPJOBPORTAL_ALREADY_EXIST;
        }

        $data['shortRegion'] = $data['name'];
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data = WPJOBPORTALincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        return WPJOBPORTAL_SAVED;
    }

    function deleteStates($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('state');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->stateCanDelete($id) == true) {
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

    function stateCanDelete($stateid) {
        if (!is_numeric($stateid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(mcity.id)
                           FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                           JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mcity ON mcity.cityid=city.id
                           WHERE city.stateid = " . esc_sql($stateid) . "
                   )
                   +
                   ( SELECT COUNT(cmcity.id)
                           FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                           JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companycities` AS cmcity ON cmcity.cityid=city.id
                           WHERE city.stateid = " . esc_sql($stateid) . "
                   )
                   +
                   ( SELECT COUNT(resume.id)
                           FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                           JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS resume ON resume.address_city=city.id
                           WHERE city.stateid = " . esc_sql($stateid) . "
                   )
                   +
                   ( SELECT COUNT(resume.id)
                           FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                           JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` AS resume ON resume.employer_city=city.id
                           WHERE city.stateid = " . esc_sql($stateid) . "
                   )
                    AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function stateCanUnpublish($stateid) {
        return true;
    }

    function isStateExist($state, $countryid) {
        if (!is_numeric($countryid))
            return false;
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_states WHERE name = '".esc_sql($state)."' AND countryid = " . esc_sql($countryid);
        $result = wpjobportaldb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getStatesForCombo($country) {
        if (is_null($country) OR empty($country) OR !is_numeric($country))
            $country = 0;
        $query = "SELECT id, name AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` WHERE enabled = '1' AND countryid = " . esc_sql($country) . " ORDER BY name ASC ";
        $rows = wpjobportaldb::get_results($query);
        if (wpjobportal::$_db->last_error != null) {
            return false;
        }
        return $rows;
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = WPJOBPORTALincluder::getJSTable('state');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'enabled' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->stateCanUnpublish($id)) {
                    if (!$row->update(array('id' => $id, 'enabled' => $status))) {
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

    function getStateIdByName($name) { // new function coded
        $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER('" . esc_sql($name) . "'), ' ', '') AND enabled = 1";
        $id = wpjobportaldb::get_var($query);
        return $id;
    }

    function storeTokenInputState($data) { // new function coded
        if (empty($data))
            return false;
        if (!isset($data['countryid']))
            return false;
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data = WPJOBPORTALincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        $row = WPJOBPORTALincluder::getJSTable('state');
        if (!$row->bind($data)) {
            return false;
        }
        if (!$row->store()) {
            return false;
        }
        return true;
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchname'] = WPJOBPORTALrequest::getVar("searchname");
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar("status");
        $jsjp_search_array['city'] = WPJOBPORTALrequest::getVar("city");
        $jsjp_search_array['search_from_state'] = 1;
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
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_state']) && $wpjp_search_cookie_data['search_from_state'] == 1){
            $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['city'] = $wpjp_search_cookie_data['city'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        wpjobportal::$_search['state']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : '';
        wpjobportal::$_search['state']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        wpjobportal::$_search['state']['city'] = isset($jsjp_search_array['city']) ? $jsjp_search_array['city'] : '';
    }

    function getMessagekey(){
        $key = 'state';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
