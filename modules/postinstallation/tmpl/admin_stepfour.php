<?php
if (!defined('ABSPATH')) die('Restricted Access');
    wp_enqueue_style( 'wpjobportal-chosen',WPJOBPORTAL_PLUGIN_URL . 'includes/js/chosen/chosen.min.css', array(), null);
    wp_enqueue_script( 'chosen', esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/js/chosen/chosen.jquery.min.js');
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function() {
          jQuery('.wpjobportal-userselect').chosen({
            placeholder_text_single: 'Select User',
            no_results_text: 'Oops, nothing found!'
          });
          jQuery('#jobseeker_list').val(0).trigger('chosen:updated')  
          jQuery('#employer_list').val(0).trigger('chosen:updated')  
        });
        function refreshList() {
            location.reload();
        }
        function showHideUserForm() {
            var sampledata = jQuery('#sampledata').val();
            if (sampledata == 0) {
                jQuery('.wpjobportal-post-show-default-user-form').addClass('wpjobportal-post-hide-default-user-form');
            } else {
                jQuery('.wpjobportal-post-show-default-user-form').removeClass('wpjobportal-post-hide-default-user-form');
            }
        }
        function checkForEmpAndJSId() {
            var jobseeker_id = jQuery('#jobseeker_id').val();
            var employer_id = jQuery('#employer_id').val();
            if (employer_id != 0 && employer_id == jobseeker_id) {
                alert('Jobseeker And Employer Cannot Be Same');
            } else {
                document.getElementById('wpjobportal-form-ins').submit();
            }
        }
        function setValueForJobSeeker() {
            var option = jQuery('#jobseeker_list').val();
            var myOption = option.split('-');
            var id =  Number(myOption[myOption.length - 1]);
            jQuery('#jobseeker_id').val(id);
        }
        function setValueForEmployer() {
            var option = jQuery('#employer_list').val();
            var myOption = option.split('-');
            var id =  Number(myOption[myOption.length - 1]);
            jQuery('#employer_id').val(id);
        }
        jQuery(document).ready(function () {
            jQuery('.chosen-single').on('click', function (e) {
              e.preventDefault();
              jQuery('.chosen-single').removeClass('active-shadow');
              jQuery('.chosen-container').css('box-shadow', '');
              jQuery('.wpjobportal-post-tit').filter(function () {
                return jQuery(this).next('.wpjobportal-post-val').find('.chosen-single').length;
              }).css('color', '');
              jQuery(this)
                .closest('.wpjobportal-post-val')
                .prev('.wpjobportal-post-tit')
                .css('color', '#1572e8');
              jQuery(this).addClass('active-shadow');
            });
            jQuery('.inputbox').on('focus', function () {
              jQuery(this)
                .closest('.wpjobportal-post-val')
                .prev('.wpjobportal-post-tit')
                .css('color', '#1572e8');
            });
            jQuery('.inputbox').on('blur', function () {
              jQuery(this)
                .closest('.wpjobportal-post-val')
                .prev('.wpjobportal-post-tit')
                .css('color', '');
            });
            jQuery(document).on('click', function (e) {
              if (!jQuery(e.target).closest('.chosen-single').length) {
                jQuery('.chosen-single').removeClass('active-shadow');
                jQuery('.chosen-container').css('box-shadow', '');
                jQuery('.wpjobportal-post-tit').filter(function () {
                  return jQuery(this).next('.wpjobportal-post-val').find('.chosen-single').length;
                }).css('color', '');
              }
            });
          });
               
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

$yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal')))
                    , (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
?>
<div id="wpjobportaladmin-wrapper" class="wpjobportal-post-installation-wrp">
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <div class="wpjobportal-post-installation-logowrp">
                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quickstrt_logo.png" />
            </div>
            <ul class="step-4">
                <li class="zero-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=quickstart")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-start.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-strt-w.png" />
                            <?php echo esc_html(__('Quick Configuration','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <li class="first-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings-white.png" />
                            <?php echo esc_html(__('General Settings','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <?php $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
                if($wpjobportal_multiple_employers == 1){ ?>
                <li class="second-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=steptwo")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/employers.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings-white.png" />
                            <?php echo esc_html(__('Employer Settings','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <?php }?>
                <li class="third-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepthree")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/jobseeker.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/jobseeker-w.png" />
                            <?php echo esc_html(__('Job Seeker Settings','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <li class="fourth-part active">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepfour")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/sample-data.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/sample-data-w.png" />
                            <?php echo esc_html(__('Sample Data','wp-job-portal')); ?>
                        </span>
                    </a>
                </li>
                <li class="setup-complete">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=setupcomplete")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/finshed.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/finshed-w.png" />
                            <?php echo esc_html(__('Setup Complete','wp-job-portal')); ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="wpjobportal-post-data">
            <div class="wpjobportal-post-heading-wrp">
                <div class="wpjobportal-post-heading">
                    <?php echo esc_html(__('Sample Data','wp-job-portal'));?>
                </div>
                <div class="wpjobportal-post-head-rightbtns-wrp">
                    <span class="wpjobportal-post-head-pagestep">
                        <?php
                        $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
                        if($wpjobportal_multiple_employers == 1){
                            echo esc_html(__('Step 5 of 5','wp-job-portal'));
                        }else{
                            echo esc_html(__('Step 4 of 4','wp-job-portal'));
                        }
                        ?>
                    </span>
                    <a class="wpjobportal-post-head-closebtn" href="admin.php?page=wpjobportal"title="<?php echo esc_html(__('Close','wp-job-portal'));?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/close.png" />
                    </a>
                </div>
            </div>
            <form id="wpjobportal-form-ins" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&task=savesampledata")); ?>">
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Insert Sample Data','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('sampledata', $yesno,1,'',array('class' => 'inputbox','onchange' => 'showHideUserForm()')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-smpledata-infowrp">
                            <img alt="<?php echo esc_html(__('Info Icon','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/info-icon.png" />
                            <?php echo esc_html(__('Import demo data to populate your site with example companies, jobs, resumes, and applications.','wp-job-portal')); ?>
                        </div>
                    </div>
                </div>

                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Select Employer','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val wpjobportal-post-val-user-list">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('employer_list', WPJOBPORTALincluder::getJSModel('postinstallation')->getWpUsersList(),1,'',array('class' => 'inputbox wpjobportal-userselect' , 'onchange' => 'setValueForEmployer()')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <span class="wpjobportal-post-refresh-btn" onclick="refreshList()" title="<?php echo esc_html(__('refresh','wp-job-portal'));?>"><?php echo esc_html(__('Refresh','wp-job-portal')); ?></span>
                        <a target="_blank" class="wpjobportal-post-create-user-btn" href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" title="<?php echo esc_html(__('create user','wp-job-portal'));?>"><?php echo esc_html(__('Create user','wp-job-portal')); ?></a>
                    </div>
                </div>

                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Select Jobseeker','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val wpjobportal-post-val-user-list">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('jobseeker_list', WPJOBPORTALincluder::getJSModel('postinstallation')->getWpUsersList(),1,'',array('class' => 'inputbox wpjobportal-userselect' , 'onchange' => 'setValueForJobSeeker()')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <span class="wpjobportal-post-refresh-btn" onclick="refreshList()" title="<?php echo esc_html(__('refresh','wp-job-portal'));?>"><?php echo esc_html(__('Refresh','wp-job-portal')); ?></span>
                        <a target="_blank" class="wpjobportal-post-create-user-btn" href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" title="<?php echo esc_html(__('create user','wp-job-portal'));?>"><?php echo esc_html(__('Create user','wp-job-portal')); ?></a>
                    </div>
                </div>

                <?php if(wpjobportal::$theme_chk == 0){ ?>
                    <div class="wpjobportal-post-heading">
                        <?php echo esc_html(__('Menu','wp-job-portal'));?>
                    </div>
                    <div class="wpjobportal-post-data-row">
                        <div class="wpjobportal-post-tit">
                            <?php echo esc_html(__('Job Seeker Menu','wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-post-val">
                            <?php echo wp_kses(WPJOBPORTALformfield::select('jsmenu', $yesno,1,'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <?php if($wpjobportal_multiple_employers == 1){?>
                        <div class="wpjobportal-post-data-row">
                            <div class="wpjobportal-post-tit">
                                <?php echo esc_html(__('Employer Menu','wp-job-portal')); ?>
                            </div>
                            <div class="wpjobportal-post-val">
                                <?php echo wp_kses(WPJOBPORTALformfield::select('empmenu', $yesno,1,'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="wpjobportal-post-data-row">
                            <div class="wpjobportal-post-tit">
                                <?php echo esc_html(__('Jobs Listing Menu','wp-job-portal')); ?>
                            </div>
                            <div class="wpjobportal-post-val">
                                <?php echo wp_kses(WPJOBPORTALformfield::select('job_listing_menu', $yesno,1,'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php  } elseif(wpjobportal::$theme_chk != 1){ ?>
                        <div class="pic-config temp-demo-data">
                            <div class="wpjobportal-post-tit">
                                <?php     echo esc_html(__('Job Hub Sample Data','wp-job-portal')); ?>
                            </div>
                            <div class="wpjobportal-post-val">
                                <?php echo wp_kses(WPJOBPORTALformfield::select('temp_data', $yesno,1,'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            </div>
                            <div class="desc"><?php echo esc_html(__('if yes is selected then pages and menus of job manager template will be cretaed and published.','wp-job-portal'));?>. </div>
                        </div>
                <?php } ?>
                <div class="wpjobportal-post-action-btn" style="text-align: center;">
                    <a class="next-step wpjobportal-post-act-btn" href="javascript:void();" onclick="document.getElementById('wpjobportal-form-ins').submit();"  title="<?php echo esc_html(__('next','wp-job-portal')); ?>" style="float: none;">
                        <?php echo esc_html(__('Next Setup','wp-job-portal')); ?>
                    </a>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('jobseeker_id', '0'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('employer_id', '0'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('step', 3),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_postinstallation_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </form>
        </div>
    </div>
</div>
