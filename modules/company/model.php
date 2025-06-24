<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALCompanyModel {

    function getCompanies_Widget($companytype, $noofcompanies,$custom_company_ids_list = []) {
        if ((!is_numeric($companytype)) || ( !is_numeric($noofcompanies)))
            return false;

        if ($companytype == 1) { // latest companies
            $inquery = '  ';
        } elseif ($companytype == 2) { // featured companeis
            $inquery = ' AND company.isfeaturedcompany = 1 AND DATE(company.endfeatureddate) >= CURDATE() ';
        } elseif ($companytype == 3) { // custom selection of companies
            // make sure the selection is not empty
            if (!empty($custom_company_ids_list) && is_array($custom_company_ids_list)) {
                $escaped_ids = array_map('intval', $custom_company_ids_list); // Sanitize Ids to int check
                $inquery = ' AND company.id IN (' . implode(',', $escaped_ids) . ') ';
            } else {
                return []; // No companies selected
            }
        } else {
            return '';
        }

        $query = "SELECT  company.*, CONCAT(company.alias,'-',company.id) AS companyaliasid ,company.id AS companyid,company.logofilename AS companylogo
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
            WHERE company.status = 1  ";
        $query .= $inquery . " ORDER BY company.created DESC ";
        if ($noofcompanies != -1 && is_numeric($noofcompanies))
            $query .=" LIMIT " . esc_sql($noofcompanies);
        $results = wpjobportaldb::get_results($query);

        $results = wpjobportaldb::get_results($query);
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
        }
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('company');
        return $results;
    }

    function getAllCompaniesForSearchForCombo() {
        $query = "SELECT id, name AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` ORDER BY name ASC ";
        $rows = wpjobportaldb::get_results($query);
        return $rows;
    }

    function getCompanybyIdForView($companyid) {
        if (is_numeric($companyid) == false)
            return false;

        $query = "SELECT company.*,city.name AS cityname
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON company.city = city.id
                    WHERE  company.id = " . esc_sql($companyid);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        wpjobportal::$_data[0]->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView(wpjobportal::$_data[0]->city);
        wpjobportal::$_data[2] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(1);
        wpjobportal::$_data[3] = wpjobportal::$_data[0]->params;
        wpjobportal::$_data['companycontactdetail'] = true;
       // if user is guest or other then owner then make sure of contact detail on view company
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest() || wpjobportal::$_data[0]->uid != WPJOBPORTALincluder::getObjectClass('user')->uid()) {
            if(in_array('credits',wpjobportal::$_active_addons)){
                $subType = wpjobportal::$_config->getConfigValue('submission_type');
                if($subType == 1){
                    wpjobportal::$_data['companycontactdetail'] = true;
                }elseif ($subType == 2 || $subType == 3) {
                    $contantdetail_paid = 1;
                    if($subType == 2){
                        if(!wpjobportal::$_config->getConfigValue('job_viewcompanycontact_price_perlisting') > 0){
                            $contantdetail_paid = 0;
                        }
                    }
                    if($contantdetail_paid == 1){
                        wpjobportal::$_data['companycontactdetail'] = $this->checkAlreadyViewCompanyContactDetail($companyid);
                    }else{
                        wpjobportal::$_data['companycontactdetail'] = true;
                    }
                }
            }else{
                wpjobportal::$_data['companycontactdetail'] = true;
            }
        }
        //update the company view counter
        //DB class limitations
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_companies` SET hits = hits + 1 WHERE id = " . esc_sql($companyid);
        wpjobportal::$_db->query($query);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('company');
        if(in_array('credits', wpjobportal::$_active_addons)){
            wpjobportal::$_data['paymentconfig'] = wpjobportal::$_wpjppaymentconfig->getPaymentConfigFor('paypal,stripe,woocommerce',true);
        }

        return;
    }

    public function checkAlreadyViewCompanyContactDetail($companyid,$data='') {
        $userobject = WPJOBPORTALincluder::getObjectClass('user');

        if($userobject->isguest() || !$userobject->isWPJOBPORTALuser())
            return false;
        if (!is_numeric($companyid))
            return false;

        if(current_user_can( 'manage_options' ) && !isset($data['uid'])){
            return true;
        }
        if(isset($data['uid'])){
           $uid = $data['uid'];
        }else{
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        }
        if(!is_numeric($uid)) return false;
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobseeker_view_company` WHERE companyid = " . esc_sql($companyid) . " AND uid = " . esc_sql($uid);
        $result = wpjobportal::$_db->get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getCompanybyId($c_id) {

        if ($c_id)
            if (!is_numeric($c_id))
                return false;
        if ($c_id) {
            $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE id =" . esc_sql($c_id);
            wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
            if(wpjobportal::$_data[0] != ''){
                wpjobportal::$_data[0]->multicity = wpjobportal::$_common->getMultiSelectEdit($c_id, 2);
            }
        }
        wpjobportal::$_data[2] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(WPJOBPORTAL_COMPANY); // company fields
        return;
    }


    function getMyCompanies($uid) {
        if (!is_numeric($uid)) return false;
        //Filters
        $searchcompany = isset(wpjobportal::$_search['search_filter']['searchcompany']) ? wpjobportal::$_search['search_filter']['searchcompany'] : '';

        //Front end search var
        $wpjobportal_city = isset(wpjobportal::$_search['search_filter']['wpjobportal_city']) ? wpjobportal::$_search['search_filter']['wpjobportal_city'] : '';

        $inquery = '';
        if ($searchcompany) {
            $inquery = " AND LOWER(company.name) LIKE '%".esc_sql($searchcompany)."%'";
        }
        if ($wpjobportal_city) {
            if(is_numeric($wpjobportal_city)){
                $inquery .= " AND LOWER(company.city) LIKE '%".esc_sql($wpjobportal_city)."%'";
            }else{
                $arr = wpjobportalphplib::wpJP_explode( ',' , $wpjobportal_city);
                $cityQuery = false;
                foreach($arr as $i){
                    if($cityQuery){
                        $cityQuery .= " OR LOWER(company.city) LIKE '%".esc_sql($i)."%' ";
                    }else{
                        $cityQuery = " LOWER(company.city) LIKE '%".esc_sql($i)."%' ";
                    }
                }
                $inquery .= " AND ( $cityQuery ) ";
            }
        }


        wpjobportal::$_data['filter']['wpjobportal-city'] = wpjobportal::$_common->getCitiesForFilter($wpjobportal_city);
        wpjobportal::$_data['filter']['searchcompany'] = $searchcompany;


        //Pagination
        // to handle base plugin showing pagination (to accomodate data query below)
            $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company WHERE uid = " . esc_sql($uid);
            $query .= $inquery;
            $total = wpjobportaldb::get_var($query);
        // to handle the case of show 1 in case of base plugin and 0 in case of not record found

        if(!in_array('multicompany', wpjobportal::$_active_addons)){
            if($total > 1){
                $total = 1;
            }
        }
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total,'mycompany');
        //Data
        $query = "SELECT company.id,company.name,company.logofilename,CONCAT(company.alias,'-',company.id) AS aliasid,company.created,company.serverid,company.city,company.status,company.isfeaturedcompany
                 ,company.endfeatureddate,company.params,company.url,company.description,company.contactemail
                FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
                WHERE company.uid = " . esc_sql($uid);
        $query .= $inquery;
        if(in_array('multicompany', wpjobportal::$_active_addons)){
            $query .= " ORDER BY company.created DESC LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        }else{
            $query .= " ORDER BY company.id ASC LIMIT 0,1";
        }
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        $data = array();
        foreach (wpjobportal::$_data[0] AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        wpjobportal::$_data[0] = $data;
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(1);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('company');
        return;
    }

    function sorting() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        wpjobportal::$_data['sorton'] = wpjobportal::$_search['search_filter']['sorton'] != '' ? wpjobportal::$_search['search_filter']['sorton']: 3;
        wpjobportal::$_data['sortby'] = wpjobportal::$_search['search_filter']['sortby'] != '' ? wpjobportal::$_search['search_filter']['sortby']: 2;

        switch (wpjobportal::$_data['sorton']) {
            case 3: // created
                wpjobportal::$_data['sorting'] = ' company.created ';
                break;
            case 1: // company title
                wpjobportal::$_data['sorting'] = ' company.name ';
                break;
            case 2: // category
                wpjobportal::$_data['sorting'] = ' cat.cat_title ';
                break;
            case 4: // location
                wpjobportal::$_data['sorting'] = ' city.name ';
                break;
            case 5: // status
                wpjobportal::$_data['sorting'] = ' company.status ';
                break;
            default:
                //wpjobportal::$_data['sorting'] = ' company.created ';
            break;
        }
        if (wpjobportal::$_data['sortby'] == 1) {
            wpjobportal::$_data['sorting'] .= ' ASC ';
        } else {
            wpjobportal::$_data['sorting'] .= ' DESC ';
        }
        wpjobportal::$_data['combosort'] = wpjobportal::$_data['sorton'];
    }

    function getAllCompanies() {
        if(wpjobportal::$_common->wpjp_isadmin()){
            $this->sorting();
        }else{
            $this->getOrdering();
        }

        //Filters
        $searchcompany = wpjobportal::$_search['search_filter']['searchcompany'];
        $searchjobcategory = wpjobportal::$_search['search_filter']['searchjobcategory'];
        $status = wpjobportal::$_search['search_filter']['status'];
        $datestart = wpjobportal::$_search['search_filter']['datestart'];
        $dateend = wpjobportal::$_search['search_filter']['dateend'];
        $featured = wpjobportal::$_search['search_filter']['featured'];
        //Front end search var
        $wpjobportal_company = wpjobportal::$_search['search_filter']['wpjobportal_company'];
        $wpjobportal_city = wpjobportal::$_search['search_filter']['wpjobportal_city'];
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        $inquery = '';
        if ($searchcompany) {
            $inquery = " AND LOWER(company.name) LIKE '%".esc_sql($searchcompany)."%'";
        }
        if ($wpjobportal_company) {
            $inquery = " AND LOWER(company.name) LIKE '%".esc_sql($wpjobportal_company)."%'";
        }
        if ($wpjobportal_city) {
			if(is_numeric($wpjobportal_city)){
				$inquery .= " AND company.city = ".esc_sql($wpjobportal_city)." ";
			}else{
				$arr = wpjobportalphplib::wpJP_explode( ',' , $wpjobportal_city);
				$cityQuery = false;
				foreach($arr as $i){
                    if(is_numeric($i)){
    					if($cityQuery){
    						$cityQuery .= " OR company.city = ".esc_sql($i)." ";
    					}else{
    						$cityQuery = " company.city = ".esc_sql($i)." ";
    					}
                    }
				}
				$inquery .= " AND ( $cityQuery ) ";
			}
        }

        if (is_numeric($status)) {
            $inquery .= " AND company.status = " . esc_sql($status);
        }

        if ($datestart != null) {
            $datestart = gmdate('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(company.created) >= '" . esc_sql($datestart) . "'";
        }

        if ($dateend != null) {
            $dateend = gmdate('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(company.created) <= '" . esc_sql($dateend) . "'";
        }
        $curdate = gmdate('Y-m-d');
        if ($featured != null) {
           $inquery .= apply_filters('wpjobportal_addons_search_feature_query',false);
        }

        wpjobportal::$_data['filter']['wpjobportal-company'] = $wpjobportal_company;
        wpjobportal::$_data['filter']['wpjobportal-city'] = wpjobportal::$_common->getCitiesForFilter($wpjobportal_city);
        wpjobportal::$_data['filter']['searchcompany'] = $searchcompany;
        wpjobportal::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['datestart'] = $datestart;
        wpjobportal::$_data['filter']['dateend'] = $dateend;
        wpjobportal::$_data['filter']['featured'] = $featured;
        //Pagination
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company WHERE company.status != 0";
        $query .=$inquery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT company.uid,company.name,CONCAT(company.alias,'-',company.id) AS aliasid,
                company.city, company.created,company.logofilename,
                company.status,company.url,company.id,company.params,company.isfeaturedcompany,company.endfeatureddate,company.description
                FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT cityid FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1)
                WHERE company.status != 0";

        $query .= $inquery;
        if(wpjobportal::$_common->wpjp_isadmin()){
            $query .= " ORDER BY " . wpjobportal::$_data['sorting'];
        }else{
            $query.= " ORDER BY " . wpjobportal::$_ordering;
        }
        $query .= " LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        $results = wpjobportaldb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        wpjobportal::$_data[0] = $data;
        if(wpjobportal::$theme_chk == 1 && wpjobportal::$_data != '' && isset($wpjobportal_city) && $wpjobportal_city != ''){
                wpjobportal::$_data['multicity'] = $this->getCitySelected($wpjobportal_city);
            }
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(1);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('company');
        return;
    }

    function getCitySelected($city){

        $query = "SELECT id, name FROM " . wpjobportal::$_db->prefix . "wj_portal_cities WHERE id IN (".esc_sql($city).")";
        $results = wpjobportaldb::get_results($query);
        return $json_response = wp_json_encode($results);
    }

    function getAllUnapprovedCompanies() {
        $this->sorting();
        //Filters
        $searchcompany = wpjobportal::$_search['search_filter']['searchcompany'];
        // $categoryid = wpjobportal::$_search['search_filter']['searchjobcategory'];
        $datestart = wpjobportal::$_search['search_filter']['datestart'];
        $dateend = wpjobportal::$_search['search_filter']['dateend'];

        wpjobportal::$_data['filter']['searchcompany'] = $searchcompany;
        // wpjobportal::$_data['filter']['searchjobcategory'] = $categoryid;
        wpjobportal::$_data['filter']['datestart'] = $datestart;
        wpjobportal::$_data['filter']['dateend'] = $dateend;

        $inquery = '';
        if ($searchcompany)
            $inquery = " AND LOWER(company.name) LIKE '%".esc_sql($searchcompany)."%'";

        if ($datestart != null) {
            $datestart = gmdate('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(company.created) >= '" . esc_sql($datestart) . "'";
        }

        if ($dateend != null) {
            $dateend = gmdate('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(company.created) <= '" . esc_sql($dateend) . "'";
        }

        //Pagination
        $query = "SELECT COUNT(company.id)
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
                    WHERE (company.status = 0 )";
        $query .=$inquery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT company.*
                FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT cityid FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1)
                WHERE (company.status = 0 OR company.isfeaturedcompany = 0)";
        $query .=$inquery;
        $query .= " ORDER BY " . wpjobportal::$_data['sorting'] . " LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(1);
        // print_r(wpjobportal::$_data[0]);
        return;
    }

    function storeCompany($data){
        if(empty($data)){
            return false;
        }
        # request parameters
            $cuser = WPJOBPORTALincluder::getObjectClass('user');
            $id = (int) $data['id'];

        $isnew = true;
        if(is_numeric($id) && $id > 0){
            $isnew = false;
        }

        //making sure uid is not changed
        // admin can edit other employers companies
        if(!wpjobportal::$_common->wpjp_isadmin()){
            $data['uid'] = $cuser->uid();
        }

        # prepare data + business logic
            if($isnew){
                $data['created'] = current_time('mysql');
                $submissionType = wpjobportal::$_config->getConfigValue('submission_type');
                if(!wpjobportal::$_common->wpjp_isadmin()){
                    $data['uid'] = $cuser->uid();
                    if(in_array('credits', wpjobportal::$_active_addons)){
                        # prepare data + credits
                        if($submissionType == 1){
                            $data['status'] = wpjobportal::$_config->getConfigValue('companyautoapprove');
                        }elseif ($submissionType == 2) {
                            // in case of per listing submission mode
                            $price_check = WPJOBPORTALincluder::getJSModel('credits')->checkIfPriceDefinedForAction('add_company');
                            if($price_check == 1){ // if price is defined then status 3
                                $data['status'] = 3;
                            }else{ // if price not defined then status set to auto approve configuration
                                $data['status'] = wpjobportal::$_config->getConfigValue('companyautoapprove');
                            }
                        }elseif ($submissionType == 3) {
                           $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                            /*Getting Package filter for All Module*/
                            $package = apply_filters('wpjobportal_addons_userpackages_permodule',false,$upakid,$cuser->uid(),'remcompany');
                            if( !$package ){
                                return WPJOBPORTAL_SAVE_ERROR;
                            }
                            if( $package->expired ){
                                return WPJOBPORTAL_SAVE_ERROR;
                            }
                            //if Department are not unlimited & there is no remaining left
                            if( $package->companies!=-1 && !$package->remcompany ){ //-1 = unlimited
                                return WPJOBPORTAL_SAVE_ERROR;
                            }
                            #user packae id--
                            $data['status'] = wpjobportal::$_config->getConfigValue('companyautoapprove');
                            $data['userpackageid'] = $upakid;
                        }
                    }else{
                        $data['status'] = wpjobportal::$_config->getConfigValue('companyautoapprove');
                    }
                }else{
                    if(wpjobportal::$_common->wpjp_isadmin()){
                        if(in_array('credits', wpjobportal::$_active_addons)){
                            if ($submissionType == 3) {
                                if ($data['payment'] == 0) {
                                    $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                                    $data['userpackageid'] = $upakid;
                                } else {
                                    $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                                    /*Getting Package filter for All Module*/
                                    $package = apply_filters('wpjobportal_addons_userpackages_permodule',false,$upakid,$data['uid'],'remcompany');
                                    if( !$package ){
                                        return WPJOBPORTAL_SAVE_ERROR;
                                    }
                                    if( $package->expired ){
                                        return WPJOBPORTAL_SAVE_ERROR;
                                    }
                                    //if Department are not unlimited & there is no remaining left
                                    if( $package->companies!=-1 && !$package->remcompany ){ //-1 = unlimited
                                        return WPJOBPORTAL_SAVE_ERROR;
                                    }
                                    #user packae id--
                                    $data['userpackageid'] = $upakid;
                                }
                            }
                        }
                    }
                }
            }else{ // edit case
                if(!wpjobportal::$_common->wpjp_isadmin()){ // checking if is admin
                    // verify that can current user is editing his owned entity
                    if(!$this->getIfCompanyOwner($id)){
                        // if current entity being edited is not owned by current user dont allow to procced further
                        return false;
                    }
                }
            }
            // admin creating a company with minimum fields (status field is unpublished)
            if(wpjobportal::$_common->wpjp_isadmin()){
                if(!isset($data['status'])){
                    $data['status'] = 1;
                }
            }
            $data['alias'] = wpjobportal::$_common->stringToAlias(empty($data['alias']) ? $data['name'] : $data['alias']);
        # sanitize data
            if(isset($data['description'])){
                $tempdesc = $data['description'];
            }
            $data = wpjobportal::wpjobportal_sanitizeData($data);
            if(isset($data['description'])){
                $data['description'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes($tempdesc)));
            }

            if(WPJOBPORTALincluder::getJSModel('common')->checkLanguageSpecialCase()){
                $data = wpjobportal::$_common->stripslashesFull($data);
            }

        # store in db
            $row = WPJOBPORTALincluder::getJSTable('company');
            if(!($row->bind($data) && $row->check() && $row->store())){
                return false;
            }
            $companyid = $row->id;
            wpjobportal::$_data['id'] = $companyid;
        #store custom fields
        wpjobportal::$_wpjpcustomfield->storeCustomFields(WPJOBPORTAL_COMPANY,$companyid,$data);
        if(in_array('credits', wpjobportal::$_active_addons)){
            if($isnew && $submissionType == 3){
                $trans = WPJOBPORTALincluder::getJSTable('transactionlog');
                $arr = array();
                if (!wpjobportal::$_common->wpjp_isadmin()) {
                    $arr['uid'] = $cuser->uid();
                }elseif (wpjobportal::$_common->wpjp_isadmin()) {
                    $arr['uid'] = $data['uid'];
                }
                $arr['userpackageid'] = $upakid;
                $arr['recordid'] = $row->id;
                $arr['type'] = 'company';
                $arr['created'] = current_time('mysql');
                $arr['status'] = 1;
                $trans->bind($arr);
                $trans->store();
            }
        }

        # store multiple cities with company
            if(isset($data['city'])){
                $this->storeMultiCitiesCompany($data['city'], $companyid);
            }

        # save company logo
            if(isset($data['company_logo_deleted'])){
                $this->deleteCompanyLogoModel($companyid);
            }
            if(isset($_FILES['logo'])){// min field issue
                if ($_FILES['logo']['size'] > 0) {
                    if(!isset($data['company_logo_deleted'])){
                        $this->deleteCompanyLogoModel($companyid);
                    }
                    $res = $this->uploadFile($companyid);
                    if ($res == 6){
                        $msg = WPJOBPORTALMessages::getMessage(WPJOBPORTAL_FILE_TYPE_ERROR, '');
                        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
                    }
                    if($res == 5){
                        $msg = WPJOBPORTALMessages::getMessage(WPJOBPORTAL_FILE_SIZE_ERROR, '');
                        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
                    }
                }
            }

        # send new company email
            if($isnew){
                WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 1, $companyid);
            }
        // action hook for add company
        if(!isset($data['id'])){
            $data['id'] = $row->id;
        }
        do_action('wpjobportal_after_store_company_hook',$data);
        return $companyid;
    }

    function storeMultiCitiesCompany($city_id, $companyid) { // city id comma seprated
        if (!is_numeric($companyid)){
            return false;
        }

        $query = "SELECT cityid FROM " . wpjobportal::$_db->prefix . "wj_portal_companycities WHERE companyid = " . esc_sql($companyid);
        $old_cities = wpjobportaldb::get_results($query);

        $id_array = wpjobportalphplib::wpJP_explode(",", $city_id);
        $row = WPJOBPORTALincluder::getJSTable('companycities');
        $error = array();

        foreach ($old_cities AS $oldcityid) {
            $match = false;
            foreach ($id_array AS $cityid) {
                if ($oldcityid->cityid == $cityid) {
                    $match = true;
                    break;
                }
            }
            if ($match == false) {
                $query = "DELETE FROM " . wpjobportal::$_db->prefix . "wj_portal_companycities WHERE companyid = " . esc_sql($companyid) . " AND cityid=" . esc_sql($oldcityid->cityid);

                if (!wpjobportaldb::query($query)) {
                    $err = wpjobportal::$_db->last_error;
                    $error[] = $err;
                }
            }
        }
        foreach ($id_array AS $cityid) {
            $insert = true;
            foreach ($old_cities AS $oldcityid) {
                if ($oldcityid->cityid == $cityid) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                $cols = array();
                $cols['id'] = "";
                $cols['companyid'] = $companyid;
                $cols['cityid'] = $cityid;
                if (!$row->bind($cols)) {
                    $err = wpjobportal::$_db->last_error;
                    $error[] = $err;
                }
                if (!$row->store()) {
                    $err = wpjobportal::$_db->last_error;
                    $error[] = $err;
                }
            }
        }
        if (empty($error)){
            return true;
        }
        return false;
    }

    function getUidByCompanyId($companyid) {
        if (!is_numeric($companyid))
            return false;
        $query = "SELECT uid FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE id = " . esc_sql($companyid);
        $uid = wpjobportaldb::get_var($query);
        // var_dump($query);
        // die();
        return $uid;
    }

    function deleteCompanies($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('company');
        $notdeleted = 0;
        $mailextradata = array();
        foreach ($ids as $id) {
            if(!is_numeric($id)){
                continue;
            }
            $query = "SELECT company.name,company.contactemail AS contactemail FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company  WHERE company.id = " . esc_sql($id);
            $companyinfo = wpjobportaldb::get_row($query);
            if(empty($companyinfo)){
                continue;
            }
            $mailextradata['companyname'] = $companyinfo->name;
            /*$mailextradata['contactname'] = $companyinfo->contactname;*/
            $mailextradata['contactemail'] = $companyinfo->contactemail;
            if ($this->companyCanDelete($id) == true) {
                if (!$row->delete($id)) {
                    $notdeleted += 1;
                } else {
                    $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` WHERE companyid = " . esc_sql($id);
                    wpjobportaldb::query($query);
                    WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 2, $id,$mailextradata); // 1 for company,2 for delete company

                    $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
                    $wpdir = wp_upload_dir();
                    array_map('wp_delete_file', glob($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo/*.*"));//deleting files
                    if ( ! function_exists( 'WP_Filesystem' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    }
                    global $wp_filesystem;
                    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
                        $creds = request_filesystem_credentials( site_url() );
                        wp_filesystem( $creds );
                    }

                    if(is_dir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo")){
                        @$wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/logo");
                    }
                    array_map('wp_delete_file', glob($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id."/*.*"));//deleting files
                    if ($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id)) {
                        @$wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$id);
                    }
                    // action hook for delete company
                    do_action('wpjobportal_after_delete_company_hook',$id);
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

    function companyCanDelete($companyid) {
        if (!is_numeric($companyid))
            return false;
        if(!wpjobportal::$_common->wpjp_isadmin()){
            if(!$this->getIfCompanyOwner($companyid)){
                return false;
            }
        }
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE companyid = " . esc_sql($companyid) . ")";
                    if(in_array('departments', wpjobportal::$_active_addons)){
                        $query .= " + ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_departments` WHERE companyid = " . esc_sql($companyid) . ")";
                    }
                    $query .= " AS total ";
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function companyEnforceDeletes($companyid) {
        if (empty($companyid))
            return false;
        if (!is_numeric($companyid))
            return false;

        $row = WPJOBPORTALincluder::getJSTable('company');
        $mailextradata = array();
        $query1 = "SELECT company.name,company.contactemail AS contactemail FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company  WHERE company.id = " . esc_sql($companyid);
        $companyinfo = wpjobportaldb::get_row($query1);
        $mailextradata['companyname'] = $companyinfo->name;
        /* $mailextradata['contactname'] = $companyinfo->contactname;*/
        $mailextradata['contactemail'] = $companyinfo->contactemail;
        $query = "DELETE  company,job,companycity";
        if(in_array('departments', wpjobportal::$_active_addons)){
            $query .= " ,department ";
        }
        if(in_array('shortlist', wpjobportal::$_active_addons)){
            $query .= ",jobshortlist";
        }
        // job enforce deleta has this code to remove messages, for the job adding it here to make the data consistent
        if(in_array('message', wpjobportal::$_active_addons)){
            $query .= ",message";
        }
        $query .= " , apply, jobcity
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companycities` AS companycity ON company.id=companycity.companyid ";
                    if(in_array('departments', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_departments` AS department ON company.id=department.companyid";
                    }
        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON company.id=job.companyid";
                    if(in_array('message', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_messages` AS message ON job.id = message.jobid";
                    }
                    if(in_array('shortlist', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobshortlist` AS jobshortlist ON job.id = jobshortlist.jobid";
                    }
        $query .= "
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS apply ON job.id=apply.jobid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON job.id=jobcity.jobid
                    WHERE company.id =" . esc_sql($companyid);
        if (!wpjobportaldb::query($query)) {
            return WPJOBPORTAL_DELETE_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 2, $companyid,$mailextradata); // 1 for company,2 for delete company

        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $file = $wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo/*.*";
        $files = glob($file);
        array_map('wp_delete_file', $files);//deleting files
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }
        if($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo")) {
            $wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid."/logo");
        }
        if($wp_filesystem->exists($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid)) {
            $wp_filesystem->rmdir($wpdir['basedir'] . '/' . $data_directory . "/data/employer/comp_".$companyid);
        }

        // action hook for delete company
        do_action('wpjobportal_after_delete_company_hook',$companyid);

        return WPJOBPORTAL_DELETED;
    }

    function getCompanyForDept() {
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $query = "SELECT id  FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE status = 1 ";
        if ($uid != null) {
            if (!is_numeric($uid))
                return false;
            $query .= " AND uid = " . esc_sql($uid);
        }
        $query .= " ORDER BY id ASC LIMIT 0,1";
        $companies = wpjobportaldb::get_var($query);
        if (wpjobportal::$_db->last_error != null) {
            return false;
        }
        return $companies;
    }

    function getCompanyForCombo($uid = null) {
        $query = "SELECT id, name AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE status = 1 ";
        if ($uid != null) {
            if (!is_numeric($uid))
                return false;
            $query .= " AND uid = " . esc_sql($uid);
        }
        $query .= " ORDER BY id ASC ";
        $companies = wpjobportaldb::get_results($query);
        if (wpjobportal::$_db->last_error != null) {
            return false;
        }
        return $companies;
    }

    function deleteCompanyLogo($companyid = 0){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-company-logo') ) {
            die( 'Security check Failed' );
        }
        if($companyid == 0){
            $companyid = WPJOBPORTALrequest::getVar('companyid');
        }
        if(!is_numeric($companyid)){
            return false;
        }
        if (!current_user_can('manage_options')) { // checking if is admin
            // verify that can current user is editing his owned entity
            if(!$this->getIfCompanyOwner($companyid)){
                // if current entity being edited is not owned by current user dont allow to procced further
                return false;
            }
        }

        $row = WPJOBPORTALincluder::getJSTable('company');
        $data_directory = wpjobportal::$_config->getConfigValue('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/employer/comp_' . $companyid . '/logo';
        $files = glob($path . '/*.*');
        array_map('wp_delete_file', $files);    // delete all file in the direcoty
        $query = "UPDATE `".wpjobportal::$_db->prefix."wj_portal_companies` SET logofilename = '', logoisfile = -1 WHERE id = ".esc_sql($companyid);
        wpjobportal::$_db->query($query);
        return true;
    }

    function deleteCompanyLogoModel($companyid = 0){

        if($companyid == 0){
            $companyid = WPJOBPORTALrequest::getVar('companyid');
        }
        if(!is_numeric($companyid)){
            return false;
        }
        $row = WPJOBPORTALincluder::getJSTable('company');
        $data_directory = wpjobportal::$_config->getConfigValue('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/employer/comp_' . $companyid . '/logo';
        $files = glob($path . '/*.*');
        array_map('wp_delete_file', $files);    // delete all file in the direcoty
        $query = "UPDATE `".wpjobportal::$_db->prefix."wj_portal_companies` SET logofilename = '', logoisfile = -1 WHERE id = ".esc_sql($companyid);
        wpjobportal::$_db->query($query);
        return true;
    }

    function uploadFile($id) {
        $result =  WPJOBPORTALincluder::getObjectClass('uploads')->uploadCompanyLogo($id);
        return $result;
    }

    function approveQueueCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = WPJOBPORTALincluder::getJSTable('company');
        if($row->load($id)){
            $row->columns['status'] = 1;
            if(!$row->store()){
                return WPJOBPORTAL_APPROVE_ERROR;
            }
        }else{
            return WPJOBPORTAL_APPROVE_ERROR;
        }
        //send email
        $company_queue_approve_email = WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 3, $id); // 1 for company, 3 for company approve
        return WPJOBPORTAL_APPROVED;
    }

    function approveQueueFeaturedCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = WPJOBPORTALincluder::getJSTable('company');
        if($row->load($id)){
            $row->columns['isfeaturedcompany'] = 1;
            $startfeatureddate = strtotime($row->startfeatureddate);
            $endfeatureddate = strtotime($row->endfeatureddate);
            $datediff = $endfeatureddate - $startfeatureddate;
            $diff_days = floor($datediff/(60*60*24));
            $row->columns['startfeatureddate'] = gmdate('Y-m-d H:i:s');
            $row->columns['endfeatureddate'] = gmdate('Y-m-d H:i:s',strtotime(" +$diff_days days"));
            if(!$row->store()){
                return WPJOBPORTAL_APPROVE_ERROR;
            }
        }else{
            return WPJOBPORTAL_APPROVE_ERROR;
        }
        //send email
        $company_queue_approve_email = WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 5, $id); // 1 for company, 5 for company featured approve
        return WPJOBPORTAL_APPROVED;
    }

    function rejectQueueCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = WPJOBPORTALincluder::getJSTable('company');
        if (!$row->update(array('id' => $id, 'status' => -1))) {
            return WPJOBPORTAL_REJECT_ERROR;
        }
        //send email
        $company_approve_email = WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 3, $id); // 1 for company, 3 for company reject
        return WPJOBPORTAL_REJECTED;
    }

    function rejectQueueFeatureCompanyModel($id) {
        if (is_numeric($id) == false)
            return false;
        $row = WPJOBPORTALincluder::getJSTable('company');
        if (!$row->update(array('id' => $id, 'isfeaturedcompany' => -1))) {
            return WPJOBPORTAL_REJECT_ERROR;
        }
        //send email
        $company_queue_approve_email = WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 5, $id); // 1 for company, 5 for company featured approve
        return WPJOBPORTAL_REJECTED;
    }


    function approveQueueAllCompaniesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->approveQueueCompanyModel($id);
        return $result;
    }

    function rejectQueueAllCompaniesModel($id, $actionid) {
        /*
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;

        $result = $this->rejectQueueCompanyModel($id);
        return $result;
    }

    function getCompaniesForCombo() {
        $query = "SELECT id, name AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE status = 1 ORDER BY name ASC ";
        $rows = wpjobportaldb::get_results($query);
        return $rows;
    }

    function getUserCompaniesForCombo() {
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        if(!is_numeric($uid)) return false;
        $query = "SELECT id, name AS text FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE uid = " . esc_sql($uid) . " AND status = 1 ORDER BY name ASC ";
        if(!wpjobportal::$_common->wpjp_isadmin()){
            if(!in_array('multicompany', wpjobportal::$_active_addons)){
                $query .= "LIMIT 1";
            }
        }
        $rows = wpjobportaldb::get_results($query);
        return $rows;
    }

    function getCompanynameById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT company.name FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company WHERE company.id = " . esc_sql($id);
        $companyname = wpjobportal::$_db->get_var($query);
        return $companyname;
    }

    function addViewContactDetail($companyid, $uid) {
// made this function funcanality same as multicompany module to handle probelms with membership case
        // if (!is_numeric($companyid))
        //     return false;
        // if (!is_numeric($uid))
        //     return false;
        // // $curdate was undefined
        // $curdate = current_time('mysql');
        // $data = array();
        // $data['uid'] = $uid;
        // $data['companyid'] = $companyid;
        // $data['status'] = 1;
        // $data['created'] = $curdate;
        // $data = wpjobportal::wpjobportal_sanitizeData($data);
        // $row = WPJOBPORTALincluder::getJSTable('jobseekerviewcompany');
        // if (!$row->bind($data)) {
        //     return false;
        // }

        // if ($row->store()) {
        //     return true;
        // }else{
        //     return false;
        // }
        if (!is_numeric($companyid))
            return false;
        if (!is_numeric($uid))
            return false;
        $curdate = gmdate('Y-m-d H:i:s');
        $data = array();
        if(in_array('credits', wpjobportal::$_active_addons)){
            #Submission Type
            $subType = wpjobportal::$_config->getConfigValue('submission_type');
            if ($subType == 3) {
                       #Membershipe Code for Featured Resume
                $packageid = WPJOBPORTALrequest::getVar('wpjobportal_packageid','',0);
                if($packageid == 0){
                    return false;
                }
                # Package Filter's
                $package = apply_filters('wpjobportal_addons_userpackages_perfeaturemodule',false,$packageid,'remcompanycontactdetail');
                if($package && !$package->expired && ($package->companycontactdetail==-1 || $package->remcompanycontactdetail)){ //-1 = unlimited
                    #Data For Featured Company Member
                    $data['uid'] = $uid;
                    $data['companyid'] = $companyid;
                    $data['status'] = 1;
                    $data['created'] = $curdate;
                    $data['userpackageid'] = $package->packageid;
                    #Job sekker Company View
                    $row = WPJOBPORTALincluder::getJSTable('jobseekerviewcompany');
                    if($this->checkAlreadyViewCompanyContactDetail($companyid) == false){
                       if($row->bind($data)){
                            if($row->store()){
                                # Company Contact View Resume Transactio Log Entries--
                                $trans = WPJOBPORTALincluder::getJSTable('transactionlog');
                                $arr = array();
                                $arr['userpackageid'] = $package->id;
                                $arr['uid'] = $uid;
                                $arr['recordid'] = $companyid;
                                $arr['type'] = 'companycontactdetail';
                                $arr['created'] = current_time('mysql');
                                $arr['status'] = 1;
                                $trans->bind($arr);
                                $trans->store();
                               WPJOBPORTALmessages::setLayoutMessage(__('You can view Company Contact Detail Now','wp-job-portal'), 'updated','company');
                                return true;
                            }else{
                                return false;
                            }
                        }
                    }else{
                        return false;
                    }
                }else{
                    // the user does not have nessery package
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
        // In case Of Free
        $data['uid'] = $uid;
        $data['companyid'] = $companyid;
        if(!isset($data['status']) && empty($data['status'])){
            $data['status'] = 1;
        }
        $data['created'] = $curdate;
        $row = WPJOBPORTALincluder::getJSTable('jobseekerviewcompany');
        if (!$row->bind($data)) {
            return false;
        }

        if ($row->store()) {
            return true;
        }else{
            return false;
        }
    }

    function canAddCompany($uid,$actionname='') {
        if (!is_numeric($uid))
            return false;
        if(in_array('credits', wpjobportal::$_active_addons)){
            $credits = apply_filters('wpjobportal_addons_userpackages_module_wise',false,$uid,$actionname);
            return $credits;
        }else{

            return $this->userCanAddCompany($uid);
        }

    }

    function employerHaveCompany($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE uid = " . esc_sql($uid);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == 0) {
            return false;
        } else {
            return true;
        }
    }

    function makeCompanySeo($company_seo , $wpjobportalid){
        //Fareed
        if(empty($company_seo))
            return '';

        $common = wpjobportal::$_common;
        $id = $common->parseID($wpjobportalid);
        if(! is_numeric($id))
            return '';
        $result = '';
        $company_seo = wpjobportalphplib::wpJP_str_replace( ' ', '', $company_seo);
        $company_seo = wpjobportalphplib::wpJP_str_replace( '[', '', $company_seo);
        $array = wpjobportalphplib::wpJP_explode(']', $company_seo);

        $total = count($array);
        if($total > 3)
            $total = 3;

        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'name':
                    $query = "SELECT name AS col FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE id = " . esc_sql($id);
                break;
                case 'category':
                    break;
                    $query = "SELECT category.cat_title AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
                        WHERE company.id = " . esc_sql($id);
                break;
                case 'location':
                    $query = "SELECT company.city AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company WHERE company.id = " . esc_sql($id);
                break;
            }
            if($query){
                $data = wpjobportaldb::get_row($query);
                if(isset($data->col)){
                    if($array[$i] == 'location'){
                        $cityids = wpjobportalphplib::wpJP_explode(',', $data->col);
                        $location = '';
                        for ($j=0; $j < count($cityids); $j++) {
                            if(is_numeric($cityids[$j])){
                                $query = "SELECT name FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` WHERE id = ". esc_sql($cityids[$j]);
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
                        if($location != ''){
                            if($result == '')
                                $result .= wpjobportalphplib::wpJP_str_replace(' ', '-', $location);
                            else
                                $result .= '-'.wpjobportalphplib::wpJP_str_replace(' ', '-', $location);
                        }
                    }else{
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


    function makeCompanySeoDocumentTitle($company_seo , $wpjobportalid){
        if(empty($company_seo))
            return '';

        $common = wpjobportal::$_common;
        $id = $common->parseID($wpjobportalid);
        if(! is_numeric($id))
            return '';
        $result = '';

        $companyname = '';
        $companylocation = '';

        $query = "SELECT name AS companyname, city AS companycity FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE id = " . esc_sql($id);
        $data = wpjobportaldb::get_row($query);
        if(!empty($data)){
            $companylocation = '';
            if($data->companycity != '' && is_numeric($data->companycity)){
                $query = "SELECT name FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` WHERE id = ". esc_sql($data->companycity);
                $companylocation = wpjobportaldb::get_var($query);
            }
            $companyname = $data->companyname;
            $matcharray = array(
                '[name]' => $companyname,
                '[location]' => $companylocation,
                '[separator]' => '-',
                '[sitename]' => get_bloginfo( 'name', 'display' )
            );
            $result = $this->replaceMatches($company_seo,$matcharray);

        }

        return $result;
    }

    function replaceMatches($string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = wpjobportalphplib::wpJP_str_replace($find, $replace, $string);
        }
        return $string;
    }

    function getCompanyExpiryStatus($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT company.id
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
        WHERE company.status = 1
        AND company.id =" . esc_sql($id);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getIfCompanyOwner($id) {
        if (!is_numeric($id))
            return false;
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $query = "SELECT company.id
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company
        WHERE company.uid = " . esc_sql($uid) . "
        AND company.id =" . esc_sql($id);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    function getMessagekey(){
        $key = 'company';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


    function getOrdering() {
        $sort = WPJOBPORTALrequest::getVar('sortby', '', 'posteddesc');
        $this->getListOrdering($sort);
        $this->getListSorting($sort);
    }

    function getListOrdering($sort) {
        switch ($sort) {
            case "namedesc":
                wpjobportal::$_ordering = "company.name DESC";
                wpjobportal::$_sorton = "name";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "nameasc":
                wpjobportal::$_ordering = "company.name ASC";
                wpjobportal::$_sorton = "name";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "categorydesc":
                wpjobportal::$_ordering = "cat.cat_title DESC";
                wpjobportal::$_sorton = "category";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "categoryasc":
                wpjobportal::$_ordering = "cat.cat_title ASC";
                wpjobportal::$_sorton = "category";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "locationdesc":
                wpjobportal::$_ordering = "city.name DESC";
                wpjobportal::$_sorton = "location";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "locationasc":
                wpjobportal::$_ordering = "city.name ASC";
                wpjobportal::$_sorton = "location";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "posteddesc":
                wpjobportal::$_ordering = "company.created DESC";
                wpjobportal::$_sorton = "posted";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "postedasc":
                wpjobportal::$_ordering = "company.created ASC";
                wpjobportal::$_sorton = "posted";
                wpjobportal::$_sortorder = "ASC";
                break;
            default: wpjobportal::$_ordering = "company.created DESC";
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
        wpjobportal::$_sortlinks['name'] = $this->getSortArg("name", $sort);
        wpjobportal::$_sortlinks['category'] = $this->getSortArg("category", $sort);
        wpjobportal::$_sortlinks['location'] = $this->getSortArg("location", $sort);
        wpjobportal::$_sortlinks['posted'] = $this->getSortArg("posted", $sort);
        return;
    }

    function validateUserCompany($companyid,$uid){
        if(!is_numeric($companyid) || !is_numeric($uid)){
            return false;
        }
        $query = "SELECT id FROM `".wpjobportal::$_db->prefix."wj_portal_companies` WHERE uid = ".esc_sql($uid)." AND id = ".esc_sql($companyid);
        $result = wpjobportal::$_db->get_var($query);
        if($result){
            return true;
        }
        return false;
    }

    function getSingleCompanyByUid($uid){
        if(!is_numeric($uid)){
            return false;
        }
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE uid =" . esc_sql($uid)." AND status =1 LIMIT 1";
        $company = wpjobportaldb::get_row($query);
        if($company){
            $company->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($company->city);
        }
        return $company;
    }

    function userCanAddCompany($uid){
        # Check Whether Not More than one
        if(!is_numeric($uid)){
            return false;
        }
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE uid =" . esc_sql($uid);
        $company = wpjobportaldb::get_var($query);
        if($company > 0){
            return false;
        }
        return true;
    }

    function getLogoUrl($companyid,$logofilename){
        $logourl = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
        if(is_numeric($companyid) && !empty($logofilename)){
            $wpdir = wp_upload_dir();
            $dir = wpjobportal::$_config->getConfigValue('data_directory');
            $logourl = $wpdir['baseurl'].'/'.$dir.'/data/employer/comp_'.$companyid.'/logo/'.$logofilename;
        }
        return $logourl;
    }

     function getCompanyDataFromJobForm($jobformdata){
        $companydata = array();
        if(is_array($jobformdata)){
            $companycustomfields = array();
            foreach(wpjobportal::$_wpjpfieldordering->getUserfieldsfor(WPJOBPORTAL_COMPANY) as $field){
                $companycustomfields[] = $field->field;
            }
            foreach($jobformdata as $name => $value){
                if(wpjobportalphplib::wpJP_stristr($name, 'company_')){
                    $companydata[wpjobportalphplib::wpJP_str_replace('company_', '', $name)] = $value;
                }elseif(in_array($name, $companycustomfields)){
                    $companydata[$name] = $value;
                }
            }
        }
        return $companydata;
    }

    // front end coookies search form data
    function getSearchFormDataMyCompany(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchcompany'] = WPJOBPORTALrequest::getVar('searchcompany');
        $jsjp_search_array['wpjobportal-city'] = WPJOBPORTALrequest::getVar('wpjobportal-city');
        $jsjp_search_array['search_from_myapply_mycompanies'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedMyCompany(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_myapply_mycompanies']) && $wpjp_search_cookie_data['search_from_myapply_mycompanies'] == 1){
            $jsjp_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjp_search_array['wpjobportal-city'] = $wpjp_search_cookie_data['wpjobportal-city'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableMyCompany($jsjp_search_array){
        wpjobportal::$_search['mycompany']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : null;
        wpjobportal::$_search['mycompany']['wpjobportal-city'] = isset($jsjp_search_array['wpjobportal-city']) ? $jsjp_search_array['wpjobportal-city'] : null;
    }

    // Admin search cookies form data
    function getSearchFormAdminCompanyData(){
        $jsjp_search_array = array();
        $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton', 'post', 3);
        $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby', 'post', 2);
        //Filters
        $jsjp_search_array['searchcompany'] = WPJOBPORTALrequest::getVar('searchcompany');
        $jsjp_search_array['searchjobcategory'] = WPJOBPORTALrequest::getVar('searchjobcategory');
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status');
        $jsjp_search_array['datestart'] = WPJOBPORTALrequest::getVar('datestart');
        $jsjp_search_array['dateend'] = WPJOBPORTALrequest::getVar('dateend');
         $jsjp_search_array['featured'] = WPJOBPORTALrequest::getVar('featured');
        //Front end search var
        $wpjobportal_company = WPJOBPORTALrequest::getVar('wpjobportal-company');
        $jsjp_search_array['wpjobportal_company'] = wpjobportal::parseSpaces($wpjobportal_company);
        $jsjp_search_array['wpjobportal_city'] = WPJOBPORTALrequest::getVar('wpjobportal-city');
        $jsjp_search_array['search_from_admin_company'] = 1;
        return $jsjp_search_array;
    }

    function getAdminCompanySavedCookies(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_admin_company']) && $wpjp_search_cookie_data['search_from_admin_company'] == 1){
            $jsjp_search_array['sorton'] = $wpjp_search_cookie_data['sorton'];
            $jsjp_search_array['sortby'] = $wpjp_search_cookie_data['sortby'];
            $jsjp_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjp_search_array['searchjobcategory'] = $wpjp_search_cookie_data['searchjobcategory'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
            $jsjp_search_array['datestart'] = $wpjp_search_cookie_data['datestart'];
            $jsjp_search_array['dateend'] = $wpjp_search_cookie_data['dateend'];
            $jsjp_search_array['featured'] = $wpjp_search_cookie_data['featured'];
            $jsjp_search_array['wpjobportal_company'] = $wpjp_search_cookie_data['wpjobportal_company'];
            $jsjp_search_array['wpjobportal_company'] = $wpjp_search_cookie_data['wpjobportal_company'];
            $jsjp_search_array['wpjobportal_city'] = $wpjp_search_cookie_data['wpjobportal_city'];
        }
        return $jsjp_search_array;
    }

    function setAdminCompanySearchVariable($jsjp_search_array){
        wpjobportal::$_search['company']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : 3;
        wpjobportal::$_search['company']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
        wpjobportal::$_search['company']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : '';
        wpjobportal::$_search['company']['searchjobcategory'] = isset($jsjp_search_array['searchjobcategory']) ? $jsjp_search_array['searchjobcategory'] : '';
        wpjobportal::$_search['company']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
        wpjobportal::$_search['company']['datestart'] = isset($jsjp_search_array['datestart']) ? $jsjp_search_array['datestart'] : '';
        wpjobportal::$_search['company']['dateend'] = isset($jsjp_search_array['dateend']) ? $jsjp_search_array['dateend'] : '';
        wpjobportal::$_search['company']['featured'] = isset($jsjp_search_array['featured']) ? $jsjp_search_array['featured'] : '';
        wpjobportal::$_search['company']['wpjobportal_company'] = isset($jsjp_search_array['wpjobportal_company']) ? $jsjp_search_array['wpjobportal_company'] : '';
        wpjobportal::$_search['company']['wpjobportal_city'] = isset($jsjp_search_array['wpjobportal_city']) ? $jsjp_search_array['wpjobportal_city'] : '';
    }

    function getCompaniesForPageBuilderWidget($no_of_companies,$company_type){
        $query = "SELECT company.uid,company.name,CONCAT(company.alias,'-',company.id) AS aliasid,
            company.city, company.created,company.logofilename,
            company.status,company.url,company.id,company.params,company.isfeaturedcompany,company.endfeatureddate,company.description
            FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT cityid FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` WHERE companyid = company.id ORDER BY id DESC LIMIT 1)
            WHERE company.status = 1";
        if($company_type == 2 && in_array('featuredcompanies',wpjobportal::$_active_addons)){
            $query .=" AND company.isfeaturedcompany=1";
        }
        if(is_numeric($no_of_companies)){
            $query .= " ORDER BY company.created DESC LIMIT " . esc_sql($no_of_companies);
        }

        $companies = wpjobportaldb::get_results($query);
        return $companies;
    }

    function getPackagePopupForCompanyContactDetail(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-package-popup-for-company-contact-detail') ) {
            die( 'Security check Failed' );
        }
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            $companyid = WPJOBPORTALrequest::getVar('wpjobportalid');
            $subtype = wpjobportal::$_config->getConfigValue('submission_type');
            #submit type popup for Featured Resume --Listing(Membership)
          // die($subtype);
            if( $subtype != 3 ){
                return false;
            }
            $userpackages = array();
            $pack = apply_filters('wpjobportal_addons_credit_get_Packages_user',false,$uid,'companycontactdetail');
            foreach($pack as $package){
                if($package->companycontactdetail == -1 || $package->remcompanycontactdetail > 0){ //-1 = unlimited
                    $userpackages[] = $package;
                }
            }
            $addonclass = '';
            if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled()){
                $addonclass = ' wjportal-elegant-addon-packages-popup ';
            }
            if (wpjobportal::$theme_chk == 1) {
                $content = '
                <div id="wpj-jp-popup-background" style="display: none;"></div>
                <div id="package-popup" class="wpj-jp-popup-wrp wpj-jp-packages-popup">
                    <div class="wpj-jp-popup-cnt-wrp">
                        <i class="fas fa-times wpj-jp-popup-close-icon" data-dismiss="modal"></i>
                        <h3 class="wpj-jp-popup-heading">
                            '.esc_html__("Select Package",'job-portal-theme').'
                            <div class="wpj-jp-popup-desc">
                                '.esc_html__("Please select a package first",'job-portal-theme').'
                            </div>
                        </h3>
                        <div class="wpj-jp-popup-contentarea">
                            <div class="wpj-jp-packages-wrp">';
                                if(count($userpackages) == 0){
                                    $content .= WPJOBPORTALmessages::showMessage(esc_html__("You do not have any View Company Contact remaining",'job-portal-theme'),'error',1);
                                } else {
                                    foreach($userpackages as $package){
                                        #User Package For Selection in Popup Model --Views
                                        $content .= '
                                            <div class="wpj-jp-pkg-item" id="package-div-'.esc_attr($package->id).'" onclick="selectPackage('.esc_attr($package->id).');">
                                                <div class="wpj-jp-pkg-item-top">
                                                    <h4 class="wpj-jp-pkg-item-title">'.wpjobportal::wpjobportal_getVariableValue( $package->title).'</h4>
                                                </div>
                                                <div class="wpj-jp-pkg-item-mid">
                                                    <div class="wpj-jp-pkg-item-row">
                                                        <span class="wpj-jp-pkg-item-tit">
                                                            '.esc_html__("View Company Contact",'job-portal-theme').' :
                                                        </span>
                                                        <span class="wpj-jp-pkg-item-val">
                                                            '.($package->companycontactdetail==-1 ? esc_html__("Unlimited",'job-portal-theme') : esc_attr($package->companycontactdetail)).'
                                                        </span>
                                                    </div>
                                                    <div class="wpj-jp-pkg-item-row">
                                                        <span class="wpj-jp-pkg-item-tit">
                                                            '.esc_html__("Remaining View Company Contact",'job-portal-theme').' :
                                                        </span>
                                                        <span class="wpj-jp-pkg-item-val">
                                                            '.($package->companycontactdetail==-1 ? esc_html__("Unlimited",'job-portal-theme') : esc_attr($package->remcompanycontactdetail)).'
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="wpj-jp-pkg-item-btm">
                                                    <a href="#" class="wpj-jp-outline-btn wpj-jp-block-btn" onclick="selectPackage('.esc_attr($package->id).');" title="'.esc_attr__("Select Package","job-portal-theme").'">
                                                        '.esc_html__("Select Package","job-portal-theme").'
                                                    </a>
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                            $content .= '</div>
                            <div class="wpj-jp-popup-msgs" id="wjportal-package-message">&nbsp;</div>
                        </div>';
                        // if user does not have any package do not show the button to view company contact detail on popup
                    if(count($userpackages) != 0){
                        $content .= '
                        <div class="wpj-jp-visitor-msg-btn-wrp">
                            <form action="'.esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company','action'=>'wpjobportaltask','task'=>'addviewcontactdetail','wpjobportalid'=>esc_attr($companyid),'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_company_nonce')).'" method="post">
                                <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">
                                <input type="submit" rel="button" id="jsre_featured_button" class="wpj-jp-visitor-msg-btn" value="'.esc_attr__('Show Company Contact','job-portal-theme').'" disabled/>
                            </form>
                        </div>';
                    }
                        $content .= '
                    </div>
                </div>';
            } else {
            $content = '
            <div id="wjportal-popup-background" style="display: none;"></div>
            <div id="package-popup" class="wjportal-popup-wrp wjportal-packages-popup '.$addonclass.'">
                <div class="wjportal-popup-cnt">
                    <img id="wjportal-popup-close-btn" alt="popup cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/popup-close.png">
                    <div class="wjportal-popup-title">
                        '.__("Select Package",'wp-job-portal').'
                        <div class="wjportal-popup-title3">
                            '.__("Please select a package first",'wp-job-portal').'
                        </div>
                    </div>
                    <div class="wjportal-popup-contentarea">
                        <div class="wjportal-packages-wrp">';
                            if(count($userpackages) == 0){
                                $content .= WPJOBPORTALmessages::showMessage(__("You do not have any View Company Contact remaining",'wp-job-portal'),'error',1);
                            } else {
                                foreach($userpackages as $package){
                                    #User Package For Selection in Popup Model --Views
                                    $content .= '
                                        <div class="wjportal-pkg-item" id="package-div-'.esc_attr($package->id).'" onclick="selectPackage('.esc_js($package->id).');">
                                            <div class="wjportal-pkg-item-top">
                                                <div class="wjportal-pkg-item-title">'.esc_html($package->title).'</div>
                                            </div>
                                            <div class="wjportal-pkg-item-btm">
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.__("View Company Contact",'wp-job-portal').' :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->companycontactdetail==-1 ? __("Unlimited",'wp-job-portal') : esc_html($package->companycontactdetail)).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.__("Remaining View Company Contact",'wp-job-portal').' :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->companycontactdetail==-1 ? __("Unlimited",'wp-job-portal') : esc_html($package->remcompanycontactdetail)).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-btn-row">
                                                    <a href="#" class="wjportal-pkg-item-btn">
                                                        '.__("Select Package","wp-job-portal").'
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                                /*$content .= '<div class="wjportal-pkg-help-txt">
                                                '.__("Click on package to select one",'wp-job-portal').'
                                            </div>';*/
                            }
                        $content .= '</div>
                        <div class="wjportal-popup-msgs" id="wjportal-package-message">&nbsp;</div>
                    </div>
                    <div class="wjportal-visitor-msg-btn-wrp">
                        <form action="'.esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company','action'=>'wpjobportaltask','task'=>'addviewcontactdetail','wpjobportalid'=>$companyid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_company_nonce')).'" method="post">
                            <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">

                                <input type="submit" rel="button" id="jsre_featured_button" class="wjportal-visitor-msg-btn" value="'.__('Show Company Contact','wp-job-portal').'" disabled/>
                        </form>
                    </div>
                </div>
            </div>';
            }
            echo wp_kses($content, WPJOBPORTAL_ALLOWED_TAGS);
            exit();
    }

    function getCompanies($only_featured_companies = 0) {

            //Filters
            $searchcompany = isset(wpjobportal::$_search['search_filter']['searchcompany']) ? wpjobportal::$_search['search_filter']['searchcompany']: '';
            //$searchcompcategory = isset(wpjobportal::$_search['search_filter']['searchcompany']) ? wpjobportal::$_search['search_filter']['searchcompany']: '';

            //Front end search var
            $wpjobportal_city = isset(wpjobportal::$_search['search_filter']['wpjobportal_city']) ? wpjobportal::$_search['search_filter']['wpjobportal_city']: '';
            // $formsearch = WPJOBPORTALrequest::getVar('WPJOBPORTAL_form_search', 'post');
            // if ($formsearch == 'WPJOBPORTAL_SEARCH') {
            //     $_SESSION['WPJOBPORTAL_SEARCH']['searchcompany'] = $searchcompany;
            //     $_SESSION['WPJOBPORTAL_SEARCH']['searchcompcategory'] = $searchcompcategory;
            //     $_SESSION['WPJOBPORTAL_SEARCH']['wpjobportal_city'] = $wpjobportal_city;
            // }
            // if (WPJOBPORTALrequest::getVar('pagenum', 'get', null) != null) {
            //     $searchcompany = (isset($_SESSION['WPJOBPORTAL_SEARCH']['searchcompany']) && $_SESSION['WPJOBPORTAL_SEARCH']['searchcompany'] != '') ? sanitize_key($_SESSION['WPJOBPORTAL_SEARCH']['searchcompany']) : null;
            //     $searchcompcategory = (isset($_SESSION['WPJOBPORTAL_SEARCH']['searchcompcategory']) && $_SESSION['WPJOBPORTAL_SEARCH']['searchcompcategory'] != '') ? sanitize_key($_SESSION['WPJOBPORTAL_SEARCH']['searchcompcategory']) : null;
            //     $wpjobportal_city = (isset($_SESSION['WPJOBPORTAL_SEARCH']['wpjobportal_city']) && $_SESSION['WPJOBPORTAL_SEARCH']['wpjobportal_city'] != '') ? sanitize_key($_SESSION['WPJOBPORTAL_SEARCH']['wpjobportal_city']) : null;
            // } elseif ($formsearch !== 'WPJOBPORTAL_SEARCH') {
            //     if (isset($_SESSION['WPJOBPORTAL_SEARCH'])) {
            //         unset($_SESSION['WPJOBPORTAL_SEARCH']);
            //     }
            // }
            // if ($searchcompcategory)
            //     if (is_numeric($searchcompcategory) == false)
            //         return false;
            $inquery = '';
            if ($searchcompany) {
                $inquery = " AND LOWER(company.name) LIKE '%".esc_sql($searchcompany)."%'";
            }
            if ($wpjobportal_city) {
                if(is_numeric($wpjobportal_city)){
                    $inquery .= " AND FIND_IN_SET('" . esc_sql($wpjobportal_city) . "', company.city) > 0 ";
                }else{
                    $arr = wpjobportalphplib::wpJP_explode( ',' , esc_sql($wpjobportal_city));
                    $cityQuery = false;
                    foreach($arr as $i){
                        if($cityQuery){
                            $cityQuery .= " OR FIND_IN_SET('" . esc_sql($i) . "', company.city) > 0 ";
                        }else{
                            $cityQuery = " FIND_IN_SET('" . esc_sql($i) . "', company.city) > 0 ";
                        }
                    }
                    $inquery .= " AND ( $cityQuery ) ";
                }
            }
            // if ($searchcompcategory) {
            //     $inquery .= " AND company.category = " . esc_sql($searchcompcategory);
            // }

            if($only_featured_companies == 1){
                $inquery .= " AND company.isfeaturedcompany = 1 AND DATE(company.endfeatureddate) >= CURDATE() ";
            }

            // this function is used for more than one case ?? not sure atm!!

            // by default these options are set to 0(so the data will be visible.)
            wpjobportal::$_data['shortcode_option_hide_company_logo'] = 0;
            wpjobportal::$_data['shortcode_option_hide_company_name'] = 0;

            wpjobportal::$_ordering = "company.created DESC"; // defult value for ordering(handling without shortocde calls to this function)
            $noofcompanies = '';
            $module_name = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module_name == 'allcompanies'){
                //shortcode attribute proceesing (filter,ordering,no of jobs)
                $attributes_query = $this->processShortcodeAttributesCompany();
                if($attributes_query != ''){
                    $inquery .= $attributes_query;
                }
                if(isset(wpjobportal::$_data['shortcode_option_no_of_companies']) && wpjobportal::$_data['shortcode_option_no_of_companies'] > 0){
                    $noofcompanies = wpjobportal::$_data['shortcode_option_no_of_companies'];
                }
            }

            wpjobportal::$_data['filter']['wpjobportal-city'] = WPJOBPORTALincluder::getJSModel('common')->getCitiesForFilter($wpjobportal_city);
            wpjobportal::$_data['filter']['searchcompany'] = $searchcompany;
        // this field does not exsist
            //wpjobportal::$_data['filter']['searchcompcategory'] = $searchcompcategory;


            //Pagination
            if($noofcompanies == ''){
                $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company WHERE status = 1";
                $query .=$inquery;

                $total = wpjobportaldb::get_var($query);
                wpjobportal::$_data['total'] = $total;
                wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);
            }
            //Data
            $query = "SELECT company.id,company.name,company.logofilename,CONCAT(company.alias,'-',company.id) AS aliasid,company.created,company.serverid,company.city,company.status,company.isfeaturedcompany
                     ,company.endfeatureddate,company.params,company.url,company.contactemail
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
                    WHERE company.status = 1 ";
            $query .= $inquery;
            $query .= " ORDER BY ".wpjobportal::$_ordering;
            if($noofcompanies == ''){
                $query .= " LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
            }elseif(is_numeric($noofcompanies)){
                $query.= " LIMIT " . esc_sql($noofcompanies);
            }

            wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
            $data = array();
            foreach (wpjobportal::$_data[0] AS $d) {
                $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
                $data[] = $d;
            }
            wpjobportal::$_data[0] = $data;
            wpjobportal::$_data['fields'] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsOrderingforView(1);
            wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('company');
            return;
        }

        function processShortcodeAttributesCompany(){
            $inquery = '';
            // cities
            $cities_list = WPJOBPORTALrequest::getVar('locations', 'shortcode_option', false);
            if ($cities_list && $cities_list !='' ) { // not empty check
                $city_array = wpjobportalphplib::wpJP_explode( ',' , esc_sql($cities_list)); // handle multi case
                $cityQuery = false;
                foreach($city_array as $city_id){ // loop over all ids
                    if($city_id != ''){ // null check
                        $city_id = trim($city_id);
                    }
                    if(!is_numeric($city_id)){ // numric check
                        continue;
                    }
                    if($cityQuery){
                        $cityQuery .= " OR FIND_IN_SET('" . esc_sql($city_id) . "', company.city) > 0 ";
                    }else{
                        $cityQuery = " FIND_IN_SET('" . esc_sql($city_id) . "', company.city) > 0 ";
                    }
                }
                $inquery .= " AND ( $cityQuery ) ";
            }

            // employers
            $employer_list = WPJOBPORTALrequest::getVar('employers', 'shortcode_option', false);
            if ($employer_list && $employer_list !='' ) { // not empty check
                $employer_array = wpjobportalphplib::wpJP_explode( ',' , esc_sql($employer_list)); // handle multi case
                $employerQuery = false;
                foreach($employer_array as $employer_id){ // loop over all ids
                    if($employer_id != ''){ // null check
                        $employer_id = trim($employer_id);
                    }
                    if(!is_numeric($employer_id)){ // numric check
                        continue;
                    }
                    if($employerQuery){
                        $employerQuery .= " OR company.uid  = " . esc_sql($employer_id);
                    }else{
                        $employerQuery = " company.uid  =  " . esc_sql($employer_id);
                    }
                }
                $inquery .= " AND ( $employerQuery ) ";
            }

            // company_ids
            $company_list = WPJOBPORTALrequest::getVar('ids', 'shortcode_option', false);
            if ($company_list && $company_list !='' ) { // not empty check
                $company_array = wpjobportalphplib::wpJP_explode( ',' , esc_sql($company_list)); // handle multi case
                $companyQuery = false;
                foreach($company_array as $company_id){ // loop over all ids
                    if($company_id != ''){ // null check
                        $company_id = trim($company_id);
                    }
                    if(!is_numeric($company_id)){ // numric check
                        continue;
                    }
                    if($companyQuery){
                        $companyQuery .= " OR company.id  = " . esc_sql($company_id);
                    }else{
                        $companyQuery = " company.id  =  " . esc_sql($company_id);
                    }
                }
                $inquery .= " AND ( $companyQuery ) ";
            }


            //handle attirbute for ordering
            $sorting = WPJOBPORTALrequest::getVar('sorting', 'shortcode_option', false);
            if($sorting && $sorting != ''){
                $this->makeOrderingQueryFromShortcodeAttributesCompany($sorting);
            }

            //handle attirbute for no of jobs
            $no_of_companies = WPJOBPORTALrequest::getVar('no_of_companies', 'shortcode_option', false);
            if($no_of_companies && $no_of_companies != ''){
                wpjobportal::$_data['shortcode_option_no_of_companies'] = (int) $no_of_companies;
            }


            // handle visibilty of data based on shortcode
            $this->handleDataVisibilityByShortcodeAttributesCompany();
            return $inquery;

        }


        function makeOrderingQueryFromShortcodeAttributesCompany($sorting) {
            switch ($sorting) {
                case "name_desc":
                    wpjobportal::$_ordering = "company.name DESC";
                    break;
                case "name_asc":
                    wpjobportal::$_ordering = "company.name ASC";
                    break;
                case "posted_desc":
                    wpjobportal::$_ordering = "company.created DESC";
                    break;
                case "posted_asc":
                    wpjobportal::$_ordering = "company.created ASC";
                    break;
            }
            return;
        }

        function handleDataVisibilityByShortcodeAttributesCompany() {
            /*
                'hide_filter' => '',
                'hide_filter_job_title' => '',
                'hide_filter_job_location' => '',
            */

            //handle attirbute for hide company logo on all company listing
            $hide_company_logo = WPJOBPORTALrequest::getVar('hide_company_logo', 'shortcode_option', false);
            if($hide_company_logo && $hide_company_logo != ''){
                wpjobportal::$_data['shortcode_option_hide_company_logo'] = 1;
            }

            //handle attirbute for hide company name on all company listing
            $hide_company_name = WPJOBPORTALrequest::getVar('hide_company_name', 'shortcode_option', false);
            if($hide_company_name && $hide_company_name != ''){
                wpjobportal::$_data['shortcode_option_hide_company_name'] = 1;
            }

            //handle attirbute for hide company name on all company listing
            $hide_company_location = WPJOBPORTALrequest::getVar('hide_company_location', 'shortcode_option', false);
            if($hide_company_location && $hide_company_location != ''){
                wpjobportal::$_data['shortcode_option_hide_company_location'] = 1;
            }

        }
}
?>
