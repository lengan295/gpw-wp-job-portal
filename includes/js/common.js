jQuery(document).ready(function () {
    jQuery(".uf_of_type_ckbox").each(function(){
        var ckCheck = false;
        var groupName = jQuery(this).attr("ckbox-group-name");
        if(jQuery("input."+groupName+":checked").length != 0){
            ckCheck = true;
        }
        if (ckCheck == true) {
            var el = document.getElementsByClassName(groupName);
            for (i = 0; i < el.length; i++) {
                jQuery(el[i]).attr("data-validation", "");
            }
        }
    });
    // configuration left menu
     jQuery( ".config-accordion" ).accordion({
        heightStyle: "content",
        collapsible: true,
        active: true,
    });
    jQuery('#tabs ul li').click(function(){
        jQuery('#tabs ul li').removeClass('ui-tabs-active');
        jQuery(this).addClass('ui-tabs-active');
    });
    jQuery('.treeview').click(function(){
        jQuery('.treeview').removeClass('active');
        jQuery(this).addClass('active');
    });
    jQuery(".wpjobportal-configurations-toggle").click(function(){
    jQuery(".wpjobportal-config-left-menu").toggleClass("show");
  });

    // Call block for all the #
    jQuery("body").delegate('a[href="#"]', "click", function (event) {
        event.preventDefault();
    });
    // Check boxess multi-selection
    jQuery('#selectall').click(function (event) {
        if (this.checked) {
            jQuery('.wpjobportal-cb').each(function () {
                this.checked = true;
            });
        } else {
            jQuery('.wpjobportal-cb').each(function () {
                this.checked = false;
            });
        }
    });
    //Close Payment PopUp
    jQuery("#wjportal-popup-close-btn, .modal-backdrop").click(function (e) {
        jQuery("div#wjportal-popup-background").hide();
        jQuery("#payment-popup, #package-popup, .wjportal-popup-wrp").slideUp('slow');
    });

    
    jQuery("body").delegate("#wjportal-popup-close-btn", "click", function(e){
        jQuery("div#wjportal-popup-background").hide();
        jQuery('div').removeClass('modal-backdrop in');
        jQuery("#payment-popup, #package-popup").slideUp('slow');
    });

    
    jQuery("body").delegate(".wpj-jp-popup-close-icon", "click", function(e){
        var themecall = 1;
        wpjobportalClosePopup(themecall);
    });

    //submit form with anchor
    jQuery("a.multioperation").click(function (e) {
        e.preventDefault();
        var total = jQuery('.wpjobportal-cb:checked').size();
        if (total > 0) {
            var task = jQuery(this).attr('data-for');
            if (task.toLowerCase().indexOf("remove") >= 0) {
                if (confirmdelete(jQuery(this).attr('confirmmessage')) == true) {
                    jQuery("input#task").val(task);
                    jQuery("form#wpjobportal-list-form").submit();
                }
            } else {
                jQuery("input#task").val(task);
                jQuery("form#wpjobportal-list-form").submit();
            }
        } else {
            var message = jQuery(this).attr('message');
            alert(message);
        }
    });

    //submit form and save ordering 
    // jQuery("input#save").click(function (e) {
    //     jQuery("input#task").val('saveordering');
    //     jQuery("input#action").val('jobtype');
    //     jQuery("form#wpjobportal-list-form").submit();  
    // });
    wpjobportalPopupLink();
});

function wpjobportalPopupLink() {
    var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

    var target_ancher = "a.wpjobportal-popup";
    if (null != themecall) {
        target_ancher = "a." + common.theme_chk_prefix + "-modal-credit-action-btn";
    }
    jQuery(target_ancher).click(function (e) {
        //      var link = jQuery(target_ancher).attr('href');

        //        e.preventDefault();

    });
}

function confirmdelete(message) {
    if (confirm(message) == true) {
        return true;
    } else {
        return false;
    }
}
function wpjobportalPopup(actionname, id,pageid) {
    if(actionname == 'featured_company'){
        if(!confirmdelete(common.company_feature_text))
            return false;
    }
    if(actionname == 'featured_job'){
        if(!confirmdelete(common.job_feature_text))
            return false;
    }
    if(actionname == 'featured_resume'){
        if(!confirmdelete(common.resume_feature_text))
            return false;
    }
    var srcid = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '';
    var anchorid = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '';
    var themecall = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;
    //var pageid = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax_popup', task: actionname, id: id, srcid: srcid, anchorid: anchorid, themecall: themecall, wpjobportal_pageid: pageid, js_nonce: common.js_nonce }, function (data) {
        if (data) {
            if (null != themecall) {
                jQuery("div#" + common.theme_chk_prefix + "-popup").html('');
                jQuery("div#" + common.theme_chk_prefix + "-popup-background").html('');
            }else{
                jQuery("div#" + common.theme_chk_prefix + "-popup").html('');
            }
            jQuery("body").append(data);
            if (null != themecall) {
                jQuery("div#" + common.theme_chk_prefix + "-popup-background").show().click(function () {
                    wpjobportalClosePopup(themecall);
                });
                jQuery("." + common.theme_chk_prefix + "-popup-close-icon").click(function () {
                    wpjobportalClosePopup(themecall);
                });
                jQuery("input[type='radio'].checkboxes").prop("checked", true);
                jQuery("input[type='radio'].checkboxes").change(function (e) {
                    setRemaingCredits();
                });
                jQuery("div#" + common.theme_chk_prefix + "-popup").slideDown();
                wpjobportalPopupLink(themecall);
            } else {
                jQuery("div#wpjobportal-popup-background").show().click(function () {
                    wpjobportalClosePopup();
                });
                jQuery("img#popup_cross").click(function () {
                    wpjobportalClosePopup();
                });
                //this line is added to resolve remaining credits issue in popup
                jQuery("input[type='radio'].checkboxes").prop("checked", true);
                jQuery("input[type='radio'].checkboxes").change(function (e) {
                    setRemaingCredits();
                });
                jQuery("div#wpjobportal-popup").slideDown();
                wpjobportalPopupLink();
            }
        }
    });
}

