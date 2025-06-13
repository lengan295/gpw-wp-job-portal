<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

    function wpjp_generate_rewrite_rules(&$rules, $rule){

        /*$html='<textarea style="float: right;width: 67%;margin-top: 30px;height: 170px;" >';
        foreach ($rules as $qvar) {
            $html.=$qvar.' ';
        }
        $html.='</textarea>';
        echo wp_kses_post($html);*/

        $_new_rules = array();
        //echo '<pre>';print_r( wp_json_encode($rules) );exit;
        foreach($rules AS $key => $value){
            if(strstr($key, $rule)){
                $newkey = substr($key,0,strlen($key) - 3);
                $matcharray = explode('$matches', $value);
                $countmatch = COUNT($matcharray);
                //on all pages
                // $_key = $newkey . '/(jobseeker-purchase-history|jobseeker-control-panel|jobseeker-credits-log|jobseeker-credits|jobseeker-messages|jobseeker-my-stats|jobseeker-rate-list|jobseeker-register|wpjobportal-login|employer-purchase-history|employer-control-panel|employer-credits-log|employer-credits|employer-messages|employer-my-stats|employer-rate-list|employer-register|folder-resumes|resume-save-searches|resume-categories|resume-search|resume-rss|add-resume|my-resumes|resumes|resume|my-companies|add-company|companies|company|add-cover-letter|my-cover-letters|cover-letter|add-department|my-departments|department|add-folder|my-folders|add-job|my-jobs|job-applied-resume|job-save-searches|job-categories|job-messages|job-by-types|job-search|job-types|job-alert|job-rss|my-applied-jobs|newest-jobs|shortlisted-jobs|messages|message|new-in-wpjobportal|folder|jobs|job|user-register|jm-user-register|resume-pdf|resume-print)(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$';
                $_key = $newkey.'/(';
                $_key .= WPJOBPORTALincluder::getJSModel('slug')->getSlugString();
                $_key .= ')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$';
                $newvalue = $value . '&wpjobportallayout=$matches['.$countmatch.']&wpjobportal1=$matches['.($countmatch + 1).']&wpjobportal2=$matches['.($countmatch + 2).']&wpjobportal3=$matches['.($countmatch + 3).']';
                $_new_rules[$_key] = $newvalue;
            }
        }
        return $_new_rules;
    }

    function wpjp_post_rewrite_rules_array($rules){
        $newrules = wpjp_generate_rewrite_rules($rules, '([^/]+)(?:/([0-9]+))?/?$');
        $newrules += wpjp_generate_rewrite_rules($rules, '([^/]+)(/[0-9]+)?/?$');
        $newrules += wpjp_generate_rewrite_rules($rules, '([0-9]+)(?:/([0-9]+))?/?$');
        $newrules += wpjp_generate_rewrite_rules($rules, '([0-9]+)(/[0-9]+)?/?$');

        //echo '<pre>';print_r($newrules);exit;
        return $newrules + $rules;
    }
    add_filter('post_rewrite_rules', 'wpjp_post_rewrite_rules_array',999);

    function wpjp_page_rewrite_rules_array($rules){
        $newrules = array();
        $newrules = wpjp_generate_rewrite_rules($rules, '(.?.+?)(?:/([0-9]+))?/?$');
        $newrules += wpjp_generate_rewrite_rules($rules, '(.?.+?)(/[0-9]+)?/?$');
        return $newrules + $rules;
    }
    add_filter('page_rewrite_rules', 'wpjp_page_rewrite_rules_array');

    function wpjp_root_rewrite_rules( $rules ) {
        // Homepage params
        $pageid = get_option('page_on_front');
        if($pageid == 0 || $pageid == ''){
            $pageid = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('default_pageid');
        }
        $key = WPJOBPORTALincluder::getJSModel('slug')->getSlugString(1);
        //echo $key;exit;
        $rules['('.$key.')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$'] = 'index.php?page_id='.$pageid.'&wpjobportallayout=$matches[1]&wpjobportal1=$matches[2]&wpjobportal2=$matches[3]&wpjobportal3=$matches[4]';
        //echo '<pre>';print_r($rules);exit;
        return $rules;
    }
    add_filter( 'root_rewrite_rules', 'wpjp_root_rewrite_rules' );

    function wpjp_query_var( $qvars ) {

        //echo '<pre style="color:#000;">';var_dump($qvars);
        $value = wp_json_encode($qvars);
        $qvars[] = 'wpjobportallayout';
        $qvars[] = 'wpjobportal1';
        $qvars[] = 'wpjobportal2';
        $qvars[] = 'wpjobportal3';
        /*$html='<textarea style="float: right;width: 100%;margin-top: 30px;height: 170px;position: fixed;top: 100px;z-index: 99;" >';
        foreach ($qvars as $qvar) {
            $html.=$qvar.' ';
        }
        $html.='</textarea>';
        echo wp_kses_post($html);*/
        return $qvars;

    }
    add_filter( 'query_vars', 'wpjp_query_var' , 10, 1 );

    function wpjp_parse_request($q) {
        /*$html='<textarea style="float: right;width: 100%;position: fixed;top: 100px;z-index: 99;" >';
        foreach ($q->query_vars as $key=>$qvar) {
            $html.=$key.' = '.$qvar.'\n ';
        }
        $html.='</textarea>';
        echo wp_kses_post($html);*/
        if(isset($q->query_vars['page_id']) && !empty($q->query_vars['page_id'])){
            wpjobportal::$_data['sanitized_args']['pageid'] = $q->query_vars['page_id'];
        }
        if(isset($q->query_vars['wpjobportallayout']) && !empty($q->query_vars['wpjobportallayout'])){

            $layout = $q->query_vars['wpjobportallayout'];
            $slug_prefix = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('slug_prefix');
            $home_slug_prefix = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('home_slug_prefix');
            $length = wpjobportalphplib::wpJP_strlen($home_slug_prefix);
            if(wpjobportalphplib::wpJP_substr($layout, 0, $length) === $home_slug_prefix){
                $layout = wpjobportalphplib::wpJP_substr($layout,$length);
            }
            $length = wpjobportalphplib::wpJP_strlen($slug_prefix);
            if(wpjobportalphplib::wpJP_substr($layout, 0, $length) === $slug_prefix){
                $slug_flag = WPJOBPORTALincluder::getJSModel('slug')->checkIfSlugExist($layout);
                if($slug_flag != true){
                    $layout = wpjobportalphplib::wpJP_substr($layout,$length);
                }
            }

            //if(wpjobportalphplib::wpJP_substr($layout, 0, 3) == 'jm-') {
            //    $layout = wpjobportalphplib::wpJP_substr($layout,3);
            //}

            $layout = WPJOBPORTALincluder::getJSModel('slug')->getDefaultSlugFromSlug($layout);
            switch ($layout) {
                case 'new-in-wpjobportal':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'common';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'newinwpjobportal';
                break;
                case '':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobs';
                break;
                case 'wpjobportal-login':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'wpjobportal';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'login';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalredirecturl'] = $wpjobportal1;
                }
                break;
                case 'jobseeker-control-panel':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobseeker';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'controlpanel';
                break;
                case 'jobseeker-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobseeker';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mystats';
                break;
                 case 'edit-profile':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'user';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'formprofile';
                break;
                case 'employer-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'reports';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mystats';
                break;
                case 'employer-control-panel':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'employer';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'controlpanel';
                break;
                case 'resumes':
                if(in_array('resumesearch', wpjobportal::$_active_addons)){
                    $mod = "resumesearch";
                }else{
                    $mod = "resume";
                }
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = $mod;
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumes';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'sortby')){
                        wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                    }else{
                        if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'vt')){
                            wpjobportal::$_data['sanitized_args']['viewtype'] = $wpjobportal1;
                        }else{
                            wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;

                        }
                    }
                }
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal2']);
                /*if(!empty($wpjobportal2)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal2;
                }*/

                if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                    $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                    wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                }else{
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'jobs':
                case 'newest-jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobs';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-companies':
                if(in_array('multicompany', wpjobportal::$_active_addons)){
                    $mod = "multicompany";
                }else{
                    $mod = "company";
                }
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = $mod;
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mycompanies';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                    $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                    wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                }else{
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'add-company':
                if(in_array('multicompany', wpjobportal::$_active_addons)){
                    $mod = "multicompany";
                }else{
                    $mod = "company";
                }
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = $mod;
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addcompany';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                    $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                    wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                }else{
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myjobs';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                }
                break;
                case 'add-job':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addjob';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                 if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                    $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                    wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                }else{
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-departments':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'departments';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mydepartments';
                break;
                case 'select-package':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'package';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'packageselection';
                break;
                case 'add-department':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'departments';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'adddepartment';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                            $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                            wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                        }else{
                            wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                        }
                    }
                break;
                case 'department':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'departments';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewdepartment';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'company':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'company';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewcompany';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'resume':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewresume';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '', $q->query_vars['wpjobportal1']);
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '', $q->query_vars['wpjobportal2']);
                if(!empty($wpjobportal2)){
                    wpjobportal::$_data['sanitized_args']['jobid'] = $wpjobportal1;
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal2;
                }else{
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'job':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewjob';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'my-folders':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folder';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myfolders';
                break;
                case 'add-folder':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folder';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addfolder';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'folder':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folder';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewfolder';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'folder-resumes':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folderresume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'folderresume';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'jobseeker-messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekermessages';
                break;
                case 'employer-messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employermessages';
                break;
                case 'message':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'sendmessage';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'job-messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobmessages';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                // case 'job-types':
                // wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                // wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsbytypes';
                // break;
                case 'messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'messages';
                break;
                case 'resume-search':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resumesearch';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumesearch';
                break;
                case 'resume-save-searches':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resumesearch';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumesavesearch';
                break;
                case 'resume-categories':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumebycategory';
                break;
                case 'employer-credits':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'credits';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employercredits';
                break;
                case 'jobseeker-credits':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'credits';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekercredits';
                break;
                case 'employer-purchase-history':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employerpurchasehistory';
                break;
                case 'employer-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'stats';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employermystats';
                break;
                case 'jobseker-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'stats';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekerstats';
                break;
                case 'employer-register':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'user';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'regemployer';
                break;
                case 'jobseeker-register':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'user';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'regjobseeker';
                break;
                case 'user-register':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'user';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'userregister';
                break;
                case 'add-resume':
                if(in_array('multiresume', wpjobportal::$_active_addons)){
                    $mod = "multiresume";
                }else{
                    $mod = "resume";
                }
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = $mod;
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addresume';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                    $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                    wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                }else{
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-resumes':
                if(in_array('multiresume', wpjobportal::$_active_addons)){
                    $mod = "multiresume";
                }else{
                    $mod = "resume";
                }
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = $mod;
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myresumes';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                }
                break;
              case 'companies':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'multicompany';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'companies';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'company-')){
                        wpjobportal::$_data['sanitized_args']['wpjobportal-company'] = $wpjobportal1;
                    }elseif(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'city-')){
                        wpjobportal::$_data['sanitized_args']['wpjobportal-city'] = $wpjobportal1;
                    }else{
                        wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                    }
                }
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal2']);
                if(!empty($wpjobportal2)){
                    wpjobportal::$_data['sanitized_args']['wpjobportal-city'] = $wpjobportal2;
                }
                break;
                case 'my-applied-jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobapply';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myappliedjobs';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                }
                break;
                case 'job-applied-resume':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobapply';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobappliedresume';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['jobid'] = $wpjobportal1;
                }
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal2']);
                    //var_dump($wpjobportal2);
                if(!empty($wpjobportal2)){
                    if(wpjobportalphplib::wpJP_strstr($wpjobportal2, 'sortby')){
                        wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal2;
                    }else{
                        wpjobportal::$_data['sanitized_args']['ta'] = $wpjobportal2;
                    }
                }
                $wpjobportal3 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal3']);
                if(!empty($wpjobportal3)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal3;
                }
                break;
                case 'job-search':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobsearch';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsearch';
                break;
                case 'job-save-searches':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobsearch';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsavesearch';
                    break;
                case 'job-alert':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobalert';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobalert';
                     $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                     if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                            $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                            wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                        }else{
                            wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                        }
                    break;
                case 'shortlisted-jobs':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'shortlist';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'shortlistedjobs';
                    break;
                case 'job-categories':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsbycategories';
                break;
                case 'newest-jobs':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'newestjobs';
                break;
                case 'job-by-types':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsbytypes';
                break;
                case 'jobs-by-cities':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsbycities';
                break;
                case 'resume-pdf':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'pdf';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'pdf';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'resume-print':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'printresume';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                    break;
                case 'my-packages':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'purchasehistory';
                break;
                case 'packages':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'package';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'packages';
                break;
                case 'my-invoices':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'invoice';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myinvoices';
                break;
                case 'my-subscriptions':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mysubscriptions';
                break;
                case 'company-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'paycompany';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'featuredcompany-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'payfeaturedcompany';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                    break;
                case 'department-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'paydepartment';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'coverletter-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'paycoverletter';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'featuredjob-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'payfeaturedjob';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'job-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'payjob';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'featuredresume-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'payfeaturedresume';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'jobapply-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'payjobapply';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'resume-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'payresume';
                     $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'resumesavesearch-payment':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'payresumesearch';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(!empty($wpjobportal1)){
                        $wpjobportal1 = wpjobportal::$_common->parseID($wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                break;
                case 'my-coverletters':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'coverletter';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mycoverletters';
                break;
                case 'add-coverletter':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'coverletter';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addcoverletter';
                    $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                    if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'package-')){
                        $userpackageid = wpjobportalphplib::wpJP_preg_replace('/[^\d]*/', '', $wpjobportal1);
                        wpjobportal::$_data['sanitized_args']['userpackageid'] = $userpackageid;
                    }else{
                        wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    }
                    //$wpjobportal1 = str_replace('/', '',$q->query_vars['wpjobportal1']);
                    // if(!empty($wpjobportal1)){
                    //     wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                    // }

                break;
                case 'coverletter':
                    wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'coverletter';
                    wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewcoverletter';
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;

                default:
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobseeker';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'controlpanel';
                break;
            }
            //echo '<pre>';print_r(wpjobportal::$_data['sanitized_args']);exit;
            /*
            switch ($layout) {
                case 'new-in-wpjobportal':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'common';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'newinwpjobportal';
                break;
                case 'wpjobportal-login':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'wpjobportal';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'login';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalredirecturl'] = $wpjobportal1;
                }
                break;
                case 'jobseeker-control-panel':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobseeker';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'controlpanel';
                break;
                case 'jobseeker-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobseeker';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mystats';
                break;
                case 'employer-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'employer';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mystats';
                break;
                case 'employer-control-panel':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'employer';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'controlpanel';
                break;
                case 'resumes':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumes';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'sortby')){
                        wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                    }else{
                        if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'vt')){
                            wpjobportal::$_data['sanitized_args']['viewtype'] = $wpjobportal1;
                        }else{
                            wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;

                        }
                    }
                }
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal2']);
                if(!empty($wpjobportal2)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal2;
                }
                break;
                case 'jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobs';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-companies':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'company';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mycompanies';
                break;
                case 'add-company':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'company';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addcompany';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myjobs';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                }
                break;
                case 'add-job':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addjob';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-departments':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'departments';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mydepartments';
                break;
                case 'add-department':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'departments';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'adddepartment';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'department':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'departments';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewdepartment';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'cover-letter':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'coverletter';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewcoverletter';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'company':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'company';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewcompany';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'resume':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewresume';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '', $q->query_vars['wpjobportal1']);
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '', $q->query_vars['wpjobportal2']);
                if(!empty($wpjobportal2)){
                    wpjobportal::$_data['sanitized_args']['jobid'] = $wpjobportal1;
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal2;
                }else{
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'job':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewjob';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'my-folders':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folder';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myfolders';
                break;
                case 'add-folder':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folder';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addfolder';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'folder':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folder';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'viewfolder';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'folder-resumes':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'folderresume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'folderresume';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'jobseeker-messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekermessages';
                break;
                case 'employer-messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employermessages';
                break;
                case 'message':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'sendmessage';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'job-messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobmessages';
                wpjobportal::$_data['sanitized_args']['wpjobportalid'] = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                break;
                case 'job-types':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsbytypes';
                break;
                case 'messages':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'message';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'messages';
                break;
                case 'resume-search':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resumesearch';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumesearch';
                break;
                case 'resume-save-searches':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resumesearch';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumesavesearch';
                break;
                case 'resume-categories':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumebycategory';
                break;
                case 'resume-rss':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'rss';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'resumerss';
                break;
                case 'employer-credits':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'credits';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employercredits';
                break;
                case 'jobseeker-credits':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'credits';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekercredits';
                break;
                case 'employer-purchase-history':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employerpurchasehistory';
                break;
                case 'employer-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'stats';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employermystats';
                break;
                case 'jobseker-my-stats':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'stats';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekerstats';
                break;
                case 'employer-register':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'user';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'regemployer';
                break;
                case 'jobseeker-register':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'user';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'regjobseeker';
                break;
                case 'user-register':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'user';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'userregister';
                break;
                case 'add-resume':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addresume';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'my-resumes':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myresumes';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                }
                break;
                case 'add-cover-letter':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'coverletter';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'addcoverletter';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'companies':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'company';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'companies';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    if(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'company-')){
                        wpjobportal::$_data['sanitized_args']['wpjobportal-company'] = $wpjobportal1;
                    }elseif(wpjobportalphplib::wpJP_strstr($wpjobportal1, 'city-')){
                        wpjobportal::$_data['sanitized_args']['wpjobportal-city'] = $wpjobportal1;
                    }
                }
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal2']);
                if(!empty($wpjobportal2)){
                    wpjobportal::$_data['sanitized_args']['wpjobportal-city'] = $wpjobportal2;
                }
                break;
                case 'my-applied-jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobapply';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'myappliedjobs';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal1;
                }
                break;
                case 'job-applied-resume':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobapply';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobappliedresume';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['jobid'] = $wpjobportal1;
                }
                $wpjobportal2 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal2']);
                    //var_dump($wpjobportal2);
                if(!empty($wpjobportal2)){
                    if(wpjobportalphplib::wpJP_strstr($wpjobportal2, 'sortby')){
                        wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal2;
                    }else{
                        wpjobportal::$_data['sanitized_args']['ta'] = $wpjobportal2;
                    }
                }
                $wpjobportal3 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal3']);
                if(!empty($wpjobportal3)){
                    wpjobportal::$_data['sanitized_args']['sortby'] = $wpjobportal3;
                }
                break;
                case 'my-cover-letters':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'coverletter';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'mycoverletters';
                break;
                case 'job-search':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobsearch';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsearch';
                break;
                case 'job-save-searches':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobsearch';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsavesearch';
                break;
                case 'job-alert':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobalert';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobalert';
                break;
                case 'job-rss':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'rss';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobrss';
                break;
                case 'shortlisted-jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'shortlistedjobs';
                break;
                case 'jobseeker-purchase-history':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'purchasehistory';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekerpurchasehistory';
                break;
                case 'jobseeker-rate-list':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'credits';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'ratelistjobseeker';
                break;
                case 'employer-rate-list':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'credits';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'ratelistemployer';
                break;
                case 'jobseeker-credits-log':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'creditslog';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobseekercreditslog';
                break;
                case 'employer-credits-log':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'creditslog';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'employercreditslog';
                break;
                case 'job-categories':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsbycategories';
                break;
                case 'newest-jobs':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'newestjobs';
                break;
                case 'job-by-types':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'job';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'jobsbytypes';
                break;
                case 'resume-pdf':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'pdf';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                case 'resume-print':
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'resume';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'printresume';
                $wpjobportal1 = wpjobportalphplib::wpJP_str_replace('/', '',$q->query_vars['wpjobportal1']);
                if(!empty($wpjobportal1)){
                    wpjobportal::$_data['sanitized_args']['wpjobportalid'] = $wpjobportal1;
                }
                break;
                default:
                wpjobportal::$_data['sanitized_args']['wpjobportalme'] = 'jobseeker';
                wpjobportal::$_data['sanitized_args']['wpjobportallt'] = 'controlpanel';
                break;
            }
            */
        }
    }

    add_action('parse_request', 'wpjp_parse_request');

    function wpjp_redirect_canonical($redirect_url, $requested_url) {

        global $wp_rewrite;
        /*
        echo 'redirect url';print_r($redirect_url);
        echo 'requested url';print_r($requested_url);exit;
        */
        if(is_home() || is_front_page()){
            $array = WPJOBPORTALincluder::getJSModel('slug')->getRedirectCanonicalArray();


            /*
            $array = array('/jm-employer-credits-log','/jm-jobseeker-credits-log','/jm-employer-rate-list','/jm-jobseeker-rate-list','/jm-jobseeker-purchase-history','/jm-shortlisted-jobs','/jm-job-rss','/jm-job-alert','/jm-job-save-searches','/jm-job-search','/jm-job-applied-resume','/jm-my-applied-jobs'
                ,'/jm-companies','/jm-job-by-types','/jm-newest-jobs','/jm-job-categories','/jm-my-cover-letters','/jm-add-cover-letter','/jm-my-resumes','/jm-jobseeker-register','/jm-employer-register','/jm-add-resume','/jm-jobseker-my-stats','/jm-employer-my-stats','/jm-employer-purchase-history','/jm-jobseeker-credits','/jm-employer-credits','/jm-resume-rss','/jm-resume-categories'
                ,'/jm-resume-save-searches','/jm-resume-search','/jm-messages','/jm-job-types','/jm-job-messages','/jm-message','/jm-employer-messages','/jm-jobseeker-messages','/jm-folder-resumes','/jm-folder','/jm-add-folder','/jm-my-folders','/jm-job','/jm-resume','/jm-company','/jm-cover-letter','/jm-department'
                ,'/jm-add-department','/jm-my-departments','/jm-add-job','/jm-my-jobs','/jm-add-company','/jm-my-companies','/jm-jobs','/jm-resumes','/jm-employer-control-panel','/jm-employer-my-stats','/jm-jobseeker-my-stats','/jm-jobseeker-control-panel','/jm-wpjobportal-login','/jm-new-in-wpjobportal','/jm-user-register','/jm-resume-pdf','/jm-resume-print');
            */
            $ret = false;
            foreach($array AS $layout){
                if(wpjobportalphplib::wpJP_strstr($requested_url, $layout)){
                    $ret = true;
                    break;
                }
            }
            if($ret == true){
                return $requested_url;
            }
        }
        return $redirect_url;
    }
    add_filter('redirect_canonical', 'wpjp_redirect_canonical', 11, 2);

    // to handle slug conflict on wordpress post/page slug
    add_filter("wp_unique_post_slug", "wpjobportal_slug_conflict_check", 10, 6);
    function wpjobportal_slug_conflict_check ($slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug) {
        $query = "SELECT COUNT(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_slug
                WHERE slug = '" . esc_sql($slug)."' ";
        $slug_flag = wpjobportaldb::get_var($query);
        if($slug_flag > 0){
            $slug = $slug ."-2";
        }

        return $slug;
    }

?>
