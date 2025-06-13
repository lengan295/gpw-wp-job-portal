<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param WP JOB PORTAL
 * @param Main  
 */
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(3);
echo '<div class="wjportal-resume-cnt-wrp">
        <div class="wjportal-resume-middle-wrp">      ';
        if(isset($listing_fields['jobtype'])){
            echo '
                <div class="wjportal-resume-data">
                    <span class="wjportal-resume-job-type" style="background-color:'.esc_attr($myresume->jobtypecolor).'">'.esc_html(wpjobportal::wpjobportal_getVariableValue($myresume->jobtypetitle)) .'</span>
                </div>';
        }
            
            if(isset($myresume->socialprofile)){
                $socialprofile = json_decode($myresume->socialprofile);
            }
            if(isset($socialprofile)){
                $myresume->first_name = isset($myresume->first_name) ? $myresume->first_name : $socialprofile->first_name; 
                $myresume->last_name = isset($myresume->last_name) ? $myresume->last_name : $socialprofile->last_name;
                $myresume->applicationtitle = isset($myresume->applicationtitle) ? $myresume->applicationtitle : $socialprofile->email;
            }
            echo '<div class="wjportal-resume-data">
            <a href='.esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))).'>
                <span class="wjportal-resume-name">'.esc_html($myresume->first_name) .' ' . esc_html($myresume->last_name).' '.'</span></a>';
                $featuredflag = true;
                $dateformat = wpjobportal::$_configuration['date_format'];
                $curdate = date_i18n('Y-m-d');
                do_action('wpjobportal_addons_feature_resume_lable',$myresume);
            echo '</div>  ';
            $hide_extra_fields = 0;
            if(isset($myresume->quick_apply) && $myresume->quick_apply == 1){
                $hide_extra_fields = 1;
            }
            if($hide_extra_fields == 0){
                echo '
                <div class="wjportal-resume-data">
                    <span class="wjportal-resume-title">'. esc_html($myresume->applicationtitle) .'</span>
                </div>
                <div class="wjportal-resume-data">';
                    if(empty(wpjobportal::$_data['shortcode_option_hide_resume_location'])){
                        if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                            if(isset($listing_fields['address_city'])){
                                echo '<div class="wjportal-resume-data-text">
                                    <span class="wjportal-resume-data-title">'. esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['address_city'])) . ': '.'</span>
                                    <span class="wjportal-resume-data-value">'. esc_html($myresume->location) .'</span>
                                </div>';
                            }
                        }
                    }
                    if(empty(wpjobportal::$_data['shortcode_option_hide_resume_salary'])){
                        if(isset($listing_fields['salaryfixed']) && (isset($module) && ($module== "resume" || $module == "myresumes"))){
                            echo '<div class="wjportal-resume-data-text">
                                    <span class="wjportal-resume-data-title">';
                                    echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['salaryfixed'])) . ': ';
                            echo '</span>
                                <span class="wjportal-resume-data-value">'. esc_html($myresume->salary).'</span>
                            </div>';
                        }
                    }
                    if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                        echo '<div class="wjportal-resume-data-text">
                            <span class="wjportal-resume-data-title">';
                                echo esc_html(__('Experience', 'wp-job-portal')) . ': ';
                            echo '</span>
                            <span class="wjportal-resume-data-value">'.
                                esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_common->getTotalExp($myresume->resumeid))) .'
                            </span>';
                        echo '</div>';
                    }
                    if(isset($listing_fields['job_category'])){
                        echo '<div class="wjportal-resume-data-text">
                            <span class="wjportal-resume-data-title">';
                                echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['job_category'])) . ': ';
                            echo '</span>
                            <span class="wjportal-resume-data-value">'.
                                esc_html(wpjobportal::wpjobportal_getVariableValue($myresume->cat_title)) .'
                            </span>';
                        echo '</div>';
                    }
                    if($module == "jobappliedresume" && !empty($myresume->comments)){
                        echo '<div class="wjportal-resume-data-text">
                                <span class="wjportal-resume-data-title">';
                                    echo esc_html(__('Notes', 'wp-job-portal')) . ': ';
                                echo '</span>
                                <span class="wjportal-resume-data-value">'. esc_html($myresume->comments)  .'
                                </span>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '
                    <div class="wjportal-custom-field-wrp">';
                    $customfields = wpjobportal::$_wpjpcustomfield->userFieldsData(3,1,1);/*apply_filters('wpjobportal_addons_get_custom_field',false,3,1,1)*/;
                    foreach ($customfields as $field) {
                        $showCustom =  wpjobportal::$_wpjpcustomfield->showCustomFields($field,9,$myresume->params)/*apply_filters('wpjobportal_addons_show_customfields_params',false,$field,9,$myresume->params)*/;
                        echo wp_kses($showCustom, WPJOBPORTAL_ALLOWED_TAGS);
                    }

                    echo  '</div>';
            }
                echo  '
        </div>      ';
        if(isset($jobapply) && $jobapply == "jobapplied"){
            do_action('wpjobportal_addons_credit_applied_resume_rating',$myresume);
       }
       if(isset($module) && $module == "myresumes"){
       	echo '<div class="wjportal-resume-right-wrp" data-per='.esc_attr($percentage).' >
                    <div class="wjportal-resume-status-wrp">
                        <span class="wjportal-resume-status-heading">
                            '. esc_html(__('Profile Status','wp-job-portal')).' :
                        </span>';
                        echo '<div class="wjportal-resume-status-counter">
                            <div class="js-mr-rp" data-progress="100"> <div class="circle"> <div class="mask full"> <div class="fill"></div> </div> <div class="mask half"> <div class="fill"></div> <div class="fill fix"></div> </div> <div class="shadow"></div> </div> <div class="inset"> <div class="percentage"> <div class="numbers"><span>-</span><span>0%</span><span>1%</span><span>2%</span><span>3%</span><span>4%</span><span>5%</span><span>6%</span><span>7%</span><span>8%</span><span>9%</span><span>10%</span><span>11%</span><span>12%</span><span>13%</span><span>14%</span><span>15%</span><span>16%</span><span>17%</span><span>18%</span><span>19%</span><span>20%</span><span>21%</span><span>22%</span><span>23%</span><span>24%</span><span>25%</span><span>26%</span><span>27%</span><span>28%</span><span>29%</span><span>30%</span><span>31%</span><span>32%</span><span>33%</span><span>34%</span><span>35%</span><span>36%</span><span>37%</span><span>38%</span><span>39%</span><span>40%</span><span>41%</span><span>42%</span><span>43%</span><span>44%</span><span>45%</span><span>46%</span><span>47%</span><span>48%</span><span>49%</span><span>50%</span><span>51%</span><span>52%</span><span>53%</span><span>54%</span><span>55%</span><span>56%</span><span>57%</span><span>58%</span><span>59%</span><span>60%</span><span>61%</span><span>62%</span><span>63%</span><span>64%</span><span>65%</span><span>66%</span><span>67%</span><span>68%</span><span>69%</span><span>70%</span><span>71%</span><span>72%</span><span>73%</span><span>74%</span><span>75%</span><span>76%</span><span>77%</span><span>78%</span><span>79%</span><span>80%</span><span>81%</span><span>82%</span><span>83%</span><span>84%</span><span>85%</span><span>86%</span><span>87%</span><span>88%</span><span>89%</span><span>90%</span><span>91%</span><span>92%</span><span>93%</span><span>94%</span><span>95%</span><span>96%</span><span>97%</span><span>98%</span><span>99%</span><span>100%</span></div></div></div></div>
                        </div>';
                         if($percentage == 100){ 
                            echo '<span class="wjportal-resume-status-title">'. esc_html(__('Profile Completed','wp-job-portal')).'</span>';
                         } 
                echo '</div>
                    </div>';
       }
    echo'<div class="wjportal-resume-right-wrp">';
                if(isset($module) && $module == "jobappliedresume"){
                    echo '<div>';
                    do_action('wpjobportal_addons_credit_applied_resume_rating',$myresume);
                    echo '</div>';

                    if(in_array('coverletter', wpjobportal::$_active_addons)){

                            $cover_letter_title = '';
                            $cover_letter_desc = '';
                            if( isset($myresume->coverletterdata) && !empty($myresume->coverletterdata) ){

                                $cover_letter_title = $myresume->coverletterdata->title;
                                $cover_letter_desc = $myresume->coverletterdata->description;
                            }
                        if(isset($myresume->coverletterid) && is_numeric($myresume->coverletterid) && $myresume->coverletterid > 0){
                            echo '<div id="cover_letter_data_title_'.esc_attr($myresume->coverletterid).'" style="display:none;" >'.wp_kses($cover_letter_title, WPJOBPORTAL_ALLOWED_TAGS).'</div>';
                            echo '<div id="cover_letter_data_desc_'.esc_attr($myresume->coverletterid).'" style="display:none;" >'.wp_kses($cover_letter_desc, WPJOBPORTAL_ALLOWED_TAGS).'</div>';
                            echo '
                            <a class="wjportal-coverletter-act-btn" href="#" onClick="showCoverLetterData('.esc_attr($myresume->coverletterid).')" title='. esc_html(__('view coverletter', 'wp-job-portal')) .'>
                                '. esc_html(__('View Cover Letter', 'wp-job-portal')) .'
                            </a>';
                        }else{
                            echo '
                            <span class="wjportal-no-coverletter-btn">
                                '. esc_html(__('No Cover Letter', 'wp-job-portal')) .'
                            </span>';
                        }
                    }
                }

            if(isset($module) && ($module == "dashboard" || $module == "resume")) :
	            echo '<div class="wjportal-resume-action">
	                <a class="wjportal-resume-act-btn" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'jobid'=>$myresume->id, 'wpjobportalid'=>$myresume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))).' title='. esc_html(__('view profile', 'wp-job-portal')) .'>
	                    '. esc_html(__('View Profile', 'wp-job-portal')) .'
	                </a>
	            </div>';
        	endif;
      echo '</div> 
    </div>';
