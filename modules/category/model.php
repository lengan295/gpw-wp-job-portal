<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALCategoryModel {
    public $class_prefix = '';

    function __construct(){
        if(wpjobportal::$theme_chk == 1){
            $this->class_prefix = 'wpj-jp';
        }elseif(wpjobportal::$theme_chk == 2){
            $this->class_prefix = 'jsjb-jh';
        }
    }

    function getCategorybyId($id,$count_flag = 0) {
        if (is_numeric($id) == false) return false;

        $query = " SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_categories WHERE id = " . esc_sql($id);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);

        if($count_flag == 3 || $count_flag == 2){
            $query = " SELECT count(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                       WHERE job.jobcategory = ".esc_sql($id)." AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() AND job.status = 1 ";
            wpjobportal::$_data[0]->count = wpjobportaldb::get_var($query);
        }else{
            if(wpjobportal::$_data[0] == ''){
                wpjobportal::$_data[0] = new stdClass();
            }
            wpjobportal::$_data[0]->count = -1;
        }
        return;
    }

    function getAllCategories() {
        //Filters
        $categoryname = wpjobportal::$_search['category']['searchname'];
        $status = wpjobportal::$_search['category']['status'];
        $pagesize = absint(WPJOBPORTALrequest::getVar('pagesize'));
        $formsearch = WPJOBPORTALrequest::getVar('WPJOBPORTAL_form_search', 'post');
        if ($formsearch == 'WPJOBPORTAL_SEARCH') {
            update_option( 'wpjobportal_page_size', $pagesize);
        }
        if(get_option( 'wpjobportal_page_size', '' ) != ''){
            $pagesize = get_option( 'wpjobportal_page_size');
        }
        $inquery = '';
        $statusop = 'WHERE parentid = 0';
        $filter_flag = 0;
        if ($categoryname != null) {
            $inquery .= " AND cat_title LIKE '%".esc_sql($categoryname)."%'";
            $statusop = 'WHERE 1 = 1 ';
            $filter_flag = 1;
        }
        if (is_numeric($status)) {
            $statusop = 'WHERE 1 = 1 ';
            $inquery .=" AND isactive = " . esc_sql($status);
            $filter_flag = 1;
        }
        $inquery .= "";

        wpjobportal::$_data['filter']['searchname'] = $categoryname;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['pagesize'] = $pagesize;
        //pagination
        if($pagesize){
           WPJOBPORTALpagination::setLimit($pagesize);
        }
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_categories  $statusop";
        $query .= $inquery;
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);
        //data
        $result = array();
        $prefix = '|-- ';
        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_categories $statusop ";
        $query .= $inquery;
        $query .= " ORDER BY ordering ASC ";
        $categories = wpjobportal::$_db->get_results($query);

        if($filter_flag == 0){
            if (isset($categories)) {
                foreach ($categories as $cat) {
                    $record = (object) array();
                    $record->id = $cat->id;
                    $record->cat_title = $cat->cat_title;
                    $record->alias = $cat->alias;
                    $record->isactive = $cat->isactive;
                    $record->isdefault = $cat->isdefault;
                    $record->ordering = $cat->ordering;
                    $result[] = $record;
                    $this->getCategoryChild($cat->id, $prefix, $result);
                }
            }
        }else{
            foreach ($categories as $cat) {
                if($cat->parentid != 0){
                    $cat->cat_title = '|--'.$cat->cat_title;
                }
                $result[] = (object) $cat;
            }
        }
        $totalresult = count($result);
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($totalresult);

        $finalresult = array();
        WPJOBPORTALpagination::$_limit = WPJOBPORTALpagination::$_limit + WPJOBPORTALpagination::$_offset;
        if (WPJOBPORTALpagination::$_limit >= $totalresult)
            WPJOBPORTALpagination::$_limit = $totalresult;
        for ($i = WPJOBPORTALpagination::$_offset; $i < WPJOBPORTALpagination::$_limit; $i++) {
            $finalresult[] = $result[$i];
        }

        wpjobportal::$_data[0] = $finalresult;
        return;
    }

    private function getCategoryChild($parentid, $prefix, &$result, $for_combo = 0) {

        if (!is_numeric($parentid))
            return false;
        // to handle the case that this function handles sub categories for combo box and admin category listing. in one case we have to show the unpublished
        $is_active_check = '';
        if($for_combo == 1){
            $is_active_check = ' AND category.isactive = 1 ';
        }

        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category WHERE category.parentid = " . esc_sql($parentid);
        $query .= $is_active_check;
        $query .= " ORDER by category.ordering "; // "isactive = 1" check to hide unpublished categories
        $kbcategories = wpjobportal::$_db->get_results($query);
        if (!empty($kbcategories)) {
            foreach ($kbcategories as $cat) {
                $subrecord = (object) array();
                $subrecord->id = $cat->id;
                $subrecord->cat_title = $prefix . wpjobportal::wpjobportal_getVariableValue($cat->cat_title);
                $subrecord->alias = $cat->alias;
                $subrecord->isactive = $cat->isactive;
                $subrecord->isdefault = $cat->isdefault;
                $subrecord->ordering = $cat->ordering;
                $result[] = $subrecord;
                $this->getCategoryChild($cat->id, $prefix . '|-- ', $result, $for_combo);
            }
            return $result;
        }
    }

    function getCategoryForCombobox($themecall=null) {
        $result = array();
        $prefix = '|-- ';
        $query = "SELECT category.* from `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
                    WHERE category.parentid = 0 AND category.isactive = 1 ORDER by category.ordering";
        $knowledgebase = wpjobportal::$_db->get_results($query);
        if (isset($knowledgebase)) {
            foreach ($knowledgebase as $kb) {
                $record = (object) array();
                $record->id = $kb->id;
                $record->cat_title = $kb->cat_title;
                $result[] = $record;
                $this->getCategoryChild($kb->id, $prefix, $result,1); // 4th parameter 1 is for making sure only published categories or subcategories are shown.
            }
        }
        $list = array();
        foreach ($result AS $category) {
            if(null != $themecall){
                //$list[$category->id] = $category->cat_title;
                $list[$category->cat_title] = intval($category->id);
            }else{
                $list[] = (object) array('id' => $category->id, 'text' => $category->cat_title);

            }
        }
        return $list;
    }

    function updateIsDefault($id) {
        if (!is_numeric($id))
            return false;
        //DB class limitations
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_categories` SET isdefault = 0 WHERE id != " . esc_sql($id);
        wpjobportaldb::query($query);
    }

    function validateFormData(&$data) {
        $category = WPJOBPORTALrequest::getVar('parentid');
        $inquery = ' ';
        if (is_numeric($category)) {
            $inquery .=" WHERE parentid = ".esc_sql($category);
        }
        $canupdate = false;
        if ($data['id'] == '') {
            $result = $this->isCategoryExist($data['cat_title']);
            if ($result == true) {
                return WPJOBPORTAL_ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering)+1 AS maxordering FROM " . wpjobportal::$_db->prefix . "wj_portal_categories " . $inquery;
                $data['ordering'] = wpjobportaldb::get_var($query);
                if ($data['ordering'] == null)
                    $data['ordering'] = 1;
            }

            if ($data['isactive'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if (isset($data['isdefault']) AND $data['isdefault'] == 1) {
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

    function storeCategory($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === WPJOBPORTAL_ALREADY_EXIST)
            return WPJOBPORTAL_ALREADY_EXIST;

        if (!empty($data['alias']))
            $cat_title_alias = WPJOBPORTALincluder::getJSModel('common')->removeSpecialCharacter($data['alias']);
        else
            $cat_title_alias = WPJOBPORTALincluder::getJSModel('common')->removeSpecialCharacter($data['cat_title']);

        $cat_title_alias = wpjobportalphplib::wpJP_strtolower(wpjobportalphplib::wpJP_str_replace(' ', '-', $cat_title_alias));
        $cat_title_alias = wpjobportalphplib::wpJP_strtolower(wpjobportalphplib::wpJP_str_replace('/', '-', $cat_title_alias));
        $cat_title_alias = wpjobportalphplib::wpJP_strtolower(wpjobportalphplib::wpJP_str_replace('_', '-', $cat_title_alias));
        $data['alias'] = $cat_title_alias;

        $row = WPJOBPORTALincluder::getJSTable('categories');

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

    function deleteCategories($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('categories');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if ($this->categoryCanDelete($id) == true) {
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

        $row = WPJOBPORTALincluder::getJSTable('categories');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if (!$row->update(array('id' => $id, 'isactive' => $status))) {
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if ($this->categoryCanUnpublish($id)) {
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

    function categoryCanUnpublish($categoryid) {
        if (!is_numeric($categoryid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` WHERE id = " . esc_sql($categoryid) . " AND isdefault = 1)
                    AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function categoryCanDelete($categoryid) {
        if (!is_numeric($categoryid))
            return false;
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE jobcategory = " . esc_sql($categoryid) . ")
                    +( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE job_category = " . esc_sql($categoryid) . ")
                    +( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` WHERE id = " . esc_sql($categoryid) . " AND isdefault = 1)
                    AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCategoryExist($title) {

        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_categories WHERE cat_title = '" . esc_sql($title) . "'";
        $result = wpjobportaldb::get_var($query);
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getCategoriesForCombo() {
        $rows = $this->getCategoryForCombobox();
        return $rows;
    }

    function getsubcategories() {
        $categoryalias = WPJOBPORTALrequest::getVar('category');
        $categoryid = WPJOBPORTALincluder::getJSModel('job')->parseid($categoryalias);
        if (!is_numeric($categoryid))
            return false;
        $query = "SELECT count(cat.id)
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                    WHERE cat.parentid = " . esc_sql($categoryid);
        $count = wpjobportal::$_db->get_var($query);
        $query = "SELECT cat.cat_title
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                    WHERE cat.id = " . esc_sql($categoryid);
        $cat_title = wpjobportal::$_db->get_var($query);
        $config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('category');
        $subcategory_limit = 3;
        if($config_array['subcategory_limit'] != ''){ // to handle float value in configuration
            $subcategory_limit = ceil($config_array['subcategory_limit']);
        }
        $query = "SELECT cat.cat_title, CONCAT(cat.alias,'-',cat.id) AS aliasid,
                    (SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE jobcategory = cat.id) AS totaljobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                    WHERE cat.parentid = " . esc_sql($categoryid) . " ORDER BY cat.ordering ASC LIMIT " . esc_sql($subcategory_limit);
        $result = wpjobportal::$_db->get_results($query);
        $html = '';
        $resume = WPJOBPORTALrequest::getVar('resume');
        if(wpjobportal::$theme_chk == 2){
            $prefix = $this->class_prefix.'-';
            $main_wrap = '';
        }else{
            $prefix = '';
            $main_wrap = 'js';
        }
        if (!empty($result)) {
            $html .= '<div class="'.esc_attr($prefix).esc_attr($main_wrap).'jobs-subcategory-wrapper">';
            foreach ($result AS $cat) {
                if ($resume == 1) {
                    $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumes', 'category'=>$cat->aliasid, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('wpjobportalpageid')));
                } else {
                    $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'category'=>$cat->aliasid, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('wpjobportalpageid')));
                }
                $html .= '  <div class="'.esc_attr($prefix).'category-wrapper" style="width:100%;">
                                <a href="' . $link . '">
                                <div class="'.esc_attr($prefix).'jobs-by-categories-wrapper">
                                    <span class="'.esc_attr($prefix).'title">' . wpjobportal::wpjobportal_getVariableValue($cat->cat_title) . '</span>';
                if ($resume == 1) {
                    if($config_array['categories_numberofresumes'] == 1){
                        $html .= '<span class="'.esc_attr($prefix).esc_html('totat-jobs">)(') . esc_html($cat->totaljobs) . ')</span>';
                    }
                }else{
                    if($config_array['categories_numberofjobs'] == 1){
                        $html .= '<span class="'.esc_attr($prefix).'totat-jobs">(' . esc_html($cat->totaljobs) . ')</span>';
                    }
                }
                $html .=    '</div>
                            </a>
                        </div>';
            }
            if ($count > $subcategory_limit) {
                $html .= '  <div class="showmore-wrapper">
                                <a href="#" class="showmorebutton" data-title="' . esc_attr($cat_title) . '" data-id="' . esc_attr($categoryalias) . '">' . esc_html(__('Show More', 'wp-job-portal')) . '</a>
                            </div>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    private function getAllParentListTillRoot($categoryid,&$parentsarray){
        if(!is_numeric($categoryid)) return false;
        $query = "SELECT id, cat_title, parentid
        FROM `".wpjobportal::$_db->prefix."wj_portal_categories`
        WHERE id = " . esc_sql($categoryid);
        $result = wpjobportal::$_db->get_row($query);
        if($result){
            $parentsarray[$result->id] = $result->cat_title;
            if(is_numeric($result->parentid) && $result->parentid != 0){
                $categoryid = $result->parentid;
                $this->getAllParentListTillRoot($categoryid,$parentsarray);
            }
        }
        return;
    }

    function getsubcategorypopup() {

        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-subcategory-popup') ) {
            die( 'Security check Failed' );
        }
        $category = WPJOBPORTALrequest::getVar('category');
        $categoryid = WPJOBPORTALincluder::getJSModel('job')->parseid($category);
        $config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('category');
        $subcategory_limit = 3;
        if($config_array['subcategory_limit'] != ''){ // to handle float value in configuration
            $subcategory_limit = ceil($config_array['subcategory_limit']);
        }
        $resume = WPJOBPORTALrequest::getVar('resume');
        if (!is_numeric($categoryid))
            return false;
        if($resume == 1){
            $query = "SELECT cat.cat_title, CONCAT(cat.alias,'-',cat.id) AS aliasid,cat.id AS categoryid,
                        (SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE job_category = cat.id AND status = 1 AND searchable = 1) AS totaljobs
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                        WHERE cat.isactive = 1 AND cat.parentid = " . esc_sql($categoryid)
                         ;
        }else{
            $query = "SELECT cat.cat_title, CONCAT(cat.alias,'-',cat.id) AS aliasid,cat.id AS categoryid,
                        (SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS jobs WHERE jobs.jobcategory = cat.id AND DATE(jobs.startpublishing) <= CURDATE() AND DATE(jobs.stoppublishing) >= CURDATE() AND jobs.status = 1) AS totaljobs
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                        WHERE cat.isactive = 1 AND cat.parentid = " . esc_sql($categoryid)
                         ;
        }
        $result = wpjobportal::$_db->get_results($query);
        foreach($result AS $cat_child){
            if($resume == 1){
                $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                    ,(SELECT count(resume.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                        where resume.job_category = category.id AND resume.status = 1)  AS totaljobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
                    WHERE category.isactive = 1 AND category.parentid = ".esc_sql($cat_child->categoryid)." ORDER BY category.ordering ASC LIMIT ".esc_sql($subcategory_limit);
            }else{
                $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                    ,(SELECT count(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                        where job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
                    WHERE category.isactive = 1 AND category.parentid = ".esc_sql($cat_child->categoryid)." ORDER BY category.ordering ASC LIMIT ".esc_sql($subcategory_limit);
            }
            $cat_child->subcat = wpjobportal::$_db->get_results($query);
        }
        $html = '';
        if (!empty($result)) {
            if(wpjobportal::$theme_chk == 1){
                $prefix = $this->class_prefix.'-';
            $html .= '<div class="'.esc_attr($prefix).'by-sub-category">';
                $main_wrap = '';
            }else{
                $prefix = 'wjportal-';
                $main_wrap = 'js';
            $html .= '<div class="'.esc_attr($prefix).'by-sub-catagory">';
            }
            foreach ($result AS $cat) {
                if ($resume == 1) {
                    $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumes', 'category'=>$cat->aliasid, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('page_id')));
                } else {
                    $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'category'=>$cat->aliasid, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('page_id')));
                }
                $html .= '  <div data-id="' . $cat->aliasid . '" class="'.esc_attr($prefix).'by-category-wrp" style="width:50%;">
                                <a href="' . $link . '">
                                <div class="'.esc_attr($prefix).'by-category-item">
                                    <span class="'.esc_attr($prefix).'by-category-item-title">' . wpjobportal::wpjobportal_getVariableValue($cat->cat_title) . '</span>';
                        if ($resume == 1) {
                            if($config_array['categories_numberofresumes'] == 1){
                                $html .= '<span class="'.esc_attr($prefix).'by-category-item-number">(' . esc_html($cat->totaljobs) . ')</span>';
                            }
                        }else{
                            if($config_array['categories_numberofjobs'] == 1){
                                $html .= '<span class="'.esc_attr($prefix).'by-category-item-number">(' . esc_html($cat->totaljobs) . ')</span>';
                            }
                        }
                $html .= '
                                </div>
                                </a>';
                if (!empty($cat->subcat)) {
                    $html .= '<div class="'.esc_attr($prefix).esc_attr($main_wrap).'by-sub-catagory" style="display:none;">';
                    $subcount = 0;
                    foreach ($cat->subcat AS $sub_cat) {
                        if($resume == 1){
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumes', 'category'=>$sub_cat->aliasid, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('page_id')));
                        }else{
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'category'=>$sub_cat->aliasid, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('page_id')));
                        }
                        $html .= '  <div class="'.esc_attr($prefix).'by-category-wrp" style="width:100%;">
                                        <a href="' . $link . '">
                                        <div class="'.esc_attr($prefix).'by-category-item">
                                            <span class="'.esc_attr($prefix).'by-category-item-title">' . wpjobportal::wpjobportal_getVariableValue($sub_cat->cat_title) . '</span>';
                        if ($resume == 1) {
                            if($config_array['categories_numberofresumes'] == 1){
                                $html .= '<span class="'.esc_attr($prefix).'by-category-item-number">(' . $sub_cat->totaljobs . ')</span>';
                            }
                        }else{
                            if($config_array['categories_numberofjobs'] == 1){
                                $html .= '<span class="'.esc_attr($prefix).'by-category-item-number">(' . $sub_cat->totaljobs . ')</span>';
                            }
                        }
                        $html .=    '</div>
                                    </a>
                                </div>';
                        $subcount++;
                    }
                    if ($subcount >= $subcategory_limit) {
                        $html .= '  <div class="'.esc_attr($prefix).'by-category-item-btn">
                                        <a href="#" class="'.esc_attr($prefix).'wjportal-by-category-item-btn-wrp" onclick="getPopupAjax(\'' . $cat->aliasid . '\', \'' . wpjobportal::wpjobportal_getVariableValue($cat->cat_title) . '\');">' . esc_html(__('Show More', 'wp-job-portal')) . '</a>
                                    </div>';
                    }
                    $html .= '</div>';
                }

                $html .= '</div>';
            }
            $html .= '</div>';
        }
        // Navigation get all parents
        $parentsarray = array();
        $this->getAllParentListTillRoot($categoryid,$parentsarray);
        if(!empty($parentsarray)){
            if(wpjobportal::$theme_chk == 1){
                $prefix = $this->class_prefix.'-';
            }else{
                $prefix = 'wjportal-';
            }
            $html .= '<ul class="'.esc_attr($prefix).'popup-navigation">';
            foreach($parentsarray AS $pcatid => $pcattitle){
                $html .= '<li onclick="getPopupAjax('.$pcatid.',\''.wpjobportal::wpjobportal_getVariableValue($pcattitle).'\');">'.wpjobportal::wpjobportal_getVariableValue($pcattitle).'</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
    function getDefaultCategoryId() {

        $query = "SELECT id FROM " . wpjobportal::$_db->prefix . "wj_portal_categories WHERE isdefault = 1";
        $id = wpjobportaldb::get_var($query);
        return $id;
    }

    function getTitleByCategory($id) {
        if(!is_numeric($id)) return false;
        $query = "SELECT cat_title FROM " . wpjobportal::$_db->prefix . "wj_portal_categories WHERE id = " . esc_sql($id);
        $title = wpjobportaldb::get_var($query);
        return $title;
    }

    function getMessagekey(){
        $key = 'category';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function getTopCategories($limit){
        $query = "SELECT category.id,category.cat_title AS title
            ,(SELECT count(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                where job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() AND job.status = 1)  AS totaljobs
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
            WHERE category.isactive = 1 having totaljobs > 0 ORDER BY totaljobs DESC LIMIT ".esc_sql($limit);
        $data = wpjobportal::$_db->get_results($query);
        return $data;
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
            $row = WPJOBPORTALincluder::getJSTable('categories');
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
            $row->update(array('id' => $sorted_array[$i], $ordering_coloumn => ($page_multiplier + $i +1) ));//+1 to handle 0
        }
        WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Ordering updated', 'wp-job-portal')), 'updated', $this->getMessagekey());
        return ;
    }

    //search cookies data
    function getSearchFormDataCategory(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchname'] = WPJOBPORTALrequest::getVar('searchname');
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status');
        $jsjp_search_array['search_from_category'] = 1;
        return $jsjp_search_array;
    }

    function getCookiesSavedCategory(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_category']) && $wpjp_search_cookie_data['search_from_category'] == 1){
            $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
            $jsjp_search_array['status'] = $wpjp_search_cookie_data['status'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableCategory($jsjp_search_array){
        wpjobportal::$_search['category']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : null;
        wpjobportal::$_search['category']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : null;
    }
}

?>