function wpjobportalPopupAdmin(actionname, id, srcid, anchorid, payment) {
    if (payment === undefined) {
        payment = 0;
    }
    if(actionname == 'copy_job' || actionname == "featured_resume"){
        var userid = payment;
    }else{
        var userid = jQuery('a[data-anchorid="' + anchorid + '"]').attr('credit_userid');
    }
    if(actionname == 'featured_company'){
        if(!confirmdelete(common.company_feature_text))
            return false;
    }
    if(actionname == 'featured_job'){
        if(!confirmdelete(common.job_feature_text))
            return false;
    }
    if(actionname == 'featured_resume'){
        if(!confirmdelete(common.resume_feature_text))
            return false;
    }
    var modal = jQuery('#package').val();
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax_popup', task: actionname, id: id, srcid: srcid, anchorid: anchorid, isadmin: 1, payment: payment, userid: userid,module:modal, js_nonce: common.js_nonce}, function (data) {
        if (data) {
            jQuery("body").append(data);
            jQuery("div#wpjobportal-popup-background").show().click(function () {
                wpjobportalClosePopup();
            });
            jQuery("img#popup_cross").click(function () {
                wpjobportalClosePopup();
            });
            //this line is added to resolve remaining credits issue in popup
            jQuery("input[type='radio'].checkboxes").prop("checked", true);
            jQuery("input[type='radio'].checkboxes").change(function (e) {
                setRemaingCredits();
            });
            jQuery("div#wpjobportal-popup").slideDown();
            wpjobportalPopupLink();
        }
    });
}

function validateUploadImage(file_element,success_callback,error_callback){
    var allowed_types = common.image_file_type;
    var allowed_size  = common.image_file_size;
    var result = validateUploadFile(file_element, allowed_types, allowed_size);
    if(result == true && typeof success_callback == "function"){
        success_callback();
    }else if(result == false && typeof error_callback == "function"){
        error_callback();
    }
}

function setRemaingCredits() {
    var requiredcredits = 0;
    var totalcredits = 0;
    jQuery('input[type=radio].checkboxes').each(function () {
        if (this.checked) {
            requiredcredits = parseInt(jQuery(this).attr('data-credits'));
            totalcredits = parseInt(jQuery(this).attr('data-totalcredits'));
        }
    });
    jQuery("span#remaing-credits").html(totalcredits - requiredcredits);
}

function wpjobportalClosePopup() {
    var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

    var popup_div = "";
    var bkpop_div = "";
    if (null != themecall) {
        popup_div = "div#" + common.theme_chk_prefix + "-popup,div#package-popup";
        bkpop_div = "div#" + common.theme_chk_prefix + "-popup-background";
    } else {
        popup_div = "div#wpjobportal-popup,div#package-popup";
        bkpop_div = "div#wpjobportal-popup-background";
    }
    jQuery(popup_div).slideUp();
    jQuery("div#wjportal-listpopup").slideUp();// to handle tell a friend case
    jQuery(bkpop_div).hide();
    // one layer remaind in some cases on popup close
    jQuery('.modal-backdrop.show').hide();

    setTimeout(function () {
        jQuery(popup_div).html(' ');
    }, 350);
}

function wpjobportalPopupProceeds(actionname, objectid, srcid, anchorid, actionid) {
    var themecall = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;

    if (actionid == -1) {
        jQuery('input[type=radio].checkboxes').each(function () {
            if (this.checked) {
                creditid = parseInt(jQuery(this).val());
            }
        });
    } else {
        creditid = actionid;
    }
    var remaingcredits = jQuery('span#remaing-credits').html();
    remaingcredits = parseInt(remaingcredits);
    if (remaingcredits < 0) {
        alert(common.insufficient_credits);
        return;
    }
    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup').prepend('<div class="' + common.theme_chk_prefix + '-loading"></div>');
    } else {
        jQuery('div#wpjobportal-popup').prepend('<div class="loading"></div>');
    }
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax_popup_action', task: actionname, id: objectid, actiona: creditid , js_nonce: common.js_nonce}, function (data) {
        if (data) {
            if (actionname == 'copy_job') {
                location.reload();
            } else {
                var obj = jQuery.parseJSON(data);
                if (1 == themecall) {
                    wpjobportalClosePopup(themecall);
                    var div = jQuery('a[data-anchorid="' + anchorid + '"]');
                    var specialtype = jQuery('a[data-anchorid="' + anchorid + '"]').attr('data-spectype');
                    jQuery(div).hide();
                    addBadgeToObject(objectid, specialtype, obj, themecall);
                } else {
                    wpjobportalClosePopup();
                    var div = jQuery('a[data-anchorid="' + anchorid + '"]');
                    var specialtype = jQuery('a[data-anchorid="' + anchorid + '"]').attr('data-spectype');
                    jQuery(div).hide();
                    jQuery('div[data-boxid="' + srcid + '"]').css("backgroundColor", "#FEF9E7");
                    addBadgeToObject(objectid, specialtype, obj);
                    makeExpiry();
                    setTimeout(function () {
                        jQuery('div[data-boxid="' + srcid + '"]').css("backgroundColor", "#FFFFFF");
                    }, 2000);
                }
            }
        }
    });
}

function makeExpiry() {
    jQuery(".featurednew").hover(function () {
        jQuery(this).find("span.featurednew-onhover").show();
    }, function () {
        jQuery(this).find('.featurednew-onhover').fadeOut("slow");
    });
}

function wpjobportalPopupProceedsAdmin(actionname, objectid, srcid, anchorid, actionid, payment) {
    if (payment === undefined) {
        payment = 0;
    }
    if (actionid == -1) {
        jQuery('input[type=radio].checkboxes').each(function () {
            if (this.checked) {
                creditid = parseInt(jQuery(this).val());
            }
        });
    } else {
        creditid = actionid;
    }
   
    jQuery('div#wpjobportal-popup').prepend('<div class="loading"></div>');
    var upakid = jQuery('#upakid').val();
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax_popup_action', task: actionname, id: objectid, actiona: creditid, isadmin: 1, payment: payment,upakid:upakid , js_nonce: common.js_nonce}, function (data) {
        if (data) {
            //Copy Job Reload Process
            if (actionname == 'copy_job') {
                location.reload();
            } else {
                var obj = jQuery.parseJSON(data);
                wpjobportalClosePopup();
                var div = jQuery('a[data-anchorid="' + anchorid + '"]').parent();
                var specialtype = jQuery('a[data-anchorid="' + anchorid + '"]').attr('data-spectype');
                jQuery('div[data-boxid="' + srcid + '"]').css("backgroundColor", "#FEF9E7");
                addBadgeToObject(objectid, specialtype, obj.expiry);
                makeExpiry();
                setTimeout(function () {
                    jQuery('div[data-boxid="' + srcid + '"]').css("backgroundColor", "#FFFFFF");
                }, 2000);
            }
        }
    });
}

