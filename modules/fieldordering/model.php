<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALFieldorderingModel {

    function __construct() {

    }

    function fieldsRequiredOrNot($ids, $value) {
        if (empty($ids))
            return false;
        if (!is_numeric($value))
            return false;
        //Db class limitations
        $total = 0;
        foreach ($ids as $id) {
            if(is_numeric($id) && is_numeric($value)){
                $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering SET required = " . esc_sql($value) . " WHERE id = " . esc_sql($id) . " AND sys=0";
                if (false === wpjobportaldb::query($query)) {
                    $total += 1;
                }
            }else{
                $total += 1;
            }
        }
        if ($total == 0) {
            WPJOBPORTALMessages::$counter = false;
            if ($value == 1)
                return WPJOBPORTAL_REQUIRED;
            else
                return WPJOBPORTAL_NOT_REQUIRED;
        }else {
            WPJOBPORTALMessages::$counter = $total;
            if ($value == 1)
                return WPJOBPORTAL_REQUIRED_ERROR;
            else
                return WPJOBPORTAL_NOT_REQUIRED_ERROR;
        }
    }

    function getFieldsOrdering($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $title = wpjobportal::$_search['customfield']['title'];
        $ustatus = wpjobportal::$_search['customfield']['ustatus'];
        $vstatus = wpjobportal::$_search['customfield']['vstatus'];
        $required = wpjobportal::$_search['customfield']['required'];
        $inquery = '';
        if ($title != null)
            $inquery .= " AND field.fieldtitle LIKE '%".esc_sql($title)."%'";
        if (is_numeric($ustatus))
            $inquery .= " AND field.published = ".esc_sql($ustatus);
        if (is_numeric($vstatus))
            $inquery .= " AND field.isvisitorpublished = ".esc_sql($vstatus);
        if (is_numeric($required))
            $inquery .= " AND field.required =". esc_sql($required);

        wpjobportal::$_data['filter']['title'] = $title;
        wpjobportal::$_data['filter']['ustatus'] = $ustatus;
        wpjobportal::$_data['filter']['vstatus'] = $vstatus;
        wpjobportal::$_data['filter']['required'] = $required;

        //Pagination
        $query = "SELECT COUNT(field.id) FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field WHERE field.fieldfor = " . esc_sql($fieldfor);
        $query .= $inquery;
        if($fieldfor == 3){
            $query .= " AND field.field NOT IN ('heighestfinisheducation','employer_supervisor','resume','section_resume')";
             if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                $query .= " AND field.section = 1";
            }
        }else if($fieldfor == 2){
            $query .= " AND field.field NOT IN ('sendmeresume','sendemail')";
        }
        // if(!in_array('customfield', wpjobportal::$_active_addons)){
        //     $query .= " AND (userfieldtype = '' OR userfieldtype = 'text' OR userfieldtype = 'email')";
        // }
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT field.*
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = " . esc_sql($fieldfor);
        $query .= $inquery;
        // if(!in_array('customfield', wpjobportal::$_active_addons)){
        //     $query .= " AND (userfieldtype = '' OR userfieldtype = 'text' OR userfieldtype = 'email')";
        // }
        if($fieldfor == 3){
            $query .= " AND field.field NOT IN ('heighestfinisheducation','employer_supervisor','resume','section_resume')";
             if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                $query .= " AND field.section = 1";
            }
        }else if($fieldfor == 2){
            $query .= " AND field.field NOT IN ('sendmeresume','sendemail')";
        }

        $query .= ' ORDER BY';
        if ($fieldfor == 3){
            //$query .=' field.section ASC, field.is_section_headline desc, field.ordering asc';
            $query .=' field.is_section_headline desc, field.ordering asc';
        }else{
            $query .= ' field.ordering';
        }
        if ($fieldfor == 3){
             $resumefieldsobject_arr = array();
            $query = "SELECT field.*
                        FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                        WHERE field.fieldfor = " . esc_sql($fieldfor);
                $query .= " AND field.is_section_headline = 1";
                $query .= " AND field.field NOT IN ('heighestfinisheducation','employer_supervisor','resume','section_resume')";
                if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                    $query .= " AND field.section = 1 ";
                }
                $query .= ' ORDER BY';
                $query .=' field.ordering';
          $sections = wpjobportaldb::get_results($query);
            foreach ($sections as $section) {
                $query = "SELECT field.*
                            FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                            WHERE field.fieldfor = " . esc_sql($fieldfor);
                $query .= " AND field.section = ".$section->section;
                $query .= " AND (field.is_section_headline IS NULL  || field.is_section_headline = 0) "; // to not fetch sections again
                $query .= $inquery;
                $query .= " AND field.field NOT IN ('heighestfinisheducation','employer_supervisor','resume','section_resume')";
                 if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                    $query .= " AND field.section = 1";
                }
                $query .= ' ORDER BY field.ordering ASC'; // to show section fields below the
                //$query .=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
                    //echo var_dump($query);
                $section_fields = wpjobportaldb::get_results($query);

                    $resumefieldsobject_arr[]  = $section;
                    foreach($section_fields as $row){
                        $resumefieldsobject = new stdClass();
                        $resumefieldsobject = $row;
                        $resumefieldsobject_arr[] = $resumefieldsobject;
                    } 
            }
            //echo '<pre>';print_r($resumefieldsobject_arr);echo '</pre>';
            wpjobportal::$_data[0] = $resumefieldsobject_arr;

