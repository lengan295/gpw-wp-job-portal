
<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param none
*/

// code to manage width of info section and show hide apply form


$show_quick_apply_form  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_for_user');
if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
    $show_quick_apply_form  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_for_visitor');
}
$google_recaptcha_3 = false;
$captcha_quick_apply  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_captcha');
?>

<?php
         wp_register_script( 'wpjobportal-inline-handle', '' );
         wp_enqueue_script( 'wpjobportal-inline-handle' );

         $inline_js_script = "
             jQuery(document).ready(function ($) {
                 $.validate();
             });
             ";
         wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        if ($show_quick_apply_form == 1 || wpjobportal::$_config->getConfigValue('showapplybutton') == 1) { ?>
            <div class="wjportal-view-job-page-job-apply-form-wraper" >
                <?php  //do_action('wpjobportal_addons_quick_apply_form');
                    echo '<div class="wjportal-form-wrp wpjobportal-quickapply-form" >';
                        echo '<div class="wjportal-job-sec-title" >';
                            echo esc_html(__('Apply to the Job', 'wp-job-portal'));
                        echo '</div>';
                        $show_job_apply_redirect_link_only = 0;
                        if($job->jobapplylink == 1 && !empty($job->joblink)){
                            $show_job_apply_redirect_link_only = 1;
                        }

                        echo '<form class="wjportal-form" id="wpjobportal-form" method="post" enctype="multipart/form-data" action="'. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'task'=>'applyonjob'))).'">';
                            $jobid =  (!empty(wpjobportal::$_data[0]) && isset(wpjobportal::$_data[0]->id)) ? wpjobportal::$_data[0]->id : '';
                            $hide_apply_btn = 0;
                            $hide_login_and_apply_btn = 1;
                            $hide_select_role_btn = 1;
                            $show_buy_package_btn = 0;
                            $show_proceed_to_payment_button = 0;
                            $payment_methods_array = array();
                            $force_hide_btn = 0;
                            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
                            if($show_job_apply_redirect_link_only ==0){
                                if($show_quick_apply_form == 1){ // quick apply case
                                    $formfields = WPJOBPORTALincluder::getTemplate('quickapply/form-fields',array());
                                    foreach ($formfields as $formfield) {
                                        WPJOBPORTALincluder::getTemplate('templates/form-field', $formfield);
                                    }
                                    if (WPJOBPORTALincluder::getObjectClass('user')->isguest() && $captcha_quick_apply == 1) {
                                        $config_array = wpjobportal::$_config->getConfigByFor('captcha');
                                        if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                                            if($config_array['recaptcha_version'] == 1){
                                                echo '<div class="g-recaptcha" data-sitekey="'.$config_array["recaptcha_publickey"].'"></div>';
                                            }else{
                                                $google_recaptcha_3 = true;
                                            }

                                        } else { // own captcha
                                            $captcha = new WPJOBPORTALcaptcha;
                                            echo '<div class="recaptcha-wrp">'.$captcha->getCaptchaForForm().'</div>';
                                        }
                                    }
                                    echo wp_kses(WPJOBPORTALformfield::hidden('quickapply', 1),WPJOBPORTAL_ALLOWED_TAGS);
                                }else{ // legacy apply case
                                    if (!WPJOBPORTALincluder::getObjectClass('user')->isguest()) { // curent user not guest
                                        $isjobseeker = WPJOBPORTALincluder::getObjectClass('user')->isjobseeker();
                                        $isemployer = WPJOBPORTALincluder::getObjectClass('user')->isemployer();
                                        if (is_numeric($uid) && $uid != 0 && $isjobseeker == true) { // not guest and is jobseeker
                                            // resume section

                                            //get resumes
                                            $resume_list = WPJOBPORTALincluder::getJSModel('resume')->getResumesForJobapply();
                                            if(!empty($resume_list)){ // if user has resumes
                                                echo '
                                                <div class="wjportal-form-row">
                                                    <div class="wjportal-form-title">
                                                        '. __('Resume', 'wp-job-portal').' <font color="#000">*</font>
                                                    </div>
                                                    <div class="wjportal-form-value"> ';
                                                        echo wp_kses(WPJOBPORTALformfield::select('cvid', $resume_list, '', '', array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => 'required')), WPJOBPORTAL_ALLOWED_TAGS);
                                                echo '
                                                    </div>
                                                </div>';
                                            }else{ // no resume message and link to add resume
                                                echo '<div class="job-detail-jobapply-message-wrap">';
                                                    echo '<span class="job-detail-jobapply-message-msg"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/not-loggedin.png" />' . esc_html(__('You do not have any resume!', 'wp-job-portal')) . '</span>';
                                                    echo '<a class="job-detail-jobapply-message-link" href="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'addresume')).'" class="resumeaddlink" target="_blank">' . esc_html(__('Add Resume', 'wp-job-portal')) . '</a>';
                                                echo '</div>';
                                                $hide_apply_btn = 1;
                                            }

                                            if(in_array('coverletter', wpjobportal::$_active_addons)){
                                                // Cover letter section
                                                // get user cover letters
                                                $cover_letter_list = WPJOBPORTALincluder::getJSModel('coverletter')->getCoverLetterForCombocoverletter($uid);
                                                if(!empty($cover_letter_list)){ // if user has coverletters
                                                    echo '
                                                    <div class="wjportal-form-row">
                                                        <div class="wjportal-form-title">
                                                            '. __('Cover Letter', 'wp-job-portal').' <font color="#000">*</font>
                                                        </div>
                                                        <div class="wjportal-form-value"> ';
                                                            echo wp_kses(WPJOBPORTALformfield::select('coverletterid', $cover_letter_list, '', '', array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => 'required')), WPJOBPORTAL_ALLOWED_TAGS);
                                                    echo '
                                                        </div>
                                                    </div>';
                                                }else{ // no cover letter message and add cover letter link
                                                    echo '<div class="job-detail-jobapply-message-wrap">';
                                                        echo '<span class="job-detail-jobapply-message-msg"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/not-loggedin.png" />' . esc_html(__('No Cover Letter!', 'wp-job-portal')) . '</span>';
                                                        echo '<a class="job-detail-jobapply-message-link" href="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'coverletter', 'wpjobportallt'=>'addcoverletter')).'" class="coverlettteraddlink" target="_blank">' . esc_html(__('Add Cover Lettter', 'wp-job-portal')) . '</a>';
                                                    echo '</div>';
                                                }
                                            }
                                        }
                                    }
                                } // legacy apply resume and cover letter section ended
                            } // show_job_apply_redirect_link_only

                            // to handle per listing and membership mode
                            $subtype = wpjobportal::$_config->getConfigValue('submission_type');
                            if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                                // check if already applied on job
                                $can_apply_on_job = WPJOBPORTALincluder::getJSModel('jobapply')->checkAlreadyAppliedJob($jobid, $uid);
                                // check if job apply payment is pending
                                $payment_not_required = WPJOBPORTALincluder::getJSmodel('jobapply')->checkjobappllystats($jobid, $uid);
                                if($can_apply_on_job == false && $payment_not_required == true){ //show already applied message
                                    echo '<div class="frontend error"><p>'.esc_html(__('You have already applied on this job.', 'wp-job-portal')).'</p></div>';
                                    $hide_apply_btn = 1;
                                    $force_hide_btn = 1;
                                }else{ // current user is job seeker and has no job apply check and impliment package system
                                    if(in_array('credits', wpjobportal::$_active_addons)){ // check for credit system
                                        if( $subtype == 3 ){ // membership mode is on
                                            $userpackages = array(); // array to handle user packages in select package drop down
                                            $userpackage = apply_filters('wpjobportal_addons_credit_get_Packages_user',false,$uid,'jobapply');
                                            if(is_array($userpackage) && !empty($userpackage)){ // user bought packages array
                                                foreach($userpackage as $package){
                                                    if($package->jobapply == -1 || $package->remjobapply > 0){ //-1 = unlimited // checking if current package has job applies remaining
                                                        $package_for_combo = new stdClass();
                                                        $package_for_combo->id = $package->id;
                                                        $package_for_combo->text = $package->title;
                                                        $package_for_combo->text .= $package->jobapply == -1 ? ' ('.esc_html(__("Unlimited job applies",'wp-job-portal')).')' : ' ('.esc_attr($package->remjobapply).' '.esc_html(__("Job applies remaining",'wp-job-portal')).')' ;
                                                        $userpackages[] = $package_for_combo;
                                                    }
                                                }
                                            }else{ // user does not have package show message and btn to buy package
                                                echo '<div class="frontend error"><p>'.esc_html(__("Buy package to apply on job.",'wp-job-portal')).'</p></div>';
                                                $hide_apply_btn = 1;
                                                $show_buy_package_btn = 1;
                                            }

                                            if(!empty($userpackages)){ // if user has package then show those packages in drop down for selection
                                                echo '
                                                <div class="wjportal-form-row wjportal-form-pckge-row">
                                                    <div class="wjportal-form-title">
                                                        '. __('Apply With Package', 'wp-job-portal').' <font color="#000">*</font>
                                                    </div>
                                                    <div class="wjportal-form-value"> ';
                                                        echo wp_kses(WPJOBPORTALformfield::select('upkid', $userpackages, '', '', array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => 'required')), WPJOBPORTAL_ALLOWED_TAGS);
                                                echo '
                                                    </div>
                                                </div>';
                                            }
                                            // memebership mode code ended

                                        }elseif( $subtype == 2 ){ // per listing mode is on
                                            //per Listing For job apply
                                            $price = wpjobportal::$_config->getConfigValue('job_jobapply_price_perlisting');
                                            $currencyid = wpjobportal::$_config->getConfigValue('job_currency_jobapply_perlisting');
                                            $decimals = WPJOBPORTALincluder::getJSModel('currency')->getDecimalPlaces($currencyid);
                                            $formattedPrice = wpjobportalphplib::wpJP_number_format($price,$decimals);
                                            $priceCompanytlist = WPJOBPORTALincluder::getJSModel('common')->getFancyPrice($price,$currencyid,array('decimal_places'=>$decimals));
                                            if(is_numeric($price) && $price > 0){
                                                echo '<div class="wjportal-job-apply-price-msg" >';
                                                echo esc_html(__('Payment of', 'wp-job-portal')). ' <strong>'.esc_html($priceCompanytlist).'</strong> '.esc_html(__('is required to complete the job apply process', 'wp-job-portal'));
                                                echo '</div>';
                                                if($payment_not_required == true){ // job apply is not pending becasue of payment

                                                    // check enabled payment methods create an array for radio button selection in case of multiple
                                                    $paymentconfig = wpjobportal::$_wpjppaymentconfig->getPaymentConfigFor('paypal,stripe,woocommerce',true);
                                                    $default_selected_payment_method = '';
                                                    if($paymentconfig['isenabled_paypal'] == 1){ // paypal as a payment method is enabled
                                                        $payment_methods_array[1] = '<img src="'. esc_url(WPJOBPORTAL_IMAGE).'/paypal.jpg" alt="'. __("paypal","wp-job-portal").'" title="'. __("paypal","wp-job-portal").'" /> '. esc_html(__('PayPal', 'wp-job-portal'));
                                                        $default_selected_payment_method = 1;
                                                    }
                                                    if($paymentconfig['isenabled_woocommerce'] == 1) { // woo commerce as a payment method is enabled
                                                        // uncomment this line
                                                        // if(class_exists( 'WooCommerce' )){
                                                            $payment_methods_array[2] = '<img src="'. esc_url(WPJOBPORTAL_IMAGE).'/woo.jpg" alt="'. __("woocommerce","wp-job-portal").'" title="'. __("woocommerce","wp-job-portal").'" /> '. esc_html(__('Woocommerce', 'wp-job-portal'));
                                                            if($default_selected_payment_method == '')
                                                                $default_selected_payment_method = 2;
                                                        // }
                                                    }
                                                    if($paymentconfig['isenabled_stripe'] == 1) { // stripe as a payment method is enabled
                                                        $payment_methods_array[3] = '<img src="'. esc_url(WPJOBPORTAL_IMAGE).'/stripe.jpg" alt="'. __("stripe","wp-job-portal").'" title="'. __("stripe","wp-job-portal").'" /> '. esc_html(__('Stripe', 'wp-job-portal'));
                                                        if($default_selected_payment_method == '')
                                                            $default_selected_payment_method = 3;
                                                    }
                                                }else{ // payment is requied for job apply
                                                    $show_proceed_to_payment_button = 1; // show proceed to payment button
                                                    $hide_apply_btn = 1; // hide apply button
                                                }
                                            }
                                        }
                                    }
                                }
                                // job seeker case ended
                            }elseif (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) { // employer case
                                echo '<div class="frontend error"><p>'.esc_html(__('You are logged in as employer.', 'wp-job-portal')).'</p></div>';
                                $hide_apply_btn = 1;
                            }elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) { // guest case
                                if($show_quick_apply_form != 1){ // dont show buttons for quick apply form
                                    echo '<div class="frontend error"><p>'.esc_html(__('You are not a logged in member.', 'wp-job-portal')).'</p></div>';
                                    $hide_apply_btn = 1;
                                    $hide_login_and_apply_btn = 0; // show login and apply button
                                }
                            }else{ // wp user but not job portal user
                                echo '<div class="frontend error"><p>'.esc_html(__('You do not have any role.', 'wp-job-portal')).'</p></div>';
                                $hide_apply_btn = 1;
                                $hide_select_role_btn = 0; // show select role button
                            }

                            $btn_visible = 0;
                            if($hide_apply_btn == 0){
                                $btn_label  = __('Apply Now', 'wp-job-portal');
                                // if payment method array is not empty show select package drop down and change button text
                                if(!empty($payment_methods_array) && $show_job_apply_redirect_link_only == 0){
                                    $btn_label  = __('Proceed to payment', 'wp-job-portal');
                                    echo '
                                    <div class="wjportal-form-row">
                                        <div class="wjportal-form-title">
                                            '. __('Payment Method', 'wp-job-portal').' <font color="#000">*</font>
                                        </div>
                                        <div class="wjportal-form-value wjportal-job-apply-payment-method"> ';
                                            echo wp_kses(WPJOBPORTALformfield::radiobutton('selected_payment_method', $payment_methods_array, $default_selected_payment_method, array('class' => 'radiobutton')),WPJOBPORTAL_ALLOWED_TAGS);
                                    echo '
                                        </div>
                                    </div>';

                                }
                                if($show_job_apply_redirect_link_only == 0){
                                    // button will remain submit for all three modes.(free, per listing, memebership)
                                    echo '<div class="wjportal-form-btn-wrp wjportal-apply-package-apply-now-button">
                                        '. wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html($btn_label), array('class' => 'button wjportal-form-btn wjportal-save-btn')),WPJOBPORTAL_ALLOWED_TAGS).'
                                    </div>';
                                    $btn_visible = 1;
                                }elseif($show_job_apply_redirect_link_only == 1){
                                    echo '<div class="wjportal-form-btn-wrp wjportal-apply-package-apply-now-button">
                                     <a class="wjportal-login-to-apply-btn" href="'.esc_url($job->joblink).'"  target="_blank">' . esc_html(__('Apply Now', 'wp-job-portal')).'</a>
                                    </div>';
                                    $btn_visible = 1;
                                }
                            }
                        if($hide_login_and_apply_btn == 0  && $show_job_apply_redirect_link_only == 0){ // show login & apply button to visitor
                                $redirect_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$jobid));
                                $redirect_url = wpjobportalphplib::wpJP_safe_encoding($redirect_url);
                                $login_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'wpjobportal', 'wpjobportallt'=>'login', 'wpjobportalredirecturl'=>$redirect_url));

                                echo '<div class="wjportal-form-btn-wrp wjportal-login-to-apply-btn-wrap">
                                    <a href="'.esc_url($login_link).'" target="_blank" class="wjportal-login-to-apply-btn" >'.__('Login', 'wp-job-portal').'</a>
                                </div>';
                                // show apply button to visitor
                                $visitor_can_apply_to_job = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_apply_to_job');
                                if($visitor_can_apply_to_job == 1){
                                    echo '<div class="wjportal-job-apply-or-visitor">
                                        <span>'. esc_html(__("Or", "wp-job-portal")) .'</span>
                                    </div>';
                                    $visitorapplylink = wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'action'=>'wpjobportaltask', 'task'=>'jobapplyasvisitor', 'wpjobportalid-jobid'=>$jobid)),'wpjobportal_job_apply_nonce') ;
                                    echo '<div class="wjportal-form-btn-wrp wjportal-apply-as-visitor-btn-wrap">
                                        <a href="'.esc_url($visitorapplylink).'" class="wjportal-apply-as-visitor-btn" >'.__('Apply as visitor', 'wp-job-portal').'</a>
                                    </div>';
                                }
                                $btn_visible = 1;
                            }

                            if($hide_select_role_btn == 0){ // show select role btn to wordpress logged in user
                                $select_role_link =  esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common','wpjobportallt'=>'newinwpjobportal', 'wpjobportalid-jobid'=>$jobid))) ;
                                echo '<div class="wjportal-form-btn-wrp wjportal-login-to-apply-btn-wrap">
                                    <a href="'.esc_url($select_role_link).'" target="_blank" class="wjportal-login-to-apply-btn" >'.esc_html(__('Select Role', 'wp-job-portal')).'</a>
                                </div>';
                                $btn_visible = 1;
                            }

                            if($show_buy_package_btn == 1){ // show buy package button in case of memeber ship mode and no package
                                $buy_packages_link =  esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'package','wpjobportallt'=>'packages'))) ;
                                echo '<div class="wjportal-form-btn-wrp wjportal-login-to-apply-btn-wrap">
                                    <a href="'.esc_url($buy_packages_link).'" target="_blank" class="wjportal-login-to-apply-btn" >'.esc_html(__('Buy Package', 'wp-job-portal')).'</a>
                                </div>';
                                $btn_visible = 1;
                            }
                            if($show_proceed_to_payment_button == 1){ // show proceed to payment button to handle per listing mode (mainly stripe payment case)
                                $buy_packages_link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory','wpjobportallt'=>'payjobapply','wpjobportalid'=>$jobid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                echo '<div class="wjportal-form-btn-wrp wjportal-login-to-apply-btn-wrap">
                                    <a href="'.esc_url($buy_packages_link).'" target="_blank" class="wjportal-login-to-apply-btn" >'.esc_html(__('Proceed to payment', 'wp-job-portal')).'</a>
                                </div>';
                                $btn_visible = 1;
                            }

                            if($btn_visible == 0  && $show_job_apply_redirect_link_only == 1){
                                echo '<div class="wjportal-form-btn-wrp wjportal-apply-package-apply-now-button">
                                 <a class="wjportal-login-to-apply-btn" href="'.esc_url($job->joblink).'"  target="_blank">' . esc_html(__('Apply Now', 'wp-job-portal')).'</a>
                                </div>';
                                $btn_visible = 1;
                            }


                            if($btn_visible == 0 && $force_hide_btn == 0){ // show dummy btn if no button is shown
                                echo '<div class="wjportal-form-btn-wrp wjportal-login-to-apply-btn-wrap">
                                    <a href="#"  class="wjportal-login-to-apply-btn" >'.esc_html(__('Apply Now', 'wp-job-portal')).'</a>
                                </div>';
                                
                            }

                            echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportalpageid', wpjobportal::wpjobportal_getPageid()),WPJOBPORTAL_ALLOWED_TAGS);
                            echo wp_kses(WPJOBPORTALformfield::hidden('jobid', (isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0])) ? wpjobportal::$_data[0]->id: '' ),WPJOBPORTAL_ALLOWED_TAGS);
                            echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS);
                            echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_job_apply_nonce'))),WPJOBPORTAL_ALLOWED_TAGS);
                        echo '</form>';
                    echo '</div>';
               ?>
            </div>
            <?php
        }
        // to handle captcha on quick apply form
        if($captcha_quick_apply == 1){
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $config_array = wpjobportal::$_config->getConfigByFor('captcha');
            if($config_array['captcha_selection'] == 1 && $config_array['recaptcha_privatekey'] ){
                wp_enqueue_script('wpjobportal-repaptcha-scripti', $protocol . 'www.google.com/recaptcha/api.js');
            }
        }
    ?>