function wpjobportalformpopupAdmin(actionname, formid) {
    var formvalid = jQuery('form#' + formid).isValid();
    if (formvalid == false) {
        event.preventDefault();
        return;
    }
    var test = true;
    jQuery("form#" + formid + " :input[type=email]").each(function(){
        var emailValue = jQuery(this).val();
        if(emailValue.length != 0){
            var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            test = pattern.test(emailValue);
            if (test == false) {
                jQuery(this).css({ "border-color": 'red'});
            }
        }
    });
    if (test == false) {
        alert('Email is not of correct Format');
        event.preventDefault();
        return;
    }
    var userid = jQuery('form#' + formid).find('input.wpjobportal-form-save-btn').attr('credit_userid');
    var modal = jQuery('#package').val();
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax_popup', task: actionname, formid: formid, isadmin: 1, userid: userid,module:modal , js_nonce: common.js_nonce}, function (data) {
        if (data) {
            jQuery("body").append(data);
            /*alert(data);
            exit();*/
            jQuery("div#wpjobportal-popup-background").show().click(function () {
                wpjobportalClosePopup();
            });
            jQuery("img#popup_cross").click(function () {
                wpjobportalClosePopup();
            });
            jQuery("div#wpjobportal-popup").slideDown();
            wpjobportalPopupLink();
            //this line is added to resolve remaining credits issue in popup
            jQuery("input[type='radio'].checkboxes").prop("checked", true);
            jQuery("input[type='radio'].checkboxes").change(function (e) {
                setRemaingCredits();
            });
        }
    });
}

function wpjobportalformpopup(actionname, formid) {
    var themecall = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;


    if (formid == 'resumeform') {
        var formvalid = jQuery('form.has-validation-callback').isValid();
        if (formvalid == false) {
            return;
        }
    } else {
        var formvalid = jQuery('form#' + formid).isValid();
        if (formvalid == false) {
            return;
        }
    }
    // check if terms and conditions is checked(if it exsists on the layout.)
    var termsandcondtions = jQuery("div.wpjobportal-terms-and-conditions-wrap").attr("data-wpjobportal-terms-and-conditions");
    if(termsandcondtions == 1){
        if(!jQuery("input[name='termsconditions']").is(":checked")){
            alert(common.terms_conditions);
            return false;
        }
    }
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax_popup', task: actionname, formid: formid, themecall: themecall, js_nonce: common.js_nonce }, function (data) {
        if (data) {
            jQuery("body").append(data);
            if (null != themecall) {
                jQuery("div#" + common.theme_chk_prefix + "-popup-background").show().click(function () {
                    wpjobportalClosePopup(themecall);
                });
                jQuery("." + common.theme_chk_prefix + "-popup-close-icon").click(function () {
                    wpjobportalClosePopup(themecall);
                });
                jQuery("div#" + common.theme_chk_prefix + "-popup").slideDown();
                wpjobportalPopupLink(themecall);
                jQuery("input[type='radio'].checkboxes").prop("checked", true);
                jQuery("input[type='radio'].checkboxes").change(function (e) {
                    setRemaingCredits();
                });
            } else {
                jQuery("div#wpjobportal-popup-background").show().click(function () {
function validateUploadFile(file_element, allowed_types, allowed_size){
    var file = file_element.files[0];
    var fileext = getExtensions(file.name);
    var filesize = (file.size / 1024);
    allowed_types = allowed_types.split(',');
    var replaceflag = 0;
    var result = true;
    if(checkExtension(allowed_types, fileext) == 'Y'){
        if(filesize > allowed_size){
            alert(common.file_size_exceeded);
            replaceflag = 1;
            result = false;
        }
    }else{
        alert(common.file_extension_mismatch);
        replaceflag = 1;
        result = false;
    }
    if(replaceflag){
        jQuery(file_element).replaceWith(file_element.outerHTML);
    }
    return result;
}

function  checkExtension(f_e_a, fileext) { //from jm-jobs-pro-124 common.js
    var match = 'N';
    for (var i = 0; i < f_e_a.length; i++) {
        if (f_e_a[i].toLowerCase() === fileext.toLowerCase()) {
            match = 'Y';
            break;
        }
    }
    return match;
}


function validateUploadFile(file_element, allowed_types, allowed_size){
    var file = file_element.files[0];
    var fileext = getExtension(file.name);
    var filesize = (file.size / 1024);
    allowed_types = allowed_types.split(',');
    var replaceflag = 0;
    var result = true;
    if(checkExtension(allowed_types, fileext) == 'Y'){
        if(filesize > allowed_size){
            alert(common.file_size_exceeded);
            replaceflag = 1;
            result = false;
        }
    }else{
        alert(common.file_extension_mismatch);
        replaceflag = 1;
        result = false;
    }
    if(replaceflag){
        jQuery(file_element).replaceWith(file_element.outerHTML);
    }
    return result;
}

function  checkExtension(f_e_a, fileext) { //from jm-jobs-pro-124 common.js
    var match = 'N';
    for (var i = 0; i < f_e_a.length; i++) {
        if (f_e_a[i].toLowerCase() === fileext.toLowerCase()) {
            match = 'Y';
            break;
        }
    }
    return match;
}

function getExtensions(filename) { //from jm-jobs-pro-124 common.js
    return filename.split('.').pop().toLowerCase();
}
function getExtension(filename) { //from jm-jobs-pro-124 common.js
    return filename.split('.').pop().toLowerCase();
}        wpjobportalClosePopup();
                });
                jQuery("img#popup_cross").click(function () {
                    wpjobportalClosePopup();
                });
                jQuery("div#wpjobportal-popup").slideDown();
                wpjobportalPopupLink();
                //this line is added to resolve remaining credits issue in popup
                jQuery("input[type='radio'].checkboxes").prop("checked", true);
                jQuery("input[type='radio'].checkboxes").change(function (e) {
                    setRemaingCredits();
                });
            }
        }
    });
}

function wpjobportalPopupResumeFormProceeds(actionid) {
    if (actionid == -1) {
        jQuery('input[type=radio].checkboxes').each(function () {
            if (this.checked) {
                creditid = parseInt(jQuery(this).val());
            }
        });
    } else {
        creditid = actionid;
    }
    var remaingcredits = jQuery('span#remaing-credits').html();
    remaingcredits = parseInt(remaingcredits);
    if (remaingcredits < 0) {
        alert(common.insufficient_credits);
        return;
    }
    jQuery("input#creditid").val(creditid);
    jQuery("div#wpjobportal-popup").slideUp();
    jQuery("div#wpjobportal-popup-background").hide();
    submitresume();
}