//echo $query;        

        }else{
            
            //$query .=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
            wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        }
        return;
    }

    function getSearchFieldsOrdering($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $search = WPJOBPORTALrequest::getVar('search','',0);
        $inquery = '';
        $inquery .= " AND field.cannotsearch = 0";
        // the below code was causing problem for the case of search disablde fields
        // if ($search == 0){
        //     $inquery .= " AND (field.search_user  = 1 OR field.search_visitor = 1 ) ";
        // }
        wpjobportal::$_data['filter']['search'] = $search;
        //Data
        $query = "SELECT field.fieldtitle,field.id,field.search_user,field.search_visitor,field.ordering
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = " . esc_sql($fieldfor);
        $query .= $inquery;
        // hide resume sub section fields from search
        if($fieldfor == 3){
            $query .= " AND field.section = 1 ";
        }
        $query .= ' ORDER BY';
        $query .= ' field.search_ordering,field.ordering ';// "field.ordering" to handle the case of new install when search ordering cloumn is set null

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        return;
    }

    function getFieldsOrderingforForm($fieldfor) {
        if (is_numeric($fieldfor) == false){
            return false;
        }
        $published = (WPJOBPORTALincluder::getObjectClass('user')->isguest()) ? "isvisitorpublished" : "published";
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering`
        WHERE $published = 1 AND fieldfor = " . esc_sql($fieldfor) . " ORDER BY";
        if ($fieldfor == 3) // for resume it must be order by section and ordering
            $query.=" section , ";
        $query.=" ordering ASC";
        $fields = array();
       // var_dump($query);
        foreach(wpjobportaldb::get_results($query) as $field){
            $field->validation = $field->required == 1 ? 'required' : '';
            $fields[$field->field] = $field;
        }
        if ($fieldfor == 3){
            $resumefields = array();
            $query = "SELECT field.*
                        FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                        WHERE field.fieldfor = " . esc_sql($fieldfor);
            $query .= " AND field.is_section_headline = 1";
                $query .= ' ORDER BY';
                $query .=' field.ordering';
          $sections = wpjobportaldb::get_results($query);
            foreach ($sections as $section) {
                $query = "SELECT field.*
                            FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                            WHERE $published = 1 AND field.fieldfor = " . esc_sql($fieldfor);
                $query .= " AND field.section = ".$section->section;
                $query .= ' ORDER BY';
                $query .=' field.ordering';
                $section_fields = wpjobportaldb::get_results($query);
                //echo "<pre>";
                //print_r($section_fields);
                    foreach($section_fields as $field){
                        $field->validation = $field->required == 1 ? 'required' : '';
                        $resumefields[$field->field] = $field;
                    } 
            }
            return $resumefields;
        }

        return $fields;
    }

    function getFieldsOrderingforSearch($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $published = ' AND search_visitor = 1 ';
        } else {
            $published = ' AND search_user = 1 ';
        }
        // to hide resume sub section fields from search
        $section_query = '';
        if($fieldfor == 3){
            $section_query = ' AND section = 1 ';
        }
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering`
                 WHERE cannotsearch = 0 AND  fieldfor = " . esc_sql($fieldfor) . esc_sql($published) . esc_sql($section_query) . " ORDER BY search_ordering ";
        $rows = wpjobportaldb::get_results($query);
        return $rows;
    }

    function getFieldsOrderingforView($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $published = (WPJOBPORTALincluder::getObjectClass('user')->isguest()) ? "isvisitorpublished" : "published";
        $query = "SELECT field,fieldtitle FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering`
                WHERE ".esc_sql($published)." = 1 AND fieldfor =  " . esc_sql($fieldfor) . " ORDER BY";
        if ($fieldfor == 3) // fields for resume
            $query.=" section ,";
        $query.=" ordering ASC";
        $rows = wpjobportaldb::get_results($query);
        $return = array();

//had make changes impliment fieldtitle in view compnay
        // if($fieldfor == 3){
        //     foreach ($rows AS $row) {
        //         $return[$row->field] = $row->required;
        //     }
        // }else{
            foreach ($rows AS $row) {
                $return[$row->field] = $row->fieldtitle;
            }
        // }
        if ($fieldfor == 3){
            $resumefields = array();
            $query = "SELECT field.*
                        FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                        WHERE field.fieldfor = " . esc_sql($fieldfor);
            $query .= " AND field.is_section_headline = 1";
                $query .= ' ORDER BY';
                $query .=' field.ordering';
          $sections = wpjobportaldb::get_results($query);
            foreach ($sections as $section) {
                $query = "SELECT field.*
                            FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                            WHERE $published = 1 AND field.fieldfor = " . esc_sql($fieldfor);
                $query .= " AND field.section = ".$section->section;
                $query .= ' ORDER BY';
                $query .=' field.ordering';
                $section_fields = wpjobportaldb::get_results($query);
                    foreach($section_fields as $field){
                        $field->validation = $field->required == 1 ? 'required' : '';
                        $resumefields[$field->field] = $field;
                    } 
            }
            return $resumefields;
        }

        return $return;
    }

    function getPublishedResumeSections(){
        // to hide disabled sections
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $published = ' AND field.isvisitorpublished = 1 ';
        } else {
            $published = ' AND field.published = 1 ';
        }
        $query = "SELECT field.*
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = 3
                    AND field.is_section_headline = 1 ";
        $query .= $published;
        if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
            $query .= " AND field.field = 'section_personal'"; // for free version
        }
        $query .= ' ORDER BY field.ordering ASC ';
        $sections = wpjobportaldb::get_results($query);
        $section_title_array = array();
        foreach($sections AS $section){
            $section_title_array[$section->field] = $section->fieldtitle;
        }
        return $section_title_array;
    }

    function getResumeSections(){
        $query = "SELECT field.*
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = 3
                    AND field.is_section_headline = 1 AND field.field != 'section_resume' ";
        if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
            $query .= " AND field.field = 'section_personal'"; // for free version
        }
        $query .= ' ORDER BY field.ordering';
        $sections = wpjobportaldb::get_results($query);
        return $sections;
    }

    function getResumeCustomSections(){
        $query = "SELECT field.field,field.section
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = 3
                    AND field.is_section_headline = 1 AND field.field != 'section_resume' AND field.section > 8 ";// 8 is for language section greater then 8 means custom sections
        if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
            $query .= " AND field.field = 'section_personal'"; // for free version
        }
        $sections = wpjobportaldb::get_results($query);
        return $sections;
    }

    function getResumeCustomSectionsFields(){
        $query = "SELECT field.field,field.section,field.userfieldtype
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = 3
                    AND field.is_section_headline = 1 AND field.field != 'section_resume' AND field.section > 8 ";// 8 is for language section greater then 8 means custom sections
        if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
            $query .= " AND field.field = 'section_personal'"; // for free version
        }
        $sections = wpjobportaldb::get_results($query);
        return $sections;
    }

    function getResumeCustomSectionFields($section){
        if(!is_numeric($section)){
            return false;
        }
        $query = "SELECT field.field,field.fieldtitle,field.section,field.is_section_headline
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = 3
                    AND  field.section = ".esc_sql($section)." ORDER BY ordering ASC ";

        $sections = wpjobportaldb::get_results($query);
        return $sections;
    }

    function getResumeCustomSectionFromSectionField($section_field){
        $query = "SELECT field.section
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS field
                    WHERE field.fieldfor = 3
                    AND  field.field = '".esc_sql($section_field)."'";

        $section = wpjobportaldb::get_var($query);
        return $section;
    }

    function fieldsPublishedOrNot($ids, $value) {
        if (empty($ids))
            return false;
        if (!is_numeric($value))
            return false;

        $total = 0;
        foreach ($ids as $id) {
            if(is_numeric($id) && is_numeric($value)){
                $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering SET published = " . esc_sql($value) . " WHERE id = " . esc_sql($id) . " AND cannotunpublish=0";
                if (false === wpjobportaldb::query($query)) {
                    $total += 1;
                }
            }else{
                $total += 1;
            }
        }
        if ($total == 0) {
            WPJOBPORTALMessages::$counter = false;
            if ($value == 1)
                return WPJOBPORTAL_PUBLISHED;
            else
                return WPJOBPORTAL_UN_PUBLISHED;
        }else {
            WPJOBPORTALMessages::$counter = $total;
            if ($value == 1)
                return WPJOBPORTAL_PUBLISH_ERROR;
            else
                return WPJOBPORTAL_UN_PUBLISH_ERROR;
        }
    }

    function visitorFieldsPublishedOrNot($ids, $value) {
        if (empty($ids))
            return false;
        if (!is_numeric($value))
            return false;
        $total = 0;
        foreach ($ids as $id) {
            if(is_numeric($id) && is_numeric($value)){
                $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering SET isvisitorpublished = " . esc_sql($value) . " WHERE id = " . esc_sql($id) . " AND cannotunpublish=0";
                if (false === wpjobportaldb::query($query)) {
                    $total += 1;
                }
            }else{
                $total += 1;
            }
        }
        if ($total == 0) {
            WPJOBPORTALMessages::$counter = false;
            if ($value == 1)
                return WPJOBPORTAL_PUBLISHED;
            else
                return WPJOBPORTAL_UN_PUBLISHED;
        }else {
            WPJOBPORTALMessages::$counter = $total;
            if ($value == 1)
                return WPJOBPORTAL_PUBLISH_ERROR;
            else
                return WPJOBPORTAL_UN_PUBLISH_ERROR;
        }
    }

    /*function fieldOrderingUp($field_id) {
        if (is_numeric($field_id) == false)
            return false;
        $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS f1, " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS f2
                SET f1.ordering = f1.ordering + 1
                WHERE f1.ordering = f2.ordering - 1
                AND f1.fieldfor = f2.fieldfor
                AND f2.id = " . esc_sql($field_id);

        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_UP_ERROR;
        }

        $query = " UPDATE " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering
                    SET ordering = ordering - 1
                    WHERE id = " . esc_sql($field_id);

        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_UP_ERROR;
        }
        return WPJOBPORTAL_ORDER_UP;
    }

    function fieldOrderingDown($field_id) {
        if (is_numeric($field_id) == false)
            return false;

        $query = "UPDATE " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS f1, " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering AS f2
                    SET f1.ordering = f1.ordering - 1
                    WHERE f1.ordering = f2.ordering + 1
                    AND f1.fieldfor = f2.fieldfor
                    AND f2.id = " . esc_sql($field_id);

        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_DOWN_ERROR;
        }

        $query = " UPDATE " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering
                    SET ordering = ordering + 1
                    WHERE id = " . esc_sql($field_id);

        if (false == wpjobportaldb::query($query)) {
            return WPJOBPORTAL_ORDER_DOWN_ERROR;
        }
        return WPJOBPORTAL_ORDER_DOWN;
    }*/

    function storeUserField($data) {
        if (empty($data)) {
            return false;
        }
        if (!is_numeric($data['fieldfor'])) {
            return false;
        }

        $row = WPJOBPORTALincluder::getJSTable('fieldsordering');
        if ($data['isuserfield'] == 1) {
            // value to add as field ordering
            if ($data['id'] == '') { // only for new
                $query = "SELECT max(ordering) FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = " . esc_sql($data['fieldfor']);
                $var = wpjobportaldb::get_var($query);
                $data['ordering'] = $var + 1;
                // search ordering code //
                $query = "SELECT max(ordering) FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = " . esc_sql($data['fieldfor']);
                $var = wpjobportaldb::get_var($query);
                $data['search_ordering'] = $var + 1;

                $data['cannotsearch'] = 0;
                $query = "SELECT max(id) FROM `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` ";
                $maxid = wpjobportaldb::get_var($query);
                $maxid++;
                $data['field'] = 'ufield_'.$maxid;
            }
            $data['isvisitorpublished'] = $data['published'];
            if(isset($data['search_user']))
                $data['search_visitor'] = $data['search_user'];
            $params = array();
            //code for depandetn field
            /*if (isset($data['userfieldtype']) && $data['userfieldtype'] == 'depandant_field') {
                if ($data['id'] != '') {
                    //to handle edit case of depandat field
                    $data['arraynames'] = $data['arraynames2'];
                }
                $flagvar = $this->updateParentField($data['parentfield'], $data['field'], $data['fieldfor']);
                if ($flagvar == false) {
                    return WPJOBPORTAL_SAVE_ERROR;
                }
                if (!empty($data['arraynames'])) {
                    $valarrays = wpjobportalphplib::wpJP_explode(',', $data['arraynames']);
                    foreach ($valarrays as $key => $value) {
                        $keyvalue = $value;
                        $value = wpjobportalphplib::wpJP_str_replace(' ','__',$value);
                        $value = wpjobportalphplib::wpJP_str_replace('.','___',$value);
                        if ( isset($data[$value]) && $data[$value] != null) {
                            $params[$keyvalue] = array_filter($data[$value]);
                        }
                    }
                }
            }*/

            /*if (!empty($data['values'])) {
                foreach ($data['values'] as $key => $value) {
                    if ($value != null) {
                        $params[] = wpjobportalphplib::wpJP_trim($value);
                    }
                }
            }*/
            $options = wpjobportalphplib::wpJP_trim($data['options']);
            if(!empty($options)){
                $options = wpjobportalphplib::wpJP_preg_split('/\s*(\r\n|\n|\r)\s*/', $options);
                foreach($options as $value){
                    $params[] = $value;
                }
            }
            //$params_string = wp_json_encode($params);
			$params_string = wp_json_encode($params, JSON_UNESCAPED_UNICODE);
            $data['userfieldparams'] = $params_string;

        }
        if($data['fieldfor'] == 3 && (isset($data['section']) &&  $data['section'] != 1 )){
            $data['cannotshowonlisting'] = 1;
        }
        if ($data['id'] == '') { // only for new
            if($data['fieldfor'] == 3 && (isset($data['userfieldtype']) &&  $data['userfieldtype'] == 'resumesection' )){
                $data['is_section_headline'] = 1; // to define current field as section for resume
                // to specify section number that can be used to add fields to this section
                // fetching all unique sections ( section field is varchar so have to handle max value here in php code)
                $query = "SELECT section FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = 3 GROUP BY section ";
                $section_values = wpjobportaldb::get_results($query);
                $section_value = 0;
                foreach ($section_values as $key => $section) { // loop over all sections to get max section value and set it as $section_value to generate value for new section
                    if($section->section > $section_value){
                        $section_value = $section->section;
                    }
                }
                $data['section'] = $section_value + 1;// plus 1 to exsisting max section value
                $data['isuserfield'] = 1;
            }
        }

        // disable listing and search for upload field
        if ( (isset($data['userfieldtype']) && $data['userfieldtype'] == 'file') || (isset($data['user_field_type']) && $data['user_field_type'] == 'file')  ) {
            $data['showonlisting'] = 0;
            $data['cannotshowonlisting'] = 1;
            $data['search_user'] = 0;
            $data['search_visitor'] = 0;
            $data['cannotsearch'] = 1;
            $data['section'] = 1;// file field can only be in main section
        }
        // visible field code
        $fieldname = $data['field'];
        // to make sure that edit case works ok.(disabled fields do not submit with form. section field is disabled in edit case)
        if($data['fieldfor'] == 3){
           if(!isset($data['section']) && isset($data['section_value'])){
                $data['section'] = $data['section_value'];
           }elseif($data['section'] == '' && isset($data['section_value'])){
                $data['section'] = $data['section_value'];
           }

        }

        // to make sure this features is only for resume main sections and custom sections.(job and company are included)
        if($data['fieldfor'] != 3 || (isset($data['section']) &&  ($data['section'] == 1 ||  $data['section'] > 8) ) ){
            if (isset($data['visibleParent']) && $data['visibleParent'] != '' && is_numeric($data['visibleParent'])  && isset($data['visibleValue']) && $data['visibleValue'] != '' && isset($data['visibleCondition']) && $data['visibleCondition'] != ''){
                $visible['visibleParentField'] = $fieldname;
                $visible['visibleParent'] = $data['visibleParent'];
                $visible['visibleCondition'] = $data['visibleCondition'];
                $visible['visibleValue'] = $data['visibleValue'];
                $visible_array = array_map(array($this,'sanitize_custom_field'), $visible);
                $data['visibleparams'] = wp_json_encode($visible_array);
                //$data['required'] = 0;

                $query = "SELECT visible_field FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE id = " . esc_sql($data['visibleParent']);
                $old_fieldname = wpjobportal::$_db->get_var($query);
                $new_fieldname = $fieldname;
                if ($data['id'] != '') {
                    $query = "SELECT id,visible_field FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE visible_field  LIKE '%".esc_sql($fieldname)."%'";
                    $query_run = wpjobportal::$_db->get_row($query);
                    if (isset($query_run) && !empty($query_run) && is_numeric($query_run->id)) {
                        $query_fieldname = $query_run->visible_field;
                        $query_fieldname =  wpjobportalphplib::wpJP_str_replace(','.$fieldname, '', $query_fieldname);
                        $query_fieldname =  wpjobportalphplib::wpJP_str_replace($fieldname, '', $query_fieldname);
                        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` SET visible_field = '" . esc_sql($query_fieldname) . "' WHERE id = " . esc_sql($query_run->id);
                        wpjobportal::$_db->query($query);
                    }

                    $old_fieldname =  wpjobportalphplib::wpJP_str_replace(','.$fieldname, '', $old_fieldname);
                    $old_fieldname =  wpjobportalphplib::wpJP_str_replace($fieldname, '', $old_fieldname);
                }
                if (isset($old_fieldname) && $old_fieldname != '') {
                    $new_fieldname = $old_fieldname.','.$new_fieldname;
                }
                // update value
                if(is_numeric($data['visibleParent'])){
                    $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` SET visible_field = '" . esc_sql($new_fieldname) . "'
                    WHERE id = " . esc_sql($data['visibleParent']);
                    wpjobportal::$_db->query($query);
                    if (wpjobportal::$_db->last_error != null) {

                        WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError();
                    }
                }

            } else if($data['id'] != '' && is_numeric($data['id'])){
                $data['visibleparams'] = '';
                $query = "SELECT visibleparams FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE id = " . esc_sql($data['id']);
                $visibleparams = wpjobportal::$_db->get_var($query);
                if (isset($visibleparams)) {
                    $decodedData = json_decode($visibleparams);
                    $visibleParent = $decodedData->visibleParent;
                }else{
                    $visibleParent = -1;
                }
                if(is_numeric($visibleParent)){
                    $query = "SELECT visible_field FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE id = " . esc_sql($visibleParent);
                    $old_fieldname = wpjobportal::$_db->get_var($query);
                    $new_fieldname = $fieldname;
                    $query = "SELECT id,visible_field FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE visible_field  LIKE '%".esc_sql($fieldname)."%'";
                    $query_run = wpjobportal::$_db->get_row($query);
                    if (isset($query_run) && !empty($query_run) && is_numeric($query_run->id)) {
                        $query_fieldname = $query_run->visible_field;
                        $query_fieldname =  wpjobportalphplib::wpJP_str_replace(','.$fieldname, '', $query_fieldname);
                        $query_fieldname =  wpjobportalphplib::wpJP_str_replace($fieldname, '', $query_fieldname);
                        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` SET visible_field = '" . esc_sql($query_fieldname) . "' WHERE id = " . esc_sql($query_run->id);
                        wpjobportal::$_db->query($query);
                    }
                }
            }
        }

        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data = WPJOBPORTALincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

        $stored_id = $row->id;
        return WPJOBPORTAL_SAVED;
    }

    function updateParentField($parentfield, $field, $fieldfor) {
        if(!is_numeric($parentfield)) return false;
        if(!is_numeric($fieldfor)) return false;
        $query = "UPDATE `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` SET depandant_field = '' WHERE fieldfor = ".esc_sql($fieldfor)." AND depandant_field = '".esc_sql($parentfield)."'";
        wpjobportal::$_db->query($query);
        $row = WPJOBPORTALincluder::getJSTable('fieldsordering');
        $row->update(array('id' => $parentfield, 'depandant_field' => $field));
        return true;
    }

    function storeSearchFieldOrdering($data) {//
        if (empty($data)) {
            return false;
        }
        $row = WPJOBPORTALincluder::getJSTable('fieldsordering');
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

        $stored_id = $row->id;
        return WPJOBPORTAL_SAVED;
    }

    function storeSearchFieldOrderingByForm($data) {//
        if (empty($data)) {
            return false;
        }
        wpjobportalphplib::wpJP_parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){
            $row = WPJOBPORTALincluder::getJSTable('fieldsordering');
            for ($i=0; $i < count($sorted_array) ; $i++) {
                $row->update(array('id' => $sorted_array[$i], 'search_ordering' => 1 + $i));
                //$row->update(array('id' => $sorted_array[$i], 'search_ordering' => 1 + $i));
            }
        }
        return WPJOBPORTAL_SAVED;
    }

    function getFieldsForComboByFieldFor() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-fields-for-combo-by-field-for') ) {
            die( 'Security check Failed' );
        }
        $fieldfor = WPJOBPORTALrequest::getVar('fieldfor');
        $parentfield = WPJOBPORTALrequest::getVar('parentfield');
        if(!is_numeric($fieldfor)) return false;
        $wherequery = '';
        if($parentfield){
            $query = "SELECT id FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = $fieldfor AND (userfieldtype = 'radio' OR userfieldtype = 'combo' OR userfieldtype = 'depandant_field') AND depandant_field = '" . esc_sql($parentfield) . "' ";
            $parent = wpjobportaldb::get_var($query);
            $wherequery = ' OR id = '.esc_sql($parent);
        }else{
            $parent = '';
        }
        $query = "SELECT fieldtitle AS text ,id FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = " . esc_sql($fieldfor) . " AND (userfieldtype = 'radio' OR userfieldtype = 'combo' OR userfieldtype = 'depandant_field') && ( depandant_field = '' ".esc_sql($wherequery)." ) ";
        $data = wpjobportaldb::get_results($query);
        $jsFunction = 'getDataOfSelectedField();';
        $html = WPJOBPORTALformfield::select('parentfield', $data, $parent, esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Parent Field', 'wp-job-portal')), array('onchange' => $jsFunction, 'class' => 'inputbox one'));
        $data = wp_json_encode($html);
        return $data;
    }

    function getFieldsForComboBySection() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-fields-for-combo-by-section') ) {
            die( 'Security check Failed' );
        }
        $sectionfor = WPJOBPORTALrequest::getVar('sectionfor');
        if(!is_numeric($sectionfor)) return false;

        $query = "SELECT fieldtitle AS text ,id FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = 3 AND userfieldtype = 'combo' AND section = ".esc_sql($sectionfor);
        $data = wpjobportaldb::get_results($query);
        $jsFunction = '';

        $html = WPJOBPORTALformfield::select('visibleParent', $data,'', esc_html(__('Select Parent', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field wpjobportal-form-input-field-visible', 'onchange' => 'getChildForVisibleCombobox(this.value);'));
        $data = wp_json_encode($html);
        return $data;
    }


    function getSectionToFillValues() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-section-to-fill-values') ) {
            die( 'Security check Failed' );
        }
        $field = WPJOBPORTALrequest::getVar('pfield');
        if(!is_numeric($field)){
            return false;
        }
        $query = "SELECT userfieldparams FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE id = ".esc_sql($field);
        $data = wpjobportaldb::get_var($query);
        $datas = json_decode($data);
        $html = '';
        $fieldsvar = '';
        $comma = '';
        foreach ($datas as $data) {
            if(is_array($data)){
                for ($i = 0; $i < count($data); $i++) {
                    $fieldsvar .= $comma . "$data[$i]";
                    $textvar = $data[$i];
                    $textvar = wpjobportalphplib::wpJP_str_replace(' ','__',$textvar);
                    $textvar = wpjobportalphplib::wpJP_str_replace('.','___',$textvar);
                    $divid = $textvar;
                    $textvar = $textvar."[]";
                    $html .= "<div class='js-field-wrapper js-row no-margin'>";
                    $html .= "<div class='js-field-title js-col-lg-3 js-col-md-3 no-padding'>" . $data[$i] . "</div>";
                    $html .= "<div class='js-col-lg-9 js-col-md-9 no-padding combo-options-fields' id='" . $divid . "'>
                                    <span class='input-field-wrapper'>
                                        " . WPJOBPORTALformfield::text($textvar, '', array('class' => 'inputbox one user-field')) . "
                                        <img class='input-field-remove-img' src='" . esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/remove.png' />
                                    </span>
                                    <input type='button' id='depandant-field-button' onClick='getNextField(\"" . $divid . "\",this);'  value='Add More' />
                                </div>";
                    $html .= "</div>";
                    $comma = ',';
                }
            }else{
                $fieldsvar .= $comma . $data;
                $textvar = $data;
                $textvar = wpjobportalphplib::wpJP_str_replace(' ','__',$data);
                $textvar = wpjobportalphplib::wpJP_str_replace('.','___',$data);
                $divid = $textvar;
                $textvar = $textvar."[]";
                $html .= "<div class='js-field-wrapper js-row no-margin'>";
                $html .= "<div class='js-field-title js-col-lg-3 js-col-md-3 no-padding'>" . $data . "</div>";
                $html .= "<div class='js-col-lg-9 js-col-md-9 no-padding combo-options-fields' id='" . $divid . "'>
                                <span class='input-field-wrapper'>
                                    " . WPJOBPORTALformfield::text($textvar, '', array('class' => 'inputbox one user-field')) . "
                                    <img class='input-field-remove-img' src='" . esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/remove.png' />
                                </span>
                                <input type='button' id='depandant-field-button' onClick='getNextField(\"" . $divid . "\",this);'  value='Add More' />
                            </div>";
                $html .= "</div>";
                $comma = ',';
            }
        }
        $html .= " <input type='hidden' name='arraynames' value='" . $fieldsvar . "' />";
        $html = wp_json_encode($html);
        return $html;
    }

    /*function getOptionsForFieldEdit() {
        $field = WPJOBPORTALrequest::getVar('field');
        $yesno = array(
            (object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal'))),
            (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));

        if(!is_numeric($field)) return false;
        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE id=" . esc_sql($field);
        $data = wpjobportaldb::get_row($query);

        $html = '<span class="popup-top">
                    <span id="popup_title" >
                    ' . esc_html(__("Edit Field", "wp-job-portal")) . '
                    </span>
                    <img id="popup_cross" alt="popup cross" onClick="closePopup();" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/popup-close.png">
                </span>';
        $html .= '<form id="wpjobportal-form" class="popup-field-from" method="post" action="' . esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&task=saveuserfield")) . '">';
        $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . esc_html(__('Field Title', 'wp-job-portal')) . '<font class="required-notifier">*</font></div>
                    <div class="popup-field-obj">' . WPJOBPORTALformfield::text('fieldtitle', isset($data->fieldtitle) ? $data->fieldtitle : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                </div>';
        if ($data->cannotunpublish == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('User Published', 'wp-job-portal')) . '</div>
                        <div class="popup-field-obj">' . WPJOBPORTALformfield::select('published', $yesno, isset($data->published) ? $data->published : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('Visitor published', 'wp-job-portal')) . '</div>
                        <div class="popup-field-obj">' . WPJOBPORTALformfield::select('isvisitorpublished', $yesno, isset($data->isvisitorpublished) ? $data->isvisitorpublished : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';

            $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . esc_html(__('Required', 'wp-job-portal')) . '</div>
                    <div class="popup-field-obj">' . WPJOBPORTALformfield::select('required', $yesno, isset($data->required) ? $data->required : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                </div>';
        }

        if ($data->cannotsearch == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('User Search', 'wp-job-portal')) . '</div>
                        <div class="popup-field-obj">' . WPJOBPORTALformfield::select('search_user', $yesno, isset($data->search_user) ? $data->search_user : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('Visitor Search', 'wp-job-portal')) . '</div>
                        <div class="popup-field-obj">' . WPJOBPORTALformfield::select('search_visitor', $yesno, isset($data->search_visitor) ? $data->search_visitor : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        }
        $showonlisting = true;
        if($data->fieldfor == 3 && $data->section != 1 ){
            $showonlisting = false;
        }
        if (($data->isuserfield == 1 || $data->cannotshowonlisting == 0) && $showonlisting == true) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('Show On Listing', 'wp-job-portal')) . '</div>
                        <div class="popup-field-obj">' . WPJOBPORTALformfield::select('showonlisting', $yesno, isset($data->showonlisting) ? $data->showonlisting : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        }
        $html .= WPJOBPORTALformfield::hidden('form_request', 'wpjobportal');
        $html .= WPJOBPORTALformfield::hidden('id', $data->id);
        $html .= WPJOBPORTALformfield::hidden('isuserfield', $data->isuserfield);
        $html .= WPJOBPORTALformfield::hidden('fieldfor', $data->fieldfor);
        $html .='<div class="js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                    ' . WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save', 'wp-job-portal')), array('class' => 'button'));
        if ($data->isuserfield == 1) {
            $html .= '<a id="user-field-anchor" href="'.esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=formuserfield&wpjobportalid=' . esc_attr($data->id) . '&ff='.esc_attr($data->fieldfor))).'"> ' . esc_html(__('Advanced', 'wp-job-portal')) . ' </a>';
        }

        $html .='</div>
            </form>';
        return wp_json_encode($html);
    }*/

    function deleteUserField($id, $is_section_headline=0){
        if (!is_numeric($id))
           return false;
        $query = "SELECT field,fieldfor,section FROM `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` WHERE id = " . esc_sql($id);
        $result = wpjobportaldb::get_row($query);
        $row = WPJOBPORTALincluder::getJSTable('fieldsordering');
        if ($this->userFieldCanDelete($result) == true) {
            if (!$row->delete($id)) {
                return WPJOBPORTAL_DELETE_ERROR;
            }else{
                // delete fields of custom section on deleting section
                if($is_section_headline == 1){
                    if( is_numeric($result->section) && $result->section > 8){// making sure this code only executes for custom sections
                        $query = "SELECT id FROM `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` WHERE section = " . esc_sql($result->section);
                        $results = wpjobportaldb::get_results($query);
                        foreach ($results as $field) {
                            $row->delete($field->id);
                        }
                    }
                }
                return WPJOBPORTAL_DELETED;
            }
        }
        return WPJOBPORTAL_IN_USE;
    }

    function enforceDeleteUserField($id){
        if (is_numeric($id) == false)
           return false;
        $query = "SELECT field,fieldfor FROM `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` WHERE id = " . esc_sql($id);
        $result = wpjobportaldb::get_row($query);
        $row = WPJOBPORTALincluder::getJSTable('fieldsordering');
        if ($this->userFieldCanDelete($result) == true) {
            if (!$row->delete($id)) {
                return WPJOBPORTAL_DELETE_ERROR;
            }else{
                return WPJOBPORTAL_DELETED;
            }
        }
        return WPJOBPORTAL_IN_USE;
    }

    function userFieldCanDelete($field) {
        $fieldname = $field->field;
        $fieldfor = $field->fieldfor;

        if($fieldfor == 1){//for deleting a company field
            $table = "companies";
        }elseif($fieldfor == 2){//for deleting a job field
            $table = "jobs";
        }elseif($fieldfor == 3){//for deleting a resume field
            $table = "resume";
        }elseif($fieldfor == 4){//for deleting a user field
            $table = "users";
        }
        $query = ' SELECT
                    ( SELECT COUNT(id) FROM `' . wpjobportal::$_db->prefix . 'wj_portal_'.esc_sql($table).'` WHERE
                        params LIKE \'%"' . esc_sql($fieldname) . '":%\'
                    )
                    AS total';
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getUserfieldsfor($fieldfor, $resumesection = null) {
        if (!is_numeric($fieldfor))
            return false;
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        if ($resumesection != null) {
            $published .= " AND section =". esc_sql($resumesection);
        }
        $query = "SELECT field,userfieldparams,userfieldtype FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` WHERE fieldfor = " . esc_sql($fieldfor) . " AND isuserfield = 1 AND " . $published;
        $fields = wpjobportaldb::get_results($query);
        return $fields;
    }

    function getUserFieldbyId($id, $fieldfor) {
        if ($id) {
            if (is_numeric($id) == false)
                return false;
            if (is_numeric($fieldfor) == false)
                return false;
            $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE id = " . esc_sql($id);
            wpjobportal::$_data[0]['userfield'] = wpjobportaldb::get_row($query);
          if(isset(wpjobportal::$_data[0]['userfield']->userfieldparams) && !empty(wpjobportal::$_data[0]['userfield']->userfieldparams)){
              $params = wpjobportal::$_data[0]['userfield']->userfieldparams;
              wpjobportal::$_data[0]['userfieldparams'] = !empty($params) ? json_decode($params, true) : '';
          }else{
            wpjobportal::$_data[0]['userfieldparams'] = '';
          }
        }
        wpjobportal::$_data[0]['fieldfor'] = $fieldfor;
        if(isset(wpjobportal::$_data[0]['userfield']->visibleparams) && wpjobportal::$_data[0]['userfield']->visibleparams != ''){
            $visibleparams = wpjobportal::$_data[0]['userfield']->visibleparams;
            wpjobportal::$_data[0]['visibleparams'] = !empty($visibleparams) ? json_decode($visibleparams, true) : '';

            $visibleparams = json_decode($visibleparams, true);
            $fieldtypes = array();
            if(isset($visibleparams['visibleParent']) && is_numeric($visibleparams['visibleParent'])){
                $query = "SELECT userfieldparams AS params FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` WHERE id = " . esc_sql($visibleparams['visibleParent']);
                $options = wpjobportal::$_db->get_var($query);
                $options = json_decode($options);
                foreach ($options as $key => $option) {
                    $fieldtypes[$key] = (object) array('id' => $option, 'text' => $option);
                }
            }
            wpjobportal::$_data[0]['visibleValue'] = $fieldtypes;
        }
        return;
    }

    function makeDependentComboFiledForResume($val,$childfield,$type,$section,$sectionid,$themecall){

        $query = "SELECT field,depandant_field,userfieldparams,fieldtitle, required FROM `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` WHERE field = '".esc_sql($childfield)."'";
        $data = wpjobportal::$_db->get_row($query);
        $decoded_data = json_decode($data->userfieldparams);
        $comboOptions = array();
        $themeclass=($themecall)?getJobManagerThemeClass('select'):"";

        $flag = 0;
        foreach ($decoded_data as $key => $value) {
            if($key==$val){
               for ($i=0; $i <count($value) ; $i++) {
                   $comboOptions[] = (object)array('id' => $value[$i], 'text' => $value[$i]);
                   $flag = 1;
               }
            }
        }
        if($themecall == 1){
            $theme_string = ' ,'.$themecall;
        }else{
            $theme_string = '';
        }

        $jsFunction = '';
        if ($data->depandant_field != null) {
            $jsFunction = "getDataForDepandantFieldResume('" . $data->field . "','" . $data->depandant_field . "','" . $type . "','" . $section . "','" . $sectionid . "'".$theme_string.");";
        }
        $cssclass="";
        if($data->required == 1){
            $cssclass = 'required';
        }
        //end
        $extraattr = array('data-validation' => $cssclass, 'class' => "inputbox one $cssclass $themeclass");
        if(""!=$jsFunction){
            $extraattr['onchange']=$jsFunction;
        }
        // handleformresume
        if($section AND $section != 1){
            if($ishidden){
                if ($required == 1) {
                    $extraattr['data-myrequired'] = $cssclass;
                    $extraattr['class'] = "inputbox one";
                }
            }
        }
        $textvar =  ($flag == 1) ?  esc_html(__('Select', 'wp-job-portal')).' '.$data->fieldtitle : '';
        $html = wpjobportal::$_wpjpcustomfield->selectResume($childfield, $comboOptions, '', $textvar, $extraattr , null,$section , $sectionid);
        $phtml = wp_json_encode($html);
        return $phtml;
    }
    function DataForDepandantFieldResume(){
        $nonce = WPJOBPORTALrequest::getVar('js_nonce');
        if (! wp_verify_nonce( $nonce, 'wp-job-portal-nonce') ) {
            die( 'Security check Failed' );
        }
        $val = WPJOBPORTALrequest::getVar('fvalue');
        $childfield = WPJOBPORTALrequest::getVar('child');
        $section = WPJOBPORTALrequest::getVar('section');
        $sectionid = WPJOBPORTALrequest::getVar('sectionid');
        $type = WPJOBPORTALrequest::getVar('type');
        $themecall = WPJOBPORTALrequest::getVar('themecall');
        switch ($type) {
            case 1: //select type dependent combo
            case 2: //radio type dependent combo
                return $this->makeDependentComboFiledForResume($val,$childfield,$type,$section,$sectionid,$themecall);
            break;
        }
        return;
    }

    function DataForDepandantField(){
        $nonce = WPJOBPORTALrequest::getVar('js_nonce');
        if (! wp_verify_nonce( $nonce, 'wp-job-portal-nonce') ) {
            die( 'Security check Failed' );
        }
        $val = WPJOBPORTALrequest::getVar('fvalue');
        $childfield = WPJOBPORTALrequest::getVar('child');
        $themecall = WPJOBPORTALrequest::getVar('themecall');
        $themeclass="";
        if($themecall){
            $theme_string = ','. $themecall ;
            if(function_exists("getJobManagerThemeClass")){
                $themeclass=getJobManagerThemeClass("select");
            }
        }else{
            $theme_string = '';
        }
        $query = "SELECT userfieldparams, fieldtitle, required, depandant_field,field  FROM `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` WHERE field = '".esc_sql($childfield)."'";
        $data = wpjobportal::$_db->get_row($query);
        $decoded_data = json_decode($data->userfieldparams);
        $comboOptions = array();
        $flag = 0;
        if(!empty($decoded_data) && $decoded_data != ''){
            foreach ($decoded_data as $key => $value) {
                if($key==$val){
                   for ($i=0; $i <count($value) ; $i++) {
                       $comboOptions[] = (object)array('id' => $value[$i], 'text' => $value[$i]);
                       $flag = 1;
                   }
                }
            }
        }
        $textvar =  ($flag == 1) ?  esc_html(__('Select', 'wp-job-portal')).' '.$data->fieldtitle : '';
        $required = '';
        if($data->required == 1){
            $required = 'required';
        }
        $jsFunction = '';
        if ($data->depandant_field != null) {
            $jsFunction = " getDataForDepandantField('" . $data->field . "','" . $data->depandant_field . "','1','',''". $theme_string.");";
        }
        $html = WPJOBPORTALformfield::select($childfield, $comboOptions, '',$textvar, array('data-validation' => $required,'class' => 'inputbox one '.$themeclass, 'onchange' => $jsFunction));
        $phtml = wp_json_encode($html);
        return $phtml;
    }

    function getFieldTitleByFieldAndFieldfor($field,$fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $query = "SELECT fieldtitle FROM `".wpjobportal::$_db->prefix."wj_portal_fieldsordering` WHERE field = '".esc_sql($field)."' AND fieldfor = ".esc_sql($fieldfor);
        $title = wpjobportal::$_db->get_var($query);
        return $title;
    }
    function getMessagekey(){
        $key = 'fieldordering';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function getFieldsForListing($fieldfor){
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }

        $query = "SELECT field  FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE showonlisting = 1 AND " . esc_sql($published) . " AND fieldfor =" . esc_sql($fieldfor) ;
        $data = wpjobportaldb::get_results($query);
        $return_array = array();
        foreach ($data as $field) {
            $return_array[$field->field] = 1;
        }

        return $return_array;
    }

    function getFieldOrderingData($fieldfor){ // to handle visibilty in case of mininmum fields
        if(!is_numeric($fieldfor)){
            return false;
        }

        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $query = "SELECT field,fieldtitle  FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE " . esc_sql($published) . " AND fieldfor =" . esc_sql($fieldfor) ;
        $data = wpjobportaldb::get_results($query);
        $return_data = array();
        foreach ($data as $field) {
            $return_data[$field->field] = $field->fieldtitle;
        }
        return $return_data;
    }

    function getFieldOrderingDataForListing($fieldfor){
        if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        if(!is_numeric($fieldfor)){
            return false;
        }

        $query = "SELECT field,fieldtitle  FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE showonlisting = 1 AND " . esc_sql($published) . " AND fieldfor =" . esc_sql($fieldfor) ;
        $data = wpjobportaldb::get_results($query);
        $return_data = array();
        foreach ($data as $field) {
            $return_data[$field->field] = $field->fieldtitle;
        }
        return $return_data;
    }



    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['title'] = WPJOBPORTALrequest::getVar("title");
        $jsjp_search_array['ustatus'] = WPJOBPORTALrequest::getVar("ustatus");
        $jsjp_search_array['vstatus'] = WPJOBPORTALrequest::getVar("vstatus");
        $jsjp_search_array['required'] = WPJOBPORTALrequest::getVar("required");
        $jsjp_search_array['search_from_customfield'] = 1;
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
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_customfield']) && $wpjp_search_cookie_data['search_from_customfield'] == 1){
            $jsjp_search_array['title'] = $wpjp_search_cookie_data['title'];
            $jsjp_search_array['ustatus'] = $wpjp_search_cookie_data['ustatus'];
            $jsjp_search_array['vstatus'] = $wpjp_search_cookie_data['vstatus'];
            $jsjp_search_array['required'] = $wpjp_search_cookie_data['required'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        wpjobportal::$_search['customfield']['title'] = isset($jsjp_search_array['title']) ? $jsjp_search_array['title'] : '';
        wpjobportal::$_search['customfield']['ustatus'] = isset($jsjp_search_array['ustatus']) ? $jsjp_search_array['ustatus'] : '';
        wpjobportal::$_search['customfield']['vstatus'] = isset($jsjp_search_array['vstatus']) ? $jsjp_search_array['vstatus'] : '';
        wpjobportal::$_search['customfield']['required'] = isset($jsjp_search_array['required']) ? $jsjp_search_array['required'] : '';
    }

    function getFieldsForVisibleCombobox($fieldfor, $field='', $cid='',$section= '') {
        if(!is_numeric($fieldfor)){
            return false;
        }
        $wherequery = '';
        if(isset($field) && $field !='' ){
            $query = "SELECT id FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = $fieldfor AND (userfieldtype = 'combo') AND visible_field = '" . esc_sql($field) . "' ";
            $parent = wpjobportal::$_db->get_var($query);
            if (is_numeric($parent)) {
                $wherequery = ' OR id = '.esc_sql($parent);
            }
        }
        $wherequeryforedit = '';
        if(isset($cid) && $cid !='' && is_numeric($cid)){
            $wherequeryforedit = ' AND id != '.esc_sql($cid);
        }
        // to handle resume section
        if(isset($section) && $section !='' && is_numeric($section)){
            $wherequeryforedit = ' AND section = '.esc_sql($section);
        }


        $query = "SELECT fieldtitle AS text ,id FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE fieldfor = ".esc_sql($fieldfor)." AND userfieldtype = 'combo' ".$wherequeryforedit.$wherequery;
        $data = wpjobportal::$_db->get_results($query);
        return $data;
    }

    function getChildForVisibleCombobox() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-child-for-visible-combobox') ) {
            die( 'Security check Failed' );
        }
        $perentid = WPJOBPORTALrequest::getVar('val');
        if (!is_numeric($perentid)){
            return false;
        }

        $query = "SELECT isuserfield, field FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` WHERE id = " . esc_sql($perentid);
        $fieldType = wpjobportal::$_db->get_row($query);
        if (isset($fieldType->isuserfield) && $fieldType->isuserfield == 1) {
            $query = "SELECT userfieldparams AS params FROM `" . wpjobportal::$_db->prefix . "wj_portal_fieldsordering` WHERE id = " . esc_sql($perentid);
            $options = wpjobportal::$_db->get_var($query);
            $options = json_decode($options);
            foreach ($options as $key => $option) {
                $fieldtypes[$key] = (object) array('id' => $option, 'text' => $option);
            }
        } else if ($fieldType->field == 'department') {
            // $query = "SELECT departmentname AS text ,id FROM " . wpjobportal::$_db->prefix . "js_ticket_departments";
            // $fieldtypes = wpjobportal::$_db->get_results($query);
        }
        $combobox = false;
        if(!empty($fieldtypes)){
            $combobox = WPJOBPORTALformfield::select('visibleValue', $fieldtypes, isset(wpjobportal::$_data[0]['userfield']->required) ? wpjobportal::$_data[0]['userfield']->required : 0, '', array('class' => 'inputbox one wpjobportal-form-select-field wpjobportal-form-input-field-visible'));
        }
        return wpjobportalphplib::wpJP_htmlentities($combobox);
    }

    function getDataForVisibleField($field) {
        $field = esc_sql($field);
        $field_array = wpjobportalphplib::wpJP_str_replace(",", "','", $field);
        $query = "SELECT visibleparams FROM ". wpjobportal::$_db->prefix ."wj_portal_fieldsordering WHERE  field IN ('". $field_array ."')";
        $fields = wpjobportal::$_db->get_results($query);
        $data = array();
        foreach ($fields as $item) {
            if(isset($item->visibleparams) && $item->visibleparams != ''){
                $d = json_decode($item->visibleparams);
                if(isset($d->visibleParentField)){
                    $d->visibleParentField = Self::getChildForVisibleField($d->visibleParentField);
                    $data[] = $d;
                }
            }
        }
        return $data;
    }

    static function getChildForVisibleField($field) {
        $field = esc_sql($field);
        $oldField = wpjobportalphplib::wpJP_explode(',',$field);
        $newField = $oldField[sizeof($oldField) - 1];
        $query = "SELECT visible_field FROM ". wpjobportal::$_db->prefix ."wj_portal_fieldsordering WHERE  field = '". $newField ."'";
        $queryRun = wpjobportal::$_db->get_var($query);
        if (isset($queryRun) && $queryRun != '') {
            $data = wpjobportalphplib::wpJP_explode(',',$queryRun);
            foreach ($data as $value) {
                $field = $field.','.$value;
                $field = Self::getChildForVisibleField($field);
            }
        }
        return $field;
    }

    function isFieldRequired(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'is-field-required') ) {
            die( 'Security check Failed' );
        }
        $field = WPJOBPORTALrequest::getVar('field');
        $query = "SELECT required  FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE  field ='".esc_sql($field)."'";
        return wpjobportal::$_db->get_var($query);
    }

    function sanitize_custom_field($arg) {
        if (is_array($arg)) {
            // foreach($arg as $ikey){
            return array_map(array($this,'sanitize_custom_field'), $arg);
            // }
        }
        return wpjobportalphplib::wpJP_htmlentities($arg, ENT_QUOTES, 'UTF-8');
    }

    function checkCompanyFieldForJob(){
        $query = "SELECT required  FROM " . wpjobportal::$_db->prefix . "wj_portal_fieldsordering WHERE  field ='company'";
        return wpjobportal::$_db->get_var($query);
    }

}
?>
