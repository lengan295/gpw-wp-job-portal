<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALcountryModel {

    function storeCountry($data) {
        if (empty($data))
            return false;

        if ($data['id'] == '') {
            $result = $this->isCountryExist($data['name']);
            if ($result == true) {
                return WPJOBPORTAL_ALREADY_EXIST;
            }
        }

        $data['shortCountry'] = wpjobportalphplib::wpJP_str_replace(' ', '-', $data['name']);
        $row = WPJOBPORTALincluder::getJSTable('country');
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

    function getCountrybyId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` WHERE id = " . esc_sql($id);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);

        return;
    }

    function getAllCountries() {

        $countryname = wpjobportal::$_search['country']['countryname'];
        $Status = wpjobportal::$_search['country']['status'];
        $states = wpjobportal::$_search['country']['states'];
        $city = wpjobportal::$_search['country']['city'];

        $inquery = '';
        $clause = ' WHERE ';
        if ($countryname) {
            $inquery .= esc_sql($clause) . "  country.name LIKE '%" . esc_sql($countryname) . "%' ";
            $clause = " AND ";
        }
        if (is_numeric($Status)) {
            $inquery .= esc_sql($clause) . " country.enabled = " . esc_sql($Status);
            $clause = " AND ";
        }

        if ($states == 1) {
            $inquery .= esc_sql($clause) . " (SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state WHERE state.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        if ($city == 1) {
            $inquery .= esc_sql($clause) . " (SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city WHERE city.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        wpjobportal::$_data['filter']['countryname'] = $countryname;
        wpjobportal::$_data['filter']['status'] = $Status;
        wpjobportal::$_data['filter']['states'] = $states;
        wpjobportal::$_data['filter']['city'] = $city;

        // Pagination
        $query = "SELECT COUNT(country.id)
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country";
        $query .= $inquery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        // Data
        $query = "SELECT country.* FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country";
        $query .= $inquery;

        $query .= " ORDER BY country.name ASC LIMIT " . WPJOBPORTALpagination::$_offset . ", " . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);

        return;
    }

    function deleteCountries($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('country');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->countryCanDelete($id) == true) {
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

        $row = WPJOBPORTALincluder::getJSTable('country');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'enabled' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->countryCanUnpublish($id)) {
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

    function countryCanUnpublish($countryid) {
        return true;
    }

    function countryCanDelete($countryid) {
        if (!is_numeric($countryid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(jobcity.id)
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity
                        JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = jobcity.cityid
                        WHERE city.countryid = " . esc_sql($countryid) . ")
                    + ( SELECT COUNT(companycity.id)
                            FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` AS companycity
                            JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = companycity.cityid
                            WHERE city.countryid = " . esc_sql($countryid) . ")
                    + ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE nationality = " . esc_sql($countryid) . ")
                    + ( SELECT COUNT(resumecity.id)
                            FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS resumecity
                            JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = resumecity.address_city
                            WHERE city.countryid = " . esc_sql($countryid) . ")
                    + ( SELECT COUNT(employeecity.id)
                            FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` AS employeecity
                            JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = employeecity.employer_city
                            WHERE city.countryid = " . esc_sql($countryid) . ")
                    + ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` WHERE countryid = " . esc_sql($countryid) . ")
                    + ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` WHERE countryid = " . esc_sql($countryid) . ")
            AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCountryExist($country) {
        if (!$country)
            return;
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_countries WHERE name = '" . esc_sql($country) . "'";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return true;
        else
            return false;
    }

    function getCountriesForCombo() {
        $query = "SELECT id , name AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` WHERE enabled = 1 ORDER BY name ASC ";
        $rows = wpjobportaldb::get_results($query);
        return $rows;
    }

    // fucntion to prepare data for country based city import combo box
    function getCountriesForComboForCityImport() {
        $query = "SELECT namecode, name,localname, internationalname FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` WHERE enabled = 1 ORDER BY internationalname ASC ";
        $data = wpjobportaldb::get_results($query);
        $countries = array();
        foreach ($data as $country_data) {
            $country = new stdClass();
            $country->id = $country_data->namecode;
            $country->text = $country_data->internationalname;
            if($country_data->localname != '' && $country->text != ''){
                $country->text .= '&nbsp;('.$country_data->localname.')';
            }else{
                $country->text = $country_data->name;
            }
            $countries[] = $country;
        }
        return $countries;
    }

    function getCountryIdByName($name) { // new function coded
        if (!$name)
            return;
        $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER('" . esc_sql($name) . "'), ' ', '') AND enabled = 1";
        $id = wpjobportaldb::get_var($query);
        return $id;
    }

    function getCountryName($id){
        if(!is_numeric($id)){
            return false;
        }
        $query = "SELECT name FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` WHERE id = ".esc_sql($id);
        $name = wpjobportaldb::get_var($query);
        return $name;
    }

    //search cookies data
    function getCountrySearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['countryname'] = WPJOBPORTALrequest::getVar("countryname");
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar("status");
        $jsjp_search_array['states'] = WPJOBPORTALrequest::getVar("states");
        $jsjp_search_array['city'] = WPJOBPORTALrequest::getVar("city");
        $jsjp_search_array['search_from_country'] = 1;
        return $jsjp_search_array;
    }

    function getCountrySavedCookiesData(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_country']) && $wpjp_search_cookie_data['search_from_country'] == 1){
            $jsjp_search_array['countryname'] = $wpjp_search_cookie_data['countryname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['states'] = $wpjp_search_cookie_data['states'];
            $jsjp_search_array['city'] = $wpjp_search_cookie_data['city'];
        }
        return $jsjp_search_array;
    }

    function setCountrySearchVariable($jsjp_search_array){
        wpjobportal::$_search['country']['countryname'] = isset($jsjp_search_array['countryname']) ? $jsjp_search_array['countryname'] : '';
        wpjobportal::$_search['country']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        wpjobportal::$_search['country']['states'] = isset($jsjp_search_array['states']) ? $jsjp_search_array['states'] : '';
        wpjobportal::$_search['country']['city'] = isset($jsjp_search_array['city']) ? $jsjp_search_array['city'] : '';
    }

    function getMessagekey(){
        $key = 'country';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
