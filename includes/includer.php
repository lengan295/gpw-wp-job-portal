<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALincluder {

    function __construct() {

    }

    /*
     * Includes files
     */

    public static function include_file($filename, $module_name = null) {
        // making usre no relative path is being used
        $filename = wpjobportalphplib::wpJP_clean_file_path($filename);
        $module_name = wpjobportalphplib::wpJP_clean_file_path($module_name);


        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        if ($module_name != null) {
            wp_enqueue_style('wpjobportal-jobseeker-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jobseekercp.css');
            $file_path = self::getPluginPath($module_name,'file',$filename);
            if ($wp_filesystem->exists(WPJOBPORTAL_PLUGIN_PATH . 'includes/css/inc-css/' . $module_name . '-' . $filename . '.css.php')) {
                require_once(WPJOBPORTAL_PLUGIN_PATH . 'includes/css/inc-css/' . $module_name . '-' . $filename . '.css.php');
            }
            if(is_array($file_path) && $wp_filesystem->exists($file_path['tmpl_file'])){
                if ($wp_filesystem->exists($file_path['inc_file'])) {
                    require_once($file_path['inc_file']);
                }
                include_once $file_path['tmpl_file'];
            }else if($wp_filesystem->exists($file_path)){
                $incfilepath = wpjobportalphplib::wpJP_explode('.php', $file_path);
                $incfilename = $incfilepath[0].'.inc.php';
            // to handle page title
                WPJOBPORTALincluder::getJSModel('common')->addWPSEOHooks($module_name,$filename);
                if ($wp_filesystem->exists($incfilename)) {
                    require_once($incfilename);
                }
                include_once $file_path; //
            }else{
                /*$file_path = self::getPluginPath('premiumplugin','file','missingaddon');
                if(is_array($file_path)){
                    include_once $file_path['tmpl_file'];
                }else{
                    include_once $file_path; //
                }*/
            }
        } else {
            $file_path = self::getPluginPath($filename,'file');
            if($wp_filesystem->exists($file_path)){
                include_once $file_path; //
            }else{
               /* $file_path = self::getPluginPath('premiumplugin','file');
                include_once $file_path; //*/
            }
        }



        return;
    }

    /*
     * Static function to handle the page slugs
     */

    public static function include_slug($page_slug) {
        include_once WPJOBPORTAL_PLUGIN_PATH . 'modules/wp-job-portal-controller.php';
    }

    /*
     * Static function for the model object
     */

    public static function getJSModel($modelname) {
        $file_path = self::getPluginPath($modelname,'model');
        include_once $file_path;
        $classname = "WPJOBPORTAL" . $modelname . 'Model';
        //var_dump($classname);
        //exit();
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes objects
     */

    public static function getObjectClass($classname) {

        $file_path = self::getPluginPath($classname,'class');
        include_once $file_path;
        $classname = "WPJOBPORTAL" . $classname ;
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes not objects
     */

    public static function getClassesInclude($classname) {
        $file_path = self::getPluginPath($classname,'class');
        include_once $file_path;
    }

    /*
     * Static function for the table object
     */

    public static function getJSTable($tableclass) {
        $file_path = self::getPluginPath($tableclass,'table');
        require_once WPJOBPORTAL_PLUGIN_PATH . 'includes/tables/table.php';
        include_once $file_path;
        $classname = "WPJOBPORTAL" . $tableclass . 'Table';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the controller object
     */

    public static function getJSController($controllername) {
        $file_path = self::getPluginPath($controllername,'controller');

        include_once $file_path;
        $classname = "WPJOBPORTAL".$controllername . "Controller";
        $obj = new $classname();
        return $obj;
    }
/*
    public static function loadComponents($filenames){
        if(!is_array($filenames)){
            $filenames = array($filenames);
        }
        foreach($filenames as $filename){
            //load component template
            $templatepath = self::getComponentTemplatePath($filename);
            if(file_exists($templatepath)){
                echo '<div id="wpjobportal-'.$filename.'" style="display:none;">';
                include $templatepath;
                echo '</div>';
            }

            //load component js file
            $jsfilepath = self::getComponentJsUrl($filename);
            wp_enqueue_script($filename,$jsfilepath,array(),false,1);
        }
    }

    public static function getComponentJsUrl($filename){
        return esc_url(WPJOBPORTAL_PLUGIN_URL) . '/components_js/'.$filename.'.vue.js';
    }

    public static function getComponentTemplatePath($filename){
        return WPJOBPORTAL_PLUGIN_PATH . '/components/'.$filename.'.vue.php';
    }*/

    public static function getTemplate($template_name, $args = array()){
        $template_name = wpjobportalphplib::wpJP_clean_file_path($template_name);
        $template = self::locateTemplate($template_name,$args);
        if(!empty($args) && is_array($args)){
            extract($args);
        }
        return include $template;
    }

    public static function getTemplateHtml($template_name, $args = array()){
        ob_start();
        self::getTemplate($template_name, $args);
        return ob_get_clean();
    }

    public static function locateTemplate($template_name,$args= array()){
        $template_name = wpjobportalphplib::wpJP_clean_file_path($template_name);
        $module = wpjobportalphplib::wpJP_substr($template_name, 0, wpjobportalphplib::wpJP_strpos($template_name, '/'));
        $template_name = wpjobportalphplib::wpJP_substr($template_name, wpjobportalphplib::wpJP_strpos($template_name, '/')+1);
        $module_name = isset($args['module_name']) ? $args['module_name'] : null;
        /* ADDONS PLUGIN DIR FOR TEMPLATE => module_name  */
       if($module_name!=null && $module_name!=""){
    //To Manage Template Working IN Addons
            if(in_array($args['module_name'], wpjobportal::$_active_addons)){
                // Check in active theme first
                $theme_template = get_stylesheet_directory() . '/wp-job-portal/' . $args['module_name'] . '/tmpl/views/' . $template_name . '.php';
                if(file_exists($theme_template)) {
                    $template = $theme_template;
                } else if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled() && file_exists(WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $args['module_name'] . '/tmpl/views/' . $template_name . '.php')){
                    $template = WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $args['module_name'] . '/tmpl/views/' . $template_name . '.php';
                }else{
                    $path = WP_PLUGIN_DIR.'/'.'wp-job-portal-'.$args['module_name'].'/';
                    $template = $path.'module/tmpl/views/'.$template_name.'.php';
                }
            }
        }else{
            if($module == 'templates'){
                // Check in active theme first
                $theme_template = get_stylesheet_directory() . '/wp-job-portal/templates/' . $template_name . '.php';
                if(file_exists($theme_template)) {
                    $template = $theme_template;
                } else {
                    $template = WPJOBPORTAL_PLUGIN_PATH.'templates/'.$template_name.'.php';
                }
            }else{
                // Check in active theme first
                $theme_template = get_stylesheet_directory() . '/wp-job-portal/' . $module . '/tmpl/' . $template_name . '.php';
                if(file_exists($theme_template)) {
                    $template = $theme_template;
                } else if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled() && file_exists(WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $module . '/tmpl/' . $template_name . '.php')){
                    $template = WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/'.$module.'/tmpl/'.$template_name.'.php';
                } else {
                    $template = WPJOBPORTAL_PLUGIN_PATH.'modules/'.$module.'/tmpl/'.$template_name.'.php';
                }
            }
        }

       return $template;
    }

    public static function getPluginPath($module,$type,$file_name = '') {
        $module = wpjobportalphplib::wpJP_clean_file_path($module);
        if($file_name != ''){
            $file_name = wpjobportalphplib::wpJP_clean_file_path($file_name);
        }

        //$addons_secondry = array('socialmedia','facebook','linkedin','xing','folderresume','mystats','creditslog','creditspack','purchasehistory','purchase','userpackage','subscription','invoice','userpackage','jobalertsetting','package','jobseekerviewcompany','employerviewresume','rating','transactionlog','jobalertcities','paymentmethodconfiguration','paypal','Stripe','resumeformAdons','ResumeViewAdons','Stripe/init','coverletter');
        $addons_secondry = array('socialmedia','facebook','linkedin','xing','folderresume','mystats','creditslog','creditspack','purchasehistory','purchase','userpackage','subscription','invoice','userpackage','jobalertsetting','package','jobseekerviewcompany','employerviewresume','rating','transactionlog','jobalertcities','paymentmethodconfiguration','paypal','Stripe','resumeformAdons','ResumeViewAdons','Stripe/init','coverletter','popup','multicompany');
        if(in_array($module, wpjobportal::$_active_addons) && $module != 'theme' && $module != 'customfields'){

            $path = WP_PLUGIN_DIR.'/'.'wp-job-portal-'.$module.'/';
            switch ($type) {
                case 'file':
                    if($file_name != ''){
                        // Check in active theme first
                        $theme_template = get_stylesheet_directory() . '/wp-job-portal/' . $module . '/tmpl/' . $file_name . '.php';
                        if (file_exists($theme_template)) {
                            $file_path['inc_file'] = $path . 'module/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = $theme_template;
                        } else if (locate_template('wp-job-portal/' . $module . '-' . $file_name . '.php', 0, 1)) {
                            $file_path['inc_file'] = $path . 'module/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = locate_template('wp-job-portal/' . $module . '-' . $file_name . '.php', 0, 1);
						}elseif(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled() && file_exists(WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $module . '/tmpl/' . $file_name . '.php')){
                            $file_path['inc_file'] = $path . 'module/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/'.$module.'/tmpl/'.$file_name.'.php';
                        } else {
                            $file_path = $path . 'module/tmpl/' . $file_name . '.php';
                        }
                    }else{
                        $file_path = $path . 'module/controller.php';
                    }
                    break;
                case 'model':
                    $file_path = $path . 'module/model.php';
                    break;
                case 'class':
                    $file_path = $path . 'classes/' . $module . '.php';
                    break;
                case 'controller':
                    $file_path = $path . 'module/controller.php';
                    break;
                case 'table':
                    $file_path = $path . 'includes/' . $module . '-table.php';
                    break;
            }

        }elseif(in_array($module, $addons_secondry)){ // to handle the case of modules that are submodules for some addon
            $parent_module = '';
            switch ($module) {// to identify addon for submodules.
                case 'folderresume':
                    $parent_module = 'folder';
                    break;
                    case 'socialmedia':
                    case 'facebook':
                    case 'linkedin':
                    case 'xing':
                    $parent_module = 'sociallogin';
                    break;
                case 'mystats':
                    $parent_module = 'reports';
                    break;
                case 'jobalertsetting':
                    $parent_module = 'jobalert';
                    break;
                case 'jobalertcities':
                    $parent_module = 'jobalert';
                    break;
                case 'creditslog':
                case 'creditspack':
                case 'purchasehistory':
                case 'purchase':
                case 'userpackage':
                case 'subscription':
                case 'package':
                case 'jobseekerviewcomny':
                case 'employerviewresume':
                case 'transactionlog':
                case 'paymentmethodconfiguration':
                case 'jobseekerviewcompany':
                case 'Stripe':
                case 'paypal':
                case 'invoice':
                case 'Stripe/init':
                case 'popup':
                    $parent_module = 'credits';
                    break;
                case 'customfields':
                    $parent_module = 'customfield';
                    break;
                case 'cronjob':
                    $parent_module = 'cronjob';
                    break;
                case 'rating':
                    $parent_module = 'resumeaction';
                    break;
                case 'resumeformAdons':
                    $parent_module  = 'advanceresumebuilder';
                    break;
                case 'ResumeViewAdons':
                    $parent_module  = 'advanceresumebuilder';
                    break;
                }
                if($parent_module == "customfield" && !in_array('customfield', wpjobportal::$_active_addons)){
                  $path = WP_PLUGIN_DIR.'/'.'wp-job-portal/includes/';
                }else{
                    $path = WP_PLUGIN_DIR.'/'.'wp-job-portal-'.$parent_module.'/';
                }
            if(in_array($parent_module, wpjobportal::$_active_addons) || $parent_module == "customfield"){
                switch ($type) {
                    case 'file':
                        if($file_name != ''){
                            // Check in active theme first
                            $theme_template_with_parent = get_stylesheet_directory() . '/wp-job-portal/' . $parent_module . '/' . $module . '/tmpl/' . $file_name . '.php';
                            $theme_template = get_stylesheet_directory() . '/wp-job-portal/' . $module . '-' . $file_name . '.php';
                            if (file_exists($theme_template_with_parent)) {
                                $file_path['inc_file'] = $path . $module.'/tmpl/' . $file_name . '.inc.php';
                                $file_path['tmpl_file'] = $theme_template_with_parent;
                            } else if (file_exists($theme_template)) {
                                $file_path['inc_file'] = $path . $module.'/tmpl/' . $file_name . '.inc.php';
                                $file_path['tmpl_file'] = $theme_template;
                            } else if (locate_template('wp-job-portal/' . $module . '-' . $file_name . '.php', 0, 1)) {
                                $file_path['inc_file'] = $path . $module.'/tmpl/' . $file_name . '.inc.php';
                                $file_path['tmpl_file'] = locate_template('wp-job-portal/' . $module . '-' . $file_name . '.php', 0, 1);
							}elseif(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled() && file_exists(WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $module . '-' . $file_name . '.php')){
                                $file_path['inc_file'] = $path . $module.'/tmpl/' . $file_name . '.inc.php';
								$file_path['tmpl_file'] = WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $module . '-' . $file_name . '.php';
                            }else{
                                if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled() && file_exists(WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $parent_module . '/'. $module . '/tmpl/' . $file_name . '.php')){
                                    $file_path = WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $parent_module . '/'. $module . '/tmpl/' . $file_name . '.php';
                                } else {
                                    $file_path = $path . $module.'/tmpl/' . $file_name . '.php';
                                }
                            }
                        }else{
                            $file_path = $path . $module.'/controller.php';
                        }
                        break;
                    case 'model':
                        $file_path = $path . $module.'/model.php';
                        break;

                    case 'class':
                        $file_path = $path . 'classes/' . $module . '.php';
                        break;
                    case 'controller':
                        $file_path = $path . $module.'/controller.php';
                        break;
                    case 'table':
                        $file_path = $path . 'includes/' . $module . '-table.php';
                        break;
                }
            }else{
               // $file_path = self::getPluginPath('premiumplugin','file');
                }
            }else{
            $path = WPJOBPORTAL_PLUGIN_PATH;
            switch ($type) {
                case 'file':
                    if($file_name != ''){
                        // Check in active theme first
                        $theme_template = get_stylesheet_directory() . '/wp-job-portal/' . $module . '/tmpl/' . $file_name . '.php';
                        if (file_exists($theme_template)) {
                            $file_path['inc_file'] = $path . 'modules/' . $module . '/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = $theme_template;
                        } else if (locate_template('wp-job-portal/' . $module . '-' . $file_name . '.php', 0, 1)) {
                            $file_path['inc_file'] = $path . 'modules/' . $module . '/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = locate_template('wp-job-portal/' . $module . '-' . $file_name . '.php', 0, 1);
						}elseif(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled() && file_exists(WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $module . '/tmpl/' . $file_name . '.php')){

                            $file_path['inc_file'] = $path . 'modules/' . $module.'/tmpl/' . $file_name . '.inc.php';
                            $file_path['tmpl_file'] = WP_PLUGIN_DIR.'/'.'wp-job-portal-elegantdesign/' . $module . '/tmpl/' . $file_name . '.php';
                        } else {
                            $file_path = $path . 'modules/' . $module . '/tmpl/' . $file_name . '.php';
                        }
                    }else{
                        $file_path = $path . 'modules/' . $module . '/controller.php';
                    }
                    break;
                case 'model':
                        $file_path = $path . 'modules/' . $module . '/model.php';
                    break;

                case 'class':
                    $file_path = $path . 'includes/classes/' . $module . '.php';
                    break;
                case 'controller':
                        $file_path = $path . 'modules/' . $module . '/controller.php';
                    break;
                case 'table':
                    $file_path = $path . 'includes/tables/' . $module . '.php';;
                    break;
            }
        }
        //echo $file_path;exit()
        return $file_path;
    }


}

$includer = new WPJOBPORTALincluder();
?>