function wpjobportalPopupResumeFormProceedsAdmin(actionid, payment) {
    if (payment === undefined) {
        payment = 0;
    }
    if (actionid == -1) {
        jQuery('input[type=radio].checkboxes').each(function () {
            if (this.checked) {
                creditid = parseInt(jQuery(this).val());
            }
        });
    } else {
        creditid = actionid;
    }
    var remaingcredits = jQuery('span#remaing-credits').html();
    remaingcredits = parseInt(remaingcredits);
    if (remaingcredits < 0 && payment == 1) {
        alert(common.insufficient_credits);
        return;
    }
    jQuery("input#payment").val(payment);
    jQuery("input#creditid").val(creditid);
    jQuery("div#wpjobportal-popup").slideUp();
    jQuery("div#wpjobportal-popup-background").hide();
    submitresume();
}

function wpjobportalPopupFormProceeds(formid, actionid) {
    if (actionid == -1) {
        jQuery('input[type=radio].checkboxes').each(function () {
            if (this.checked) {
                creditid = parseInt(jQuery(this).val());
            }
        });
    } else {
        creditid = actionid;
    }
    var remaingcredits = jQuery('span#remaing-credits').html();
    remaingcredits = parseInt(remaingcredits);
    if (remaingcredits < 0) {
        alert(common.insufficient_credits);
        return;
    }
    jQuery("input#creditid").val(creditid);
    jQuery("div#wpjobportal-popup").slideUp();
    jQuery("div#wpjobportal-popup-background").hide();
    jQuery("form#" + formid).submit();
}

function wpjobportalPopupFormProceedsAdmin(formid, actionid, payment) {
    if (payment === undefined) {
        payment = 0;
    }
    if (actionid == -1) {
        jQuery('input[type=radio].checkboxes').each(function () {
            if (this.checked) {
                creditid = parseInt(jQuery(this).val());
            }
        });
    } else {
        creditid = actionid;
    }
    var remaingcredits = jQuery('span#remaing-credits').html();
    remaingcredits = parseInt(remaingcredits);
    if (remaingcredits < 0 && payment == 1) {
        alert(common.insufficient_credits);
        return;
    }
    jQuery("input#payment").val(payment);
    jQuery("input#creditid").val(creditid);
    jQuery("div#wpjobportal-popup").slideUp();
    jQuery("div#wpjobportal-popup-background").hide();
    jQuery("form#" + formid).submit();
}

function getQuickViewByJobId(jobid, pageid) {
    jQuery("div#wpjobportal-popup-background").show();
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'job', task: 'getQuickViewByJobId', jobid: jobid, wpjobportalpageid: pageid, js_nonce: common.js_nonce }, function (data) {
        if (data) {
            var d = jQuery.parseJSON(data);
            jQuery("div#wpjobportal-listpopup span.popup-title span.title").html(d.title);
            jQuery("div#wpjobportal-listpopup div.wpjobportal-contentarea").html(d.content);
            jQuery("div#wpjobportal-listpopup").slideDown();
            setTimeout(loadMap1(), 4000);
        }
    });
}

function getShortlistViewByJobid(jobid) {
    var themecall = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    task = "getShortListViewByJobId";
    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup-background').show();
        task = "getShortListViewByJobIdJobPortal";
    } else {
        jQuery("div#wjportal-popup-background").show();
    }
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'shortlist', task: task, jobid: jobid, js_nonce: common.js_nonce }, function (data) {
        if (data) {
            var d = jQuery.parseJSON(data);
            if (1 == themecall) {
                jQuery("div#" + common.theme_chk_prefix + "-popup").html('');
                jQuery("div#" + common.theme_chk_prefix + "-popup").first().html(d.content);
                jQuery("div#" + common.theme_chk_prefix + "-popup").slideDown("slow");
            } else {
                jQuery("div#wjportal-listpopup div.wjportal-popup-title span.wjportal-popup-title2").html(d.title);
                jQuery("div#wjportal-listpopup div.wjportal-popup-contentarea").html('');
                jQuery("div#wjportal-listpopup div.wjportal-popup-contentarea").html(d.content);
                jQuery("div#wjportal-listpopup").slideDown();
            }
        }
    });
}

function setrating(src, newrating) {
    jQuery("#" + src).width(parseInt(newrating * 20) + '%');
}

function saveJobShortlist() {
    var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    task = "saveJobShortlist";
    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup').prepend('<div class="' + common.theme_chk_prefix + '-loading"></div>');
        task = "saveJobShortlistJobManager";
    }

    var jobid = jQuery("input#jobid").val();
    var slid = jQuery("input#wpjobportalid").val();
    var comments = jQuery("textarea#wpjobportalcomment").val();
    rating = jQuery('#rating_' + jobid).width();
    rateintvalue = parseInt(rating);
    rate = rateintvalue / 20;
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'shortlist', task: task, jobid: jobid, comments: comments, rate: rate, slid: slid, js_nonce: common.js_nonce  }, function (data) {
        if (data) {
            if (null != themecall) {
                jQuery('div#' + common.theme_chk_prefix + '-popup').find("div." + common.theme_chk_prefix + "-loading").remove();
                jQuery("div#" + common.theme_chk_prefix + "-popup div." + common.theme_chk_prefix + "-visitor-msg-btn-wrp").html(data);
            } else {
                jQuery("div.wjportal-visitor-msg-btn-wrp").html(data); //retuen value
            }
        }
    });
}

