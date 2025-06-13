<?php if (!defined('ABSPATH')) die('Restricted Access');

wp_register_script( 'wpjobportal-inline-handle', '' );
wp_enqueue_script( 'wpjobportal-inline-handle' );

if(wpjobportal::$theme_chk == 1){
?>
<?php

    $inline_js_script = "
        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
        function addTouchEvent(){
             jQuery('div.'+common.theme_chk_prefix+'-by-category-wrp').on('touchstart', function (e) {
                'use strict'; //satisfy code inspectors
                var link = jQuery(this); //preselect the link
                if (link.hasClass('touch')) {
                    return true;
                }else {
                    jQuery('div.'+common.theme_chk_prefix+'-by-category-wrp').not(this).removeClass('touch');
                    link.addClass('touch');
                    jQuery('div.'+common.theme_chk_prefix+'-by-category-wrp').not(jQuery(this).parents()).find('div.'+common.theme_chk_prefix+'-by-sub-category').slideUp('slow');
                    jQuery(this).find('div.'+common.theme_chk_prefix+'-by-sub-category').slideDown('slow');
                    e.preventDefault();
                    return false; //extra, and to make sure the function has consistent return points
                }
            });
            jQuery('div.'+common.theme_chk_prefix+'-by-category-wrp').hover(function(){
                jQuery(this).find('div.'+common.theme_chk_prefix+'-by-sub-category').slideDown('slow');
            },function(){
                jQuery(this).find('div.'+common.theme_chk_prefix+'-by-sub-category').slideUp('slow');
            });
        }
        function attachClosePopup() {
            jQuery('i.'+common.theme_chk_prefix+'-popup-close-icon, div#'+common.theme_chk_prefix+'-popup-background').click(function(){
                jQuery('div.'+common.theme_chk_prefix+'-popup-wrp').slideUp('slow');
                setTimeout(function () {
                    jQuery('div#'+common.theme_chk_prefix+'-popup-background').hide();
                }, 700);
            });
        }
        function getPopupAjax(category,categorytitle){
            jQuery('div.'+common.theme_chk_prefix+'-by-sub-category').hide();
            var page_id = '". wpjobportal::wpjobportal_getPageid()."';
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'category', task: 'getsubcategorypopup', category: category, page_id:page_id, '_wpnonce':'". esc_attr(wp_create_nonce("get-subcategory-popup"))."'}, function (data) {
                if (data) {
                    jQuery('div.'+common.theme_chk_prefix+'-popup-wrp div.'+common.theme_chk_prefix+'-popup-cnt-wrp h1.'+common.theme_chk_prefix+'-popup-heading').html(categorytitle);
                    jQuery('div#'+common.theme_chk_prefix+'-popup-background').show();
                    jQuery('div.'+common.theme_chk_prefix+'-popup-wrp div.'+common.theme_chk_prefix+'-popup-cnt-wrp div.'+common.theme_chk_prefix+'-popup-contentarea').html(data);
                    jQuery('div.'+common.theme_chk_prefix+'-popup-wrp').show();
                    addTouchEvent();
                    attachClosePopup();
                }
            });
        }
        jQuery(document).ready(function(){
            addTouchEvent();
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

<?php
}else{
?>
<?php

    $inline_js_script = "
        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
        function addTouchEvent(){
            jQuery('div.wjportal-by-category-wrp').on('touchstart', function (e) {
                'use strict'; //satisfy code inspectors
                var link = jQuery(this); //preselect the link
                if (link.hasClass('touch')) {
                    return true;
                }else {
                    jQuery('div.wjportal-by-category-wrp').not(this).removeClass('touch');
                    link.addClass('touch');
                    jQuery('div.wjportal-by-category-wrp').not(jQuery(this).parents()).find('div.wjportal-by-sub-catagory').slideUp('slow');
                    jQuery(this).find('div.wjportal-by-sub-catagory').slideDown('slow');
                    e.preventDefault();
                    return false; //extra, and to make sure the function has consistent return points
                }
            });
            jQuery('div.wjportal-by-category-wrp').hover(function(){
                jQuery(this).find('div.wjportal-by-sub-catagory').slideDown(150);
            },function(){
                jQuery(this).find('div.wjportal-by-sub-catagory').slideUp(150);
            });
        }
        function attachClosePopup() {
            jQuery('img#wjportal-popup-close-btn,div#wjportal-popup-background').click(function(){
                jQuery('div#wpjobportal-search-popup,div#wjportal-listpopup').slideUp('slow');
                setTimeout(function () {
                    jQuery('div#wjportal-popup-background').hide();
                }, 700);
            });
        }
        function getPopupAjax(category,categorytitle){
            jQuery('div.wjportal-by-sub-catagory').hide();
            var page_id = '". wpjobportal::wpjobportal_getPageid()."';
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'category', task: 'getsubcategorypopup', category: category, page_id:page_id, '_wpnonce':'". esc_attr(wp_create_nonce("get-subcategory-popup"))."'}, function (data) {
                if (data) {
                    jQuery('div#wjportal-listpopup div.wjportal-popup-title span.wjportal-popup-title2').html(categorytitle);
                    jQuery('div#wjportal-popup-background').show();
                    jQuery('div#wjportal-listpopup div.wjportal-popup-contentarea').html(data);
                    jQuery('div#wjportal-listpopup').show();
                    addTouchEvent();
                    attachClosePopup();
                }
            });
        }
        jQuery(document).ready(function(){
            addTouchEvent();
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

<?php } ?>
