<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALslugModel {

    private $_params_flag;
    private $_params_string;

    function __construct() {
        $this->_params_flag = 0;
    }

    function getSlug() {
        // Filter
        $slug = wpjobportal::$_search['slug']['slug'];

        $inquery = '';
        if ($slug != null){
            $inquery .= " AND slug.slug LIKE '%".esc_sql($slug)."%'";
        }
        wpjobportal::$_data['slug'] = $slug;

        //pagination
        $query = "SELECT COUNT(id) FROM ".wpjobportal::$_db->prefix."wj_portal_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $total = wpjobportaldb::get_var($query);

        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT *
                  FROM ".wpjobportal::$_db->prefix ."wj_portal_slug AS slug WHERE slug.status = 1 ";
        $query .= $inquery;
        $query .= " LIMIT " . WPJOBPORTALpagination::$_offset . " , " . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);

        return;
    }


    function storeSlug($data) {
        if (empty($data)) {
            return false;
        }
        $row = WPJOBPORTALincluder::getJSTable('slug');
        foreach ($data as $id => $slug) {
            if($id != '' && is_numeric($id)){
                $slug = sanitize_title($slug);
                if($slug != ''){
                    $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_slug
                            WHERE slug = '" . esc_sql($slug)."' ";
                    $slug_flag = wpjobportaldb::get_var($query);
                    if($slug_flag > 0){
                        continue;
                    }else{
                        $row->update(array('id' => $id, 'slug' => $slug));
                    }
                }
            }
        }
        update_option('rewrite_rules', '');
        return WPJOBPORTAL_SAVED;
    }

    function savePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = sanitize_title($data['prefix']);
        if($data['prefix'] == ''){
            return WPJOBPORTAL_SAVE_ERROR;
        }
        $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_config
                    SET configvalue = '".esc_sql($data['prefix'])."'
                    WHERE configname = 'slug_prefix'";
        if(wpjobportaldb::query($query)){
             update_option('rewrite_rules', '');
            return WPJOBPORTAL_SAVED;
        }else{
             update_option('rewrite_rules', '');
            return WPJOBPORTAL_SAVE_ERROR;
        }
    }

    function saveHomePrefix($data) {
        if (empty($data)) {
            return false;
        }
        $data['prefix'] = sanitize_title($data['prefix']);
        if($data['prefix'] == ''){
            return WPJOBPORTAL_SAVE_ERROR;
        }
        $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_config
                    SET configvalue = '".esc_sql($data['prefix'])."'
                    WHERE configname = 'home_slug_prefix'";
        if(wpjobportaldb::query($query)){
            update_option('rewrite_rules', '');
            return WPJOBPORTAL_SAVED;
        }else{
             update_option('rewrite_rules', '');
            return WPJOBPORTAL_SAVE_ERROR;
        }
    }

    function resetAllSlugs() {
        $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_slug
                    SET slug = defaultslug ";
        if(wpjobportaldb::query($query)){
            update_option('rewrite_rules', '');
            return WPJOBPORTAL_SAVED;
        }else{
             update_option('rewrite_rules', '');
            return WPJOBPORTAL_SAVE_ERROR;
        }
    }

    function getOptionsForEditSlug() {

        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-options-for-edit-slug') ) {
            die( 'Security check Failed' );
        }
        $slug = WPJOBPORTALrequest::getVar('slug');
        $html = '<span class="popup-top">
                    <span id="popup_title" >' . esc_html(__("Edit","wp-job-portal"))." ". esc_html(__("Slug", "wp-job-portal")) . '</span>
                        <img id="popup_cross" alt="popup cross" onClick="closePopup();" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/popup-close.png"></span>';

        $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . esc_html(__('Slug','wp-job-portal')).' '. esc_html(__('Name', 'wp-job-portal')) . ' <span style="color: red;"> *</span></div>
                         <div class="popup-field-obj">' . WPJOBPORTALformfield::text('slugedit', isset($slug) ? wpjobportalphplib::wpJP_trim($slug) : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        $html .='<div class="popup-act-btn-wrp">
                    ' . WPJOBPORTALformfield::button('save', esc_html(__('Save', 'wp-job-portal')), array('class' => 'button savebutton popup-act-btn','onClick'=>'getFieldValue();'));
        $html .='</div>';
        return wp_json_encode($html);
    }

    function getDefaultSlugFromSlug($layout) {
        $query = "SELECT  defaultslug FROM `".wpjobportal::$_db->prefix."wj_portal_slug` WHERE slug = '".esc_sql($layout)."'";
        $val = wpjobportal::$_db->get_var($query);
        return sanitize_title($val);
    }

    function getSlugFromFileName($layout,$module) {
        $where_query = '';
        if($layout == 'controlpanel'){
            if($module == 'jobseeker'){
                $where_query = " AND defaultslug = 'jobseeker-control-panel'";
            }elseif($module == 'employer'){
                $where_query = " AND defaultslug = 'employer-control-panel'";
            }
        }
        if($layout == 'mystats'){
            if($module == 'jobseeker'){
                $where_query = " AND defaultslug = 'jobseeker-my-stats'";
            }elseif($module == 'employer'){
                $where_query = " AND defaultslug = 'employer-my-stats'";
            }
        }
        $query = "SELECT slug FROM `".wpjobportal::$_db->prefix."wj_portal_slug` WHERE filename = '".esc_sql($layout)."' ".$where_query;
        $val = wpjobportal::$_db->get_var($query);
        return $val;
    }

    function getSlugString($home_page = 0) {

            //$query = "SELECT slug AS value, pkey AS akey FROM `".wpjobportal::$_db->prefix."wj_portal_slug`";
            global $wp_rewrite;
            $rules = wp_json_encode($wp_rewrite->rules);
            $query = "SELECT slug AS value FROM `".wpjobportal::$_db->prefix."wj_portal_slug`";
            $val = wpjobportal::$_db->get_results($query);
            $string = '';
            $bstring = '';
            //$rules = wp_json_encode($rules);
            $prefix = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
            $homeprefix = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
            foreach ($val as $slug) {
                    if($home_page == 1){
                        $slug->value = $homeprefix.$slug->value;
                    }
                    if(wpjobportalphplib::wpJP_strpos($rules,$slug->value) === false){
                        $string .= $bstring. $slug->value;
                    }else{
                        $string .= $bstring.$prefix. $slug->value;
                    }
                $bstring = '|';
            }
        return $string;
    }

    function getRedirectCanonicalArray() {
        global $wp_rewrite;
        $slug_prefix = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
        $homeprefix = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
        $rules = wp_json_encode($wp_rewrite->rules);
        $query = "SELECT slug AS value FROM `".wpjobportal::$_db->prefix."wj_portal_slug`";
        $val = wpjobportal::$_db->get_results($query);
        $string = array();
        $bstring = '';
        foreach ($val as $slug) {
            $slug->value = $homeprefix.$slug->value;
            $string[] = $bstring.$slug->value;
            $bstring = '/';
        }
        return $string;
    }

    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['slug'] = WPJOBPORTALrequest::getVar("slug");
        $jsjp_search_array['search_from_slug'] = 1;
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
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_slug']) && $wpjp_search_cookie_data['search_from_slug'] == 1){
            $jsjp_search_array['slug'] = $wpjp_search_cookie_data['slug'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        wpjobportal::$_search['slug']['slug'] = isset($jsjp_search_array['slug']) ? $jsjp_search_array['slug'] : '';
    }

    function getMessagekey(){
        $key = 'slug';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