function getApplyNowByJobid(jobid, pageid ,package = '') {
    if (jQuery("#jsre_featured_button").hasClass('disabled')) {
        return;
    }
    wpjobportalClosePopup();
    //var themecall = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var themecall = common.theme_chk_flag;// theme check flag from main plugin file
    if(themecall == 0){
        themecall = null;
    }
    if (null != themecall) {
        //jQuery("div#wpjobportal-popup-background").show();
       jQuery('div#' + common.theme_chk_prefix + '-popup-background').show();
    } else {
        jQuery("div#wjportal-popup-background").hide();
        jQuery("div#wjportal-popup-background:lt(1)").show();
    }
    var permalink = jQuery('div#wpjobportal_permalink').html();
    var selected_pack = jQuery("#jsre_featured_button").attr('selected_pack');
    if (typeof selected_pack !== 'undefined' && selected_pack !== false && selected_pack != 0) {
        package = selected_pack;
    }
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: 'getApplyNowByJobid', jobid: jobid, jobpermalink: permalink, wpjobportal_pageid: pageid, wpjobportalpageid: pageid , themecall: themecall,upkid: package, js_nonce: common.js_nonce }, function (data) {
        if (data) {

            var d = jQuery.parseJSON(data);
            if (null != themecall) {
               jQuery("div#" + common.theme_chk_prefix + "-popup").html('');
               jQuery("div#" + common.theme_chk_prefix + "-popup").first().html(d.content);
               jQuery("div#" + common.theme_chk_prefix + "-popup").slideDown("slow");

            } else {
                jQuery("div#wjportal-listpopup div.wjportal-popup-title span.wjportal-popup-title2").html(d.title);
                jQuery("div#wjportal-listpopup div.wjportal-popup-job-list").html('');
                jQuery("div#wjportal-listpopup div.wjportal-popup-job-list").first().html(d.popupjoblist);
                jQuery("div#wjportal-listpopup div.wjportal-popup-contentarea").html('');
                jQuery("div#wjportal-listpopup div.wjportal-popup-contentarea").first().html(d.content);
                jQuery("div#wjportal-listpopup").slideDown("slow");
            }
        }
    });
    return;
}

function jobApply(jobid,upkid,jp_page_id) { 
    // updated the code to accomodate page id parameter
    var themecall = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    task = "jobapply";
    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup').prepend('<div class="transparentbg loading"></div>');
        task = "jobapplyjobmanager";
    } else {
        jQuery('div.wjportal-popup-contentarea').prepend('<div class="transparentbg loading"></div>');
    }
    var cvid = jQuery('select#cvid').val();
    var coverletterid = jQuery('select#coverletterid').val();
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'jobapply', task: task, jobid: jobid, cvid: cvid, coverletterid: coverletterid, themecall: themecall,upkid: upkid , js_nonce: common.js_nonce,wpjobportalpageid:jp_page_id}, function (data) {
        if (data) {
            if (null != themecall) {
                jQuery("div." + common.theme_chk_prefix + "-visitor-msg-btn-wrp").html(data);
                jQuery("div#" + common.theme_chk_prefix + "-popup").slideDown("slow");
                jQuery("div.transparentbg").removeClass('loading');
            } else {
                jQuery("div.wjportal-visitor-msg-btn-wrp").html(data);
                jQuery("div.transparentbg").removeClass('loading');
            }
        }
    });
}
var tell_a_friend_captcha_resp;
function getTellaFriend(jobid) {
    var themecall = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    task = "getTellaFriend";

    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup-background').show();
        // task = "getTellaFriendJobManager";
    } else {
        jQuery("div#wjportal-popup-background").show();
    }
    jQuery("div#wjportal-popup-background").show();
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'tellfriend', task: task, jobid: jobid, js_nonce: common.js_nonce }, function (data) {
        if (data) {
            var d = jQuery.parseJSON(data);
            if (null != themecall) {
                jQuery("div#" + common.theme_chk_prefix + "-popup").html('');
                jQuery("div#" + common.theme_chk_prefix + "-popup").first().html(d.content);
                jQuery("div#" + common.theme_chk_prefix + "-popup").slideDown();
                if (typeof grecaptcha != 'undefined') {
                    if (jQuery("#tell-a-friend-captcha").length) {
                      var recaptcha_version = jQuery("div#tell-a-friend-captcha").attr('data-recaptcha-version');
                      if(recaptcha_version == 2){
                        grecaptcha.render("tell-a-friend-captcha", {
                            sitekey: jQuery("div#tell-a-friend-captcha").attr('data-sitekey'),
                            callback: function callback(response) {
                                tell_a_friend_captcha_resp = response;
                            }
                        });
                      }else{ // recaptcha version 3
                        const siteKey = jQuery("div#tell-a-friend-captcha").attr('data-sitekey');

                        var recaptchaToken = '';
                          grecaptcha.ready(function() {
                            grecaptcha.execute(siteKey, { action: 'submit' }).then(function(token) {
                              // Store the token in a variable
                              recaptchaToken = token;
                              tell_a_friend_captcha_resp_v3 = token;
                              // Now, you can send 'recaptchaToken' to your server using AJAX or fetch
                              // Example AJAX request to send the token to the server
                              var uniqueId = 'recpathc_field_v3';

                              // Create the hidden field with the unique name and ID
                              var hiddenField = jQuery('<input type="hidden">');
                              hiddenField.attr('id', uniqueId);
                              hiddenField.attr('name', uniqueId);

                              // Set the value of the hidden field
                              hiddenField.val(token);
                            
                              // Append the hidden field to the #tell-a-friend-captcha div
                              jQuery('#tell-a-friend-captcha').append(hiddenField);
                            });
                        });
                     }
              } //
             }
                  
            } else {
                jQuery("div#wjportal-listpopup div.wjportal-popup-title span.wjportal-popup-title2").html(d.title);
                jQuery("div#wjportal-listpopup div.wjportal-popup-contentarea").html('');
                jQuery("div#wjportal-listpopup div.wjportal-popup-contentarea").html(d.content);
                jQuery("div#wjportal-listpopup").slideDown();
                //     const siteKey = jQuery("div#tell-a-friend-captcha").attr('data-sitekey');

                // var recaptchaToken = '';
                //   grecaptcha.ready(function() {
                //     grecaptcha.execute(siteKey, { action: 'submit' }).then(function(token) {
                //       // Store the token in a variable
                //       recaptchaToken = token;
                //       tell_a_friend_captcha_resp_v3 = token;
                //       // Now, you can send 'recaptchaToken' to your server using AJAX or fetch
                //       // Example AJAX request to send the token to the server
                //       var uniqueId = 'recpathc_field_v3';

                //       // Create the hidden field with the unique name and ID
                //       var hiddenField = jQuery('<input type="hidden">');
                //       hiddenField.attr('id', uniqueId);
                //       hiddenField.attr('name', uniqueId);

                //       // Set the value of the hidden field
                //       hiddenField.val(token);
                        
                //       // Append the hidden field to the #tell-a-friend-captcha div
                //       jQuery('#tell-a-friend-captcha').append(hiddenField);
                //     });
                // });
                if (typeof grecaptcha != 'undefined') {
                    if (jQuery("#tell-a-friend-captcha").length) {
                          var recaptcha_version = jQuery("div#tell-a-friend-captcha").attr('data-recaptcha-version');
                          if(recaptcha_version == 2){
                            grecaptcha.render("tell-a-friend-captcha", {
                                sitekey: jQuery("div#tell-a-friend-captcha").attr('data-sitekey'),
                                callback: function callback(response) {
                                    tell_a_friend_captcha_resp = response;
                                }
                            });
                          }else{ // recaptcha version 3
                            const siteKey = jQuery("div#tell-a-friend-captcha").attr('data-sitekey');
                            var recaptchaToken = '';
                              grecaptcha.ready(function() {
                                grecaptcha.execute(siteKey, { action: 'submit' }).then(function(token) {
                                  // Store the token in a variable
                                  recaptchaToken = token;
                                  tell_a_friend_captcha_resp_v3 = token;
                                  // Now, you can send 'recaptchaToken' to your server using AJAX or fetch
                                  // Example AJAX request to send the token to the server
                                  var uniqueId = 'recpathc_field_v3';

                                  // Create the hidden field with the unique name and ID
                                  var hiddenField = jQuery('<input type="hidden">');
                                  hiddenField.attr('id', uniqueId);
                                  hiddenField.attr('name', uniqueId);

                                  // Set the value of the hidden field
                                  hiddenField.val(token);
                                
                                  // Append the hidden field to the #tell-a-friend-captcha div
                                  jQuery('#tell-a-friend-captcha').append(hiddenField);
                                });
                            });
                         }
                    } //
                }
            }
        }
    });
}

