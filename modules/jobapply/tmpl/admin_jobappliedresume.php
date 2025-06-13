<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_style('wpjobportal-wpjobportalrating', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/wpjobportalrating.css');
if (!WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'jobapply'))){
    return;
}
$nationality = WPJOBPORTALincluder::getJSModel('country')->getCountriesForCombo();
$job_type = WPJOBPORTALincluder::getJSModel('jobtype')->getJobTypeForCombo();
$heighesteducation = WPJOBPORTALincluder::getJSModel('highesteducation')->getHighestEducationForCombo();
$job_categories = WPJOBPORTALincluder::getJSModel('category')->getCategoriesForCombo();
$gender = array(
    (object) array('id' => '', 'text' => esc_html(__('Search All', 'wp-job-portal'))),
    (object) array('id' => 1, 'text' => esc_html(__('Male', 'wp-job-portal'))),
    (object) array('id' => 2, 'text' => esc_html(__('Female', 'wp-job-portal'))));
?>
<?php
    // to handle the email candidate case
    $jobid = WPJOBPORTALrequest::getVar('jobid','','');
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        function sendMessageToCandidate(resumeid, jobseekerid, jobid, name, employerid) {
            jQuery('span#popup_title.message').html(name);
            jQuery('input#resumeid').val(resumeid);
            jQuery('input#jobseekerid').val(jobseekerid);
            jQuery('input#employerid').val(employerid);
            jQuery('input#jobid').val(jobid);
            jQuery('div#full_background').show();
            jQuery('div#popup-main-outer.sendmessage').show();
            jQuery('div#popup-main.sendmessage').slideDown('slow');
            jQuery('div#full_background').click(function () {
                closePopup();
            });
            jQuery('img#popup_cross').click(function () {
                closePopup();
            });
        }
        function sendMessage() {
            var jobseekerid = jQuery('input#jobseekerid').val();
            var employerid = jQuery('input#employerid').val();
            var resumeid = jQuery('input#resumeid').val();
            var jobid = jQuery('input#jobid').val();
            var subject = jQuery('input#subject').val();
            if (subject == '') {
                alert(\"". esc_html(__("Please fill the subject", 'wp-job-portal'))."\");
                return false;
            }
            is_tinyMCE_active = true;
            if (typeof (tinyMCE) != 'undefined') {
                if (tinyMCE.activeEditor == null || tinyMCE.activeEditor.isHidden() != false) {
                    is_tinyMCE_active = false;
                }
            }
            if (is_tinyMCE_active == true) {
                var message = tinyMCE.get('jobseekermessage').getContent();
            } else {
                var message = jQuery('textarea#jobseekermessage').val();
            }
            if (message == '') {
                alert(\"". esc_html(__("Please fill the message", 'wp-job-portal'))."\");
                return false;
            }
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'message', task: 'sendmessageresume', subject: subject, message: message, resumeid: resumeid, uid: jobseekerid, jobid: jobid, isadmin:1, employerid:employerid, '_wpnonce':'". esc_attr(wp_create_nonce("send-message-resume"))."'}, function (data) {
                if (data) {
                    alert(\"". esc_html(__("Message sent", 'wp-job-portal'))."\");
                    jQuery('div#popup-main-outer').slideUp('slow',function(){
                        jQuery('div#full_background').hide();
                    })
                }else{
                    alert(\"". esc_html(__("Message not sent", 'wp-job-portal'))."\");
                }
            });
        }
        function tabaction(jobid, action) {
            jQuery('#jobid').val(jobid);
            jQuery('#tab_action').val(action);
            jQuery('#task').val('aappliedresumetabactions');
            jQuery('#wpjobportal-form').submit();
        }
        function tabsearch(jobid, searchtype, selected_tab) {
            var element = jQuery('#wpjobportal-applied-tabs-container .wpjobportal_appliedapplication_tab_selected');
            element.removeClass('wpjobportal_appliedapplication_tab_selected');
            jQuery('#'+selected_tab).parents('span').addClass('wpjobportal_appliedapplication_tab_selected');
            var searchhtml = '#wpjobportal_appliedresume_tab_search';
            jQuery('form.wpjobportal-adv-srch-filter-form').show();
            jQuery(searchhtml).slideToggle('slow');
        }
        function closetabsearch(src) {
            jQuery(src).slideUp('slow');
        }
        function actioncall(jobapplyid, jobid, resumeid, action) {
            if (action == 3) { // folder
                getfolders('resumeaction_' + jobapplyid, jobid, resumeid, jobapplyid);
            } else if (action == 4) { // comments
                getresumecomments('resumeaction_' + jobapplyid, jobapplyid);
            } else if (action == 5) { // email candidate
                mailtocandidate('resumeaction_' + jobapplyid, resumeid, jobapplyid);
            } else {
                var src = '#resumeactionmessage_' + jobapplyid;
                var htmlsrc = '#wpjobportal_appliedresume_data_action_message_' + jobapplyid;
                jQuery(src).html('Loading ...');
            }
        }
        function closeresumeactiondiv(src) {
            jQuery(src).slideUp('slow');
        }
        function setresumeid(resumeid, action) {
            jQuery('#resumeid').val(resumeid);
            jQuery('#action').val(jQuery('#' + action).val());
            jQuery('wpjobportal-form').submit();
        }
        function saveresumecomments(jobapplyid, resumeid) {
            var src = '#resumeactionmessage_' + jobapplyid;
            var htmlsrc = '#wpjobportal_appliedresume_data_action_message_' + jobapplyid;
            var clearhtml = '#resumeaction_' + jobapplyid;
            var comments = jQuery('#comments').val();
            jQuery(src).html('Loading ...');
            jQuery.post('index.php?option=com_wpjobportal&task=jobapply.saveresumecomments', {jobapplyid: jobapplyid, resumeid: resumeid, comments: comments}, function (data) {
                if (data) {
                    jQuery(src).html(data);
                    jQuery(clearhtml).html('');
                    jQuery(htmlsrc).slideDown('slow');
                    setTimeout(function () {
                        closeresumeactiondiv(htmlsrc)
                    }, 3000);
                }
            });
        }
        function mailtocandidate(src, resumeid, jobapplyid) {
            jQuery('#' + src).html('Loading ...');
            jQuery.post('index.php?option=com_wpjobportal&task=jobapply.mailtocandidate', {resumeid: resumeid, jobapplyid: jobapplyid}, function (data) {
                if (data) {
                    jQuery('#' + src).html(data).show();
                }
            });
        }
        function sendmailtocandidate(jobapplyid) {
            var src = 'resumeactionmessage_' + jobapplyid;
            var arr = new Array();
            var emmailaddress = document.getElementById('emmailaddress').value;
            if (emmailaddress) {
                var result = echeck(emmailaddress);
                if (result == false) {
                    alert(\"". esc_html(__("JS invalid email", 'wp-job-portal'))."\");
                    document.getElementById('emmailaddress').focus();
                    return false;
                }
                arr[0] = emmailaddress;
                arr[1] = document.getElementById('jsmailaddress').value;
                arr[2] = document.getElementById('jssubject').value;
                arr[3] = document.getElementById('candidatemessage').value;
                sendtocandidate(arr, jobapplyid);
            } else {
                alert(\"". esc_html(__("JS your email is required", 'wp-job-portal'))."\");
                document.getElementById('emmailaddress').focus();
                return false;
            }
        }
        function sendtocandidate(arr, jobapplyid) {
            var src = '#resumeactionmessage_' + jobapplyid;
            var htmlsrc = '#wpjobportal_appliedresume_data_action_message_' + jobapplyid;
            var clearhtml = '#resumeaction_' + jobapplyid;
            jQuery(src).html('Loading ...');
            jQuery.post('index.php?option=com_wpjobportal&task=jobapply.sendtocandidate', {val: JSON.stringify(arr)}, function (data) {
                if (data) {
                    jQuery(src).html(data);
                    jQuery(clearhtml).html('');
                    jQuery(htmlsrc).slideDown('slow');
                    setTimeout(function () {
                        closeresumeactiondiv(htmlsrc)
                    }, 3000);
                }
            });
        }
        function clsjobdetail(src) {
            jQuery('#' + src).html('');
        }
        function clsaddtofolder(src) {
            jQuery('#' + src).html('');
        }
        function echeck(str) {
            var at = '@';
            var dot = '.';
            var lat = str.indexOf(at);
            var lstr = str.length;
            var ldot = str.indexOf(dot);

            if (str.indexOf(at) == -1)
                return false;
            if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr)
                return false;
            if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr)
                return false;
            if (str.indexOf(at, (lat + 1)) != -1)
                return false;
            if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot)
                return false;
            if (str.indexOf(dot, (lat + 2)) == -1)
                return false;
            if (str.indexOf(' ') != -1)
                return false;
            return true;
        }";

        wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        $inline_js_script = "
        function changeStatusOfResume(id, resumeid, aid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: 'updateJobApplyResumeStatus', jobapplyid: id, actionid: aid, '_wpnonce':'". esc_attr(wp_create_nonce("update-job-apply-resume-status"))."'}, function (data) {
                if (data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.saved == 'save') {
                        jQuery('div#' + resumeid).html('<div id=\"notification-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/approve.png\"/>' + obj.message + '</label></div>');
                    } else {
                        jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>' + obj.message + '</label></div>');
                    }
                }
                setTimeout(function () {
                    window.location.reload();
                }, 700);
            });
        }
        function addComments(jid, resumeid) {
            //Addons
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: 'getResumeCommentSection', jobapplyid: jid, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-comment-section"))."'}, function (data) {
                if (data) {
                    jQuery('div.' + resumeid).html(data).show();
                }
            });
        }
        function closeSection() {
            jQuery('div#comments').html('').hide();
        }
        function getFolders(uid, resumeid, jobid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            /*Advance*/
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'folder', task: 'getFolderSection', userid: uid, rid: resumeid, jid: jobid, '_wpnonce':'". esc_attr(wp_create_nonce("get-folder-section"))."'}, function (data) {
                if (data) {
                    jQuery('div.' + resumeid).html('<img id=\"close-section\" onclick=\"closeSection()\" src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/no.png\"/>' + data).show();
                }
            });
        }
        function saveToFolder(uid, resumeid, jobid) {
            var val = jQuery('#combobox').find('option:selected').val();
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'folderresume', task: 'saveToFolderResume', userid: uid, rid: resumeid, jid: jobid, folid: val, '_wpnonce':'". esc_attr(wp_create_nonce("save-to-folder-resume"))."'}, function (data) {
                if (data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.saved == 'save') {
                        jQuery('div#' + resumeid).html('<div id=\"notification-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/approve.png\"/>' + obj.message + '</label></div>');
                    } else {
                        jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>' + obj.message + '</label></div>');
                    }
                }
            });
        }
        function saveComments(jobid, resumeid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            var comment = jQuery('textarea#comments').val();
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: 'storeResumeComments', jobapplyid: jobid, commenttext: comment, '_wpnonce':'". esc_attr(wp_create_nonce("store-resume-comments"))."'}, function (data) {
                if (data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.saved == 'save') {
                        jQuery('div#' + resumeid).html('<div id=\"notification-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/approve.png\"/>". esc_html(__('Note have been saved successfully','wp-job-portal'))."</label></div>');
                    } else {
                        jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>". esc_html(__('Failed while performing action','wp-job-portal'))."</label></div>');
                    }
                }
            });
        }
        function sendEmail(resumeid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            var jid = jQuery('input#jobseeker').val();
            var subject = jQuery('input#e-subject').val();
            var sid = jQuery('input#sender').val();
            var test = true;
            var jobid = jQuery('div.'+resumeid).find('input#jobid').val();
            if(subject =='' && sid ==''){ // not to proceed for empty fields
                jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>". esc_html(__('Please Fill out fields','wp-job-portal'))."</label></div>');
                event.preventDefault();
                return false;
            }
            if(sid.length != 0){
                var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                test = pattern.test(sid);
            }
            if (test == false) {
                jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>". esc_html(__('sender email is not of correct format','wp-job-portal'))."</label></div>');
                event.preventDefault();
                return false;
            } else {
                var body = jQuery('textarea#email-body').val();
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: 'sendEmailToJobSeeker', jobseekerid: jid, emailsubject: subject, senderid: sid, mailbody: body, jobid: jobid, resumeid: resumeid,  '_wpnonce':'". esc_attr(wp_create_nonce("send-email-to-jobseeker"))."'}, function (data) {
                    if (data) {
                        jQuery('div#' + resumeid).html('<div id=\"notification-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/approve.png\"/>". esc_html(__('Email has been send','wp-job-portal'))."</label></div>');
                    }else{
                        jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL).">includes/images/unpublish.png\"/>". esc_html(__('Error sending email','wp-job-portal'))."</label></div>');
                    }
                });
            }
        }
        function resetFrom() {
            if(jQuery('#application_title').length > 0 && jQuery('#application_title').val() != ''){
                document.getElementById('application_title').value = '';
            } if(jQuery('#applicantname').length > 0 && jQuery('#applicantname').val() != ''){
                document.getElementById('applicantname').value = '';
            } if(jQuery('#experince').length > 0 && jQuery('#experince').val() != ''){
                document.getElementById('experince').value = '';
            } if(jQuery('#nationality').length > 0 && jQuery('#nationality').val() != ''){
                document.getElementById('nationality').value = '';
            } if(jQuery('#jobcategory').length > 0 && jQuery('#jobcategory').val() != ''){
                document.getElementById('jobcategory').value = '';
            } if(jQuery('#gender').length > 0 && jQuery('#gender').val() != ''){
                document.getElementById('gender').value = '';
            } if(jQuery('#jobtype').length > 0 && jQuery('#jobtype').val() != ''){
                document.getElementById('jobtype').value = '';
            } if(jQuery('#currency').length > 0 && jQuery('#currency').val() != ''){
                document.getElementById('currency').value = '';
            } if(jQuery('#jobsalaryrange').length > 0 && jQuery('#jobsalaryrange').val() != ''){
                document.getElementById('jobsalaryrange').value = '';
            } if(jQuery('#heighestfinisheducation').length > 0 && jQuery('#heighestfinisheducation').val() != ''){
                document.getElementById('heighestfinisheducation').value = '';
            }
            document.getElementById('wpjobportalform').submit();
        }
        function setRating(src,newrating){
            src = 'rating_'+src;
            setrating_jsadmin(src,newrating);
        }
        function setrating_jsadmin(src, newrating) { 
            var jobapplyid = jQuery('#jobapplyid').val();
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: 'setResumeRatting', jobapplyid:jobapplyid, rate: newrating, '_wpnonce':'". esc_attr(wp_create_nonce("set-resume-ratting"))."'}, function (data) {
                    if (data) {
                        jQuery('#' + src).width(parseInt(newrating * 20) + '%');
                    }
                });        
        }
        function showPopupAndSetValues(name, title, id) {
            var desc = jQuery('input#cover-letter-text_' + id).val();
            jQuery('div#full_background').css('display', 'block');
            jQuery('div#popup-main.coverletter').css('display', 'block');
            jQuery('div#popup-main-outer.coverletter').css('display', 'block');
            jQuery('div#full_background').click(function () {
                closePopup();
            });
            jQuery('img#popup_cross').click(function () {
                closePopup();
            });
            jQuery('div#popup_main.coverletter').slideDown('slow');
            jQuery('span#popup_title.coverletter').html(name);
            jQuery('span#popup_coverletter_title.coverletter').html(title);
            jQuery('span#popup_coverletter_desc.coverletter').html(desc);
        }
        function closePopup() {
            jQuery('div#popup-main-outer').slideUp('slow');
            setTimeout(function () {
                jQuery('div#full_background').hide();
                jQuery('span#popup_title.coverletter').html('');
                jQuery('div#popup-main').css('display', 'none');
                jQuery('span#popup_coverletter_title.coverletter').html('');
                jQuery('span#popup_coverletter_desc.coverletter').html('');
            }, 700);
        }
        function getResumeDetails(resumeid, salary, exp, inisi, study, available) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: 'getResumeDetail', sal: salary, expe: exp, institue: inisi, stud: study, ava: available, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-detail"))."'}, function (data) {
                if (data) {
                    jQuery('div.' + resumeid).html(data).show();
                }
            });
        }
        function getEmailFields(emailid, resumeid) {
            var jobid = ".$jobid.";// needed to get employer email address
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: 'getEmailFields', em: emailid,resumeid: resumeid, jobid:jobid, '_wpnonce':'". esc_attr(wp_create_nonce("get-email-fields"))."'}, function (data) {
                if (data) {
                    jQuery('div.' + resumeid).html(data).show();
                }
            });
        }
        function showCoverLetterData(cover_letter_id) {
            var cover_letter_title = jQuery('div#cover_letter_data_title_'+cover_letter_id).html();
            var cover_letter_desc = jQuery('div#cover_letter_data_desc_'+cover_letter_id).html();

            jQuery('span#popup_title.coverletter').html(cover_letter_title);
            jQuery('#popup_coverletter_desc.coverletter').html(cover_letter_desc);
            jQuery('div#full_background.coverletter').show();
            jQuery('div#popup-main-outer.coverletter').show();
            jQuery('div#popup-main.coverletter').slideDown('slow');
            jQuery('div#full_background').click(function () {
                closePopup();
            });
            jQuery('img#popup_cross').click(function () {
                closePopup();
            });
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
	<div id="full_background" class="coverletter"  style="display:none;"></div>
    <div id="popup-main-outer" class="coverletter" style="display:none;">
        <div id="popup-main" class="coverletter" style="display:none;">
            <span class="popup-top">
                <span id="popup_title" class="coverletter"></span>
                <img id="popup_cross" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/popup-close.png">
            </span>
            <div class="popup-content-area" id="popup-bottom-part">
                <span id="popup_coverletter_title" class="coverletter popup-content-title"></span>
                <span id="popup_coverletter_desc" class="coverletter popup-content-desc"> </span>
            </div>
        </div>
    </div>
    <?php /*do_action('wpjobportal_addons_popup_main_outer_admin');*/  ?>
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <!-- top bar -->
        <div id="wpjobportal-wrapper-top">
            <div id="wpjobportal-wrapper-top-left">
                <div id="wpjobportal-breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('dashboard','wp-job-portal')); ?>">
                                <?php echo esc_html(__('Dashboard','wp-job-portal')); ?>
                            </a>
                        </li>
                        <li><?php echo esc_html(__('Job Applied Resume','wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>    
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>    
        </div>
        <!-- top head -->
        <?php
            if(!WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'jobapply','layouts' => 'jobapply'))){
                return;
            }
        ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <?php
                do_action('wpjobportal_addons_admin_search_jobapplied');
            ?>
            <?php 
            // to handle the case of empty record showing errors. (deleted enitity case)
            if(isset(wpjobportal::$_data[4]['jobinfo'][0]) && !empty(wpjobportal::$_data[4]['jobinfo'][0])){

                WPJOBPORTALincluder::getTemplate('job/views/admin/joblist',array(
                    'job' => wpjobportal::$_data[4]['jobinfo'][0],
                    'layout' => '',
                    'logo' => 'logo'
                ));
            }
            ?>
           <div id="wpjobportal-applied-tabs-container">
                <?php
                    $ta = WPJOBPORTALrequest::getVar('ta', null, 1);
                    do_action('wpjobportal_addons_resume_top_buttons_actions_admin',wpjobportal::$_data[0],$ta);
                    do_action('wpjobportal_addons_resume_top_buttons_actions_export_admin',wpjobportal::$_data['jobid']); 
                    //do_action('wpjobportal_addons_popup_main_outer_admin');
                ?>   
            </div> 
            <!-- advance search -->
            <?php do_action('wpjobportal_addons_popup_main_outer_admin'); ?>
            <?php

                if (!empty(wpjobportal::$_data[0]['data'])) {
                    foreach (wpjobportal::$_data[0]['data'] as $data) {
                        WPJOBPORTALincluder::getTemplate('jobapply/views/admin/detail',array('data' => $data ));
                      
                    } // loop End
                    if (wpjobportal::$_data[1]) {
                        if(!WPJOBPORTALincluder::getTemplate('templates/admin/pagination',array('module' => 'jobapply' ,'pagination' =>wpjobportal::$_data[1] ))){
                            return;
                        }
                    }
                    $jobapplyid = wpjobportal::$_data[0]['data'][0]->jobapplyid;
                    echo wp_kses(WPJOBPORTALformfield::hidden('id', ''),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('jobapplyid', $jobapplyid ),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('task', 'actionresume'),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('action', ''),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('action_status', ''),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('tab_action', ''),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('boxchecked', ''),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('jobid', wpjobportal::$_data[0]['ta']),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('ta', wpjobportal::$_data[0]['ta']),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS);
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
<?php
    $inline_js_script = "
        jQuery(document).ready(function () {
            jQuery('a#print-link').click(function (e) {
                e.preventDefault();
                var printurl = jQuery(this).attr('data-print-url');
                print = window.open(printurl, 'print_win', 'width=1024, height=800, scrollbars=yes');
            });
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
