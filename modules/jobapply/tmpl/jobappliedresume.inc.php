<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php

// to handle the email candidate case
$jobid = WPJOBPORTALrequest::getVar('jobid','','');
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        function tabsearch(jobid, searchtype, selected_tab) {
            var element = jQuery('#wpjobportal-applied-tabs-container .wpjobportal_appliedapplication_tab_selected');
            element.removeClass('wpjobportal_appliedapplication_tab_selected');
            jQuery('#'+selected_tab).parents('span').addClass('wpjobportal_appliedapplication_tab_selected');
            var searchhtml = '#wpjobportal_appliedresume_tab_search';
            jQuery(searchhtml).slideToggle('slow');
        }

        function closetabsearch(src) {
            jQuery(src).slideUp('slow');
        }

        function sendMessageToCandidate(resumeid, jobseekerid, jobid, name,themecall) {
            var themecall = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
            jQuery('input#resumeid').val(resumeid);
            jQuery('input#jobseekerid').val(jobseekerid);
            jQuery('input#jobid').val(jobid);
            if(null != themecall){
                jQuery('.'+common.theme_chk_prefix+'-popup-heading').html(name);
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').show();
                jQuery('div.'+common.theme_chk_prefix+'-popup-wrp').slideDown('slow');
                jQuery('div.'+common.theme_chk_prefix+'-sendmessage-modal-data-wrp').show();
                jQuery('div.'+common.theme_chk_prefix+'-viewcover-modal-data-wrp').hide();
                jQuery('div#'+common.theme_chk_prefix+'-popup-background, .'+common.theme_chk_prefix+'-popup-close-icon').click(function () {
                    closePopupJobManager();
                });
            }else{
                jQuery('span.wjportal-popup-title2').html(name);
                jQuery('div#wjportal-popup-background').show();
                jQuery('div#popup-main-outer.sendmessage').show();
                jQuery('div#popup-main.sendmessage').slideDown('slow');
                jQuery('div#wjportal-popup-background').click(function () {
                    closePopup();
                });
                jQuery('img#wjportal-popup-close-btn').click(function () {
                    closePopup();
                });
            }
        }

        function sendMessage(themecall = null) {
            //var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
            var jobseekerid = jQuery('input#jobseekerid').val();
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
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'message', task: 'sendmessageresume', subject: subject, message: message, resumeid: resumeid, uid: jobseekerid, jobid: jobid, '_wpnonce':'". esc_attr(wp_create_nonce("send-message-resume"))."'}, function (data) {
                if (data) {
                    alert(\"". esc_html(__("Message sent", 'wp-job-portal'))."\");
                    if(null != themecall){
                        closePopupJobManager();
                    }else{
                        closePopup();
                    }
                }else{
                    alert(\"". esc_html(__("Message not sent", 'wp-job-portal'))."\");
                }
            });
        }

        function changeStatusOfResume(id, resumeid, aid,themecall) {
            var themecall = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php'))."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: 'updateJobApplyResumeStatus', jobapplyid: id, actionid: aid, '_wpnonce':'". esc_attr(wp_create_nonce("update-job-apply-resume-status"))."'}, function (data) {
                if (data) {
                    var obj = jQuery.parseJSON(data);
                    if(null != themecall){
                        if (obj.saved == 'save') {
                            jQuery('div#' + resumeid).html('<div class=\"'+common.theme_chk_prefix+'-alert-msg success\">' + obj.message + '</div>');
                        } else {
                            jQuery('div#' + resumeid).html('<div class=\"'+common.theme_chk_prefix+'-alert-msg error\">' + obj.message + '</div>');
                        }
                    }else{
                        if (obj.saved == 'save') {
                            jQuery('div#' + resumeid).html('<div id=\"notification-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/approve.png\"/>' + obj.message + '</label></div>');
                        } else {
                            jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>' + obj.message + '</label></div>');
                        }
                    }
                }
                setTimeout(function () {
                    window.location.reload();
                }, 700);
            });
        }

        function addComments(jid, resumeid,themecall) {
            var themecall = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
            task='getResumeCommentSection';
            if(null != themecall){
                task='getResumeCommentSectionJobManager';
                jQuery('div#comments').css({
                   'display' : 'block',
                   'width' : '100%',
                   'float' : 'left'
                });
            }else{
                jQuery('div#comments').css('display' , 'inline-block');
            }
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: task, jobapplyid: jid, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-comment-section"))."'}, function (data) {
                if (data) {
                    jQuery('div.' + resumeid).html(data);
                }
            });
        }

        function closeSection() {
            jQuery('div#comments, div#coments').html(' ');
            jQuery('div#comments, div#coments').css('display', 'none');
        }

        function getFolders(uid, resumeid, jobid,themecall) {
            var themecall = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
            task='getFolderSection';
            if(null != themecall){
                task='getFolderSectionJobManager';
                jQuery('div#coments, div#comments').css({
                   'display' : 'block',
                   'width' : '100%',
                   'float' : 'left'
                });
            }else{
                jQuery('div#coments, div#comments').css('display' , 'inline-block');
            }
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'folder', task: task, userid: uid, rid: resumeid, jid: jobid, '_wpnonce':'". esc_attr(wp_create_nonce("get-folder-section"))."'}, function (data) {
                if (data) {
                    if(null != themecall){
                        jQuery('div.' + resumeid).html(data);
                    }else{
                        jQuery('div.' + resumeid).html('<img id=\"close-section\" onclick=\"closeSection()\" src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/no.png\"/>' + data);
                    }
                }
            });
        }

        function saveToFolder(uid, resumeid, jobid,themecall) {
            var themecall = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
            var val = jQuery('#combobox').find('option:selected').val();
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'folderresume', task: 'saveToFolderResume', userid: uid, rid: resumeid, jid: jobid, folid: val, '_wpnonce':'". esc_attr(wp_create_nonce("save-to-folder-resume"))."'}, function (data) {
                if (data) {
                    var obj = jQuery.parseJSON(data);
                    if(null != themecall){
                        if (obj.saved == 'save') {
                            jQuery('div#' + resumeid).html('<div class=\"'+common.theme_chk_prefix+'-alert-msg success\">' + obj.message + '</div>');
                        } else {
                            jQuery('div#' + resumeid).html('<div class=\"'+common.theme_chk_prefix+'-alert-msg error\">' + obj.message + '</div>');
                        }
                    }else{
                        if (obj.saved == 'save') {
                            jQuery('div#' + resumeid).html('<div id=\"notification-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/approve.png\"/>' + obj.message + '</label></div>');
                        } else {
                            jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>' + obj.message + '</label></div>');
                        }

                    }
                }
                setTimeout(function () {
                    window.location.reload();
                }, 700);
            });
        }

        function saveComments(jobid, resumeid,themecall) {
            var themecall = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            var comment = jQuery('textarea#comments').val();
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: 'storeResumeComments', jobapplyid: jobid, commenttext: comment, '_wpnonce':'". esc_attr(wp_create_nonce("store-resume-comments"))."'}, function (data) {
                if (data) {
                    var obj = jQuery.parseJSON(data);
                    if(null != themecall){
                        if (obj.saved == 'save') {
                            jQuery('div#' + resumeid).html('<div class=\"'+common.theme_chk_prefix+'-alert-msg success\">". esc_html(__('Note have been saved successfully','wp-job-portal'))."</div>');
                        } else {
                            jQuery('div#' + resumeid).html('<div class=\"'+common.theme_chk_prefix+'-alert-msg error\">". esc_html(__('Failed While Performing Action','wp-job-portal'))."</div>');
                        }
                    }else{
                        if (obj.saved == 'save') {
                            jQuery('div#' + resumeid).html('<div id=\"notification-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/approve.png\"/>". esc_html(__('Note have been saved successfully','wp-job-portal'))."</label></div>');
                        } else {
                            jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>". esc_html(__('Failed While Performing Action','wp-job-portal'))."</label></div>');
                        }
                    }
                }
                setTimeout(function () {
                    window.location.reload();
                }, 700);
            });
        }

        function sendEmail(resumeid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            var jid = jQuery('div.'+resumeid).find('input#jobseeker').val();
            var subject = jQuery('div.'+resumeid).find('input#e-subject').val();
            var sid = jQuery('div.'+resumeid).find('input#sender').val();
            var body = jQuery('div.'+resumeid).find('textarea#email-body').val();
            var jobid = jQuery('div.'+resumeid).find('input#jobid').val();
            if(subject =='' && sid ==''){ // not to proceed for empty fields
                jQuery('div#' + resumeid).html('<div id=\"notification-not-ok\"><label id=\"popup_message\"><img src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/unpublish.png\"/>". esc_html(__('Please Fill out fields','wp-job-portal'))."</label></div>');
                return;
            }
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: 'sendEmailToJobSeeker', jobseekerid: jid, emailsubject: subject, senderid: sid, mailbody: body, jobid: jobid, resumeid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("send-email-to-jobseeker"))."'}, function (data) {
                if (data) {
                    alert(data);
                }
            });
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
            }if(jQuery('#title').length > 0 && jQuery('#title').val() != ''){
                document.getElementById('title').value = '';
            }
            document.getElementById('job_form').submit();
            document.getElementById('wpjobportalform-search').submit();
        }

        function setRating(src,newrating){
            setRating_ja_front(src,newrating);
        }

        function setRating_ja_front(jobapplyid, newrating) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'resumeaction', task: 'setResumeRatting', jobapplyid:jobapplyid, rate: newrating, '_wpnonce':'". esc_attr(wp_create_nonce("set-resume-ratting"))."'}, function (data) {
                    if (data) {
                        jQuery('#rating_' + jobapplyid).width(parseInt(newrating * 20) + '%');
                    }
                });
        }

        function showPopupAndSetValues(name, title, id, themecall) {
            var themecall = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
            if(null != themecall){
                var desc = jQuery('input#cover-letter-text_' + id).val();
                jQuery('#'+common.theme_chk_prefix+'-modal-ar-title').html(name);
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').show();
                jQuery('div.'+common.theme_chk_prefix+'-sendmessage-modal-data-wrp').hide();
                jQuery('div.'+common.theme_chk_prefix+'-viewcover-modal-data-wrp').show();
                jQuery('div.'+common.theme_chk_prefix+'-viewcover-title').html(title);
                jQuery('div.'+common.theme_chk_prefix+'-viewcover-desc').html(desc);
                jQuery('div.js-field-wrapper js-row no-margin center').show();
                jQuery('div#'+common.theme_chk_prefix+'-popup').slideDown('slow');
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').click(function () {
                    closePopupJobManager();
                });
            }else{
                var desc = jQuery('input#cover-letter-text_' + id).val();
                jQuery('div#full_background').css('display', 'block');
                jQuery('div#popup-main-outer.coverletter').show();
                jQuery('div#popup-main.coverletter').slideDown('slow');
                jQuery('div#full_background').click(function () {
                    closePopup();
                });
                jQuery('img#popup_cross').click(function () {
                    closePopup();
                });
                jQuery('span#popup_title.coverletter').html(name);
                jQuery('span#popup_coverletter_title.coverletter').html(title);
                jQuery('span#popup_coverletter_desc.coverletter').html(desc);
            }
        }


        function closePopup() {
            jQuery('div#popup-main-outer').slideUp('slow');
            setTimeout(function () {
                jQuery('div#full_background, div#wjportal-popup-background').hide();
                jQuery('span#popup_title').html('');
                jQuery('div#popup-main').css('display', 'none');
                jQuery('span#popup_coverletter_title.coverletter').html('');
                jQuery('span#popup_coverletter_desc.coverletter').html('');
            }, 700);

        }

        function closePopupJobManager() {
            closePopupForTemplate();
        }

        function closePopupJobHub() {
            closePopupForTemplate()
        }

        function closePopupForTemplate() {
            jQuery('div#'+common.theme_chk_prefix+'-popup').slideUp('slow');
            setTimeout(function () {
                jQuery('div#'+common.theme_chk_prefix+'-popup-background').hide();
                jQuery('#'+common.theme_chk_prefix+'-modal-ar-title').html('');
                jQuery('div#'+common.theme_chk_prefix+'-popup').css('display', 'none');
                /*jQuery('span#popup_coverletter_title.coverletter').html('');
                jQuery('span#popup_coverletter_desc.coverletter').html('');*/
            }, 700);

        }


        function getResumeDetails(resumeid, salary, exp, inisi, study, available,themecall) {
            var themecall = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : null;
            task='getResumeDetail';
            if(null != themecall){
                task='getResumeDetailJobManager';
                jQuery('div#comments').css({
                   'display' : 'block',
                   'width' : '100%',
                   'float' : 'left'
                });
            }else{
                jQuery('div#comments').css('display', 'inline-block');
            }
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: task, sal: salary, expe: exp, institue: inisi, stud: study, ava: available, '_wpnonce':'". esc_attr(wp_create_nonce("get-resume-detail"))."'}, function (data) {
                if (data) {
                    jQuery('div.' + resumeid).html(data);
                }
            });

        }

        function getEmailFields(emailid, resumeid,themecall) {
           var themecall = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
            task='getEmailFields';
            if(null != themecall){
                task='getEmailFieldsJobManager';
                jQuery('div#comments').css({
                   'display' : 'block',
                   'width' : '100%',
                   'float' : 'left'
                });
            }else{
                jQuery('div#comments').css('display', 'inline-block');
            }
            var jobid = ".$jobid.";// needed to get employer email address
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: task, em: emailid, resumeid:resumeid, jobid:jobid, '_wpnonce':'". esc_attr(wp_create_nonce("get-email-fields"))."'}, function (data) {
                if (data) {
                    jQuery('div.' + resumeid).html(data);
                }
            });

        }
        jQuery(document).ready(function () {
            jQuery('a#print-link').click(function (e) {
                e.preventDefault();
                var printurl = jQuery(this).attr('data-print-url');
                print = window.open(printurl, 'print_win', 'width=1024, height=800, scrollbars=yes');
            });
        });

        jQuery(document).ready(function () {
            jQuery('a.sort-icon').click(function (e) {
                e.preventDefault();
                changeSortBy();
            });
         });

        function changeSortBy() {
            var value = jQuery('a.sort-icon').attr('data-sortby');
            var img = '';
            if (value == 1) {
                value = 2;
                img = jQuery('a.sort-icon').attr('data-image2');
            } else {
                img = jQuery('a.sort-icon').attr('data-image1');
                value = 1;
            }
            jQuery('img#sortingimage').attr('src', img);
            jQuery('input#sortby').val(value);
            jQuery('form#job_form').submit();
        }

        function changeCombo() {
            jQuery('input#sorton').val(jQuery('select#sorting').val());
            changeSortBy();
        }

        function showCoverLetterData(cover_letter_id,themecall) {
            var cover_letter_title = jQuery('div#cover_letter_data_title_'+cover_letter_id).html();
            var cover_letter_desc = jQuery('div#cover_letter_data_desc_'+cover_letter_id).html();


            if(null != themecall){
               //console.log(common.theme_chk_prefix);
                jQuery('.'+common.theme_chk_prefix+'-popup-heading.viewcoverletter').html(cover_letter_title);
                jQuery('.'+common.theme_chk_prefix+'-popup-field.viewcoverletter').html(cover_letter_desc);
                jQuery('div#'+common.theme_chk_prefix+'-popup-background.viewcoverletter').show();
                jQuery('div.'+common.theme_chk_prefix+'-popup-wrp.viewcoverletter').slideDown('slow');
                jQuery('div.'+common.theme_chk_prefix+'-sendmessage-modal-data-wrp.viewcoverletter').html(cover_letter_desc);
                jQuery('div.'+common.theme_chk_prefix+'-sendmessage-modal-data-wrp.viewcoverletter').show();
                jQuery('div.'+common.theme_chk_prefix+'-viewcover-modal-data-wrp.viewcoverletter').hide();
                jQuery('div#'+common.theme_chk_prefix+'-popup-background.viewcoverletter, .'+common.theme_chk_prefix+'-popup-close-icon2').click(function () {
                    closePopupJobManager();
                });

            }else{
                jQuery('span.wjportal-popup-title2').html(cover_letter_title);
                jQuery('div#wjportal-popup-background.viewcoverletter').show();
                jQuery('div#popup-main-outer.viewcoverletter').show();
                jQuery('div#popup-main.viewcoverletter').slideDown('slow');
                jQuery('div#popup-main.viewcoverletter .wjportal-cover-letter-desc').html(cover_letter_desc);
                jQuery('div#wjportal-popup-background').click(function () {
                    closePopup();
                });
                jQuery('img#wjportal-popup-close-btn').click(function () {
                    closePopup();
                });
            }
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
