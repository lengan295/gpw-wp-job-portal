<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALwpjobportalwidgetsModel {

    function __construct() {

    }

    function listModuleJobs($layoutName, $jobs, $location, $showtitle, $title, $listtype, $noofjobs, $category, $subcategory, $company, $jobtype, $posteddate, $theme, $separator, $moduleheight, $jobsinrow, $jobsinrowtab, $jobmargintop, $jobmarginleft, $companylogo, $logodatarow, $sliding, $datacolumn, $speedTest, $slidingdirection, $consecutivesliding, $jobheight, $companylogowidth, $companylogoheight) {
        $speed = 50;
        if(!is_numeric($speedTest)){
            $speedTest =0;
        }
        if ($speedTest < 5) {
            for ($i = 5; $i > $speedTest; $i--)
                $speed += 10;
            if ($speed > 100)
                $speed = 100;
        }elseif ($speedTest > 5) {
            for ($i = 5; $i < $speedTest; $i++)
                $speed -= 10;
            if ($speed < 10)
                $speed = 10;
        }
        $dateformat = wpjobportal::$_configuration['date_format'];

        $moduleName = $layoutName;

        $contentswrapperstart = '';
        $contents = '';
        if ($jobs) {
            if ($listtype == 0) { //list style
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" style="height:' . esc_attr($moduleheight) . 'px;" >';
                if ($showtitle == 1) {

                    $contentswrapperstart .= '
                        <div id="tp_heading" class="wjportal-mod-heading">
                            ' . esc_html($title) . '
                        </div>
                    ';
                }
                $contentswrapperstart .= '<div id="wpjobportal_modulelist_titlebar" class="' . esc_attr($moduleName) . '" ><span id="whiteback"></span>';
                //For desktop
                $desktop_w = 1;
                if (($company == 1 || $company == 2 || $company == 4 || $company == 6) || ($companylogo == 1 || $companylogo == 2 || $companylogo == 4 || $companylogo == 6)) {
                    $desktop_w++;
                }
                if ($category == 1 || $category == 2 || $category == 3 || $category == 5) {
                    $desktop_w++;
                }
                if ($jobtype == 1 || $jobtype == 2 || $jobtype == 3 || $jobtype == 5) {
                    $desktop_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 3 || $posteddate == 5) {
                    $desktop_w++;
                }
                if ($location == 1 || $location == 2 || $location == 3 || $location == 5) {
                    $desktop_w++;
                }
                //For tablet
                $tablet_w = 1;
                if (($company == 1 || $company == 2 || $company == 4 || $company == 6) || ($companylogo == 1 || $companylogo == 2 || $companylogo == 4 || $companylogo == 6)) {
                    $tablet_w++;
                }
                if ($category == 1 || $category == 2 || $category == 4 || $category == 6) {
                    $tablet_w++;
                }
                if ($jobtype == 1 || $jobtype == 2 || $jobtype == 4 || $jobtype == 6) {
                    $tablet_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 4 || $posteddate == 6) {
                    $tablet_w++;
                }
                if ($location == 1 || $location == 2 || $location == 4 || $location == 6) {
                    $tablet_w++;
                }
                //For mobile
                $mobile_w = 1;
                if (($company == 1 || $company == 2 || $company == 4 || $company == 6) || ($companylogo == 1 || $companylogo == 2 || $companylogo == 4 || $companylogo == 6)) {
                    $mobile_w++;
                }
                if ($category == 1 || $category == 3 || $category == 4 || $category == 7) {
                    $mobile_w++;
                }
                if ($jobtype == 1 || $jobtype == 3 || $jobtype == 4 || $jobtype == 7) {
                    $mobile_w++;
                }
                if ($posteddate == 1 || $posteddate == 3 || $posteddate == 4 || $posteddate == 7) {
                    $mobile_w++;
                }
                if ($location == 1 || $location == 3 || $location == 4 || $location == 7) {
                    $mobile_w++;
                }

                if ($company != 0 || $companylogo != 0) {
                    $class = $this->getClasses($companylogo);
                    $class .= $this->getClasses($company);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Company', 'wp-job-portal')) . '</span>';
                }
                $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' visible-all">' . esc_html(__('Title', 'wp-job-portal')) . '</span>';
                if ($category != 0) {
                    $class = $this->getClasses($category);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Category', 'wp-job-portal')) . '</span>';
                }
                if ($jobtype == 1) {
                    $class = $this->getClasses($jobtype);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Type', 'wp-job-portal')) . '</span>';
                }
                if ($location == 1) {
                    $class = $this->getClasses($location);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Location', 'wp-job-portal')) . '</span>';
                }
                if ($posteddate == 1) {
                    $class = $this->getClasses($posteddate);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Posted', 'wp-job-portal')) . '</span>';
                }
                $contentswrapperstart .= '</div>';
                $wpdir = wp_upload_dir();
                $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
                if (isset($jobs)) {
                    foreach ($jobs as $job) {
                        $contents .= '<div id="wpjobportal_modulelist_databar"><span id="whiteback"></span>';
                        if ($company != 0 || $companylogo != 0) {
                            $class = $this->getClasses($company);
                            $class .= $this->getClasses($companylogo);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">';
                            if ($companylogo != 0) {
                                $class = $this->getClasses($companylogo);

                                $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));

                                $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
                                if($job->logofilename != ''){
                                    $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;
                                }
                                $contents .= '<a href=' . esc_url($c_l) . '><img  src="' . esc_url($logo) . '"  /></a>';
                            }
                            if ($company != 0) {
                                $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $contents .= '<span id="themeanchor"><a class="anchor" href=' . esc_url($c_l) . '>' . esc_html($job->companyname) . '</a></span>';
                            }
                            $contents .= '</span>';
                        }
                        $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$job->jobaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' visible-all">
                                        <span id="themeanchor">
                                            <a class="anchor" href="' . esc_url($an_link) . '">
                                                ' . esc_html($job->title) . '
                                            </a>
                                        </span>
                                        </span>';
                        if ($category != 0) {
                            $class = $this->getClasses($category);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($job->cat_title) . '</span>';
                        }
                        if ($jobtype != 0) {
                            $class = $this->getClasses($jobtype);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . $job->jobtypetitle . '</span>';
                        }
                        if ($location != 0) {
                            $class = $this->getClasses($location);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . $job->location . '</span>';
                        }
                        if ($posteddate != 0) {
                            $class = $this->getClasses($posteddate);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . date_i18n($dateformat, strtotime($job->created)) . '</span>';
                        }
                        $contents .= '</div>';
                    }
                }

                if ($sliding == 1) { // Sliding is enable
                    $consectivecontent = '';
                    for ($i = 0; $i < $consecutivesliding; $i++) {
                        $consectivecontent .= $contents;
                    }

                    if ($slidingdirection == 1) { // UP
                        $contents = '<marquee id="mod_hotwpjobportal"  style="height:' . esc_attr($moduleheight) . 'px;" direction="up" scrolldelay="' . $speed . '" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>' . $consectivecontent . '</marquee>';
                    }
                }
                $contentswrapperend = '</div>';
            } else { //box style
                $jobwidthclass = "modjob" . $jobsinrow;
                $jobtabwidthclass = "modjobtab" . $jobsinrowtab;
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" >';
                if ($showtitle == 1) {
                    $contentswrapperstart .= '
                        <div id="tp_heading" class="wjportal-mod-heading">
                            ' . esc_html($title) . '
                        </div>
                    ';
                }
                $inlineCSS = 'margin-top:' . esc_attr($jobmargintop) . 'px;margin-left:' . esc_attr($jobmarginleft) . 'px;';
                $wpdir = wp_upload_dir();
                $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
                if (isset($jobs)) {
                    foreach ($jobs as $job) {
                        $contents .= '<div id="wpjobportal_module_wrap" class="' . esc_attr($jobwidthclass) . ' ' . esc_attr($jobtabwidthclass) . ' wjportal-job-mod">
                                      <div id="wpjobportal_module">';
                        $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$job->jobaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $dataclass = 'data100';
                        if ($companylogo != 0) {
                            $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            if ($logodatarow == 1) { // Combine
                                $logoclass = "comp40";
                                $dataclass = "data60";
                                $logocss = 'width:' . esc_attr($companylogowidth) . 'px;';
                            } else {
                                $logoclass = "comp100";
                                $dataclass = "data100";
                                $logocss = 'height:' . esc_attr($companylogoheight) . 'px;';
                            }
                            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
                            if($job->logofilename != ''){
                                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;
                            }

                            /*$logoclass .= $this->getClasses($companylogo);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . $logoclass . ' wjportal-job-logo" >
                                                    <a href=' . esc_url($c_l) . '><img  src="' . esc_url($logo) . '" /></a>
                                                </div>
                                              ';*/
                        }
                        $contents .= '<div class="wjportal-job-cont">';
                        $contents .= '<div id="wpjobportal_module_heading" class="wjportal-job-data wjportal-job-title">
                                        <a class="wjportal-jobname" href="' . esc_url($an_link) . '">
                                            ' . esc_html($job->title) . '
                                        </a>
                                      </div>';
                        $contents .= '<div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($dataclass) . ' visible-all">';
                        $colwidthclass = esc_attr('modcolwidth') . esc_attr($datacolumn);
                        if ($company != 0) {
                            $class = $this->getClasses($company);
                            $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-job-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-job-data-tit">' . esc_html(__('Company', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-job-data-val">
                                                        <a class="wjportal-compname" href=' . esc_url($c_l) . '>' . esc_html($job->companyname) . '</a>
                                                    </span>
                                                </div>
                                              ';
                        }
                        if ($category != 0) {
                            $class = $this->getClasses($category);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-job-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-job-data-tit">' . esc_html(__('Category', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-job-data-val">' . esc_html($job->cat_title) . '</span>
                                                </div>
                                              ';
                        }
                        if ($jobtype != 0) {
                            $class = $this->getClasses($jobtype);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-job-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-job-data-tit">' . esc_html(__('Type', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-job-data-val">' . $job->jobtypetitle . '</span>
                                                </div>
                                              ';
                        }
                        if ($location != 0) {
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-job-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-job-data-tit">' . esc_html(__('Location', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-job-data-val">' . $job->location . '</span>
                                                </div>
                                              ';
                        }
                        if ($posteddate != 0) {
                            $class = $this->getClasses($posteddate);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-job-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-job-data-tit">' . esc_html(__('Posted', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-job-data-val">' . date_i18n($dateformat, strtotime($job->created)) . '</span>
                                                </div>
                                              ';
                        }
                        $contents .= '</div>
                                </div>
                            </div>
                            </div>';
                    }
                }
                $contentswrapperend = '</div>';
            }
            return $contentswrapperstart . $contents . $contentswrapperend;
        }
    }

    function getClasses($for) {
        $class = '';
        switch ($for) {
            case 1: // Show all
                $class = ' visible-all ';
                break;
            case 2: // Show desktop and tablet
                $class = ' visible-desktop visible-tablet ';
                break;
            case 3: // Show desktop and mobile
                $class = ' visible-desktop visible-mobile ';
                break;
            case 4: // Show tablet and mobile
                $class = ' visible-tablet visible-mobile ';
                break;
            case 5: // Show desktop
                $class = ' visible-desktop ';
                break;
            case 6: // Show tablet
                $class = ' visible-tablet ';
                break;
            case 7: // Show mobile
                $class = ' visible-mobile ';
                break;
        }
        return $class;
    }

    function listModuleCompanies($layoutName, $companies, $noofcompanies, $category, $posteddate, $listtype, $theme, $location, $moduleheight, $jobwidth, $jobheight, $jobfloat, $jobmargintop, $jobmarginleft, $companylogo, $companylogowidth, $companylogoheight, $datacolumn, $listtype_extra, $title, $showtitle, $speedTest, $sliding, $slidingdirection, $consecutivesliding, $resumesinrow, $resumesinrowtab, $logodatarow) {

        $speed = 50;
        if(!is_numeric($speedTest)){
            $speedTest = 0;
        }
        if ($speedTest < 5) {
            for ($i = 5; $i > $speedTest; $i--)
                $speed += 10;
            if ($speed > 100)
                $speed = 100;
        }elseif ($speedTest > 5) {
            for ($i = 5; $i < $speedTest; $i++)
                $speed -= 10;
            if ($speed < 10)
                $speed = 10;
        }
        $moduleName = $layoutName;
        $contentswrapperstart = '';
        $contents = '';

        $dateformat = wpjobportal::$_configuration['date_format'];
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        if ($companies) {
            if ($listtype == 0) { //list style
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" style="height:' . esc_attr($moduleheight) . 'px;" >';
                if ($showtitle == 1) {

                    $contentswrapperstart .= '
                        <div id="tp_heading" class="wjportal-mod-heading">
                            ' . esc_html($title) . '
                        </div>
                    ';
                }
                $contentswrapperstart .= '<div id="wpjobportal_modulelist_titlebar" class="' . esc_attr($moduleName) . '" ><span id="whiteback"></span>';
                //For desktop
                $desktop_w = 1;
                if ($noofcompanies == 1 || $noofcompanies == 2 || $noofcompanies == 4 || $noofcompanies == 6) {
                    $desktop_w++;
                }
                if ($category == 1 || $category == 2 || $category == 4 || $category == 6) {
                    $desktop_w++;
                }
                if ($title == 1 || $title == 2 || $title == 3 || $title == 5) {
                    $desktop_w++;
                }
                if ($location == 1 || $location == 2 || $location == 3 || $location == 5) {
                    $desktop_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 3 || $posteddate == 5) {
                    $desktop_w++;
                }
                //For tablet
                $tablet_w = 1;
                if ($noofcompanies == 1 || $noofcompanies == 2 || $noofcompanies == 4 || $noofcompanies == 6) {
                    $tablet_w++;
                }
                if ($category == 1 || $category == 2 || $category == 4 || $category == 6) {
                    $tablet_w++;
                }
                if ($title == 1 || $title == 2 || $title == 3 || $title == 5) {
                    $tablet_w++;
                }
                if ($location == 1 || $location == 2 || $location == 3 || $location == 5) {
                    $tablet_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 3 || $posteddate == 5) {
                    $tablet_w++;
                }
                //For mobile
                $mobile_w = 1;
                if ($noofcompanies == 1 || $noofcompanies == 2 || $noofcompanies == 4 || $noofcompanies == 6) {
                    $mobile_w++;
                }
                if ($category == 1 || $category == 2 || $category == 4 || $category == 6) {
                    $mobile_w++;
                }
                if ($title == 1 || $title == 2 || $title == 3 || $title == 5) {
                    $mobile_w++;
                }
                if ($location == 1 || $location == 2 || $location == 3 || $location == 5) {
                    $mobile_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 3 || $posteddate == 5) {
                    $mobile_w++;
                }

                if ($noofcompanies != 0) {
                    $class = $this->getClasses($noofcompanies);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Photo', 'wp-job-portal')) . '</span>';
                }
                if ($category != 0) {
                    $class = $this->getClasses($category);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Category', 'wp-job-portal')) . '</span>';
                }
                if ($location != 0) {
                    $class = $this->getClasses($location);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Location', 'wp-job-portal')) . '</span>';
                }
                if ($posteddate != 0) {
                    $class = $this->getClasses($posteddate);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Posted', 'wp-job-portal')) . '</span>';
                }
                $contentswrapperstart .= '</div>';
                $wpdir = wp_upload_dir();
                if (isset($companies)) {
                    foreach ($companies as $company) {
                        $contents .= '<div id="wpjobportal_modulelist_databar"><span id="whiteback"></span>';
                        if ($companylogo != 0) {
                            $class = $this->getClasses($companylogo);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">';
                            $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));

                            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
                            if($company->logofilename != ''){
                                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
                            }

                            $contents .= '<a href=' . esc_url($c_l) . '><img  src="' . esc_url($logo) . '"  /></a>';
                            $contents .= '</span>';
                        }
                        if ($title != 0) {
                            $class = $this->getClasses($title);
                           $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">
                                            <span id="themeanchor">
                                                <a class="anchor" href="' . esc_url($an_link) . '">
                                                    ' . esc_html($company->title) . '
                                                </a>
                                            </span>
                                            </span>';
                        }
                        if ($category != 0) {
                            $class = $this->getClasses($category);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($company->cat_title) . '</span>';
                        }
                        if ($location != 0) {
                            $class = $this->getClasses($location);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($company->location) . '</span>';
                        }
                        if ($posteddate != 0) {
                            $class = $this->getClasses($posteddate);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(date_i18n($dateformat, strtotime($company->created))) . '</span>';
                        }
                        $contents .= '</div>';
                    }
                }

                $contentswrapperend = '</div>';
            } else { //box style
                $jobwidthclass = "modjob" . esc_attr($resumesinrow);
                $jobtabwidthclass = "modjobtab" . esc_attr($resumesinrowtab);
                //$contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" style="height:' . esc_attr($moduleheight) . 'px;overflow:hidden;">';
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" >';
                if ($showtitle == 1) {
                    $contentswrapperstart .= '
                                <div id="tp_heading" class="wjportal-mod-heading">
                                    ' . esc_html($title) . '
                                </div>
                    ';
                }
                $inlineCSS = 'margin-top:' . esc_attr($jobmargintop) . 'px;margin-left:' . esc_attr($jobmarginleft) . 'px;';
                if (isset($companies)) {
                    $wpdir = wp_upload_dir();
                    foreach ($companies as $company) {
                        $contents .= '<div id="wpjobportal_module_wrap" class="' . esc_attr($jobwidthclass) . ' ' . esc_attr($jobtabwidthclass) . ' wjportal-comp-mod ">
                                      <div id="wpjobportal_module">';
                        $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $dataclass = 'data100';
                        if ($companylogo != 0) {
                            $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            if ($logodatarow == 1) { // Combine
                                $logoclass = "comp40";
                                $dataclass = "data60";
                                $logocss = 'width:' . esc_attr($companylogowidth) . 'px;';
                            } else {
                                $logoclass = "comp100";
                                $dataclass = "data100";
                                $logocss = 'height:' . esc_attr($companylogoheight) . 'px;';
                            }
                            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
                            if($company->logofilename != ''){
                                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
                            }

                            /*$logoclass .= $this->getClasses($companylogo);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . $logoclass . ' wjportal-comp-logo" >
                                                    <a href=' . esc_url($c_l) . '><img  src="' . esc_url($logo) . '" style="' . $logocss . 'display:block;margin:auto;" /></a>
                                                </div>
                                              ';*/
                        }
                        $contents .= '<div class="wjportal-comp-cont">';
                        $contents .= '<div id="wpjobportal_module_heading" class="wjportal-company-data wjportal-company-title">
                                        <a class="wjportal-companyname" href="' . esc_url($an_link) . '">
                                            ' . esc_html($company->name) . '
                                        </a>
                                      </div>';
                        $contents .= '<div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($dataclass) . ' visible-all ">';
                        $colwidthclass = 'modcolwidth' . esc_attr($datacolumn);
                        if ($category != 0) {
                            $class = $this->getClasses($category);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-company-data wjportal-company-catg">
                                                </div>
                                              ';
                        }
                        if ($location != 0) {
                            $class = $this->getClasses($location);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-company-data wjportal-company-loc">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-company-data-tit">' . esc_html(__('Location', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-company-data-val">' . esc_html($company->location) . '</span>
                                                </div>
                                              ';
                        }
                        if ($posteddate != 0) {
                            $class = $this->getClasses($posteddate);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-company-data ">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-company-data-tit">' . esc_html(__('Posted', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-company-data-val">' . esc_html(date_i18n($dateformat, strtotime($company->created))) . '</span>
                                                </div>
                                              ';
                        }
                        $contents .= '</div>
                                </div>
                            </div>
                        </div>';
                    }
                }
                $contentswrapperend = '</div>';
            }

            return $contentswrapperstart . $contents . $contentswrapperend;
        }
    }

    function listModuleResumes($layoutName, $resumes, $noofresumes, $applicationtitle, $name, $experience, $available, $gender, $nationality, $location, $category, $subcategory, $jobtype, $posteddate, $separator, $moduleheight, $resumeheight, $resumemargintop, $resumemarginleft, $photowidth, $photoheight, $datacolumn, $listtype, $title, $showtitle, $speedTest, $sliding, $consecutivesliding, $slidingdirection, $resumephoto, $resumesinrow, $resumesinrowtab, $logodatarow) {
        $speed = 50;
        if(!is_numeric($speedTest)){
            $speedTest = 0;
        }
        if ($speedTest < 5) {
            for ($i = 5; $i > $speedTest; $i--)
                $speed += 10;
            if ($speed > 100)
                $speed = 100;
        }elseif ($speedTest > 5) {
            for ($i = 5; $i < $speedTest; $i++)
                $speed -= 10;
            if ($speed < 10)
                $speed = 10;
        }

        $moduleName = $layoutName;

        $contentswrapperstart = '';
        $contents = '';

        $dateformat = wpjobportal::$_configuration['date_format'];
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');


        if ($resumes) {
            if ($listtype == 0) { //list style
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" style="height:' . esc_attr($moduleheight) . 'px;" >';
                if ($showtitle == 1) {
                    $contentswrapperstart .= '
                        <div id="tp_heading" class="wjportal-mod-heading">
                            ' . esc_html($title) . '
                        </div>
                    ';
                }
                $contentswrapperstart .= '<div id="wpjobportal_modulelist_titlebar" class="' . esc_attr($moduleName) . '" ><span id="whiteback"></span>';
                //For desktop
                $desktop_w = 1;
                if ($resumephoto == 1 || $resumephoto == 2 || $resumephoto == 4 || $resumephoto == 6) {
                    $desktop_w++;
                }
                if ($applicationtitle == 1 || $applicationtitle == 2 || $applicationtitle == 4 || $applicationtitle == 6) {
                    $desktop_w++;
                }
                if ($name == 1 || $name == 2 || $name == 3 || $name == 5) {
                    $desktop_w++;
                }
                if ($category == 1 || $category == 2 || $category == 3 || $category == 5) {
                    $desktop_w++;
                }
                if ($jobtype == 1 || $jobtype == 2 || $jobtype == 3 || $jobtype == 5) {
                    $desktop_w++;
                }
                if ($experience == 1 || $experience == 2 || $experience == 3 || $experience == 5) {
                    $desktop_w++;
                }
                if ($available == 1 || $available == 2 || $available == 3 || $available == 5) {
                    $desktop_w++;
                }
                if ($gender == 1 || $gender == 2 || $gender == 3 || $gender == 5) {
                    $desktop_w++;
                }
                if ($nationality == 1 || $nationality == 2 || $nationality == 3 || $nationality == 5) {
                    $desktop_w++;
                }
                if ($location == 1 || $location == 2 || $location == 3 || $location == 5) {
                    $desktop_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 3 || $posteddate == 5) {
                    $desktop_w++;
                }
                //For tablet
                $tablet_w = 1;
                if ($resumephoto == 1 || $resumephoto == 2 || $resumephoto == 4 || $resumephoto == 6) {
                    $tablet_w++;
                }
                if ($applicationtitle == 1 || $applicationtitle == 2 || $applicationtitle == 4 || $applicationtitle == 6) {
                    $tablet_w++;
                }
                if ($name == 1 || $name == 2 || $name == 3 || $name == 5) {
                    $tablet_w++;
                }
                if ($category == 1 || $category == 2 || $category == 3 || $category == 5) {
                    $tablet_w++;
                }
                if ($jobtype == 1 || $jobtype == 2 || $jobtype == 3 || $jobtype == 5) {
                    $tablet_w++;
                }
                if ($experience == 1 || $experience == 2 || $experience == 3 || $experience == 5) {
                    $tablet_w++;
                }
                if ($available == 1 || $available == 2 || $available == 3 || $available == 5) {
                    $tablet_w++;
                }
                if ($gender == 1 || $gender == 2 || $gender == 3 || $gender == 5) {
                    $tablet_w++;
                }
                if ($nationality == 1 || $nationality == 2 || $nationality == 3 || $nationality == 5) {
                    $tablet_w++;
                }
                if ($location == 1 || $location == 2 || $location == 3 || $location == 5) {
                    $tablet_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 3 || $posteddate == 5) {
                    $tablet_w++;
                }
                //For mobile
                $mobile_w = 1;
                if ($resumephoto == 1 || $resumephoto == 2 || $resumephoto == 4 || $resumephoto == 6) {
                    $mobile_w++;
                }
                if ($applicationtitle == 1 || $applicationtitle == 2 || $applicationtitle == 4 || $applicationtitle == 6) {
                    $mobile_w++;
                }
                if ($name == 1 || $name == 2 || $name == 3 || $name == 5) {
                    $mobile_w++;
                }
                if ($category == 1 || $category == 2 || $category == 3 || $category == 5) {
                    $mobile_w++;
                }
                if ($jobtype == 1 || $jobtype == 2 || $jobtype == 3 || $jobtype == 5) {
                    $mobile_w++;
                }
                if ($experience == 1 || $experience == 2 || $experience == 3 || $experience == 5) {
                    $mobile_w++;
                }
                if ($available == 1 || $available == 2 || $available == 3 || $available == 5) {
                    $mobile_w++;
                }
                if ($gender == 1 || $gender == 2 || $gender == 3 || $gender == 5) {
                    $mobile_w++;
                }
                if ($nationality == 1 || $nationality == 2 || $nationality == 3 || $nationality == 5) {
                    $mobile_w++;
                }
                if ($location == 1 || $location == 2 || $location == 3 || $location == 5) {
                    $mobile_w++;
                }
                if ($posteddate == 1 || $posteddate == 2 || $posteddate == 3 || $posteddate == 5) {
                    $mobile_w++;
                }

                if ($resumephoto != 0) {
                    $class = $this->getClasses($resumephoto);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Photo', 'wp-job-portal')) . '</span>';
                }
                if ($applicationtitle != 0) {
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' visible-all">' . esc_html(__('Application title', 'wp-job-portal')) . '</span>';
                }
                if ($name != 0) {
                    $class = $this->getClasses($name);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Name', 'wp-job-portal')) . '</span>';
                }
                if ($category != 0) {
                    $class = $this->getClasses($category);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Category', 'wp-job-portal')) . '</span>';
                }
                if ($jobtype != 0) {
                    $class = $this->getClasses($jobtype);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Work preference', 'wp-job-portal')) . '</span>';
                }
                if ($experience != 0) {
                    $class = $this->getClasses($experience);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Experience', 'wp-job-portal')) . '</span>';
                }
                if ($available != 0) {
                    $class = $this->getClasses($available);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Available', 'wp-job-portal')) . '</span>';
                }
                if ($gender != 0) {
                    $class = $this->getClasses($gender);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Gender', 'wp-job-portal')) . '</span>';
                }
                if ($nationality != 0) {
                    $class = $this->getClasses($nationality);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Nationality', 'wp-job-portal')) . '</span>';
                }
                if ($location != 0) {
                    $class = $this->getClasses($location);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Location', 'wp-job-portal')) . '</span>';
                }
                if ($posteddate != 0) {
                    $class = $this->getClasses($posteddate);
                    $contentswrapperstart .= '<span id="wpjobportal_modulelist_titlebar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html(__('Posted', 'wp-job-portal')) . '</span>';
                }
                $contentswrapperstart .= '</div>';
                $wpdir = wp_upload_dir();
                if (isset($resumes)) {
                    foreach ($resumes as $resume) {
                        $contents .= '<div id="wpjobportal_modulelist_databar"><span id="whiteback"></span>';
                        if ($resumephoto != 0) {
                            $class = $this->getClasses($resumephoto);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">';

                            $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));

                            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
                            if($resume->photo != ''){
                                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->resumeid . '/photo/' . $resume->photo;
                            }

                            $contents .= '<a href=' . esc_url($c_l) . '><img  src="' . esc_url($logo) . '"  /></a>';
                            $contents .= '</span>';
                        }
                        if ($applicationtitle != 0) {
                            $class = $this->getClasses($applicationtitle);

                            $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">
                                            <span id="themeanchor">
                                                <a class="anchor" href="' . esc_url($an_link) . '">
                                                    ' . esc_html($resume->applicationtitle) . '
                                                </a>
                                            </span>
                                            </span>';
                        }
                        if ($name != 0) {
                            $class = $this->getClasses($name);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($resume->name) . '</span>';
                        }
                        if ($category != 0) {
                            $class = $this->getClasses($category);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($resume->cat_title) . '</span>';
                        }
                        if ($jobtype != 0) {
                            $class = $this->getClasses($jobtype);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($resume->jobtypetitle) . '</span>';
                        }
                        if ($experience != 0) {
                            $class = $this->getClasses($experience);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($resume->experiencetitle) . '</span>';
                        }
                        if ($available != 0) {
                            $class = $this->getClasses($available);
                            $resumeavail = ($resume->available == 1) ? esc_html(__('Yes', 'wp-job-portal')) : esc_html(__('No', 'wp-job-portal'));
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($resumeavail) . '</span>';
                        }
                        if ($gender != 0) {
                            $class = $this->getClasses($gender);
                            $resumegender = ($resume->gender == 1) ? esc_html(__('Male', 'wp-job-portal')) : esc_html(__('Female', 'wp-job-portal'));
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($resumegender) . '</span>';
                        }
                        if ($nationality != 0) {
                            $class = $this->getClasses($nationality);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($resume->nationalityname) . '</span>';
                        }
                        if ($location != 0) {
                            $class = $this->getClasses($location);
                            $addlocation = JSModel::getJSModel('configurations')->getConfigValue('defaultaddressdisplaytype');
                            $joblocation = !empty($job->cityname) ? $job->cityname : ' ';
                            switch ($addlocation) {
                                case 'csc':
                                    $joblocation .=!empty($job->statename) ? ', ' . $job->statename : '';
                                    $joblocation .=!empty($job->countryname) ? ', ' . $job->countryname : '';
                                    break;
                                case 'cs':
                                    $joblocation .=!empty($job->statename) ? ', ' . $job->statename : '';
                                    break;
                                case 'cc':
                                    $joblocation .=!empty($job->countryname) ? ', ' . $job->countryname : '';
                                    break;
                            }
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . esc_html($joblocation) . '</span>';
                        }
                        if ($posteddate != 0) {
                            $class = $this->getClasses($posteddate);
                            $contents .= '<span id="wpjobportal_modulelist_databar" class="desktop_w-' . esc_attr($desktop_w) . ' tablet_w-' . esc_attr($tablet_w) . ' mobile_w-' . esc_attr($mobile_w) . ' ' . esc_attr($class) . '">' . gmdate($dateformat, strtotime($resume->created)) . '</span>';
                        }
                        $contents .= '</div>';
                    }
                }

                $contentswrapperend = '</div>';
            } else { //box style
                $jobwidthclass = "modjob" . esc_attr($resumesinrow);
                $jobtabwidthclass = "modjobtab" . esc_attr($resumesinrowtab);
                //$contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" style="height:' . esc_attr($moduleheight) . 'px;overflow:hidden;" >';
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '">';
                if ($showtitle == 1) {
                    $contentswrapperstart .= '
                        <div id="tp_heading" class="wjportal-mod-heading">
                            ' . esc_html($title) . '
                        </div>
                    ';
                }
                $inlineCSS = 'margin-top:' . esc_attr($resumemargintop) . 'px;margin-left:' . esc_attr($resumemarginleft) . 'px;';
                if (isset($resumes)) {
                    $wpdir = wp_upload_dir();
                    foreach ($resumes as $resume) {
                        $contents .= '<div id="wpjobportal_module_wrap" class="' . esc_attr($jobwidthclass) . ' ' . esc_attr($jobtabwidthclass) . ' wjportal-resume-mod">
                                      <div id="wpjobportal_module">';

                        $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $dataclass = 'data100';
                        if ($resumephoto != 0) {

                            $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            if ($logodatarow == 1) { // Combine
                                $logoclass = "comp40";
                                $dataclass = "data60";
                                $logocss = 'width:' . esc_attr($photowidth) . 'px;';
                            } else {
                                $logoclass = "comp100";
                                $dataclass = "data100";
                                $logocss = 'height:' . esc_attr($photoheight) . 'px;';
                            }
                            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
                            if($resume->photo != ''){
                                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->resumeid . '/photo/' . $resume->photo;
                            }
                            $logoclass .= $this->getClasses($resumephoto);
                            /*$contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . $logoclass . ' wjportal-res-logo" >
                                                    <a href=' . esc_url($c_l) . '><img  src="' . esc_url($logo) . '" /></a>
                                                </div>
                                              ';*/
                        }
                        $contents .= '<div class="wjportal-res-cont">';
                        $contents .= '<div id="wpjobportal_module_heading" class="wjportal-res-data wjportal-res-title">
                                        <a class="wjportal-res-name" href="' . esc_url($an_link) . '">
                                            ' . esc_html($resume->name) . '
                                        </a>
                                      </div>';
                        $contents .= '<div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($dataclass) . ' visible-all">';
                        $colwidthclass = 'modcolwidth' . esc_attr($datacolumn);
                        if ($applicationtitle != 0) {
                            $class = $this->getClasses($applicationtitle);

                            $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <a class="wjportal-res-app" href=' . esc_url($an_link) . '>' . esc_html($resume->applicationtitle) . '</a>
                                                </div>
                                              ';
                        }
                        /*if ($name != 0) {
                            $class = $this->getClasses($name);

                            $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Name', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">
                                                        <a class="wjportal-res-name" href=' . esc_url($c_l) . '>' . $resume->name . '</a></span>
                                                    </span>
                                                </div>
                                              ';
                        }*/
                        if ($category != 0) {
                            $class = $this->getClasses($category);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Category', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html($resume->cat_title) . '</span>
                                                </div>
                                              ';
                        }
                        if ($jobtype != 0) {
                            $class = $this->getClasses($jobtype);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Type', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html($resume->jobtypetitle) . '</span>
                                                </div>
                                              ';
                        }
                        /*if ($experience != 0) {
                            $class = $this->getClasses($experience);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Experience', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html($resume->experiencetitle) . '</span>
                                                </div>
                                              ';
                        }
                        if ($available != 0) {
                            $class = $this->getClasses($available);
                            $resume->available = esc_html(__("No",'wp-job-portal'));
                            if($resume->available == 1){
                                $resume->available = esc_html(__("Yes",'wp-job-portal'));
                            }
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Available', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html($resume->available) . '</span>
                                                </div>
                                              ';
                        }
                        if ($gender != 0) {
                            $class = $this->getClasses($gender);
                            $resumegender = ($resume->gender == 1) ? esc_html(__('Male', 'wp-job-portal')) : esc_html(__('Female', 'wp-job-portal'));
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Gender', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html($resumegender .) '</span>
                                                </div>
                                              ';
                        }*/
                        if ($nationality != 0) {
                            $class = $this->getClasses($nationality);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Nationality', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html($resume->nationalityname) . '</span>
                                                </div>
                                              ';
                        }
                        if ($location != 0) {
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Location', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html($resume->location) . '</span>
                                                </div>
                                              ';
                        }
                        if ($posteddate != 0) {
                            $class = $this->getClasses($posteddate);
                            $contents .= '
                                                <div id="wpjobportal_module_data_fieldwrapper" class="' . esc_attr($colwidthclass) . esc_attr($class) . ' wjportal-res-data">
                                                    <span id="wpjobportal_module_data_fieldtitle" class="wjportal-res-data-tit">' . esc_html(__('Posted', 'wp-job-portal')) . ' : </span>
                                                    <span id="wpjobportal_module_data_fieldvalue" class="wjportal-res-data-val">' . esc_html(date_i18n($dateformat, strtotime($resume->created))) . '</span>
                                                </div>
                                              ';
                        }
                        $contents .= '</div>
                                    </div>
                                </div>
                            </div>';
                    }
                }

                $contentswrapperend = '</div>';
            }

            return $contentswrapperstart . $contents . $contentswrapperend;
        }
    }

    function listModuleByJobcatOrType($jobs, $classname, $showtitle, $title, $columnperrow, $jobfor){

        if (!(is_numeric($columnperrow) || $columnperrow < 0)) {
            $columnperrow = 3;
        }
        $width = (int) 100 / $columnperrow;

        $html = '
            <div id="wpjobportal_mod_wrapper" class="wjportal-job-by-mod">';
                if ($showtitle == 1) {
                    $html .= '<div id="tp_heading" class="wjportal-mod-heading">'.esc_html($title).'</div>';
                }
                $html .= '<div id="wpjobportal-data-wrapper" class="'.esc_attr($classname).' wjportal-job-by">';
                if (isset($jobs)) {
                    foreach ($jobs as $job) {
                        if($jobfor == 1) //Types
                            $anchor = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'jobtype'=>$job->aliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if($jobfor == 2) //Categories
                            $anchor = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'category'=>$job->aliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $html .='<div class="wjportal-job-by-item" style="width:'.esc_attr($width).'%">
                                    <a href="'.esc_attr($anchor).'" class="wjportal-job-by-item-cnt">
                                        ' . esc_attr($job->objtitle) . '<span class="wjportal-job-by-item-num"> (' . esc_html($job->totaljobs) . ')</span>
                                    </a>
                                </div>';
                    }
                }
                $html .= '</div>
            </div>
        ';

        return $html;
    }

    function listModuleLocation($jobs, $classname, $showtitle, $title, $columnperrow, $locationfor){

        if (!(is_numeric($columnperrow) || $columnperrow < 0)) {
            $columnperrow = 3;
        }
        $width = (int) 100 / $columnperrow;

        $html = '
            <div id="wpjobportal_mod_wrapper" class="wjportal-job-by-location-mod">';
                if ($showtitle == 1) {
                    $html .= '<div id="tp_heading" class="wjportal-mod-heading">'.esc_html($title).'</div>';
                }
                $html .= '<div id="wpjobportal-data-wrapper" class="'.esc_attr($classname).' wjportal-job-by-loc">';
                if (is_array($jobs)) {
                    foreach ($jobs as $job) {
                        if($locationfor == 1)
                            $anchor = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'city'=>$job->locationid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if($locationfor == 2)
                            $anchor = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'state'=>$job->locationid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if($locationfor == 3)
                            $anchor = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'country'=>$job->locationid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $html .='<div class="wjportal-job-by-loc-item" style="width:'.esc_attr($width).'%">
                                    <a class="wjportal-job-by-loc-item-cnt" href="'.esc_url($anchor).'">
                                        ' . esc_html($job->locationname) . ' <span class="wjportal-job-by-item-num">(' . esc_html($job->totaljobs) . ')</span>
                                    </a>
                                </div>';
                    }
                }
                $html .= '</div>
            </div>
        ';

        return $html;
    }

    function prepareStyleForStats($classname, $color1, $color2, $color3){

        $style = '<style type="text/css">';
            if (!empty($color1)) {
                $style .='  div.'.esc_attr($classname).' div.wpjobportal-value{color: '.esc_attr($color1).' !important;}';
            }
            if (!empty($color2)) {
                $style .='  div.'.esc_attr($classname).' div.wpjobportal-value{background: '.esc_attr($color2).' !important;}';
            }
            if (!empty($color3)) {
                $style .='  div.'.esc_attr($classname).' div.wpjobportal-value{border: 1px solid '.esc_attr($color3).' !important;}';
            }
        $style .='</style>';

        return $style;
    }

    function prepareStyleForBlocks($classname, $color1, $color2, $color3){
        $style = '<style type="text/css">';
            if (!empty($color1)) {
                $style .='  div.'.esc_attr($classname).' div.anchor a.anchor{color: '.esc_attr($color1).' !important;}';
            }
            if (!empty($color2)) {
                $style .='  div.'.esc_attr($classname).' div.anchor a.anchor{background: '.esc_attr($color2).' !important;}';
            }
            if (!empty($color3)) {
                $style .='  div.'.esc_attr($classname).' div.anchor a.anchor{border: 1px solid '.esc_attr($color3).' !important;}';
            }
        $style .='</style>';

        return $style;
    }

    function perpareStyleSheet($classname , $color1 , $color2 , $color3 , $color4 , $color5 , $color6 ){

        $style = '<style type="text/css">';
            if (!empty($color1)) {
                $style .='  div#wpjobportal_module_wrapper.'.esc_attr($classname).' a{color:'.esc_attr($color1).';}';
            }
            if (!empty($color3)) {
                $style .='  div.'.esc_attr($classname).' div#wpjobportal_module{background: '.esc_attr($color3).';}
                            div.'.esc_attr($classname).' div#wpjobportal_modulelist_databar{background: '.esc_attr($color3).';}
                            div.'.esc_attr($classname).' div#wpjobportal_modulelist_titlebar{background: '.esc_attr($color3).';}
                        ';
            }
            if (!empty($color4)) {
                $style .='  div.'.esc_attr($classname).' div#wpjobportal_module{border: 1px solid '.esc_attr($color4).';}
                            div.'.esc_attr($classname).' div#wpjobportal_modulelist_titlebar{border: 1px solid '.esc_attr($color4).';}
                            div.'.esc_attr($classname).' div#wpjobportal_modulelist_databar{border: 1px solid '.esc_attr($color4).';}
                        ';
            }
            if (!empty($color5)) {
                $style .='  div#wpjobportal_module_wrapper.'.esc_attr($classname).' div#wpjobportal_module_wrap div#wpjobportal_module_data_fieldwrapper span#wpjobportal_module_data_fieldtitle{color: '.esc_attr($color5).';}
                            div.'.esc_attr($classname).' div#wpjobportal_modulelist_databar{color: '.esc_attr($color5).';}
                            div.'.esc_attr($classname).' div#wpjobportal_modulelist_titlebar span#wpjobportal_modulelist_titlebar{color: '.esc_attr($color5).';}
                        ';
            }
            if (!empty($color6)) {
                $style .='  div#wpjobportal_module_wrapper.'.esc_attr($classname).' div#wpjobportal_module_wrap div#wpjobportal_module_data_fieldwrapper span#wpjobportal_module_data_fieldvalue{color: '.esc_attr($color6).';}';
            }
            if (!empty($color2)) {
                $style .='  div.'.esc_attr($classname).' div#wpjobportal_module span#wpjobportal_module_heading {border-bottom: 1px solid '.esc_attr($color2).';}';
            }
        $style .='</style>';
        return $style;
    }

    function listModuleJobsForMap($jobs, $title, $showtitle, $company, $category, $moduleheight, $mapzoom){
        $mappingservice = wpjobportal::$_config->getConfigValue('mappingservice');
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $wpdir = wp_upload_dir();
        $logopath = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_'/* . $comp->id . '/logo/' . $comp->logofilename*/;
        $default_logoPath = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');


        $html = ''; 
        if($mappingservice == "gmap"){
            $filekey = WPJOBPORTALincluder::getJSModel('common')->getGoogleMapApiAddress();
            wp_enqueue_script( 'jp-google-map', $filekey, array(), '1.1.1', false );
            //$html = $filekey;

        }elseif ($mappingservice == "osm") {
            $html = ''; 
            wp_enqueue_script('wpjobportal-ol-script', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/ol.min.js');
            wp_enqueue_style('wpjobportal-ol-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/ol.min.css');
        }
        $default_longitude = wpjobportal::$_config->getConfigurationByConfigName('default_longitude');
        $default_latitude = wpjobportal::$_config->getConfigurationByConfigName('default_latitude');
        if($showtitle == 1){
            $html .= '
            <div id="tp_heading" class="wjportal-mod-heading">
                '.esc_html($title).'
            </div>';
        }
            if ($jobs) {
                $html .= '<div id="map-canvas" class="map-canvas-module" style="height:'.$moduleheight.'px;width:100%;"></div>';
                if($mappingservice == "gmap"){
                    wp_register_script( 'wpjobportal-inline-handle', '' );
                    wp_enqueue_script( 'wpjobportal-inline-handle' );
                    $inline_js_script = '
                    var jobsarray = '.wp_json_encode($jobs).';
                    var showCategory = '.$category.';
                    var showCompany = '.$company.';

                    var map = new google.maps.Map(document.getElementById("map-canvas"), {
                      zoom: '.esc_attr($mapzoom).',
                      center: new google.maps.LatLng('.$default_latitude.','.$default_longitude.'),
                    });
                    var markers = [];
                    for(i = 0; i < jobsarray.length; i++){
                      var geocoder =  new google.maps.Geocoder();
                      if(jobsarray[i].multicity !== undefined){
                        var job = jobsarray[i];
                        for(k = 0; k < jobsarray[i].multicity.length; k++){
                          geocoder.geocode( { "address": jobsarray[i].multicity[k].cityname + \',\' + jobsarray[i].multicity[k].countryname}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                              latitude = results[0].geometry.location.lat();
                              longitude = results[0].geometry.location.lng();
                              setMarker(map,job,latitude,longitude);
                            } else {
                              latitude = 0;
                              longitude = 0;
                            }
                          });
                        }
                      }else{
                        if(jobsarray[i].latitude.indexOf(",") > -1){ // multi location
                            var latarray = jobsarray[i].latitude.split(",");
                            var longarray = jobsarray[i].longitude.split(",");
                            for(l = 0; l < latarray.length; l++){
                                var latitudemap = latarray[l];
                                var longitudemap = longarray[l];
                                var marker = setMarker(map,jobsarray[i],latitudemap,longitudemap);
                                markers.push(marker);
                            }
                        }else{
                            var marker = setMarker(map,jobsarray[i],jobsarray[i].latitude,jobsarray[i].longitude);
                            markers.push(marker);
                        }
                      }
                    }

                    function setMarker(map,jobObject,latitude,longitude){
                      marker = new google.maps.Marker({
                        position: new google.maps.LatLng(latitude, longitude),
                        map: map
                      });
                      var infowindow = new google.maps.InfoWindow();
                      google.maps.event.addListener(marker, "click", (function(marker) {
                        return function() {
                          var markerContent = "<div class=\'wjportal-jobs-list-map\'><div class=\'wjportal-jobs-list\'>";
                          if(jobObject.companylogo != ""){
                            markerContent += "<div class=\'wjportal-jobs-logo\'><img src=\''.$logopath.'"+jobObject.companyid+"/logo/"+jobObject.companylogo+"\' ></div>";
                          }else{
                            markerContent += "<div class=\'wjportal-jobs-logo\'><img src=\''.$default_logoPath.'\' ></div>";
                          }
                          markerContent += "<div class=\'wjportal-jobs-cnt\'>";
                          if(showCompany == 1){
                           markerContent += "<div class=\'wjportal-jobs-data\'><a href=\'#\' class=\'wjportal-companyname\'>" + jobObject.companyname + "</a></div>";
                          }
                          if(showCategory == 1){
                            markerContent += "<div class=\'wjportal-jobs-data\'><a href=\'#\' class=\'wjportal-job-title\'>"+jobObject.title+"</a></div><div class=\'wjportal-jobs-data\'><span class=\'wjportal-jobs-data-txt\'>"+jobObject.cat_title+"</span></div></div></div></div>";
                          }
                          infowindow.setContent(markerContent);
                          infowindow.open(map, marker);
                        }
                      })(marker));
                      return marker;
                    }
                    /*
                    function autoCenter() {
                      //  Create a new viewpoint bound
                      var bounds = new google.maps.LatLngBounds();
                      //  Go through each...
                      jQuery.each(markers, function (index, marker) {
                        bounds.extend(marker.position);
                      });
                      //  Fit these bounds to the map
                      map.fitBounds(bounds);
                    }
                    autoCenter();
                    */
                  ';
                  wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
                  return $html;
            }elseif ($mappingservice == "osm") {
                wp_register_script( 'wpjobportal-inline-handle', '' );
                wp_enqueue_script( 'wpjobportal-inline-handle' );
                $inline_js_script = '
                            osmMap = null;
                            var showCategory = '.$category.';
                            var showCompany = '.$company.';
                            var default_latitude = parseFloat('.$default_latitude.');
                            var default_longitude = parseFloat('.$default_latitude.');;
                            var coordinate = [default_longitude,default_latitude];
                            if(!osmMap){
                                osmMap = new ol.Map({
                                    target: "map-canvas",
                                    layers: [
                                        new ol.layer.Tile({
                                            source: new ol.source.OSM()
                                        })
                                    ],
                                });
                            }
                            osmMap.setView(new ol.View({
                                center: ol.proj.fromLonLat(coordinate),
                                zoom: '.esc_attr($mapzoom).'
                            }));
                            // For showing multiple marker on map
                            var jobsarray = '.wp_json_encode($jobs).';
                            for(i = 0; i < jobsarray.length; i++){
                                var latarray = jobsarray[i].latitude.split(",");
                                var longarray = jobsarray[i].longitude.split(",");
                                for(l = 0; l < latarray.length; l++){
                                    var latitudemap = parseFloat(latarray[l]);
                                    var longitudemap = parseFloat(longarray[l]);
                                }
                                coordinate = [longitudemap,latitudemap];
                                osmAddMarker(osmMap, coordinate);
                                osmMap.addEventListener("click",function(event){
                                    osmMap.forEachFeatureAtPixel(event.pixel, function (feature, layer) {
                                        var index = ol.coordinate.toStringXY(feature.getGeometry().getCoordinates());
                                        var box = document.getElementById("osmmappopup");
                                        if(!box){
                                            box = document.createElement("div");
                                            box.id = "osmmappopup";
                                        }
                                        var html = "<div class=\'wjportal-jobs-list-map\'><div class=\'wjportal-jobs-list\'><div class=\'wjportal-jobs-logo\'><img src=\''. WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer') .'\' ></div><div class=\'wjportal-jobs-cnt\'><div class=\'wjportal-jobs-data\'><a href=\'#\' class=\'wjportal-companyname\'>Company Name</a></div><div class=\'wjportal-jobs-data\'><a href=\'#\' class=\'wjportal-job-title\'>Job Title</a></div><div class=\'wjportal-jobs-data\'><span class=\'wjportal-jobs-data-txt\'>Category</span></div></div></div></div>";
                                        box.innerHTML = html;
                                        var prev_infowindow = new ol.Overlay({
                                            element: box,
                                            offset: [-140,-35]
                                        });
                                        prev_infowindow.setPosition(event.coordinate);
                                        osmMap.addOverlay(prev_infowindow);
                                    });
                                });
                            }

                        function osmAddMarker(osmMap, coordinate, icon) {
                            if(osmMap && ol){
                                if(!icon){
                                    icon = "http://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png";
                                }
                                var vectorLayer = new ol.layer.Vector({
                                    source: new ol.source.Vector({
                                        features: [
                                            new ol.Feature({
                                                geometry: new ol.geom.Point(ol.proj.transform(coordinate, "EPSG:4326", "EPSG:3857")),
                                            })
                                        ]
                                    }),
                                    style: new ol.style.Style({
                                        image: new ol.style.Icon({
                                            src: icon
                                        })
                                    })
                                });
                                osmMap.addLayer(vectorLayer);
                                return vectorLayer;
                            }
                            return false;
                        }';
                        wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
             return $html;
          }
        }
    }

    function getJOBSWidgetHTML($jobs,$pageid,$title,$no_of_columns,$layoutName,$listtype,$typetag){
        $dateformat = wpjobportal::$_configuration['date_format'];

        $moduleName = $layoutName;
        $moduleheight = '500';
        $contentswrapperstart = '';
        $contents = '';
        $class = ' visible-all';

        if ($jobs) {
            /*if ($listtype == 1) {*/ //list style
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" >';
                    $contentswrapperstart .= '
                                        <div id="tp_heading">
                                            <span id="tp_headingtext">
                                                <span id="tp_headingtext_center">' . esc_html($title) . '</span>
                                            </span>
                                        </div>
                                    ';
                $wpdir = wp_upload_dir();
                $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                if (isset($jobs)) {
                    foreach ($jobs as $job) {
                        $contents .= '<div id="wpjobportal-module-datalist" class="wjportal-jobs-list">';
                            $contents .= '<div class="wjportal-jobs-list-top-wrp">';
                                $contents .= '<div class="wjportal-jobs-logo">';
                                    $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                    $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
                                    if($job->logofilename != ''){
                                        $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;
                                    }
                                    $contents .= '<a href=' . esc_url($c_l) . '><img src="' . esc_url($logo) . '"  /></a>';
                                $contents .= '</div>';
                                $an_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$job->jobaliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $contents .= '<div class="wjportal-jobs-cnt-wrp">
                                                <div class="wjportal-jobs-middle-wrp">
                                                    <div class="wjportal-jobs-data">
                                                        <a href="#" class="wjportal-companyname" title="'. esc_html(__("Company Name",'wp-job-portal')) .'">
                                                            '. esc_html(__("Company Name",'wp-job-portal')) .'
                                                        </a>
                                                    </div>
                                                    <div class="wjportal-jobs-data">
                                                        <span class="wjportal-job-title">
                                                            <a href="' . esc_url($an_link) . '">
                                                                ' . esc_html($job->title) . '
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div class="wjportal-jobs-data">
                                                        <span class="wjportal-jobs-data-text">
                                                            '. esc_html($job->cat_title) .'
                                                        </span>
                                                        <span class="wjportal-jobs-data-text">
                                                            '. $job->location .'
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="wjportal-jobs-right-wrp">
                                                    <div class="wjportal-jobs-info">';
                                                        $tagname = 'New';
                                                        $tagcolor = '#00A859';
                                                        $textcolor = '#fff';
                                                        if ($typetag == 1) {
                                                            $tagname = 'New';
                                                            $tagcolor = '#00A859';
                                                            $textcolor = '#fff';
                                                        } elseif ($typetag == 2) {
                                                            $tagname = 'Top';
                                                            $tagcolor = '#EFCEC5';
                                                            $textcolor = '#0085BA';
                                                        } elseif ($typetag == 3) {
                                                            $tagname = 'Hot';
                                                            $tagcolor = '#DC143C';
                                                            $textcolor = '#fff';
                                                        } elseif ($typetag == 4) {
                                                            $tagname = 'Gold';
                                                            $tagcolor = '#D6B043';
                                                            $textcolor = '#fff';
                                                        } elseif ($typetag == 5) {
                                                            $tagname = 'Featured';
                                                            $tagcolor = '#378AD8';
                                                            $textcolor = '#fff';
                                                        }
                                                        $contents .= '<span class="wjportal-job-type" style="background:'.$tagcolor.';color:'.$textcolor.';">'. $tagname .'</span>
                                                    </div>

                                                    <div class="wjportal-jobs-info">
                                                        <div class="wjportal-jobs-salary">
                                                            '. esc_html(__("0 $",'wp-job-portal')) .'
                                                            <span class="wjportal-salary-type">
                                                                '. esc_html(__(" / Per Month", 'wp-job-portal')) .'
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="wjportal-jobs-info">
                                                        '.human_time_diff(strtotime($job->created),strtotime(date_i18n("Y-m-d H:i:s"))).' '.esc_html(__("Ago",'wp-job-portal'))  .'
                                                    </div>
                                                </div>
                                            </div>';
                            $contents .= '</div>';
                        $contents .= '</div>';
                    }
                }

                $contentswrapperend = '</div>';
            /*}*/
            return $contentswrapperstart . $contents . $contentswrapperend;
        }
    }

      function getCompanies_WidgetHtml($title,$layoutName, $companies, $noofcompanies, $listingstyle,$companytype,$no_of_columns){
        $dateformat = wpjobportal::$_configuration['date_format'];

        $moduleName = $layoutName;
        $moduleheight = '500';
        $contentswrapperstart = '';
        $contents = '';
        $class = ' visible-all';
        if ($companies) {
            /*if ($listingstyle == 1) {*/ //list style
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" >';
                    $contentswrapperstart .= '
                                        <div id="tp_heading">
                                            <span id="tp_headingtext">
                                                <span id="tp_headingtext_center">' . esc_html($title) . '</span>
                                            </span>
                                        </div>
                                    ';
                if (isset($companies)) {
                    $wpdir = wp_upload_dir();
                    $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                    foreach ($companies as $company) {
                        $color = ($company->status == 1) ? "green" : "red";
                        if ($company->status == 1) {
                            $statusCheck = esc_html(__('Approved', 'wp-job-portal'));
                        } elseif ($company->status == 0) {
                            $statusCheck = esc_html(__('Waiting for approval', 'wp-job-portal'));
                        }elseif($company->status == 2){
                             $statusCheck = esc_html(__('Pending For Approval of Payment', 'wp-job-portal'));
                        }elseif ($company->status == 3) {
                            $statusCheck = esc_html(__('Pending Due To Payment', 'wp-job-portal'));
                        }else {
                            $statusCheck = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                         if(in_array('multicompany', wpjobportal::$_active_addons)){
                            $mod = "multicompany";
                        }else{
                            $mod = "company";
                        }
                        $contents .= '<div id="wpjobportal-module-datalist" class="wjportal-company-list">';
                            $contents .= '<div class="wjportal-company-list-top-wrp">';
                                $contents .= '<div class="wjportal-company-logo">';
                                    $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->alias, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                    $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
                                    if($company->logofilename != ''){
                                        $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
                                    }
                                    $contents .= '<a href=' . esc_url($c_l) . '><img src="' . esc_url($logo) . '"  /></a>';
                                $contents .= '</div>';
                                $contents .= '<div class="wjportal-company-cnt-wrp">';
                                    $contents .= '<div class="wjportal-company-middle-wrp">
                                                    <div class="wjportal-company-data">
                                                        <a class="wjportal-companyname" href="' . $company->url . '">
                                                            ' . $company->url . '
                                                        </a>
                                                    </div>
                                                    <div class="wjportal-company-data">
                                                        <span class="wjportal-company-title">
                                                            <a href="' . $c_l . '">
                                                                ' . esc_html($company->name) . '
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div class="wjportal-company-data">
                                                        <div class="wjportal-company-data-text">
                                                            <span class="wjportal-company-data-title">'. esc_html(__("Created",'wp-job-portal')) .':</span>
                                                            <span class="wjportal-company-data-value">'. human_time_diff(strtotime($company->created),strtotime(date_i18n("Y-m-d H:i:s"))).' '.esc_html(__("Ago",'wp-job-portal')) .':</span>
                                                        </div>
                                                        <div class="wjportal-company-data-text">
                                                            <span class="wjportal-company-data-title">'. esc_html(__("Status",'wp-job-portal')) .':</span>
                                                            <span class="wjportal-company-data-value '.esc_attr($color).' ">'. wpjobportal::wpjobportal_getVariableValue($statusCheck) .'</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wjportal-company-right-wrp">
                                                    <div class="wjportal-company-action">
                                                        <a href="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'viewcompany','wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid() ,'wpjobportalid'=>$company->companyaliasid)).'" class="wjportal-company-act-btn" title="'. esc_html(__("View Company",'wp-job-portal')) .'">
                                                            '. esc_html(__("View Company",'wp-job-portal')) .'
                                                        </a>
                                                    </div>
                                                </div>
                                                ';
                                    $contents .= '</div>';
                                $contents .= '</div>';
                            $contents .= '</div>';
                        $contents .= '</div>';
                    }
                }

                $contentswrapperend = '</div>';
            /*} */
            return $contentswrapperstart . $contents . $contentswrapperend;
        }else{
            $html = '<div id="tp_heading">
                        <span id="tp_headingtext">
                                <span id="tp_headingtext_left"></span>
                                <span id="tp_headingtext_center">' . esc_html(__("No Record Found",'wp-job-portal')) . '</span>
                                <span id="tp_headingtext_right"></span>
                        </span>
                    </div>';
            return $html;
        }
    }

    function getResume_WidgetHtml($title,$layoutName, $resumes, $noofresumes, $listingstyle,$resumetype,$no_of_columns){
        $dateformat = wpjobportal::$_configuration['date_format'];

        $moduleName = $layoutName;
        $moduleheight = '500';
        $contentswrapperstart = '';
        $contents = '';
        $class = ' visible-all';
        $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        if ($resumes) {
            /*if ($listingstyle == 1) {*/ //list style
                $contentswrapperstart .= '<div id="wpjobportal_module_wrapper" class="' . esc_attr($moduleName) . '" >';
                    $contentswrapperstart .= '
                                        <div id="tp_heading">
                                            <span id="tp_headingtext">
                                                    <span id="tp_headingtext_center">' . esc_html($title) . '</span>
                                            </span>
                                        </div>
                                    ';
                $wpdir = wp_upload_dir();
                if (isset($resumes)) {
                    foreach ($resumes as $resume) {
                        $contents .= '<div id="wpjobportal-module-datalist" class="wjportal-resume-list">';
                            $contents .= '<div class="wjportal-resume-list-top-wrp">';
                                $contents .= '<div class="wjportal-resume-logo">';
                                    $c_l = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                    $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
                                    if($resume->photo != ''){
                                        $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->resumeid . '/photo/' . $resume->photo;
                                    }
                                    $contents .= '<a href=' . esc_url($c_l) . '><img class="wpjobportal-module-datalist-img" src="' . esc_url($logo) . '"  /></a>';
                                $contents .= '</div>';
                                $contents .= '<div class="wjportal-resume-cnt-wrp">
                                                <div class="wjportal-resume-middle-wrp">
                                                    <div class="wjportal-resume-data">
                                                        <span class="wjportal-resume-job-type" style="background:'.$resume->jobtypecolor.'">
                                                            ' . $resume->jobtypetitle . '
                                                        </span>
                                                    </div>
                                                    <div class="wjportal-resume-data">
                                                        <a class="wpjobportal-module-datalist-anchor" href="' . $c_l . '">
                                                            <span class="wjportal-resume-name">
                                                                ' . $resume->name . '
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="wjportal-resume-data">
                                                        <span class="wjportal-resume-title">
                                                            '. $resume->applicationtitle .'
                                                        </span>
                                                    </div>
                                                    <div class="wjportal-resume-data">';
                                                        if(isset($resume->location) && !empty($resume->location)){
                                                            $contents .= '<div class="wjportal-resume-data-text">
                                                                        <span class="wjportal-resume-data-title">'. esc_html(__("Location",'wp-job-portal')) .':</span>
                                                                        <span class="wjportal-resume-data-value">'. $resume->location .'</span>
                                                                    </div>';
                                                       }
                                                    $contents .='    <div class="wjportal-resume-data-text">
                                                            <span class="wjportal-resume-data-title">'. esc_html(__("Experience",'wp-job-portal')) .':</span>
                                                            <span class="wjportal-resume-data-value">'.wpjobportal::$_common->getTotalExp($resume->resumeid).'</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wjportal-resume-right-wrp">
                                                    <div class="wjportal-resume-action">
                                                        <a href="#" class="wjportal-resume-act-btn" title="' . esc_html(__("View Profile",'wp-job-portal')) . '">
                                                            ' . esc_html(__("View Profile",'wp-job-portal')) . '
                                                        </a>
                                                    </div>
                                                </div>
                                        ';
                                $contents .= '</div>';
                            $contents .= '</div>';
                        $contents .= '</div>';
                    }
                }
                 $contentswrapperend = '</div>';
            /*}*/
            return $contentswrapperstart . $contents . $contentswrapperend;
        }else{
            $html = '<div id="tp_heading">
                        <span id="tp_headingtext">
                                <span id="tp_headingtext_left"></span>
                                <span id="tp_headingtext_center">' . esc_html(__("No Record Found",'wp-job-portal')) . '</span>
                                <span id="tp_headingtext_right"></span>
                        </span>
                    </div>';
            return $html;
        }
    }

    function getSearchJobs_WidgetHTML($title, $showtitle, $fieldtitle, $category, $jobtype, $jobstatus, $salaryrange, $shift, $duration, $startpublishing, $stoppublishing, $company, $address, $columnperrow) {

        if ($columnperrow <= 0)
            $columnperrow = 1;
        $width = round(100 / $columnperrow);
        $style = "style='width:" . $width . "%'";

        $html = '
                <div id="wpjobportal_module_wrapper">';
        if ($showtitle == 1) {
            $html .= '<div id="tp_heading" class="">
                        <span id="tp_headingtext">
                            <span id="tp_headingtext_center">' . esc_html($title) . '</span>
                        </span>
                    </div>';
        }
        $html .='<div class="wjportal-form-wrp wjportal-search-job-form">';
        $html .='<form class="job_form wjportal-form" id="job_form" method="post" action="' . esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))) . '">';

        if ($fieldtitle == 1) {
            $title = esc_html(__('Title', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::text('jobtitle', '', array('class' => 'inputbox wjportal-form-input-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                <div class="wjportal-form-title">' . esc_html($title) . '</div>
                <div class="wjportal-form-value">' . wp_kses($value,WPJOBPORTAL_ALLOWED_TAGS) . '</div>
            </div>';
        }

        if ($category == 1) {
            $title = esc_html(__('Category', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::select('category[]', WPJOBPORTALincluder::getJSModel('category')->getCategoriesForCombo(), isset(wpjobportal::$_data[0]['filter']->category) ? wpjobportal::$_data[0]['filter']->category : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Category', 'wp-job-portal')), array('class' => 'inputbox wjportal-form-select-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                <div class="wjportal-form-title">' . esc_html($title) . '</div>
                <div class="wjportal-form-value">' . wp_kses($value,WPJOBPORTAL_ALLOWED_TAGS) . '</div>
            </div>';
        }

        if ($jobtype == 1) {
            $title = esc_html(__('Job Type', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::select('jobtype[]', WPJOBPORTALincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(wpjobportal::$_data[0]['filter']->jobtype) ? wpjobportal::$_data[0]['filter']->jobtype : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Job Type', 'wp-job-portal')), array('class' => 'inputbox wjportal-form-select-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                <div class="wjportal-form-title">' . esc_html($title) . '</div>
                <div class="wjportal-form-value">' . wp_kses($value,WPJOBPORTAL_ALLOWED_TAGS) . '</div>
            </div>';
        }
        if ($jobstatus == 1) {
            $title = esc_html(__('Job Status', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::select('jobstatus[]', WPJOBPORTALincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(wpjobportal::$_data[0]['filter']->jobstatus) ? wpjobportal::$_data[0]['filter']->jobstatus : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Job Status', 'wp-job-portal')), array('class' => 'inputbox wjportal-form-select-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                <div class="wjportal-form-title">' . esc_html($title) . '</div>
                <div class="wjportal-form-value">' . wp_kses($value,WPJOBPORTAL_ALLOWED_TAGS) . '</div>
            </div>';
        }
        if ($salaryrange == 1) {
            $salarytypelist = array(
                (object) array('id'=>WPJOBPORTAL_SALARY_NEGOTIABLE,'text'=>esc_html(__("Negotiable",'wp-job-portal'))),
                (object) array('id'=>WPJOBPORTAL_SALARY_FIXED,'text'=>esc_html(__("Fixed",'wp-job-portal'))),
                (object) array('id'=>WPJOBPORTAL_SALARY_RANGE,'text'=>esc_html(__("Range",'wp-job-portal'))),
            );
            $title = esc_html(__('Salary Range', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::select('salarytype', $salarytypelist,'', esc_html(__("Select",'wp-job-portal')).' '.esc_html(__("Salary Type",'wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field'));
            $value .= WPJOBPORTALformfield::text('salaryfixed','', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 45000','wp-job-portal'))));
            $value .=  WPJOBPORTALformfield::text('salarymin', '', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 3000','wp-job-portal'))));
            $value .=  WPJOBPORTALformfield::text('salarymax', '', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 6000','wp-job-portal'))));
            $value .= WPJOBPORTALformfield::select('salaryduration', WPJOBPORTALincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), WPJOBPORTALincluder::getJSModel('salaryrangetype')->getDefaultSalaryRangeTypeId(), esc_html(__('Select','wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                        <div class="wjportal-form-title">' . esc_html($title) . '</div>
                        <div class="wjportal-form-value">
                                <div class="wjportal-form-5-fields">
                                    <div class="wjportal-form-inner-fields">
                                        '.WPJOBPORTALformfield::select('salarytype', $salarytypelist,'', esc_html(__("Select",'wp-job-portal')).' '.esc_html(__("Salary Type",'wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field')).'
                                    </div>
                                    <div class="wjportal-form-inner-fields">
                                        '.WPJOBPORTALformfield::text('salaryfixed','', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 45000','wp-job-portal')))).'
                                    </div>
                                    <div class="wjportal-form-inner-fields">
                                        '.WPJOBPORTALformfield::text('salarymin', '', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 3000','wp-job-portal')))).'
                                    </div>
                                    <div class="wjportal-form-inner-fields">
                                        '.WPJOBPORTALformfield::text('salarymax', '', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 6000','wp-job-portal')))).'
                                    </div>
                                    <div class="wjportal-form-inner-fields">
                                        '.WPJOBPORTALformfield::select('salaryduration', WPJOBPORTALincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), WPJOBPORTALincluder::getJSModel('salaryrangetype')->getDefaultSalaryRangeTypeId(), esc_html(__('Select','wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field')).'
                                    </div>
                                </div>
                        </div>
            </div>';
        }
        if ($duration == 1) {
            $title = esc_html(__('Duration', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::text('duration', isset(wpjobportal::$_data[0]['filter']->duration) ? wpjobportal::$_data[0]['filter']->duration : '', array('class' => 'inputbox wjportal-form-input-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                <div class="wjportal-form-title">' . esc_html($title) . '</div>
                <div class="wjportal-form-value">' . wp_kses($value,WPJOBPORTAL_ALLOWED_TAGS) . '</div>
            </div>';
        }
        if ($startpublishing == 1) {

        }
        if ($stoppublishing == 1) {

        }
        if ($company == 1) {
            $title = esc_html(__('Company', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::select('company[]', WPJOBPORTALincluder::getJSModel('company')->getCompaniesForCombo(), isset(wpjobportal::$_data[0]['filter']->company) ? wpjobportal::$_data[0]['filter']->company : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')), array('class' => 'inputbox wjportal-form-select-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                <div class="wjportal-form-title">' . esc_html($title) . '</div>
                <div class="wjportal-form-value">' . wp_kses($value,WPJOBPORTAL_ALLOWED_TAGS) . '</div>
            </div>';
        }
        if ($address == 1) {
            $title = esc_html(__('City', 'wp-job-portal'));
            $value = WPJOBPORTALformfield::text('city', isset(wpjobportal::$_data[0]['filter']->city) ? wpjobportal::$_data[0]['filter']->city : '', array('class' => 'inputbox wjportal-form-input-field'));
            $html .= '<div class="wjportal-form-row" ' . esc_attr($style) . '>
                <div class="wjportal-form-title">' . esc_html($title) . '</div>
                <div class="wjportal-form-value">' . wp_kses($value,WPJOBPORTAL_ALLOWED_TAGS) . '</div>
            </div>';
        }

        $html .= '<div class="wjportal-form-btn-wrp">
                        <div class="wjportal-form-2-btn">
                            ' . WPJOBPORTALformfield::submitbutton('save', esc_html(__('Search Job', 'wp-job-portal')), array('class' => 'button wjportal-form-btn wjportal-form-srch-btn')) . '
                        </div>
                        <div class="wjportal-form-2-btn">
                            <a class="anchor wjportal-form-btn wjportal-form-cancel-btn" href="' . esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobsearch', 'wpjobportallt'=>'jobsearch', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))) . '">
                            ' . esc_html(__('Advance Search', 'wp-job-portal')) . '
                            </a>
                        </div>
                    </div>
                    <input type="hidden" id="issearchform" name="issearchform" value="1"/>
                    <input type="hidden" id="WPJOBPORTAL_form_search" name="WPJOBPORTAL_form_search" value="WPJOBPORTAL_SEARCH"/>
                    <input type="hidden" id="wpjobportallay" name="wpjobportallay" value="jobs"/>
                </form>
            </div>
';
            wp_register_script( 'wpjobportal-inline-handle', '' );
            wp_enqueue_script( 'wpjobportal-inline-handle' );
            $inline_js_script = '
                function getTokenInput() {
                    var cityArray = "' . esc_url_raw(admin_url("admin.php?page=wpjobportal_city&action=wpjobportaltask&task=getaddressdatabycityname")) . '";
                    jQuery("#city").tokenInput(cityArray, {
                        theme: "wpjobportal",
                        preventDuplicates: true,
                        hintText: "' . esc_html(__('Type In A Search Term', 'wp-job-portal')) . '",
                        noResultsText: "' . esc_html(__('No Results', 'wp-job-portal')) . '",
                        searchingText: "' . esc_html(__('Searching', 'wp-job-portal')) . '"
                    });
                }
                jQuery(document).ready(function(){
                    getTokenInput();
                });
                jQuery(document).delegate("#salarytype", "change", function(){
                    var salarytype = jQuery(this).val();
                    if(salarytype == 1){ //negotiable
                        jQuery("#salaryfixed").hide();
                        jQuery("#salarymin").hide();
                        jQuery("#salarymax").hide();
                        jQuery("#salaryduration").hide();
                        jQuery(".wjportal-form-symbol").hide();
                    }else if(salarytype == 2){ //fixed
                        jQuery("#salaryfixed").show();
                        jQuery("#salarymin").hide();
                        jQuery("#salarymax").hide();
                        jQuery("#salaryduration").show();
                        jQuery(".wjportal-form-symbol").show();
                    }else if(salarytype == 3){ //range
                        jQuery("#salaryfixed").hide();
                        jQuery("#salarymin").show();
                        jQuery("#salarymax").show();
                        jQuery("#salaryduration").show();
                        jQuery(".wjportal-form-symbol").show();
                    }else{ //not selected
                        jQuery("#salaryfixed").hide();
                        jQuery("#salarymin").hide();
                        jQuery("#salarymax").hide();
                        jQuery("#salaryduration").hide();
                        jQuery(".wjportal-form-symbol").hide();
                    }
                });

                jQuery("#salarytype").change();
            ';
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        return $html;
    }

    function getMessagekey(){
        $key = 'wpjobportalwidgets';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function wpjobportalRenderJobsTemplate($jobs, $layout = 'list', $num_of_columns = 1, $show_title = true, $show_company = true, $show_location = true, $show_jobtype = true, $show_salary = true, $show_stoppublishing = true, $show_careerlevel = true, $show_posted = true, $show_category = true, $show_logo = true, $logo_width = 80, $logo_height = 80, $labels_for_values = 1, $field_order = array(), $elemntor_call = 0) {

        $html = '';

            if(empty($num_of_columns) || $num_of_columns == 0){
                $num_of_columns = 1;
            }
            $layout_class = 'wpjobportal-layout-' . esc_attr($layout);
            $column_class = 'wpjobportal-cols-' . intval($num_of_columns);

            $html .= '<div class="wpjobportal-job-widget-multi-style-wrapper ' . esc_attr($layout_class . ' ' . $column_class) . '">';

        $wpdir = wp_upload_dir();
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $i = 0;

        $pageid = wpjobportal::wpjobportal_getPageidForWidgets();

        foreach ($jobs as $job) {
            $job_id = isset($job->jobaliasid) ? $job->jobaliasid : 0;
            $company_id = isset($job->companyid) ? $job->companyid : 0;
            $company_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme' => 'company', 'wpjobportallt' => 'viewcompany', 'wpjobportalid' => isset($job->companyaliasid) ? $job->companyaliasid : 0, 'wpjobportalpageid' => $pageid));
            $job_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme' => 'job', 'wpjobportallt' => 'viewjob', 'wpjobportalid' => $job_id, 'wpjobportalpageid' => $pageid));

            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
            if (!empty($job->logofilename)) {
                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company_id . '/logo/' . $job->logofilename;
            }

            if ($i % $num_of_columns === 0) {
                if ($i !== 0) $html .= '</div>';
                $html .= '<div class="wpjobportal-job-row">';
            }
            $i++;

            $html .= $this->wpjobportalRenderSingleJob($job, $layout, $show_title, $show_company, $show_location, $show_jobtype, $show_salary, $show_stoppublishing, $show_careerlevel, $show_posted, $show_category, $show_logo, $logo_width, $logo_height, $labels_for_values, $field_order, $company_url, $job_url, $logo,$elemntor_call);
        }

        if ($i != 0) {
            $html .= '</div>';
        }
        //if($elemntor_call == 0){
            $html .= '</div>';
        //}

        return $html;
    }

    function wpjobportalRenderSingleJob($job, $layout, $show_title, $show_company, $show_location, $show_jobtype, $show_salary, $show_stoppublishing, $show_careerlevel, $show_posted, $show_category, $show_logo, $logo_width, $logo_height, $labels_for_values, $field_order, $company_url, $job_url, $logo,$elemntor_call) {
        $html = '<div class="wpjobportal-job-box wpjobportal-floatbox">';

        // Company Logo
        if ($show_logo) {
            $html .= '<div class="wpjobportal-job-logo">';
            $html .= '<a href="' . esc_url($company_url) . '">';
            $html .= '<img src="' . esc_url($logo) . '" alt="' . esc_attr(isset($job->companyname) ? $job->companyname : '') . '" width="' . esc_attr($logo_width) . '" height="' . esc_attr($logo_height) . '">';
            $html .= '</a></div>';
        }

        // Job Details
        $html .= '<div class="wpjobportal-job-details">';

        if ($show_title && !empty($job->title)) {
            $html .= '<div class="wpjobportal-job-title">';
            $html .= '<a href="' . esc_url($job_url) . '">' . esc_html($job->title) . '</a>';
            $html .= '</div>';
        }

        if ($show_company && !empty($job->companyname)) {
            $html .= '<div class="wpjobportal-job-company">';
            $html .= '<a href="' . esc_url($company_url) . '">' . esc_html($job->companyname) . '</a>';
            $html .= '</div>';
        }

        // Meta Info Grouped
        $meta_class = ($layout === 'list') ? 'wpjobportal-job-meta-row' : 'wpjobportal-job-meta-col';
        $html .= '<div class="' . esc_attr($meta_class) . '">';

        // Render fields in specified order

        if($elemntor_call == 0){
            $field_order = array();
            $field_order[] = 'salary';
            $field_order[] = 'location';
            $field_order[] = 'jobtype';
            $field_order[] = 'job_category';
            $field_order[] = 'careerlevel';
            $field_order[] = 'posted';
            $field_order[] = 'apply_before';
        }

        foreach ($field_order as $field_key) {
            $html .= $this->wpjobportalRenderJobField($job, $field_key, $labels_for_values, $show_location, $show_jobtype, $show_salary, $show_stoppublishing, $show_careerlevel, $show_posted, $show_category);
        }

        $html .= '</div>'; // .wpjobportal-job-meta-*
        $html .= '</div>'; // .wpjobportal-job-details
        $html .= '</div>'; // .wpjobportal-job-box

        return $html;
    }

    function wpjobportalRenderJobField($job, $field_key, $labels_for_values, $show_location, $show_jobtype, $show_salary, $show_stoppublishing, $show_careerlevel, $show_posted, $show_category) {
        $field_value = '';

        switch ($field_key) {
            case 'salary':
                if ($show_salary && !empty($job->salarytype)) {
                    $salary = wpjobportal::$_common->getSalaryRangeView($job->salarytype, $job->salarymin, $job->salarymax, isset($job->currency) ? $job->currency : '');
                    if (isset($job->salarytype) && ($job->salarytype == 3 || $job->salarytype == 2)) {
                        $salary .= ' / ' . esc_html(wpjobportal::wpjobportal_getVariableValue ($job->srangetypetitle));
                    }
                    $field_value = $salary;
                }
                break;

            case 'location':
                if ($show_location && !empty($job->location)) {
                    $field_value = $job->location;
                }
                break;

            case 'jobtype':
                if ($show_jobtype && !empty($job->jobtypetitle)) {
                    $field_value = $job->jobtypetitle;
                }
                break;

            case 'job_category':
                if ($show_category && !empty($job->cat_title)) {
                    $field_value = $job->cat_title;
                }
                break;

            case 'careerlevel':
                if ($show_careerlevel && !empty($job->careerleveltitle)) {
                    $field_value = $job->careerleveltitle;
                }
                break;

            case 'posted':
                if ($show_posted && !empty($job->created)) {
                    $field_value = human_time_diff(strtotime($job->created), strtotime(date_i18n("Y-m-d H:i:s"))) . ' ' . esc_html(__("Ago", 'wp-job-portal'));
                }
                break;

            case 'apply_before':
                if ($show_stoppublishing && !empty($job->stoppublishing)) {
                    $dateformat = wpjobportal::$_configuration['date_format'];
                    $field_value = date_i18n($dateformat, strtotime($job->stoppublishing));
                }
                break;
        }

        if (empty($field_value)) {
            return '';
        }

        $html = '<div class="wpjobportal-job-widget-detail-field-data wpjobportal-job-' . esc_attr($field_key) . '">' .
            $this->wpjobportalRenderFieldLabel($field_key, $labels_for_values) .
            esc_html($field_value) . '</div>';

        return $html;
    }

    function wpjobportalRenderFieldLabel($field_key, $labels_for_values) {
        $icons = array(
            'salary' => 'fa-money',
            'location' => 'fa-globe',
            'jobtype' => 'fa-briefcase',
            'job_category' => 'fa-folder',
            'posted' => 'fa-clock-o',
            'careerlevel' => 'fa-level-up',
            'stoppublishing' => 'fa-calendar',
            'apply_before' => 'fa-calendar',
        );

        if($labels_for_values == 1){ // use text
            $label = ucfirst(str_replace('_', ' ', $field_key));
            return esc_html($label) . ': ';
        }
        if($labels_for_values == 2){ // use icons
            if (isset($icons[$field_key])) {
                return '<i class="fa ' . esc_attr($icons[$field_key]) . '"></i> ';
            }
        }

        return '';
    }

    function wpjobportalRenderResumesWidgets($resumes, $layout = 'list', $num_of_columns = 1, $show_title = true, $show_photo = true, $show_name = true, $show_category = true, $show_jobtype = true, $show_experience = true, $show_available = true, $show_gender = true, $show_nationality = true, $show_location = true, $show_posted = true, $photo_width = 80, $photo_height = 80, $labels_for_values = 1, $field_order = array(), $elemntor_call = 0) {
        $html = '';

        if(empty($num_of_columns) || $num_of_columns == 0){
            $num_of_columns = 1;
        }

        $layout_class = 'wpjobportal-layout-' . esc_attr($layout);
        $column_class = 'wpjobportal-cols-' . intval($num_of_columns);

        $html .= '<div class="wpjobportal-resume-widget-multi-style-wrapper ' . esc_attr($layout_class . ' ' . $column_class) . '">';

        $wpdir = wp_upload_dir();
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $i = 0;

        $pageid = wpjobportal::wpjobportal_getPageidForWidgets();

        foreach ($resumes as $resume) {
            $resume_id = isset($resume->resumealiasid) ? $resume->resumealiasid : 0;
            $resume_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme' => 'resume', 'wpjobportallt' => 'viewresume', 'wpjobportalid' => $resume_id, 'wpjobportalpageid' => $pageid));

            $photo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
            if (!empty($resume->photo)) {
                $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->resumeid . '/photo/' . $resume->photo;
            }

            if ($i % $num_of_columns === 0) {
                if ($i !== 0) $html .= '</div>';
                $html .= '<div class="wpjobportal-resume-row">';
            }
            $i++;

            $html .= $this->wpjobportalRenderSingleResume($resume, $layout, $show_title, $show_photo, $show_name, $show_category, $show_jobtype, $show_experience, $show_available, $show_gender, $show_nationality, $show_location, $show_posted, $photo_width, $photo_height, $labels_for_values, $field_order, $resume_url, $photo, $elemntor_call);
        }

        if ($i != 0) {
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    function wpjobportalRenderSingleResume($resume, $layout, $show_title, $show_photo, $show_name, $show_category, $show_jobtype, $show_experience, $show_available, $show_gender, $show_nationality, $show_location, $show_posted, $photo_width, $photo_height, $labels_for_values, $field_order, $resume_url, $photo, $elemntor_call) {
        $html = '<div class="wpjobportal-resume-box wpjobportal-floatbox">';

        // Resume Photo
        if ($show_photo) {
            $html .= '<div class="wpjobportal-resume-photo">';
            $html .= '<a href="' . esc_url($resume_url) . '">';
            $html .= '<img src="' . esc_url($photo) . '" alt="' . esc_attr(isset($resume->name) ? $resume->name : '') . '" width="' . esc_attr($photo_width) . '" height="' . esc_attr($photo_height) . '">';
            $html .= '</a></div>';
        }

        // Resume Details
        $html .= '<div class="wpjobportal-resume-details">';

        if ($show_name && !empty($resume->name)) {
            $html .= '<div class="wpjobportal-resume-name">';
            $html .= '<a href="' . esc_url($resume_url) . '">' . esc_html($resume->name) . '</a>';
            $html .= '</div>';
        }

        if ($show_title && !empty($resume->applicationtitle)) {
            $html .= '<div class="wpjobportal-resume-title">';
            $html .= '<a href="' . esc_url($resume_url) . '">' . esc_html($resume->applicationtitle) . '</a>';
            $html .= '</div>';
        }

        // Meta Info Grouped
        $meta_class = ($layout === 'list') ? 'wpjobportal-resume-meta-row' : 'wpjobportal-resume-meta-col';
        $html .= '<div class="' . esc_attr($meta_class) . '">';

        // Render fields in specified order
        if($elemntor_call == 0){
            $field_order = array();
            $field_order[] = 'category';
            $field_order[] = 'jobtype';
            $field_order[] = 'experience';
            $field_order[] = 'location';
            $field_order[] = 'nationality';
            $field_order[] = 'gender';
            $field_order[] = 'available';
            $field_order[] = 'posted';
        }

        foreach ($field_order as $field_key) {
            $html .= $this->wpjobportalRenderResumeFieldsData($resume, $field_key, $labels_for_values, $show_category, $show_jobtype, $show_experience, $show_available, $show_gender, $show_nationality, $show_location, $show_posted);
        }

        $html .= '</div>'; // .wpjobportal-resume-meta-*
        $html .= '</div>'; // .wpjobportal-resume-details
        $html .= '</div>'; // .wpjobportal-resume-box

        return $html;
    }

    function wpjobportalRenderResumeFieldsData($resume, $field_key, $labels_for_values, $show_category, $show_jobtype, $show_experience, $show_available, $show_gender, $show_nationality, $show_location, $show_posted) {
        $field_value = '';

        switch ($field_key) {
            case 'category':
                if ($show_category && !empty($resume->cat_title)) {
                    $field_value = $resume->cat_title;
                }
                break;

            case 'jobtype':
                if ($show_jobtype && !empty($resume->jobtypetitle)) {
                    $field_value = $resume->jobtypetitle;
                }
                break;

            // case 'experience':
            //     if ($show_experience && !empty($resume->experiencetitle)) {
            //         $field_value = $resume->experiencetitle;
            //     }
            //     break;

            case 'location':
                if ($show_location && !empty($resume->location)) {
                    $field_value = $resume->location;
                }
                break;

            case 'nationality':
                if ($show_nationality && !empty($resume->nationalityname)) {
                    $field_value = $resume->nationalityname;
                }
                break;

            // case 'gender':
            //     if ($show_gender && isset($resume->gender)) {
            //         $field_value = ($resume->gender == 1) ? esc_html(__('Male', 'wp-job-portal')) : esc_html(__('Female', 'wp-job-portal'));
            //     }
            //     break;

            // case 'available':
            //     if ($show_available && isset($resume->available)) {
            //         $field_value = ($resume->available == 1) ? esc_html(__('Yes', 'wp-job-portal')) : esc_html(__('No', 'wp-job-portal'));
            //     }
            //     break;

            case 'posted':
                if ($show_posted && !empty($resume->created)) {
                    $field_value = human_time_diff(strtotime($resume->created), strtotime(date_i18n("Y-m-d H:i:s"))) . ' ' . esc_html(__("Ago", 'wp-job-portal'));
                }
                break;
        }

        if (empty($field_value)) {
            return '';
        }

        $html = '<div class="wpjobportal-resume-widget-detail-field-data wpjobportal-resume-' . esc_attr($field_key) . '">' .
            $this->wpjobportalRenderResumeFieldLabel($field_key, $labels_for_values) .
            esc_html($field_value) . '</div>';

        return $html;
    }

    function wpjobportalRenderResumeFieldLabel($field_key, $labels_for_values) {
        $icons = array(
            'category' => 'fa-folder',
            'jobtype' => 'fa-briefcase',
            'experience' => 'fa-line-chart',
            'location' => 'fa-globe',
            'nationality' => 'fa-globe',
            'gender' => 'fa-venus-mars',
            'available' => 'fa-check-circle',
            'posted' => 'fa-clock-o'
        );

        if($labels_for_values == 1){ // use text
            $label = ucfirst(str_replace('_', ' ', $field_key));
            return esc_html($label) . ': ';
        }
        if($labels_for_values == 2){ // use icons
            if (isset($icons[$field_key])) {
                return '<i class="fa ' . esc_attr($icons[$field_key]) . '"></i> ';
            }
        }

        return '';
    }

    // companies widget
    function wpjobportalRenderCompaniesTemplate($companies, $layout = 'list', $num_of_columns = 1, $show_comapny_name = true, $show_category = true, $show_location = true, $show_posted = true, $show_logo = true, $logo_width = 80, $logo_height = 80, $labels_for_values = 1, $field_order = array()) {
        $html = '';

        // Set default field order if not provided
        if(empty($field_order)) {
            $field_order = array('category', 'location', 'posted');
        }

        // Module wrapper and title
        $html .= '<div class="wpjobportal-companies-widget-wrapper">';

        // if($show_module_title && !empty($module_title)) {
        //     $html .= '<div class="wjportal-mod-heading">'.esc_html($module_title).'</div>';
        // }

        // List layout
        if($layout == 'list') {
            $html .= $this->widgetRenderCompanyList($companies, $show_comapny_name, $show_category, $show_location, $show_posted, $show_logo, $logo_width, $logo_height, $labels_for_values, $field_order);
        }
        // Grid/Box layout
        else {
            $html .= $this->widgetRenderCompanyGrid($companies, $num_of_columns, $show_comapny_name, $show_category, $show_location, $show_posted, $show_logo, $logo_width, $logo_height, $labels_for_values, $field_order);
        }

        $html .= '</div>'; // End wrapper

        return $html;
    }

    function widgetRenderCompanyList($companies, $show_comapny_name, $show_category, $show_location, $show_posted, $show_logo, $logo_width, $logo_height, $labels_for_values, $field_order) {
        $html = '';
        $wpdir = wp_upload_dir();
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $dateformat = wpjobportal::$_configuration['date_format'];

        $pageid = wpjobportal::wpjobportal_getPageidForWidgets();

        // Company rows
        foreach($companies as $company) {
            $company_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme' => 'company','wpjobportallt' => 'viewcompany','wpjobportalid' => isset($company->companyaliasid) ? $company->companyaliasid : 0,'wpjobportalpageid' => $pageid ));

            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
            if (!empty($company->logofilename)) {
                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
            }

            $html .= '<div class="wpjobportal-companies-list-row">';

            // Logo
            if($show_logo) {
                $html .= '<div class="wpjobportal-companies-list-col-logo">';
                $html .= '<a href="'.esc_url($company_url).'">';
                $html .= '<img src="'.esc_url($logo).'" alt="'.esc_attr($company->name).'" width="'.esc_attr($logo_width).'" height="'.esc_attr($logo_height).'">';
                $html .= '</a></div>';
            }

            // company name
            if($show_comapny_name) {
                $html .= '<div class="wpjobportal-companies-list-col-title">';
                $html .= '<a href="'.esc_url($company_url).'">'.esc_html($company->name).'</a>';
                $html .= '</div>';
            }

            // Fields
            foreach($field_order as $field) {
                $field_value = '';
                $field_class = '';
                $field_label = '';

                switch($field) {
                    case 'category':
                        // if($show_category && !empty($company->cat_title)) {
                        //     $field_value = $company->cat_title;
                        //     $field_class = 'category';
                        //     $field_label = __('Category', 'wp-job-portal');
                        // }
                        break;
                    case 'location':
                        if($show_location && !empty($company->location)) {
                            $field_value = $company->location;
                            $field_class = 'location';
                            $field_label = __('Location', 'wp-job-portal');
                        }
                        break;
                    case 'posted':
                        if($show_posted && !empty($company->created)) {
                            $field_value = date_i18n($dateformat, strtotime($company->created));
                            $field_class = 'posted';
                            $field_label = __('Posted', 'wp-job-portal');
                        }
                        break;
                }

                if(!empty($field_value)) {
                   // $html .= '<div class="wpjobportal-companies-list-col-'.esc_attr($field_class).'">'.esc_html($field_value).'</div>';
                    $html .= $this->widgetRenderCompanyField($field_value, $field_label, $labels_for_values, $field);
                }
            }

            $html .= '</div>'; // End row
        }

        return $html;
    }

    function widgetRenderCompanyGrid($companies, $num_of_columns, $show_company_name, $show_category, $show_location, $show_posted, $show_logo, $logo_width, $logo_height, $labels_for_values, $field_order) {
        $html = '';
        $wpdir = wp_upload_dir();
        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
        $dateformat = wpjobportal::$_configuration['date_format'];

        $column_class = 'wpjobportal-cols-'.intval($num_of_columns);
        $html .= '<div class="wpjobportal-companies-grid-wrapper '.esc_attr($column_class).'">';
        $count_company_wrp = 0;

        $pageid = wpjobportal::wpjobportal_getPageidForWidgets();

        foreach($companies as $company) {
            $company_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme' => 'company','wpjobportallt' => 'viewcompany','wpjobportalid' => isset($company->companyaliasid) ? $company->companyaliasid : 0,'wpjobportalpageid' => $pageid));
            $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
            if (!empty($company->logofilename)) {
                $logo = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
            }

            if ($count_company_wrp % $num_of_columns === 0) {
                if ($count_company_wrp !== 0) $html .= '</div>';
                $html .= '<div class="wpjobportal-companies-widget-company-row">';
            }
            $count_company_wrp++;

            $html .= '<div class="wpjobportal-company-box">';

            // Logo
            if($show_logo) {
                $html .= '<div class="wpjobportal-company-logo">';
                $html .= '<a href="'.esc_url($company_url).'">';
                $html .= '<img src="'.esc_url($logo).'" alt="'.esc_attr($company->name).'" width="'.esc_attr($logo_width).'" height="'.esc_attr($logo_height).'">';
                $html .= '</a></div>';
            }

            // Title
            if($show_company_name) {
                $html .= '<div class="wpjobportal-company-title">';
                $html .= '<a href="'.esc_url($company_url).'">'.esc_html($company->name).'</a>';
                $html .= '</div>';
            }

            // Fields
            $html .= '<div class="wpjobportal-company-details">';
            foreach($field_order as $field) {
                $field_value = '';
                $field_label = '';

                switch($field) {
                    case 'category':
                        if($show_category && !empty($company->cat_title)) {
                            $field_value = $company->cat_title;
                            $field_label = __('Category', 'wp-job-portal');
                        }
                        break;
                    case 'location':
                        if($show_location && !empty($company->location)) {
                            $field_value = $company->location;
                            $field_label = __('Location', 'wp-job-portal');
                        }
                        break;
                    case 'posted':
                        if($show_posted && !empty($company->created)) {
                            $field_value = date_i18n($dateformat, strtotime($company->created));
                            $field_label = __('Posted', 'wp-job-portal');
                        }
                        break;
                }

                if(!empty($field_value)) {
                    $html .= $this->widgetRenderCompanyField($field_value, $field_label, $labels_for_values, $field);
                }
            }
            $html .= '</div>';

            $html .= '</div>'; // End company box
        }
        // close row wrapper
        if ($count_company_wrp !== 0) $html .= '</div>';

        $html .= '</div>'; // End grid wrapper

        return $html;
    }

    function widgetRenderCompanyField($value, $label, $labels_for_values, $field_key) {
        $icons = array(
            'category' => 'fa-folder',
            'location' => 'fa-globe',
            'posted' => 'fa-calendar'
        );

        $html = '<div class="wpjobportal-company-field wpjobportal-company-'.esc_attr($field_key).'">';

        if($labels_for_values == 1) { // Text labels
            $html .= '<span class="wpjobportal-company-field-label">'.esc_html($label).':</span> ';
        } elseif($labels_for_values == 2 && isset($icons[$field_key])) { // Icons
            $html .= '<i class="fa '.esc_attr($icons[$field_key]).'"></i> ';
        }

        $html .= '<span class="wpjobportal-company-field-value">'.esc_html($value).'</span>';
        $html .= '</div>';

        return $html;
    }

}
?>