function emailverify(email) {
    var emailParts = email.toLowerCase().split('@');
    if (emailParts.length == 2) {
        regex = /^[a-zA-Z0-9.!#$%&‚Äô*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        return regex.test(email);
    }
    return false;
}

function getDataForDepandantFieldResume(parentf, childf, type) {
    var section = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var sectionid = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
    var themecall = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;

    var val;
    if (type == 1) {
        if (1 != section) {
            val = jQuery("select#" + parentf + sectionid).val();
        } else if (1 == section) {
            val = jQuery("select#" + parentf).val();
        }
    } else if (type == 2) {
        if (1 != section) {
            val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]\\[" + sectionid + "\\]]:checked").val();
        } else if (1 == section) {
            val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]]:checked").val();
        }
    }
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'fieldordering', task: 'DataForDepandantFieldResume', fvalue: val, child: childf, section: section, sectionid: sectionid, type: type, themecall: themecall, js_nonce: common.js_nonce}, function (data) {
        if (data) {

            var d = jQuery.parseJSON(data);
            /*console.log(d);
            console.log(section);*/
            if (1 != section) {
                //console.log(childf+sectionid);
                jQuery("select#" + childf + sectionid).replaceWith(d);
            } else {
                jQuery("select#" + childf).replaceWith(d);
            }
        }
    });
}

function getDataForDepandantField(parentf, childf, type) {
    var section = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var sectionid = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
    var themecall = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;

    if (type == 1) {
        var val = jQuery("select#" + parentf).val();
    } else if (type == 2) {
        if (section == 1) {
            var val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]]:checked").val();
        } else {
            var val = jQuery("input[name=" + parentf + "]:checked").val();
        }
    }
    jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'fieldordering', task: 'DataForDepandantField', fvalue: val, child: childf, themecall: themecall,js_nonce: common.js_nonce }, function (data) {
        if (data) {

            var d = jQuery.parseJSON(data);
            jQuery("select#" + childf).replaceWith(d);
        }
    });
}
var tell_a_friend_captcha_resp_v3;
function sendEmailToFriend() {
    var themecall = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    var yourname = jQuery("input#yourname").val();
    if (yourname == '') {
        jQuery("input#yourname").css({ "border": "1px solid red" }).focus();
        return false;
    }
    var youremail = jQuery("input#youremail").val();
    if (youremail == '' || emailverify(youremail) == false) {
        jQuery("input#youremail").css({ "border": "1px solid red" }).focus();
        return false;
    }
    var femail1 = jQuery("input#femail1").val();
    if (femail1 == '' || emailverify(femail1) == false) {
        jQuery("input#femail1").css({ "border": "1px solid red" }).focus();
        return false;
    }
    var femail2 = jQuery("input#femail2").val();

    if (typeof femail2 != "undefined" && femail2 != '' && emailverify(femail2) == false) {
        jQuery("input#femail2").css({ "border": "1px solid red" }).focus();
        return false;
    }
    var femail3 = jQuery("input#femail3").val();
    if (typeof femail3 != "undefined" && femail3 != '' && emailverify(femail3) == false) {
        jQuery("input#femail3").css({ "border": "1px solid red" }).focus();
        return false;
    }
    var femail4 = jQuery("input#femail4").val();
    if (typeof femail4 != "undefined" && femail4 != '' && emailverify(femail4) == false) {
        jQuery("input#femail4").css({ "border": "1px solid red" }).focus();
        return false;
    }
    var femail5 = jQuery("input#femail5").val();
    if (typeof femail5 != "undefined" && femail5 != '' && emailverify(femail5) == false) {
        jQuery("input#femail5").css({ "border": "1px solid red" }).focus();
        return false;
    }
    var message = jQuery("textarea#message").val();
    if (message == '') {
        jQuery("textarea#message").css({ "border": "1px solid red" }).focus();
        return false;
    }

    var captch = jQuery("div.tell-a-friend-captcha-wrapper").find('input[type="text"]').val();
    var name = jQuery("div.tell-a-friend-captcha-wrapper").find('input[type="text"]').attr('name');

    var jobtitle = jQuery("input#jobtitle").val();
    var jobid = jQuery("input#jobid").val();
    var task = "sendmailtofriend";
    if (null != themecall) {
        jQuery('div#' + common.theme_chk_prefix + '-popup').prepend('<div class="' + common.theme_chk_prefix + '-loading"></div>');
        // task = "sendmailtofriendJobManager";
        /*if(jQuery("div#"+common.theme_chk_prefix+"-tellafriend").find("div."+common.theme_chk_prefix+"-modal-data-wrp").length){
            jQuery("div#"+common.theme_chk_prefix+"-tellafriend").find("div."+common.theme_chk_prefix+"-multi-popup-overlay").show();
            jQuery("div#"+common.theme_chk_prefix+"-tellafriend").find("img."+common.theme_chk_prefix+"-multipop-loading-gif").show();
        }*/
    }

    var data = {
        action: 'wpjobportal_ajax',
        wpjobportalme: 'tellfriend',
        task: task,
        yourname: yourname,
        youremail: youremail,
        message: message,
        femail1: femail1,
        femail2: femail2,
        femail3: femail3,
        femail4: femail4,
        femail5: femail5,
        jobtitle: jobtitle,
        jobid: jobid,
        js_nonce: common.js_nonce
    };
    if (name != '' && name != undefined) {
        data[name] = captch;
    }
    data['g-recaptcha-response'] = tell_a_friend_captcha_resp;
  	var recaptcha_version = jQuery("div#tell-a-friend-captcha").attr('data-recaptcha-version');
  	
  	if(recaptcha_version == 3){
  		data['g-recaptcha-response'] = jQuery("#recpathc_field_v3").val();
    }
  jQuery.post(common.ajaxurl, data, function (rdata) {
        if (rdata) {
            if (null != themecall) {
                obj = jQuery.parseJSON(rdata);
                if (obj.status == 1) {
                    jQuery("div." + common.theme_chk_prefix + "-visitor-msg-btn-wrp").html(obj.message); //retuen value
                } else {
                    alert(obj.message);
                }
            } else {
                obj = jQuery.parseJSON(rdata);
                if (obj.status == 1) {
                    jQuery("div.wjportal-visitor-msg-btn-wrp").html(obj.message); //retuen value
                } else {
                    alert(obj.message);
                }
            }
        }
    });
}

