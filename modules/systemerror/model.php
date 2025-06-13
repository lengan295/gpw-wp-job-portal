<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALsystemerrorModel {

    function getSystemErrors() {
        $inquery = '';
        // Pagination
        $query = "SELECT COUNT(`id`) FROM `" . wpjobportal::$_db->prefix . "wj_portal_system_errors`";
        $query .= $inquery;
        $total = wpjobportal::$_db->get_var($query);
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        // Data
        $query = " SELECT systemerror.*
					FROM `" . wpjobportal::$_db->prefix . "wj_portal_system_errors` AS systemerror ";
        $query .= $inquery;
        $query .= " ORDER BY systemerror.created DESC LIMIT " . WPJOBPORTALpagination::$_offset . ", " . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportal::$_db->get_results($query);
        if (wpjobportal::$_db->last_error != null) {
            $this->addSystemError();
        }
        return;
    }

    function addSystemError() {
        $error = wpjobportal::$_db->last_error;
        $query_array = array('error' => $error,
            'uid' => get_current_user_id(),
            'isview' => 0,
            'created' => gmdate("Y-m-d H:i:s")
        );

        $row = WPJOBPORTALincluder::getJSTable('systemerror');
        if (!$row->bind($query_array)) {
            
        } elseif (!$row->store()) {
            
        }

        return;
    }
    function getMessagekey(){
        $key = 'systemerror';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
