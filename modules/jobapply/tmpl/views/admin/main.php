<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param Object--refrence
*/
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(3);
?>
<?php
	switch ($layout) {
		case 'logo':
			$photo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
			$padding = ' style="padding:15px;" ';
			if (isset($data->photo) && $data->photo != '') {
				$data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
				$wpdir = wp_upload_dir();
				$photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $data->resumeid . '/photo/' . $data->photo;
				$padding = "";
			}
			?>
			<div class="wpjobportal-resume-logo">
                <?php if(isset($listing_fields['photo'])){ ?>
                    <img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_html(__('logo','wp-job-portal')); ?>" />
                <?php } ?>
                <div class="wpjobportal-resume-crt-date">
                    <?php echo esc_html(date_i18n(wpjobportal::$_configuration['date_format'], strtotime($data->apply_date))); ?>
                </div>
            </div>
            <?php
		break;
    	case 'detail':
            if(isset($data->socialprofile)){
                $socialprofile = json_decode($data->socialprofile);
            }
            if(isset($socialprofile)){
                $data->first_name = isset($data->first_name) ? $data->first_name : $socialprofile->first_name; 
                $data->last_name = isset($data->last_name) ? $data->last_name : $socialprofile->last_name;
                $data->applicationtitle = isset($data->applicationtitle) ? $data->applicationtitle : $socialprofile->email;
            }
    		?>
			<div class="wpjobportal-resume-cnt-wrp">
                <div class="wpjobportal-resume-middle-wrp">
                    <div class="wpjobportal-resume-data">
                        <?php if( isset($listing_fields['jobtype']) ){ ?>
                            <span class="wpjobportal-resume-job-type" style="background: <?php echo esc_attr($data->jobtypecolor); ?>;">
                                <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($data->jobtypetitle)); ?>
                            </span>
                        <?php }?>
                    </div>    
                    <div class="wpjobportal-resume-data">
                        <span class="wpjobportal-resume-name">
                            <?php echo esc_html($data->first_name) . " " . esc_html($data->last_name) ?>
                        </span>
                    </div>
                    <?php if($data->quick_apply != 1){ ?>
                        <div class="wpjobportal-resume-data">
                            <span class="wpjobportal-resume-title">
                                <?php echo esc_html($data->applicationtitle); ?>
                            </span>
                        </div>

                        <div class="wpjobportal-resume-data">
                            <?php if( isset($listing_fields['salaryfixed']) ){ ?>
                                <div class="wpjobportal-resume-data-text">
                                    <span class="wpjobportal-resume-data-title">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['salaryfixed'])).': '; ?>
                                    </span>
                                    <span class="wpjobportal-resume-data-value">
                                        <?php echo esc_html($data->salary); ?>
                                    </span>
                                </div>
                            <?php } ?>
                            <?php if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){ ?>
                                <div class="wpjobportal-resume-data-text">
                                    <span class="wpjobportal-resume-data-title">
                                        <?php echo esc_html(__('Total Experience', 'wp-job-portal')) . ': '; ?>
                                    </span>
                                    <span class="wpjobportal-resume-data-value">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_common->getTotalExp($data->resumeid))); ?>
                                    </span>
                                </div>
                                <?php if(isset($listing_fields['address_city'])){ ?>
                                    <div class="wpjobportal-resume-data-text">
                                        <span class="wpjobportal-resume-data-title">
                                            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['address_city'])) . ': '; ?>
                                        </span>
                                        <span class="wpjobportal-resume-data-value">
                                            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($data->location)); ?>
                                        </span>
                                    </div>
                                <?php }?>
                            <?php }?>
                            <?php if(isset($listing_fields['job_category'])) { ?>
                                <div class="wpjobportal-resume-data-text">
                                    <span class="wpjobportal-resume-data-title">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['job_category'])) . ': '; ?>
                                    </span>
                                    <span class="wpjobportal-resume-data-value">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($data->resume_category)); ?>
                                    </span>
                                </div>
                            <?php }?>
                            <?php if(isset($listing_fields['jobtype'])) {?>
                                <div class="wpjobportal-resume-data-text">
                                    <span class="wpjobportal-resume-data-title">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['jobtype'])) . ': '; ?>
                                    </span>
                                    <span class="wpjobportal-resume-data-value">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($data->jobtypetitle)); ?>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if(isset($data->apply_message) && $data->apply_message !=''){
                        $apply_message_label = wpjobportal::$_wpjpfieldordering->getFieldTitleByFieldAndFieldfor('message',5); ?>
                        <div class="wpjobportal-resume-data">
                            <div class="wpjobportal-resume-data-text">
                                <span class="wpjobportal-resume-data-title">
                                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($apply_message_label)) . ': '; ?>
                                </span>
                                <span class="wpjobportal-resume-data-value">
                                    <?php echo esc_html($data->apply_message); ?>
                                </span>
                            </div>
                        </div>
                    <?php }?>
                    <?php do_action('wpjobportal_addon_search_applied_resume'); ?>
                </div>
                <div class="wpjobportal-resume-right-wrp">
                    <?php do_action('wpjobportal_addons_rating_resume_applied',$data); ?>
                    <?php  do_action('wpjobportal_addons_credit_applied_resume_ratting_admin',$data); ?>
                    <?php
                        if(in_array('coverletter', wpjobportal::$_active_addons)){
                                 $cover_letter_title = '';
                                 $cover_letter_desc = '';
                                 if( isset($data->coverletterdata) && !empty($data->coverletterdata) ){

                                     $cover_letter_title = $data->coverletterdata->title;
                                     $cover_letter_desc = $data->coverletterdata->description;
                                 }
                                if(isset($data->coverletterid) && is_numeric($data->coverletterid) && $data->coverletterid > 0){
                                     echo '<div id="cover_letter_data_title_'.esc_attr($data->coverletterid).'" style="display:none;" >'.esc_html($cover_letter_title).'</div>';
                                     echo '<div id="cover_letter_data_desc_'.esc_attr($data->coverletterid).'" style="display:none;" >'.wp_kses($cover_letter_desc,WPJOBPORTAL_ALLOWED_TAGS).'</div>';

                                     echo '
                                     <a class="wpjobportal-viewcover-act-btn" href="#" onClick="showCoverLetterData('.esc_attr($data->coverletterid).')" title='. esc_html(__('view coverletter', 'wp-job-portal')) .'>
                                         '. esc_html(__('View Cover Letter', 'wp-job-portal')) .'
                                     </a>';
                                }else{
                                    echo '
                                    <span class="wjportal-no-coverletter-btn">
                                        '. esc_html(__('No Cover Letter', 'wp-job-portal')) .'
                                    </span>';
                                }
                           }?>
                </div>
            </div>
            <div id="<?php echo esc_attr($data->appid); ?>" ></div>
            <div id="comments" class="wpjobportal-applied-job-actions-popup <?php echo esc_attr($data->appid); ?>" ></div>
            <?php
		break;
    }
?>