function validateRemaingCredits() {
    var remaingcredits = jQuery('span#remaing-credits').html();
    remaingcredits = parseInt(remaingcredits);
    if (remaingcredits < 0) {
        alert(common.insufficient_credits);
        return false;
    }
    return true;
}

function draw() {
    var objects = document.getElementsByClassName('goldjob');
    for (var i = 0; i < objects.length; i++) {
        var canvas = objects[i];
        if (canvas.getContext) {
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = "#FFFFFF";
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(10, 10);
            ctx.lineTo(0, 20);
            ctx.fill();
        }
    }
}

window.onload = function () {
    draw();
};

function fillSpaces(string) {
    string = string.replace(" ", "%20");
    return string;
}

function showloginpopupjobmanager() {
    jQuery("a." + common.theme_chk_prefix + "-tp-link").click();
    return;
}

function showloginpopupjobhub() {
    jQuery("a." + common.theme_chk_prefix + "-tp-link").click();
    return;
}
function osmAddMarker(osmMap, coordinate, icon) {
    if(osmMap && ol){
        if(!icon){
            icon = 'http://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png';
        }
        var vectorLayer = new ol.layer.Vector({
            source: new ol.source.Vector({
                features: [
                    new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.transform(coordinate, 'EPSG:4326', 'EPSG:3857')),
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
}
function deRequireUfCheckbox(elClass) {
    var el = document.getElementsByClassName(elClass);
    var atLeastOneChecked = false; //at least one cb is checked
    for (i = 0; i < el.length; i++) {
        if (el[i].checked === true) {
            atLeastOneChecked = true;
        }
    }

    if (atLeastOneChecked === true) {
        for (i = 0; i < el.length; i++) {
            jQuery(el[i]).attr("data-validation", "");
        }
    } else {
        for (i = 0; i < el.length; i++) {
            jQuery(el[i]).attr("data-validation", "required");
        }
    }
}
function onSubmitTellAFriendPlugin(token) {
    sendEmailToFriend();
}

function onSubmitTellAFriendTheme(token) {
    sendEmailToFriend(1);
}

function deleteCutomUploadedFile (field1) {
    console.log(field1);
    //jQuery("input#"+field1).val(1);
    jQuery("input#"+field1+"_1").val(1);
    jQuery("span."+field1).hide();
    jQuery("span."+field1+"_1").hide();
}



function getDataForVisibleField(wpnonce, val, parentf, child, pvalue, cond) {

    var childs = child.split(",");
    var field_type = 'required';
    jQuery.each(childs, function(childi,childf){

        var type = jQuery('[name="sec_1['+childf+']"]').attr("type");
        console.log(childf+"=>"+type);
        if (type == 'text' ||type == 'email' || type == 'password' || type == 'file') {
            jQuery('[name="'+childf+'"]').val('');
        }else if (type == 'checkbox') {
            jQuery('[name="sec_1['+childf+'][]').removeAttr('checked');
            jQuery('[name="sec_1['+childf+']"]').removeAttr('checked');
        }else if (type == 'radio') {
            jQuery('[name="sec_1['+childf+']"]').prop('checked', false);
        }else if (jQuery('[name="sec_1['+childf+']"]').hasClass("wpjobportal-form-textarea-field")) {
            jQuery('[name="sec_1['+childf+']"]').val("");
        }else if (jQuery('[name="sec_1['+childf+']"]').hasClass("wjportal-form-multi-select-field")) {
            jQuery('[name="sec_1['+childf+']"]').prop('selectedIndex', 0);
        }else if (jQuery('[name="sec_1['+childf+']"]').hasClass("wjportal-form-input-field ")) {
            jQuery('[name="sec_1['+childf+']"]').val('');
        }else if (jQuery('[name="sec_1['+childf+']"]').hasClass("wjportal-form-select-field")) {
            jQuery('[name="sec_1['+childf+']"]').val('');
        }else{
            if (jQuery('[name="sec_1['+childf+'][]"]').attr("type") == 'checkbox') {
                field_type = 'notRequired';
            }
            type = "checkboxOrMultiple"
            if (jQuery('[name="sec_1['+childf+'][]"]').attr("multiple")) {
                jQuery('[name="sec_1['+childf+'][]"]').children().prop('selected', false);
                jQuery('[name="sec_1['+childf+'][]"]').prop('selectedIndex', 0);
            } else {
                jQuery('[name="sec_1['+childf+'][]"]').removeAttr('checked');
            }
        }
    });

    var childs = child.split(",");
    var field_type = 'required';
    jQuery.each(childs, function(childi,childf){
        var type = jQuery('[name="'+childf+'"]').attr("type");
        console.log(childf+"=>"+type);
        if (type == 'text' ||type == 'email' || type == 'password' || type == 'file') {
            jQuery('[name="'+childf+'"]').val('');
        }else if (type == 'checkbox') {
            jQuery('[name="'+childf+'[]"]').removeAttr('checked');
            jQuery('[name="'+childf+'"]').removeAttr('checked');
        }else if (type == 'radio') {
            jQuery('[name="'+childf+'"]').prop('checked', false);
        }else if (jQuery('[name="'+childf+'"]').hasClass("wpjobportal-form-textarea-field")) {
            jQuery('[name="'+childf+'"]').val("");
        }else if (jQuery('[name="'+childf+'"]').hasClass("wjportal-form-multi-select-field")) {
            jQuery('[name="'+childf+'"]').prop('selectedIndex', 0);
        }else if (jQuery('[name="'+childf+'"]').hasClass("wjportal-form-input-field ")) {
            jQuery('[name="'+childf+'"]').val('');
        }else if (jQuery('[name="'+childf+'"]').hasClass("wjportal-form-select-field")) {
            jQuery('[name="'+childf+'"]').val('');
        }else{
            if (jQuery('[name="'+childf+'[]"]').attr("type") == 'checkbox') {
                field_type = 'notRequired';
            }
            type = "checkboxOrMultiple"
            if (jQuery('[name="'+childf+'[]"]').attr("multiple")) {
                jQuery('[name="'+childf+'[]"]').children().prop('selected', false);
                jQuery('[name="'+childf+'[]"]').prop('selectedIndex', 0);
            } else {
                jQuery('[name="'+childf+'[]"]').removeAttr('checked');
            }
        }


        

        if (val.length != 0){
            if (type == 'checkboxOrMultiple') {
                if (cond == 1) {
                    if (pvalue == val) {
                        if (childi == 0) {
                            jQuery('[name="'+childf+'[]"]').closest("div.js-form-custm-flds-wrp").removeClass('visible');
                            //jQuery('[name="'+childf+'[]"]').closest("div.js-ticket-from-field-wrp").removeClass('visible');
                            isFieldRequired (field_type, childf, 'show', wpnonce);
                        }
                    }else{
                        jQuery('[name="'+childf+'[]"]').closest("div.js-form-custm-flds-wrp").addClass('visible');
                        //jQuery('[name="'+childf+'[]"]').closest("div.js-ticket-from-field-wrp").addClass('visible');
                        isFieldRequired (field_type, childf, 'hide', wpnonce);
                    }
                }else if (cond ==0) {
                    if (pvalue != val) {
                        if (childi == 0) {
                            jQuery('[name="'+childf+'[]"]').closest("div.js-form-custm-flds-wrp").removeClass('visible');
                            //jQuery('[name="'+childf+'[]"]').closest("div.js-ticket-from-field-wrp").removeClass('visible');
                            isFieldRequired (field_type, childf, 'show', wpnonce);
                        }
                    }else{
                        jQuery('[name="'+childf+'[]"]').closest("div.js-form-custm-flds-wrp").addClass('visible');
                        //jQuery('[name="'+childf+'[]"]').closest("div.js-ticket-from-field-wrp").addClass('visible');
                        isFieldRequired (field_type, childf, 'hide', wpnonce);
                    }
                }
                
            }else{
                if (cond == 1) {
                    if (pvalue == val) {
                        console.log(childi);
                        if (childi == 0) {
                            jQuery('[name="'+childf+'"]').closest("div.js-form-custm-flds-wrp").removeClass('visible');
                            //jQuery('[name="'+childf+'"]').closest("div.js-ticket-from-field-wrp").removeClass('visible');
                            isFieldRequired (field_type, childf, 'show', wpnonce);
                        }
                    }else{
                        jQuery('[name="'+childf+'"]').closest("div.js-form-custm-flds-wrp").addClass('visible');
                        jQuery('[name="'+childf+'"]').closest("div.js-ticket-from-field-wrp").addClass('visible');
                        isFieldRequired (field_type, childf, 'hide', wpnonce);
                    }
                }else if (cond ==0) {
                    if (pvalue != val) {
                        if (childi == 0) {
                            jQuery('[name="'+childf+'"]').closest("div.js-form-custm-flds-wrp").removeClass('visible');
                            //jQuery('[name="'+childf+'"]').closest("div.js-ticket-from-field-wrp").removeClass('visible');
                            isFieldRequired (field_type, childf, 'show', wpnonce);
                        }
                    }else{
                        jQuery('[name="'+childf+'"]').closest("div.js-form-custm-flds-wrp").addClass('visible');
                        //jQuery('[name="'+childf+'"]').closest("div.js-ticket-from-field-wrp").addClass('visible');
                        isFieldRequired (field_type, childf, 'hide', wpnonce);
                    }
                }
            }
        }else{
            if (type == 'checkboxOrMultiple') {
                jQuery('[name="'+childf+'[]"]').closest("div.js-form-custm-flds-wrp").addClass('visible');
                //jQuery('[name="'+childf+'[]"]').closest("div.js-ticket-from-field-wrp").addClass('visible');
            } else {
                jQuery('[name="'+childf+'"]').closest("div.js-form-custm-flds-wrp").addClass('visible');
                //jQuery('[name="'+childf+'"]').closest("div.js-ticket-from-field-wrp").addClass('visible');
            }
            isFieldRequired (field_type, childf , 'hide', wpnonce);
        }
            
    });
}

function isFieldRequired (field_type, field, state, wpnonce) {
    //jQuery.post(common.ajaxurl, { action: 'wpjobportal_ajax', wpjobportalme: 'fieldordering', task: 'DataForDepandantField', fvalue: val, child: childf, themecall: themecall,js_nonce: common.js_nonce }, function (data) {
    jQuery.post(common.ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'fieldordering', task: 'isFieldRequired', field:field, '_wpnonce':wpnonce}, function (data) {
        if (data) {
            if (data == 1 && state == 'show' && field_type == 'required') {
                jQuery('[name="'+field+'"]').attr('data-validation', 'required');
                jQuery('[name="'+field+'[]"]').attr('data-validation', 'required');
            } else if(data == 1 && state == 'hide') {
                jQuery('[name="'+field+'"]').attr('data-validation', '');
                jQuery('[name="'+field+'[]"]').attr('data-validation', '');
            }
        }
    });
    
}
jQuery(document).ready(function(){
    jQuery('.specialClass').closest("div.js-form-custm-flds-wrp").removeClass('visible');
});


function hidePackagePopupForJobApply(){
    wpjobportalClosePopup();
    jQuery("div#wjportal-popup-background").hide();
    var selected_pack = jQuery("#jsre_featured_button").attr('selected_pack');
    if (typeof selected_pack !== 'undefined' && selected_pack !== false && selected_pack != 0) {
        package = selected_pack;
        alert(package);
        jQuery("input#upkid").val(package);
    }
    jQuery('.wjportal-apply-package-button').slideUp();
    jQuery('.wjportal-apply-package-apply-now-button').slideDown();
    alert('done');

}
