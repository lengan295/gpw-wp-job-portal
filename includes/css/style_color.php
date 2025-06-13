<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('ABSPATH'))
    die('Restricted Access');


$color1 = "#3baeda";
$color2 = "#333333";
$color3 = "#575757";
$color5 = "#d4d4d5";
$color4 = "#666666";
$color6 = "#f0f0f0";
$color7 = "#fff";
$color8 = "#3c3435";
$color9 = "#D34034";
$color10 = $color1;

$color_string_values = get_option("wpjp_set_theme_colors");
if($color_string_values != ''){
    $json_values = json_decode($color_string_values,true);
    if(is_array($json_values) && !empty($json_values)){
        $color1 = esc_attr($json_values['color1']);
        $color2 = esc_attr($json_values['color2']);
        $color3 = esc_attr($json_values['color3']);
    }
}

$result = "

    /**********************
        general
    **********************/
    div.wjportal-main-wrapper {background: ".esc_attr($color7).";}
    div#wjportal-popup-background {background: rgba(0,0,0,0.7);}
    ::-webkit-input-placeholder { /* Chrome/Opera/Safari */color: #999 !important;}
    ::-moz-placeholder { /* Firefox 19+ */color: #999 !important;}
    :-ms-input-placeholder { /* IE 10+ */color: #999 !important;}
    :-moz-placeholder { /* Firefox 18- */color: #999 !important;}
    div.error {background-color: #ffbaba;color: #D7010D;border: 1px solid #D7010D;}
    div.updated {background-color: #dff2bf;color: #3F8000;border: 1px solid #3F8000;background-image: url('../images/user-publish.png');}
    span.wjportal-featured-tag-icon-wrp span.wjportal-featured-tag-icon {background: ".esc_attr($color1).";}
    span.wjportal-featured-tag-icon-wrp span.wjportal-featured-tag-icon {color: ".esc_attr($color7).";}
    span.wjportal-featured-tag-icon-wrp span.wjportal-featured-hover-wrp {background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    span.wjportal-featured-tag-icon-wrp span.wjportal-featured-hover-wrp::before {border-top: 7px solid transparent;border-right: 7px solid ".esc_attr($color7).";border-bottom: 7px solid transparent;}
    .wjportal-payment-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    .wjportal-payment-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    .wjportal-resume-adv-act-c-detail-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    .wjportal-resume-adv-act-c-detail-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    .wjportal-show-contact-det-btn {color: ".esc_attr($color2).";border: 1px solid ".esc_attr($color2).";background: ".esc_attr($color7).";}
    .wjportal-show-contact-det-btn:hover {color: ".esc_attr($color7).";background: ".esc_attr($color2).";}
    div.wjportal-payment-action-wrp {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-visitor-apply-job-message {border: 1px solid #8bb0e9;background: #c8dcfc;color: #1a3867;}
    div.wjportal-visitor-apply-job-message {border: 1px solid #8bb0e9;background: #c8dcfc;color: #1a3867;}
    div.visitor-apply-job-jobinforamtion-message {border: 1px solid #8bb0e9;background: #c8dcfc;color: #1a3867;}
    .wjportal-free {color: #f69292;}
    .wjportal-stripe {color: #008cdd;}
    .wjportal-paypal {color: #1e477a;}
    .wjportal-woocommerce {color: #d92bb3;}
    div.wjportal-container-small label {color: ".esc_attr($color2).";}
    div.wjportal-container-small span#shortlist-stars {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color5).";}
    .wjportal-item-status {color: ".esc_attr($color7).";}
    .wjportal-unsubscribed-btn-wrp {background: ".esc_attr($color7)." !important;color: #a5323d !important;border: 1px solid #a5323d !important;}
    .wjportal-unsubscribed-btn-wrp:hover {background: #a5323d !important;color: ".esc_attr($color7)." !important;}
    .wjportal-inbox {background-color:#00a859;}
    .wjportal-spam {background-color:#84716b;}
    .wjportal-hired {background-color:#99d000;}
    .wjportal-rejected {background-color:#ed3237;}
    .wjportal-shortlist {background-color:#fea702;}
    .wjportal-waiting {background: #fea702;color: ".esc_attr($color7).";}
    .wjportal-rejected {background: #e22828;color: ".esc_attr($color7).";}
    table#wjportal-table tr th {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color2).";}
    table#wjportal-table tr td {border: 1px solid ".esc_attr($color5).";}

    /**********************
        header
    **********************/
    div.wjportal-page-header div.wjportal-page-heading {color: ".esc_attr($color2).";}
    div.wjportal-page-header div.wjportal-page-heading span.wjportal-company-salogon {color: ".esc_attr($color3).";}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-filter-wrp div.wjportal-filter select {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-filter-wrp div.wjportal-filter-image {border: 1px solid ".esc_attr($color5).";border-left: 0;background: #fafafa;}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-filter-wrp div.wjportal-filter-image:hover {background: ".esc_attr($color7).";}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-act-btn-wrp .wjportal-act-btn {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color1).";}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-act-btn-wrp .wjportal-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-act-btn-wrp .wjportan-canc-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7)."f;color: ".esc_attr($color2).";}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-act-btn-wrp .wjportan-canc-act-btn:hover {border-color: ".esc_attr($color2).";background: ".esc_attr($color2).";color: ".esc_attr($color7).";}
    div.wjportal-page-header div.wjportal-header-actions div.wjportal-view-job-count span.wjportal-view-job-txt {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}

    /**********************
        breadcrums
    **********************/
    div.wjportal-breadcrumbs-wrp div.wjportal-breadcrumbs-links {color: ".esc_attr($color3).";}
    div.wjportal-breadcrumbs-wrp div.wjportal-breadcrumbs-links::after {color: ".esc_attr($color3).";}
    div.wjportal-breadcrumbs-wrp div.wjportal-breadcrumbs-links a.wjportal-breadcrumbs-link {color: ".esc_attr($color1).";}

    /**********************
        pagination
    **********************/
    div.wjportal-pagination-wrp {background: #fafafa;border: 1px solid ".esc_attr($color5).";}
    div.wjportal-pagination-wrp span.page-numbers {border: 1px solid ".esc_attr($color5).";color:  ".esc_attr($color3).";background: ".esc_attr($color7).";}
    div.wjportal-pagination-wrp span.page-numbers.current {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-pagination-wrp span.page-numbers.current:hover {background: ".esc_attr($color2).";}
    div.wjportal-pagination-wrp a.page-numbers {border: 1px solid ".esc_attr($color5).";color:  ".esc_attr($color3).";background: ".esc_attr($color7).";}
    div.wjportal-pagination-wrp a.page-numbers:hover {color: ".esc_attr($color1).";}
    div.wjportal-pagination-wrp a.page-numbers.next {background: ".esc_attr($color2).";color: ".esc_attr($color7).";}
    div.wjportal-pagination-wrp a.page-numbers.next:hover {background: ".esc_attr($color1).";}
    div.wjportal-pagination-wrp a.page-numbers.prev {background: ".esc_attr($color2).";color: ".esc_attr($color7).";}
    div.wjportal-pagination-wrp a.page-numbers.prev:hover {background: ".esc_attr($color1).";}

    /**********************
        error messages
    ************************/
    div.wjportal-error-messages-wrp {background: ".esc_attr($color7).";}
    div.wjportal-error-messages-wrp div.wjportal-error-msg-txt {color: #a5a7a9;}
    div.wjportal-error-messages-wrp div.wjportal-error-msg-txt2 {color: #afb0b2;}
    div.wjportal-error-messages-wrp div.wjportal-error-msg-actions-wrp .wjportal-error-msg-act-btn {border: 1px solid ;}
    div.wjportal-error-messages-wrp div.wjportal-error-msg-actions-wrp .wjportal-error-msg-act-login-btn {background: ".esc_attr($color1).";color: ".esc_attr($color7).";border-color: ".esc_attr($color1).";}
    div.wjportal-error-messages-wrp div.wjportal-error-msg-actions-wrp .wjportal-error-msg-act-login-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-error-messages-wrp div.wjportal-error-msg-actions-wrp .wjportal-error-msg-act-register-btn {background: ".esc_attr($color2).";color: ".esc_attr($color7).";border-color: ".esc_attr($color2).";}
    div.wjportal-error-messages-wrp div.wjportal-error-msg-actions-wrp .wjportal-error-msg-act-register-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color2).";}

    /**********************
        company list
    **********************/
    div.wjportal-company-list {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color4).";background: ".esc_attr($color7).";}
    div.wjportal-company-list:hover {background: #fafafa;}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-middle-wrp div.wjportal-company-data span.wjportal-companyname {color: ".esc_attr($color2).";}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-middle-wrp div.wjportal-company-data span.wjportal-company-title a {color: ".esc_attr($color1).";}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-middle-wrp div.wjportal-company-data span.wjportal-company-title a:hover {color: ".esc_attr($color2).";}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-middle-wrp div.wjportal-company-data div.wjportal-company-data-text {color: ".esc_attr($color3).";}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-middle-wrp div.wjportal-company-data div.wjportal-company-data-text span.wjportal-company-data-title {color: ".esc_attr($color3).";}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-middle-wrp div.wjportal-company-data div.wjportal-company-data-text span.wjportal-company-data-value {color: ".esc_attr($color2).";}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-right-wrp div.wjportal-company-action .wjportal-company-act-btn {color: ".esc_attr($color1).";background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";}
    div.wjportal-company-list div.wjportal-company-list-top-wrp div.wjportal-company-cnt-wrp div.wjportal-company-right-wrp div.wjportal-company-action .wjportal-company-act-btn:hover {color: ".esc_attr($color7).";background: ".esc_attr($color1).";}
    div.wjportal-company-list div.wjportal-company-list-btm-wrp {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-company-list div.wjportal-company-list-btm-wrp div.wjportal-company-action-wrp .wjportal-company-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-company-list div.wjportal-company-list-btm-wrp div.wjportal-company-action-wrp .wjportal-company-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        company detail
    **********************/
    div.wjportal-companydetail-wrapper div.wjportal-company-desc {color: ".esc_attr($color3).";}
    div.wjportal-companydetail-wrapper div.wjportal-companyinfo-wrp div.wjportal-companyinfo .wjportal-companyinfo-link {color: ".esc_attr($color1).";}
    div.wjportal-companydetail-wrapper div.wjportal-companyinfo-wrp div.wjportal-companyinfo .wjportal-companyinfo-link:hover {color: ".esc_attr($color2).";}
    div.wjportal-companydetail-wrapper div.wjportal-companyinfo-wrp div.wjportal-companyinfo span.wjportal-companyinfo-data {color: ".esc_attr($color3).";}
    div.wjportal-companydetail-wrapper div.wjportal-companyinfo-wrp div.wjportal-companyinfo span.wjportal-comp-status {color: ".esc_attr($color7).";background: ".esc_attr($color1).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-wrp div.wjportal-company-logo {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-data-wrp div.wjportal-company-sec-title {color: ".esc_attr($color2).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-data-wrp div.wjportal-company-data {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-data-wrp div.wjportal-company-data span.wjportal-company-data-tit {color: ".esc_attr($color3).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-data-wrp div.wjportal-company-data span.wjportal-company-data-val {color: ".esc_attr($color2).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-data-wrp div.wjportal-custom-field {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-btn-wrp .wjportal-company-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-companydetail-wrapper div.wjportal-company-btn-wrp .wjportal-company-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        filter search
    **********************/
    div.wjportal-filter-search-main-wrp {background: #fafafa;border: 1px solid ".esc_attr($color5).";}
    div.wjportal-filter-search-main-wrp div.wjportal-filter-search-wrp div.wjportal-filter-search-field-wrp .wjportal-filter-search-input-field {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color3).";}
    div.wjportal-filter-search-main-wrp div.wjportal-filter-search-wrp div.wjportal-filter-search-field-wrp ul.wpjobportal-input-list-wpjobportal {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color3).";}
    div.wjportal-filter-search-main-wrp div.wjportal-filter-search-wrp div.wjportal-filter-search-btn-wrp .wjportal-filter-search-btn {background: ".esc_attr($color7).";color: ".esc_attr($color1).";border: 1px solid ".esc_attr($color1).";}
    div.wjportal-filter-search-main-wrp div.wjportal-filter-search-wrp div.wjportal-filter-search-btn-wrp .wjportal-filter-search-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-filter-search-main-wrp div.wjportal-filter-search-wrp div.wjportal-filter-search-btn-wrp .wjportal-filter-reset-btn {background: ".esc_attr($color7).";color: ".esc_attr($color3).";border: 1px solid ".esc_attr($color5).";}
    div.wjportal-filter-search-main-wrp div.wjportal-filter-search-wrp div.wjportal-filter-search-btn-wrp .wjportal-filter-reset-btn:hover {background: #5b5b5b;color: ".esc_attr($color7).";}

    /**********************
        jobs list
    **********************/
    div.wjportal-jobs-list {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color4).";background: ".esc_attr($color7).";}
    div.wjportal-jobs-list:hover {background: #fafafa;}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname:hover {color: ".esc_attr($color1).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title a {color: ".esc_attr($color1).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title a:hover {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text {color: ".esc_attr($color3).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-shortlist-job-comments span.wjportal-shortlist-job-comment-tit {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-shortlist-job-comments span.wjportal-shortlist-job-comment-val {color: ".esc_attr($color3).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info {color: ".esc_attr($color3).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type {color: ".esc_attr($color7).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp {background: #fafafa;border-top: 1px solid ".esc_attr($color5).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-list-resume-wrp div.wjportal-jobs-list-resume-data span.wjportal-jobs-list-resume-tit {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-list-resume-wrp div.wjportal-jobs-list-resume-data span.wjportal-jobs-list-resume-val {color: ".esc_attr($color3).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-list-resume-wrp span.wjportal-applied-job-resume-status {color: ".esc_attr($color7).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-act-btn {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color1).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";border-color: ".esc_attr($color1).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res:hover {background: ".esc_attr($color2).";color: ".esc_attr($color7).";border-color: ".esc_attr($color2).";}

    /**********************
        view job detail
    **********************/
    div.wjportal-jobdetail-wrapper div.wjportal-jobinfo-wrp div.wjportal-jobinfo span.wjportal-jobtype {color: ".esc_attr($color7).";}
    div.wjportal-jobdetail-wrapper div.wjportal-jobinfo-wrp div.wjportal-jobinfo span.wjportal-jobinfo-data {color: ".esc_attr($color3).";}
    div.wjportal-jobdetail-wrapper div.wjportal-jobinfo-wrp div.wjportal-jobinfo .wjportal-salary-type {color: ".esc_attr($color3).";}
    div.wjportal-jobdetail-wrapper div.wjportal-jobinfo-wrp div.wjportal-jobinfo span.wjportal-job-close-date {color: #f58634;}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-logo {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color5).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-cnt div.wjportal-job-company-info a.wjportal-job-company-name {color: ".esc_attr($color1).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-cnt div.wjportal-job-company-info span.wjportal-job-company-info-tit {color: ".esc_attr($color3).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-cnt div.wjportal-job-company-info span.wjportal-job-company-info-val {color: ".esc_attr($color2).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-btn-wrp .wjportal-job-company-apply-status {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-btn-wrp .wjportal-job-company-btn {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-btn-wrp .wjportal-job-company-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-btn-wrp .wjportal-job-jobapply-btn {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-btn-wrp .wjportal-job-jobapply-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp .wjportal-job-act-btn {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp .wjportal-job-act-btn:hover {color: ".esc_attr($color7).";background: ".esc_attr($color1).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-sec-title {color: ".esc_attr($color2).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-desc {color: ".esc_attr($color3).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-data-wrp div.wjportal-custom-field {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-data-wrp div.wjportal-job-data {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-data-wrp div.wjportal-job-data span.wjportal-job-data-tit {color: ".esc_attr($color3).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-data-wrp div.wjportal-job-data span.wjportal-job-data-val {color: ".esc_attr($color2).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-btn-wrp .wjportal-job-act-btn {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";}
    div.wjportal-jobdetail-wrapper div.wjportal-job-btn-wrp .wjportal-job-act-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}

    /**********************
        social share
    **********************/
    div.wjportal-social-share-wrp div.wjportal-social-share-sec-title {color: ".esc_attr($color2).";}

    /**********************
        tags
    **********************/
    div.wjportal-tags-wrp div.wjportal-tags-sec-title {color: ".esc_attr($color2).";}
    div.wjportal-tags-wrp .wjportal-tags-item {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color2).";}
    div.wjportal-tags-wrp .wjportal-tags-item:hover {background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-tags-wrp .wpjobportal_tags_a.wjportal-tags {border: 1px solid ".esc_attr($color5).";}

    /**********************
        facebook comment
    **********************/
    div.wjportal-fb-comments-wrp div.wjportal-fb-comments-heading {color: ".esc_attr($color2).";}

    /**********************
        save search
    **********************/
    div.wjportal-save-search-list {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-save-search-list div.wjportal-save-search-title {color: ".esc_attr($color2).";}
    div.wjportal-save-search-list div.wjportal-save-search-created span.wjportal-save-search-created-text {color: ".esc_attr($color3).";}
    div.wjportal-save-search-list div.wjportal-save-search-action-wrp .wjportal-save-search-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-save-search-list div.wjportal-save-search-action-wrp .wjportal-save-search-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        all forms layout
    **********************/
    div.wjportal-main-wrapper div.wjportal-job-sec-heading {color: ".esc_attr($color2).";}
    div.wjportal-form-wrp div.wjportal-form-sec-heading{color: ".esc_attr($color2).";}
    div.wjportal-form-wrp div.wjportal-form-row {border-top: 1px solid ".esc_attr($color5).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-title {color: ".esc_attr($color2).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value {color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value input.wjportal-form-input-field {color: ".esc_attr($color3).";border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value select.wjportal-form-select-field {color: ".esc_attr($color3).";border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value textarea {color: ".esc_attr($color3).";border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-upload div.wjportal-form-upload-btn-wrp {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-upload div.wjportal-form-upload-btn-wrp .wjportal-form-upload-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-upload div.wjportal-form-upload-btn-wrp .wjportal-form-upload-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value .wjportal-form-map-link {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value .wjportal-form-map-link:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value input.wjportal-form-date-field {color: ".esc_attr($color3).";border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-help-txt {color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-text {color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value ul.wpjobportal-input-list-wpjobportal {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value ul.wpjobportal-input-list-wpjobportal li.wpjobportal-input-token-wpjobportal {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value .chosen-container .chosen-choices {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value .chosen-container .chosen-drop {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-email-field-wrp div.wjportal-form-email-field {color: ".esc_attr($color2).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-email-field-wrp div.wjportal-form-email-field-txt {color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value .wjportal-form-add-comp {color: ".esc_attr($color1).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value label.wjportal-input-box-switch span.wjportal-input-box-slider {background: #ccc;}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value label.wjportal-input-box-switch span.wjportal-input-box-slider:before {background: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value label.wjportal-input-box-switch input:checked + span.wjportal-input-box-slider {background-color: ".esc_attr($color1).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value label.wjportal-input-box-switch input:focus + span.wjportal-input-box-slider {box-shadow: 0 0 1px ".esc_attr($color1).";}
    div.wjportal-form-wrp div.wjportal-form-row div.wjportal-form-value div.wjportal-form-5-fields div.wjportal-form-symbol-fields span.wjportal-form-symbol {border: 1px solid ".esc_attr($color5).";}
    div.wjportal-form-wrp div.wpjobportal-terms-and-conditions-wrap {color: ".esc_attr($color3).";}
    div.wjportal-form-wrp div.wjportal-terms-and-conditions-wrap {color: ".esc_attr($color3).";}
    /* div.wjportal-form-wrp div.wjportal-form-btn-wrp {border-top: 1px solid ".esc_attr($color5).";} */
    div.wjportal-form-wrp div.wjportal-form-btn-wrp .wjportal-form-btn {color: ".esc_attr($color7)."!important;background: ".esc_attr($color1)."!important;border: 1px solid ".esc_attr($color1)."!important;}
    div.wjportal-form-wrp div.wjportal-form-btn-wrp .wjportal-form-btn:hover {color: ".esc_attr($color1)."!important;background: ".esc_attr($color7)."!important;}
    div.wjportal-form-wrp div.wjportal-form-btn-wrp .wjportal-form-save-btn {color: ".esc_attr($color7).";background: ".esc_attr($color1).";border: 1px solid ".esc_attr($color1).";}
    div.wjportal-form-wrp div.wjportal-form-btn-wrp .wjportal-form-save-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div.wjportal-form-wrp div.wjportal-form-btn-wrp .wjportal-form-cancel-btn {color: ".esc_attr($color2).";background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color2).";}
    div.wjportal-form-wrp div.wjportal-form-btn-wrp .wjportal-form-cancel-btn:hover {color: ".esc_attr($color7).";background: ".esc_attr($color2).";}

    /**********************
        login form
    **********************/
    div.wjportal-form-wrp form p.login-username,
    div.wjportal-form-wrp form p.login-password,
    div.wjportal-form-wrp form p.login-remember {border-top: 1px solid ".esc_attr($color5).";}
    div.wjportal-form-wrp form p.login-username label,
    div.wjportal-form-wrp form p.login-password label,
    div.wjportal-form-wrp form p.login-remember label {color: ".esc_attr($color2).";}
    div.wjportal-form-wrp form p.login-username input,
    div.wjportal-form-wrp form p.login-password input {color: ".esc_attr($color3).";border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-form-wrp form p.login-submit #wp-submit {color: ".esc_attr($color7).";background: ".esc_attr($color1).";border: 1px solid ".esc_attr($color1).";}
    div.wjportal-form-wrp form p.login-submit #wp-submit:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div.wjportal-form-wrp form a.wjportal-form-lost-password {color: ".esc_attr($color2).";}
    div.wjportal-form-wrp form a.wjportal-form-lost-password:hover {color: ".esc_attr($color1).";}
    div.wjportal-form-wrp a.wjportal-form-reg-btn {color: ".esc_attr($color2).";}
    div.wjportal-form-wrp a.wjportal-form-reg-btn:hover {color: ".esc_attr($color1).";}

    /**********************
        save search form
    **********************/
    div.wjportal-save-search-form-wrp {border: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-save-search-form-wrp form label.wjportal-save-search-label {color: ".esc_attr($color2).";}
    div.wjportal-save-search-form-wrp form input.wjportal-save-search-input-field {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-save-search-form-wrp form .wjportal-save-search-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1)." !important;}
    div.wjportal-save-search-form-wrp form .wjportal-save-search-btn:hover {background: ".esc_attr($color1)." !important;color: ".esc_attr($color7)."!important;}

    /**********************
        social login form
    **********************/
    div.wjportal-social-login-wrp div.wjportal-sec-seprator div.wjportal-sec-seprator-text {color: ".esc_attr($color2).";}
    div.wjportal-social-login-wrp div.wjportal-sec-seprator div.wjportal-sec-seprator-text::before {background: ".esc_attr($color5).";}
    div.wjportal-social-login-wrp div.wjportal-sec-seprator div.wjportal-sec-seprator-text::after {background: #fafafa;}
    div.wjportal-social-login-wrp div.wjportal-social-login div.wjportal-social-login-title {color: ".esc_attr($color2).";}
    div.wjportal-social-login-wrp div.wjportal-social-login div.wjportal-social-login-items .wjportal-social-login-btn {color: ".esc_attr($color3).";border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-social-login-wrp div.wjportal-social-login div.wjportal-social-login-items .wjportal-social-login-btn:hover {color: ".esc_attr($color1).";border-color: ".esc_attr($color1).";}


    /***********************
        department list
    **********************/
    div.wjportal-department-list {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-department-list div.wjportal-department-list-top-wrp div.wjportal-department-cnt-wrp div.wjportal-department-middle-wrp div.wjportal-department-data div.wjportal-department-title {color: ".esc_attr($color2).";}
    div.wjportal-department-list div.wjportal-department-list-top-wrp div.wjportal-department-cnt-wrp div.wjportal-department-middle-wrp div.wjportal-department-data div.wjportal-department-info-data a.wjportal-companyname {color: ".esc_attr($color1).";}
    div.wjportal-department-list div.wjportal-department-list-top-wrp div.wjportal-department-cnt-wrp div.wjportal-department-middle-wrp div.wjportal-department-data div.wjportal-department-info-data span.wjportal-department-info-tit {color: ".esc_attr($color3).";}
    div.wjportal-department-list div.wjportal-department-list-top-wrp div.wjportal-department-cnt-wrp div.wjportal-department-middle-wrp div.wjportal-department-data div.wjportal-department-info-data span.wjportal-department-info-val {color: ".esc_attr($color2).";}
    div.wjportal-department-list div.wjportal-department-list-top-wrp div.wjportal-department-cnt-wrp div.wjportal-department-middle-wrp div.wjportal-department-data div.wjportal-department-desc {color: ".esc_attr($color3).";}
    div.wjportal-department-list div.wjportal-department-list-top-wrp div.wjportal-department-cnt-wrp div.wjportal-department-right-wrp div.wjportal-department-info span.wjportal-department-status {color: ".esc_attr($color7).";}
    div.wjportal-department-list div.wjportal-department-list-btm-wrp {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-department-list div.wjportal-department-list-btm-wrp div.wjportal-department-action-wrp a.wjportal-department-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-department-list div.wjportal-department-list-btm-wrp div.wjportal-department-action-wrp a.wjportal-department-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        department detail
    **********************/
    div.wjportal-departmentdetail-wrapper div.wjportal-department-data-wrp div.wjportal-department-sec-title {color: ".esc_attr($color2).";}
    div.wjportal-departmentdetail-wrapper div.wjportal-department-data-wrp div.wjportal-department-data a.wjportal-companyname {color: ".esc_attr($color1).";}
    div.wjportal-departmentdetail-wrapper div.wjportal-department-data-wrp div.wjportal-department-data span.wjportal-department-data-tit {color: ".esc_attr($color3).";}
    div.wjportal-departmentdetail-wrapper div.wjportal-department-data-wrp div.wjportal-department-data span.wjportal-department-data-val {color: ".esc_attr($color2).";}
    div.wjportal-departmentdetail-wrapper div.wjportal-department-data-wrp div.wjportal-department-desc {color: ".esc_attr($color3).";}


    /**********************
        folder list
    **********************/
    div.wjportal-folder-list {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-folder-list div.wjportal-folder-title {color: ".esc_attr($color2).";}
    div.wjportal-folder-list div.wjportal-folder-status span.wjportal-folder-status-text {color: ".esc_attr($color7).";}
    div.wjportal-folder-list div.wjportal-folder-action-wrp .wjportal-folder-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-folder-list div.wjportal-folder-action-wrp .wjportal-folder-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        folder detail
    **********************/
    div.wjportal-folderdetail-wrapper div.wjportal-folder-data-wrp div.wjportal-folder-sec-title {color: ".esc_attr($color2).";}
    div.wjportal-folderdetail-wrapper div.wjportal-folder-data-wrp div.wjportal-folder-desc {color: ".esc_attr($color3).";}

    /**********************
        messages list
    **********************/
    div.wjportal-messages-list-wrapper div.wjportal-messages-list {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-messages-list-wrapper div.wjportal-messages-list div.wjportal-msg-list-top div.wjportal-msg-name {color: ".esc_attr($color2).";}
    div.wjportal-messages-list-wrapper div.wjportal-messages-list div.wjportal-msg-list-btm {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-messages-list-wrapper div.wjportal-messages-list div.wjportal-msg-list-btm div.wjportal-msg-info div.wjportal-msg-info-data span.wjportal-msg-info-data-tit {color: ".esc_attr($color2).";}
    div.wjportal-messages-list-wrapper div.wjportal-messages-list div.wjportal-msg-list-btm div.wjportal-msg-info div.wjportal-msg-info-data span.wjportal-msg-info-data-val {color: ".esc_attr($color3).";}
    div.wjportal-messages-list-wrapper div.wjportal-messages-list div.wjportal-msg-list-btm div.wjportal-msg-action-wrp .wjportal-msg-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-messages-list-wrapper div.wjportal-messages-list div.wjportal-msg-list-btm div.wjportal-msg-action-wrp .wjportal-msg-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        send messages
    **********************/
    div.wjportal-send-message-wrapper div.wjportal-send-message {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-send-message-wrapper div.wjportal-send-message div.wjportal-send-msg-subject {color: ".esc_attr($color2).";}
    div.wjportal-send-message-wrapper div.wjportal-send-message div.wjportal-send-msg-text {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color3).";}
    div.wjportal-send-message-wrapper div.wjportal-send-message-form div.wjportal-form-row div.wjportal-form-title {color: ".esc_attr($color2).";}
    div.wjportal-send-message-wrapper div.wjportal-send-message-form div.wjportal-form-row div.wjportal-form-value {color: ".esc_attr($color3).";}
    div.wjportal-send-message-wrapper div.wjportal-send-message-form div.wjportal-form-btn-wrp .wjportal-form-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-send-message-wrapper div.wjportal-send-message-form div.wjportal-form-btn-wrp .wjportal-form-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-send-message-wrapper div.wjportal-message-history-wrp div.wjportal-msg-history {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-send-message-wrapper div.wjportal-message-history-wrp div.wjportal-msg-history div.wjportal-msg-history-cnt div.wjportal-msg-history-top span.wjportal-msg-history-name {color: ".esc_attr($color1).";}
    div.wjportal-send-message-wrapper div.wjportal-message-history-wrp div.wjportal-msg-history div.wjportal-msg-history-cnt div.wjportal-msg-history-top span.wjportal-msg-history-created {color: ".esc_attr($color2).";}
    div.wjportal-send-message-wrapper div.wjportal-message-history-wrp div.wjportal-msg-history div.wjportal-msg-history-cnt div.wjportal-msg-history-top span.wjportal-msg-history-status {color: ".esc_attr($color7).";}
    div.wjportal-send-message-wrapper div.wjportal-message-history-wrp div.wjportal-msg-history div.wjportal-msg-history-cnt div.wjportal-msg-history-top span.wjportal-msg-history-status.pending {background: #f58634;}
    div.wjportal-send-message-wrapper div.wjportal-message-history-wrp div.wjportal-msg-history div.wjportal-msg-history-cnt div.wjportal-msg-history-top span.wjportal-msg-history-status.rejected {background: #ed3237;}
    div.wjportal-send-message-wrapper div.wjportal-message-history-wrp div.wjportal-msg-history div.wjportal-msg-history-cnt div.wjportal-msg-history-text {color: ".esc_attr($color3).";}




    /***********************
        coverletter list
    **********************/
    div.wjportal-coverletter-list {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-top-wrp div.wjportal-coverletter-cnt-wrp div.wjportal-coverletter-middle-wrp div.wjportal-coverletter-data div.wjportal-coverletter-title {color: ".esc_attr($color2).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-top-wrp div.wjportal-coverletter-cnt-wrp div.wjportal-coverletter-middle-wrp div.wjportal-coverletter-data div.wjportal-coverletter-info-data a.wjportal-companyname {color: ".esc_attr($color1).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-top-wrp div.wjportal-coverletter-cnt-wrp div.wjportal-coverletter-middle-wrp div.wjportal-coverletter-data div.wjportal-coverletter-info-data span.wjportal-coverletter-info-tit {color: ".esc_attr($color3).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-top-wrp div.wjportal-coverletter-cnt-wrp div.wjportal-coverletter-middle-wrp div.wjportal-coverletter-data div.wjportal-coverletter-info-data span.wjportal-coverletter-info-val {color: ".esc_attr($color2).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-top-wrp div.wjportal-coverletter-cnt-wrp div.wjportal-coverletter-middle-wrp div.wjportal-coverletter-data div.wjportal-coverletter-desc {color: ".esc_attr($color3).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-top-wrp div.wjportal-coverletter-cnt-wrp div.wjportal-coverletter-right-wrp div.wjportal-coverletter-info span.wjportal-coverletter-status {color: ".esc_attr($color7).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-btm-wrp {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-btm-wrp div.wjportal-coverletter-action-wrp a.wjportal-coverletter-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-coverletter-list div.wjportal-coverletter-list-btm-wrp div.wjportal-coverletter-action-wrp a.wjportal-coverletter-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        coverletter detail
    **********************/
    div.wjportal-coverletterdetail-wrapper div.wjportal-coverletter-data-wrp div.wjportal-coverletter-sec-title {color: ".esc_attr($color2).";}
    div.wjportal-coverletterdetail-wrapper div.wjportal-coverletter-data-wrp div.wjportal-coverletter-data a.wjportal-companyname {color: ".esc_attr($color1).";}
    div.wjportal-coverletterdetail-wrapper div.wjportal-coverletter-data-wrp div.wjportal-coverletter-data span.wjportal-coverletter-data-tit {color: ".esc_attr($color3).";}
    div.wjportal-coverletterdetail-wrapper div.wjportal-coverletter-data-wrp div.wjportal-coverletter-data span.wjportal-coverletter-data-val {color: ".esc_attr($color2).";}
    div.wjportal-coverletterdetail-wrapper div.wjportal-coverletter-data-wrp div.wjportal-coverletter-desc {color: ".esc_attr($color3).";}


        /**********************
        ai search form
    **********************/
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp .wjportal-ai-searchfrm-logo-wrp{background-color: ".esc_attr($color1).";}
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp .wjportal-aifilter-search-wrp .wjportal-filter-search-btn-wrp .wjportal-filter-search-btn{background-color: ".esc_attr($color1).";color:#fff;}
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp .wjportal-aifilter-search-wrp .wjportal-filter-search-btn-wrp .wjportal-filter-search-btn:hover{background-color: ".esc_attr($color2).";color:#fff;border-color:".esc_attr($color2).";}
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp{border-color:".esc_attr($color5).";}
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp .wjportal-aifilter-search-wrp .wjportal-filter-search-field-wrp{border-color:".esc_attr($color5).";}
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp .wjportal-aifilter-search-wrp .wjportal-filter-search-btn-wrp{border-color:".esc_attr($color5).";}
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp .wjportal-aifilter-search-wrp .wjportal-filter-ai-searchfrm-discription{color:".esc_attr($color3).";}
    div.wjportal-main-up-wrapper .wjportal-filter-ai-searchfrm-wrp .wjportal-aifilter-search-wrp .wjportal-filter-ai-searchfrm-title{color:".esc_attr($color2).";}



";
$result1 = "

    /**********************
        jobs by catagory
        resume by catagory
    **********************/
    div.wjportal-by-categories-main-wrp div.wjportal-by-category-wrp div.wjportal-by-category-item {border: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-by-categories-main-wrp div.wjportal-by-category-wrp div.wjportal-by-category-item:hover {background: ".esc_attr($color7).";}
    div.wjportal-by-categories-main-wrp div.wjportal-by-category-wrp div.wjportal-by-category-item span.wjportal-by-category-item-title {color: ".esc_attr($color3).";}
    div.wjportal-by-categories-main-wrp div.wjportal-by-category-wrp div.wjportal-by-category-item span.wjportal-by-category-item-number {color: ".esc_attr($color2).";}
    div.wjportal-by-categories-main-wrp div.wjportal-by-category-wrp div.wjportal-by-sub-catagory {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-by-categories-main-wrp div.wjportal-by-category-wrp div.wjportal-by-category-item-btn-wrp a.wjportal-by-category-item-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-by-categories-main-wrp div.wjportal-by-category-wrp div.wjportal-by-category-item-btn-wrp a.wjportal-by-category-item-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}

    /**********************
        all popups
    **********************/
    div.wjportal-popup-wrp {background: ".esc_attr($color7).";box-shadow: 0px 0px 30px #b0adad;}
    div.wjportal-payemt-popup {background: #fafafa !important;}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-title {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-title span.wjportal-popup-title2 {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-title div.wjportal-popup-title3 {color: ".esc_attr($color3).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-contentarea div.wjportal-by-sub-catagory div.wjportal-by-category-wrp div.wjportal-by-category-item {border: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-contentarea div.wjportal-by-sub-catagory div.wjportal-by-category-wrp div.wjportal-by-category-item:hover {background: ".esc_attr($color7).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-contentarea div.wjportal-by-sub-catagory div.wjportal-by-category-wrp div.wjportal-by-category-item span.wjportal-by-category-item-title {color: ".esc_attr($color3).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-contentarea div.wjportal-by-sub-catagory div.wjportal-by-category-wrp div.wjportal-by-category-item span.wjportal-by-category-item-number {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-contentarea ul.wjportal-popup-navigation li {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-field-wrp div.wjportal-popup-field label {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-field-wrp div.wjportal-popup-field input[type='text'] {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color3)."}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-field-wrp div.wjportal-popup-field textarea {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color3)."}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-popup-field-wrp div.wjportal-popup-field select {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3)."}
    div.wjportal-popup-wrp div.wjportal-visitor-msg-wrp {color: ".esc_attr($color3).";background: #fefed8;border: 1px solid #f8e69c;}
    div.wjportal-popup-wrp div.wjportal-visitor-msg-btn-wrp .wjportal-visitor-msg-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-popup-wrp div.wjportal-visitor-msg-btn-wrp .wjportal-visitor-msg-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-popup-wrp div.wjportal-visitor-msg-btn-wrp .wjportal-visitor-msg-btn.login {border-color: ".esc_attr($color2).";background: ".esc_attr($color7).";color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-visitor-msg-btn-wrp .wjportal-visitor-msg-btn.login:hover {background: ".esc_attr($color2).";color: ".esc_attr($color7).";}
    div.wjportal-popup-wrp div.wjportal-visitor-msg-btn-wrp .wjportal-visitor-msg-close-btn {border: 1px solid ".esc_attr($color2).";background: ".esc_attr($color7).";color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-visitor-msg-btn-wrp .wjportal-visitor-msg-close-btn:hover {background: ".esc_attr($color2).";color: ".esc_attr($color7).";}
    #notification-ok {border: 1px solid #72ba60;background: #d1ffc6;}
    #notification-ok button {background: transparent;border: 0;padding: 0;}
    #notification-ok button.applynow-closebutton {color: ".esc_attr($color4).";}
    #notification-not-ok {border: 1px solid #ff0000;background: #ffd9d9;}
    #notification-not-ok button {background: transparent;border: 0;}

    /**********************
        select packages popup//
    **********************/
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item.wjportal-pkg-selected {border-color: ".esc_attr($color1).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-top {border-bottom: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item.wjportal-pkg-selected div.wjportal-pkg-item-top {border-color: ".esc_attr($color1).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-top div.wjportal-pkg-item-title {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-btm div.wjportal-pkg-item-row {border-top: 1px solid ".esc_attr($color5).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-btm div.wjportal-pkg-item-row:first-child {border-top: 0px;}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-btm div.wjportal-pkg-item-row span.wjportal-pkg-item-tit {color: ".esc_attr($color3).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-btm div.wjportal-pkg-item-row span.wjportal-pkg-item-val {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-btm div.wjportal-pkg-item-btn-row .wjportal-pkg-item-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item div.wjportal-pkg-item-btm div.wjportal-pkg-item-btn-row .wjportal-pkg-item-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-popup-wrp div.wjportal-packages-wrp div.wjportal-pkg-item.wjportal-pkg-selected div.wjportal-pkg-item-btm div.wjportal-pkg-item-btn-row .wjportal-pkg-item-btn {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        payemet methods popup
    **********************/
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-payemt-methods-wrp div.wjportal-payemt-method-desc div.wjportal-payemt-method-desc-data label {color: ".esc_attr($color2).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-payemt-methods-wrp div.wjportal-payemt-method-desc div.wjportal-payemt-method-desc-data span.wjportal-payemt-method-desc-txt {color: ".esc_attr($color3).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-payemt-methods-wrp div.wjportal-payemt-method-desc form button span {background: ".esc_attr($color1).";border: 1px solid ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-payemt-methods-wrp div.wjportal-payemt-method-desc form button span:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-payemt-methods-wrp div.wjportal-payemt-method-desc .wjportal-payemt-method-desc-btn {background: ".esc_attr($color1).";border: 1px solid ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-popup-wrp div.wjportal-popup-cnt div.wjportal-payemt-methods-wrp div.wjportal-payemt-method-desc .wjportal-payemt-method-desc-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}

    /**********************
        resume list
    **********************/
    div.wjportal-resume-list {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp:hover {background: #fafafa;}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-logo {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-middle-wrp div.wjportal-resume-data span.wjportal-resume-job-type {color: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-middle-wrp div.wjportal-resume-data span.wjportal-resume-name {color: ".esc_attr($color2).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-middle-wrp div.wjportal-resume-data span.wjportal-resume-title {color: ".esc_attr($color1).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-middle-wrp div.wjportal-resume-data div.wjportal-resume-data-text {color: ".esc_attr($color3).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-middle-wrp div.wjportal-resume-data div.wjportal-resume-data-text span.wjportal-resume-data-title {color: ".esc_attr($color3).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-middle-wrp div.wjportal-resume-data div.wjportal-resume-data-text span.wjportal-resume-data-value {color: ".esc_attr($color2).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-right-wrp div.wjportal-resume-action .wjportal-resume-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-right-wrp div.wjportal-resume-action .wjportal-resume-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp {background: #fafafa;border-top: 1px solid ".esc_attr($color5).";}
    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp div.wjportal-resume-action-wrp .wjportal-resume-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp div.wjportal-resume-action-wrp .wjportal-payment-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp div.wjportal-resume-action-wrp .wjportal-resume-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp div.wjportal-resume-action-wrp .wjportal-payment-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp div.wjportal-resume-action-wrp .wjportal-resume-cancel-act-btn {border: 1px solid ".esc_attr($color2).";background: ".esc_attr($color7).";color: ".esc_attr($color2).";}
    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp div.wjportal-resume-action-wrp .wjportal-resume-cancel-act-btn:hover {background: ".esc_attr($color2).";color: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-right-wrp div.wjportal-resume-status-wrp {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-right-wrp div.wjportal-resume-status-wrp span.wjportal-resume-status-heading {color: ".esc_attr($color2).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-right-wrp div.wjportal-resume-status-wrp span.wjportal-resume-status-title {color: ".esc_attr($color3).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-right-wrp div.wjportal-resume-status-wrp div.wjportal-resume-status-counter div.js-mr-rp .circle .mask .fill {background: ".esc_attr($color1).";}
    div.wjportal-resume-list div.wjportal-resume-list-top-wrp div.wjportal-resume-cnt-wrp div.wjportal-resume-right-wrp div.wjportal-resume-status-wrp div.wjportal-resume-status-counter div.js-mr-rp .inset .percentage .numbers span {color: ".esc_attr($color1).";}

    /**********************
        resume detail
    **********************/
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-resume-image {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-personal-data div.wjportal-resume-title {color: ".esc_attr($color2).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-resume-adv-act-wrp .wjportal-resume-adv-act-btn {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-resume-adv-act-wrp .wjportal-resume-adv-act-btn:hover {border-color: ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-personal-data div.wjportal-resume-info {color: ".esc_attr($color3).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-personal-data div.wjportal-resume-info span.wjportal-jobtype {color: ".esc_attr($color7).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-personal-data div.wjportal-resume-info .wjportal-resume-download-all-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-top-section div.wjportal-personal-data div.wjportal-resume-info .wjportal-resume-download-all-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-section-title {color: ".esc_attr($color2).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-section-wrapper div.wjportal-resume-inner-sec-heading {color: ".esc_attr($color2).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-section-wrapper div.wjportal-resume-sec-row div.wjportal-resume-sec-data {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-section-wrapper div.wjportal-resume-sec-row div.wjportal-resume-sec-data div.wjportal-resume-sec-data-title {color: ".esc_attr($color3).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-section-wrapper div.wjportal-resume-sec-row div.wjportal-resume-sec-data div.wjportal-resume-sec-data-value {color: ".esc_attr($color2).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-section-wrapper div.wjportal-resume-attachments-wrp div.wjportal-resume-sec-data div.wjportal-resume-sec-data-value a {color: ".esc_attr($color1).";border: 1px solid ".esc_attr($color1).";}
    div.wjportal-resume-detail-wrapper div.wjportal-resume-section-wrapper div.wjportal-resume-attachments-wrp div.wjportal-resume-sec-data div.wjportal-resume-sec-data-value a img.wjportal-resume-attachment-file-download {background: ".esc_attr($color1).";}

";
$result2 = "

    /**********************
        resume form
    **********************/
    div.wjportal-form-wrp div.wjportal-resume-section-title {border-top: 1px solid ".esc_attr($color5).";}
    div.wjportal-form-wrp div#jsresume_sectionid1.wjportal-resume-section-title {border-top: 0;}
    div.wjportal-form-wrp div.wjportal-resume-section-wrp div.wjportal-resume-section div.wjportal-resume-section-head {background: #fafafa;color: ".esc_attr($color2).";}
    div.wjportal-form-wrp div.wjportal-resume-section-wrp div.wjportal-resume-section div.wjportal-resume-section-undo {background: rgba(219, 221, 224, 0.7);}
    div.wjportal-form-wrp div.wjportal-resume-add-new-section-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-form-wrp div.wjportal-resume-add-new-section-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wpjp-resume-form div.jsresume_addnewbutton {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color1).";}
    div.wpjp-resume-form form.wpjp-form div.wpjp-form-wrapper div.wpjp-form-value textarea {background: #fafafa;}
    div.wpjp-resume-form form.wpjp-form div.wpjp-form-wrapper div.wpjp-form-value ul.wpjobportal-input-list-wpjobportal {border: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wpjobportal-wrapper form.wpjp-form div.wpjp-form-wrapper div.wpjp-form-value .wpjp-upload-btn {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wpjobportal-wrapper form.wpjp-form div.wpjp-form-wrapper div.wpjp-form-value img {border: 1px solid ".esc_attr($color5).";}
    div.wpjobportal-wrapper form.wpjp-form div.wpjp-form-wrapper div.wpjp-form-value .wpjp-upload-file {border: 1px solid ".esc_attr($color5).";}
    div.wpjp-resume-form div.wpjp-add-new-section-link {border: 1px solid ".esc_attr($color1).";color: ".esc_attr($color1).";}
    div.wpjp-resume-form div.wpjp-add-new-section-link:hover {color: ".esc_attr($color7).";background: ".esc_attr($color1).";}
    div.wpjp-resume-form div.jssectionwrapper:last-child {border: none;}
    div.wpjobportal-wrapper.wpjp-resume-form-wrp div.wpjp-resume-section-title {border-top: 1px solid ".esc_attr($color5).";}
    div.wpjobportal-wrapper.wpjp-resume-form-wrp div.wpjp-resume-form-btn-wrp {border-top: 1px solid ".esc_attr($color5).";}
    div.wpjobportal-wrapper.wpjp-resume-form-wrp div.wpjp-resume-form-btn-wrp div.btn-color {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wpjobportal-wrapper.wpjp-resume-form-wrp div.wpjp-resume-form-btn-wrp div.wpjp-resume-btn .wpjp-savebtn {border: 1px solid transparent;}
    div.wpjobportal-wrapper.wpjp-resume-form-wrp div.wpjp-resume-form-btn-wrp div.wpjp-resume-btn .wpjp-savebtn.bg-color {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wpjobportal-wrapper.wpjp-resume-form-wrp div.wpjp-resume-form-btn-wrp div.wpjp-resume-btn:hover .wpjp-savebtn {background: ".esc_attr($color7).";color: ".esc_attr($color1).";border: 1px solid ".esc_attr($color1).";}

    /**********************
        resume popup
    **********************/
    div#black_wrapper_jobapply {background: rgba(0,0,0,.8);}
    div.wpjp-resume-popup-wrp {background: ".esc_attr($color7).";border-bottom: 5px solid ".esc_attr($color1).";}
    div.wpjp-resume-popup-wrp span.close-resume-files {background: ".esc_attr($color1).";border-bottom: 5px solid ".esc_attr($color10).";color: ".esc_attr($color7).";}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span.wpjp-clickable-files {background: ".esc_attr($color6).";color: ".esc_attr($color8).";border: 1px solid ".esc_attr($color5).";}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span.wpjp-heading-popup {background: ".esc_attr($color1).";color: ".esc_attr($color6).";}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span#resume-files-selected {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color5).";}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span#resume-files-selected div.resumefileselected {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color4).";}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span#resume-files-selected div.resumefileselected.errormsg {border: 1px solid #ED3237;background: #fafafa;}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span#resume-files-selected div.resumefileselected.errormsg span.filename {color: #ED3237;}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span#resume-files-selected div.error_msg {color: #ED3237;}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span#resume-files-selected div.resumefileselected button {background: ".esc_attr($color6).";color: ".esc_attr($color8).";border: 1px solid ".esc_attr($color5).";}
    div.wpjp-resume-popup-wrp div.wpjp-resumepopup-section-wrapper span#resume-files-selected div.resumefileselected button:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wpjp-resume-popup-wrp div.wpjp-resume-filepopup-lowersection-wrapper {color: ".esc_attr($color4).";}

    /**********************
        job apllied resume
    **********************/
    div.wjportal-job-applied-resume div.wjportal-section-heading {color: ".esc_attr($color2).";}
    div.wjportal-job-applied-resume-actions {border-bottom: 3px solid ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div.wjportal-job-applied-resume-actions ul li a {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color3).";}
    div.wjportal-job-applied-resume-actions ul li a:hover {background: ".esc_attr($color7).";color: ".esc_attr($color2).";}
    div.wjportal-job-applied-resume-actions ul li a.selected {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-job-applied-resume-actions ul li a.selected:hover {background: ".esc_attr($color7)."color: ".esc_attr($color1).";}
    div.wjportal-job-applied-resume-actions .wjportal-export-all-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-job-applied-resume-actions .wjportal-export-all-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-applied-job-adv-search-wrp {border: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-applied-job-adv-search-wrp div.wjportal-applied-job-adv-search-field select.wjportal-applied-job-adv-search-select-field,
    div.wjportal-applied-job-adv-search-wrp div.wjportal-applied-job-adv-search-field input.wjportal-applied-job-adv-search-input-field {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-applied-job-adv-search-wrp div.wjportal-applied-job-adv-search-action-wrp .wjportal-applied-job-adv-search-save-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-applied-job-adv-search-wrp div.wjportal-applied-job-adv-search-action-wrp .wjportal-applied-job-adv-search-save-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-applied-job-adv-search-wrp div.wjportal-applied-job-adv-search-action-wrp .wjportal-applied-job-adv-search-cancel-btn {border: 1px solid ".esc_attr($color2).";background: ".esc_attr($color7).";color: ".esc_attr($color2).";}
    div.wjportal-applied-job-adv-search-wrp div.wjportal-applied-job-adv-search-action-wrp .wjportal-applied-job-adv-search-cancel-btn:hover {background: ".esc_attr($color2).";color: ".esc_attr($color7).";}
    div.wjportal-applied-job-actions-popup div.wjportal-applied-job-actions-wrp div.wjportal-applied-job-actions-row label {color: ".esc_attr($color2).";}
    div.wjportal-applied-job-actions-popup div.wjportal-applied-job-actions-wrp div.wjportal-applied-job-actions-row input {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-applied-job-actions-popup div.wjportal-applied-job-actions-wrp div.wjportal-applied-job-actions-row select {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-applied-job-actions-popup div.wjportal-applied-job-actions-wrp div.wjportal-applied-job-actions-row textarea {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";color: ".esc_attr($color3).";}
    div.wjportal-applied-job-actions-popup div.wjportal-job-actions-detail-wrp div.wjportal-job-actions-detail-row span.wjportal-job-actions-detail-tit {color: ".esc_attr($color3).";}
    div.wjportal-applied-job-actions-popup div.wjportal-job-actions-detail-wrp div.wjportal-job-actions-detail-row span.wjportal-job-actions-detail-val {color: ".esc_attr($color2).";}
    div.wjportal-applied-job-actions-popup div.wjportal-job-applied-actions-btn-wrp .wjportal-job-applied-actions-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-applied-job-actions-popup div.wjportal-job-applied-actions-btn-wrp .wjportal-job-applied-actions-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-applied-job-actions-popup span.wjportal-applied-job-actions-popup-norec {color: red;border: 1px solid red;background: #ffdada;}
    div.wjportal-applied-job-actions-popup span.wjportal-applied-job-actions-popup-norec .wjportal-applied-job-actions-popup-norec-link {color: ".esc_attr($color2).";}
    div.wjportal-applied-job-actions-popup span.wjportal-applied-job-actions-popup-norec .wjportal-applied-job-actions-popup-norec-link:hover {color: ".esc_attr($color1).";}

    /**********************
        employer cp
    **********************/
    div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title {border-bottom: 1px solid ".esc_attr($color5).";color: ".esc_attr($color2).";background: #fafafa;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left {border: 1px solid ".esc_attr($color5).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user {background: #fafafa;border-bottom: 1px solid ".esc_attr($color5).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-user-logo {border: 5px solid ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-user-name {color: ".esc_attr($color2).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-user-tagline {color: ".esc_attr($color3).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action {border-top: 1px solid ".esc_attr($color5).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-sec-title {background: ".esc_attr($color7).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list {border-width: 1px 1px 1px 1px;border-style: solid;border-color: transparent transparent ".esc_attr($color5)." transparent;background: #fafafa;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list:hover {background: ".esc_attr($color7).";border-color: ".esc_attr($color1).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list a.wjportal-list-anchor {color: ".esc_attr($color3).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list a.wjportal-list-anchor:hover {color: ".esc_attr($color1).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box div.wjportal-cp-box-top {color: ".esc_attr($color7).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box1 div.wjportal-cp-box-top {background: #55aa99;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box2 div.wjportal-cp-box-top {background: #a2433e;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box3 div.wjportal-cp-box-top {background: #ad7c32;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box4 div.wjportal-cp-box-top {background: #f5814f;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box div.wjportal-cp-box-btm span.wjportal-cp-box-text {color: ".esc_attr($color3).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box1 div.wjportal-cp-box-btm a {color: #55aa99;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box2 div.wjportal-cp-box-btm a {color: #a2433e;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box3 div.wjportal-cp-box-btm a {color: #ad7c32;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box4 div.wjportal-cp-box-btm a {color: #f5814f;}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-sect-wrp {border: 1px solid ".esc_attr($color5).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp {border-top: 1px solid ".esc_attr($color5).";}
    div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

";
$result3 = "

    /**********************
        jobseeker cp
    **********************/
    div#wjportal-job-cp-wrp div.wjportal-cp-sec-title {border-bottom: 1px solid ".esc_attr($color5).";color: ".esc_attr($color2).";background: #fafafa;}
    div#wjportal-job-cp-wrp div.wjportal-cp-left {border: 1px solid ".esc_attr($color5).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user {background: #fafafa;border-bottom: 1px solid ".esc_attr($color5).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-user-logo {border: 5px solid ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-user-name {color: ".esc_attr($color2).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-user-tagline {color: ".esc_attr($color3).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action  {border-top: 1px solid ".esc_attr($color5).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-sec-title {background: ".esc_attr($color7).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list {border-width: 1px 1px 1px 1px;border-style: solid;border-color: transparent transparent ".esc_attr($color5)." transparent;background: #fafafa;}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list:hover {background: ".esc_attr($color7).";border-color: ".esc_attr($color1).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list a.wjportal-list-anchor {color: ".esc_attr($color3).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list a.wjportal-list-anchor:hover {color: ".esc_attr($color1).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box div.wjportal-cp-box-top {color: ".esc_attr($color7).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box1 div.wjportal-cp-box-top {background: #55aa99;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box2 div.wjportal-cp-box-top {background: #a2433e;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box3 div.wjportal-cp-box-top {background: #ad7c32;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box4 div.wjportal-cp-box-top {background: #31559c;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box div.wjportal-cp-box-btm span.wjportal-cp-box-text {color: ".esc_attr($color3).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box1 div.wjportal-cp-box-btm a {color: #55aa99;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box2 div.wjportal-cp-box-btm a {color: #a2433e;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box3 div.wjportal-cp-box-btm a {color: #ad7c32;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-boxes div.wjportal-cp-box.box4 div.wjportal-cp-box-btm a {color: #31559c;}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-sect-wrp {border: 1px solid ".esc_attr($color5).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp {border-top: 1px solid ".esc_attr($color5).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn {background: ".esc_attr($color1).";color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";}
    div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color1).";}

    /**********************
        custom fields
    **********************/
    div.wjportal-custom-field span.wjportal-custom-field-tit {color: ".esc_attr($color3).";}
    div.wjportal-custom-field span.wjportal-custom-field-val {color: ".esc_attr($color2).";}

    /**********************
        tables
    **********************/
    table#wjportal-table {border: 1px solid ".esc_attr($color5).";}
    table#wjportal-table thead tr {background: #fafafa;color: ".esc_attr($color7).";border-bottom: 1px solid ".esc_attr($color5).";}
    table#wjportal-table tbody {background: ".esc_attr($color7).";}
    table#wjportal-table tbody tr {color: ".esc_attr($color3).";border-bottom: 1px solid ".esc_attr($color5).";}
    table#wjportal-table tbody tr:last-child {border-bottom: 0;}
    table#wjportal-table tr th {background: #fafafa;}
    table#wjportal-table tbody tr td a {color: ".esc_attr($color1).";box-shadow: none;}
    table#wjportal-table tbody tr td a:hover {color: ".esc_attr($color2).";}

    /**********************
        my invoices
    **********************/
    div.wjportal-my-invoices-wrapper div.wjportal-my-invoices-sec-tit {border: 1px solid ".esc_attr($color5).";border-bottom: 0;background: ".esc_attr($color7).";}
    div.wjportal-my-invoices-wrapper div.wjportal-my-invoices-sec-tit div.wjportal-my-invoices-sec-tit-txt {color: ".esc_attr($color2).";}
    div.wjportal-my-invoices-wrapper div.wjportal-my-invoices-sec-action-wrp .wjportal-my-invoices-sec-act-btn {border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
    div.wjportal-my-invoices-wrapper div.wjportal-my-invoices-sec-action-wrp .wjportal-my-invoices-sec-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}

    /**********************
        packages list
    **********************/
    div.wjportal-packages-list div.wjportal-packages-list-title div.wjportal-packages-list-title-txt {color: ".esc_attr($color2).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-top {border-bottom: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-top div.wjportal-pkg-list-item-title div.wjportal-pkg-list-item-title-txt {color: ".esc_attr($color2).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-top div.wjportal-pkg-list-item-crt-date {color: ".esc_attr($color3).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-top span.wjportal-pkg-list-item-disc {background: #0def0d;color: ".esc_attr($color7).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-top div.wjportal-pkg-list-item-price span.wjportal-pkg-list-item-price-txt {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-top div.wjportal-pkg-list-item-price span.wjportal-pkg-list-item-price-discount {color: ".esc_attr($color2).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-top div.wjportal-pkg-list-item-price span.wjportal-pkg-list-item-price-discount::before {background: #f00;}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-mid div.wjportal-pkg-list-item-data div.wjportal-pkg-list-item-row {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-mid div.wjportal-pkg-list-item-data div.wjportal-pkg-list-item-row span.wjportal-pkg-list-item-row-tit {color: ".esc_attr($color3).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-mid div.wjportal-pkg-list-item-data div.wjportal-pkg-list-item-row span.wjportal-pkg-list-item-row-val {color: ".esc_attr($color2).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-btm {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-btm div.wjportal-pkg-list-item-action-wrp .wjportal-pkg-list-item-act-btn {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";color: ".esc_attr($color1).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-btm div.wjportal-pkg-list-item-action-wrp .wjportal-pkg-list-item-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-btm div.wjportal-pkg-list-item-action-wrp div.wjportal-pkg-list-renew-discount {color: ".esc_attr($color3).";}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-btm div.wjportal-pkg-list-item-action-wrp div.wjportal-pkg-list-purchased-text {color: #01a101;}
    div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-btm div.wjportal-pkg-list-item-exp-date {color: #f00;}

    /**********************
        select packages list
    **********************/
    div.wjportal-select-packages-list div.wjportal-packages-list div.wjportal-pkg-list-item div.wjportal-pkg-list-item-mid div.wjportal-pkg-list-item-data div.wjportal-pkg-list-item-row:last-child {border-bottom: 0;}

    /**********************
        by type
    **********************/
    div.wjportal-by-type-wrp div.wjportal-type-wrapper a {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color3).";background: #fafafa;}
    div.wjportal-by-type-wrp div.wjportal-type-wrapper a:hover {border-color: ".esc_attr($color1).";color: ".esc_attr($color2).";background: ".esc_attr($color7).";}

    /**********************
        purchase history
    **********************/
    div.wjportal-purchase-hist div.wjportal-purchase-hist-title div.wjportal-purchase-hist-title-txt {color: ".esc_attr($color2).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item {border: 1px solid ".esc_attr($color5).";background: ".esc_attr($color7).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-top {border-bottom: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-top div.wjportal-purch-hist-title div.wjportal-purch-hist-title-txt {color: ".esc_attr($color2).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-top div.wjportal-purch-hist-crt-date {color: ".esc_attr($color3).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-top span.wjportal-purch-hist-disc {background: #0def0d;color: ".esc_attr($color7).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-top div.wjportal-purch-hist-price span.wjportal-purch-hist-price-txt {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-top div.wjportal-purch-hist-price span.wjportal-purch-hist-price-discount {color: ".esc_attr($color2).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-top div.wjportal-purch-hist-price span.wjportal-purch-hist-price-discount::before {background: #f00;}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-mid div.wjportal-purch-hist-data div.wjportal-purch-hist-row {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-mid div.wjportal-purch-hist-data div.wjportal-purch-hist-row span.wjportal-purch-hist-row-tit {color: ".esc_attr($color3).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-mid div.wjportal-purch-hist-data div.wjportal-purch-hist-row span.wjportal-purch-hist-row-val {color: ".esc_attr($color2).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-btm {border-top: 1px solid ".esc_attr($color5).";background: #fafafa;}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-btm div.wjportal-purch-hist-action-wrp .wjportal-purch-hist-act-btn {background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";color: ".esc_attr($color1).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-btm div.wjportal-purch-hist-action-wrp .wjportal-purch-hist-act-btn:hover {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-btm div.wjportal-purch-hist-action-wrp div.wjportal-pkg-list-renew-discount {color: ".esc_attr($color3).";}
    div.wjportal-purchase-hist div.wjportal-pkg-list-item div.wjportal-purch-hist-btm div.wjportal-purch-hist-action-wrp div.wjportal-pkg-list-purchased-text {color: #01a101;}

    /**********************
        widgets
    **********************/

    .wjportal-mod-heading {color: ".esc_attr($color2).";}
    #wpjobportal_module_wrapper #tp_heading span#tp_headingtext {color: ".esc_attr($color2).";}

    /* jobs on map */
    div.wjportal-jobs-list-map::after {border-top: 10px solid ".esc_attr($color7).";border-right: 10px solid transparent;border-left: 10px solid transparent;}
    div.wjportal-jobs-list-map div.wjportal-jobs-list {border: 0;}
    div.wjportal-jobs-list-map div.wjportal-jobs-list div.wjportal-jobs-cnt div.wjportal-jobs-data .wjportal-companyname {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list-map div.wjportal-jobs-list div.wjportal-jobs-cnt div.wjportal-jobs-data .wjportal-companyname:hover {color: ".esc_attr($color1).";}
    div.wjportal-jobs-list-map div.wjportal-jobs-list div.wjportal-jobs-cnt div.wjportal-jobs-data .wjportal-job-title {color: ".esc_attr($color1).";}
    div.wjportal-jobs-list-map div.wjportal-jobs-list div.wjportal-jobs-cnt div.wjportal-jobs-data .wjportal-job-title:hover {color: ".esc_attr($color2).";}
    div.wjportal-jobs-list-map div.wjportal-jobs-list div.wjportal-jobs-cnt div.wjportal-jobs-data .wjportal-jobs-data-txt {color: ".esc_attr($color3).";}

    /* company widget */
    div.wjportal-comp-mod {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-comp-mod:last-child {border-bottom: 0;}
    div.wjportal-comp-mod div.wjportal-comp-cont div.wjportal-company-title .wjportal-companyname {color: ".esc_attr($color1).";}
    div.wjportal-comp-mod div.wjportal-comp-cont div.wjportal-company-title .wjportal-companyname:hover {color: ".esc_attr($color2).";}
    div.wjportal-comp-mod div.wjportal-comp-cont div.wjportal-company-data span.wjportal-company-data-tit {color: ".esc_attr($color3).";}
    div.wjportal-comp-mod div.wjportal-comp-cont div.wjportal-company-data span.wjportal-company-data-val {color: ".esc_attr($color2).";}

    /* job widget */
    div.wjportal-job-mod {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-job-mod:last-child {border-bottom: 0;}
    div.wjportal-job-mod div.wjportal-job-cont div.wjportal-job-title .wjportal-jobname {color: ".esc_attr($color1).";}
    div.wjportal-job-mod div.wjportal-job-cont div.wjportal-job-title .wjportal-jobname:hover {color: ".esc_attr($color2).";}
    div.wjportal-job-mod div.wjportal-job-cont div.wjportal-job-data span.wjportal-job-data-tit {color: ".esc_attr($color3).";}
    div.wjportal-job-mod div.wjportal-job-cont div.wjportal-job-data span.wjportal-job-data-val {color: ".esc_attr($color2).";}
    div.wjportal-job-mod div.wjportal-job-cont div.wjportal-job-data span.wjportal-job-data-val .wjportal-compname {color: ".esc_attr($color1).";}
    div.wjportal-job-mod div.wjportal-job-cont div.wjportal-job-data span.wjportal-job-data-val .wjportal-compname:hover {color: ".esc_attr($color2).";}

    /* resume widget */
    div.wjportal-resume-mod {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-resume-mod:last-child {border-bottom: 0;}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-title .wjportal-res-name {color: ".esc_attr($color1).";}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-title .wjportal-res-name:hover {color: ".esc_attr($color2).";}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-data span.wjportal-res-data-tit {color: ".esc_attr($color3).";}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-data span.wjportal-res-data-val {color: ".esc_attr($color2).";}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-data .wjportal-res-app {color: ".esc_attr($color2).";}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-data .wjportal-res-app:hover {color: ".esc_attr($color1).";}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-data span.wjportal-res-data-val .wjportal-res-name {color: ".esc_attr($color2).";}
    div.wjportal-resume-mod div.wjportal-res-cont div.wjportal-res-data span.wjportal-res-data-val .wjportal-res-name:hover {color: ".esc_attr($color1).";}

    /* job by category / type widget */
    div.wjportal-job-by-mod div.wjportal-job-by div.wjportal-job-by-item {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-job-by-mod div.wjportal-job-by div.wjportal-job-by-item:last-child {border-bottom: 0;}
    div.wjportal-job-by-mod div.wjportal-job-by div.wjportal-job-by-item .wjportal-job-by-item-cnt {color: ".esc_attr($color2).";}
    div.wjportal-job-by-mod div.wjportal-job-by div.wjportal-job-by-item .wjportal-job-by-item-cnt:hover {color: ".esc_attr($color1).";}

    /* job by location widget */
    div.wjportal-job-by-location-mod div.wjportal-job-by-loc div.wjportal-job-by-loc-item {border-bottom: 1px solid ".esc_attr($color5).";}
    div.wjportal-job-by-location-mod div.wjportal-job-by-loc div.wjportal-job-by-loc-item:last-child {border-bottom: 0;}
    div.wjportal-job-by-location-mod div.wjportal-job-by-loc div.wjportal-job-by-loc-item .wjportal-job-by-loc-item-cnt {color: ".esc_attr($color2).";}
    div.wjportal-job-by-location-mod div.wjportal-job-by-loc div.wjportal-job-by-loc-item .wjportal-job-by-loc-item-cnt:hover {color: ".esc_attr($color1).";}

    /* job by stats widget */
    div.wjportal-stats-mod div.wjportal-stats div.wjportal-stats-data {border-bottom: 1px solid ".esc_attr($color5).";color: ".esc_attr($color2).";}
    div.wjportal-stats-mod div.wjportal-stats div.wjportal-stats-data:last-child {border-bottom: 0;}

    /* search job / resume widget */
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-row div.wjportal-form-tit {color: ".esc_attr($color2).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-row div.wjportal-form-val {color: ".esc_attr($color3).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-row div.wjportal-form-val input[type='text'] {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color3).";background: ".esc_attr($color7).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-row div.wjportal-form-val select {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color3).";background: ".esc_attr($color7).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-row div.wjportal-form-val textarea {border: 1px solid ".esc_attr($color5).";color: ".esc_attr($color3).";background: ".esc_attr($color7).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-btn-row .wjportal-form-srch-btn {border: 1px solid ".esc_attr($color1).";color: ".esc_attr($color7).";background: ".esc_attr($color1).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-btn-row .wjportal-form-srch-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-btn-row .wjportal-form-adv-srch-btn {border: 1px solid ".esc_attr($color2).";color: ".esc_attr($color2).";background: ".esc_attr($color7).";}
    div.wjportal-search-mod form.wjportal-form div.wjportal-form-btn-row .wjportal-form-adv-srch-btn:hover {color: ".esc_attr($color7).";background: ".esc_attr($color2).";}

    /* themes */
    div#theme_heading div.color_portion input[type='text'] {color: ".esc_attr($color7).";}
    div#theme_heading {color:".esc_attr($color7).";border-bottom: 1px solid ".esc_attr($color5).";}
    div#theme_heading span.js_theme_heading {border-bottom:4px solid #1572e8;}
    div.js_effect_preview div#jsst_breadcrumbs_parent {border:1px solid #D5D5D5;background:#FCFCFC;}
    div.js_effect_preview div#jsst_breadcrumbs_parent div{border-left:1px solid #ababab;}
    div.js_effect_preview div#jsst_breadcrumbs_parent img{box-shadow: none;}
    div.js_effect_preview div#jsst_breadcrumbs_parent div.home {background-color:#4d4d4d;}
    div.js_effect_preview div#jsst_breadcrumbs_parent div.links a.links {box-shadow: unset;}
    div.js_effect_preview div#jsst_breadcrumbs_parent div.lastlink {color: #343538;}
    div.js_effect_preview div#wpjobportal-wrapper {background: ".esc_attr($color7)."FFF;}
    div#theme_heading div.color_portion span.color_title {color: ".esc_attr($color4)."}

    /* themes preset */
    div#black_wrapper_jobapply{background:rgba(0,0,0,.8);}
    div#js_job_wrapper span.js_job_controlpanelheading {background: #1572e8;color: ".esc_attr($color7)."FFF;}
    div#js_job_wrapper div.js_theme_wrapper{background:#262626;border-bottom: 9px solid #1572e8;}
    div#js_job_wrapper div.js_theme_wrapper div.theme_platte div.color_wrapper img.preview{box-shadow: 0px 0px 3px ".esc_attr($color7)."fff;}
    div#js_job_wrapper div.js_theme_wrapper div.theme_platte div.color_wrapper span.theme_name{color:".esc_attr($color7)."fff;}

    div.wjportal-resume-list .wjportal-coverletter-act-btn{border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color3).";}

    div.wjportal-view-job-page-job-apply-form-wraper div.wjportal-job-sec-title {color: ".esc_attr($color2).";border-bottom:1px solid ".$color5.";}
    div.wjportal-view-job-page-job-apply-form-wraper div.wjportal-job-desc {color: ".esc_attr($color3).";}



    div.wjportal-view-job-page-job-apply-form-wraper .wjportal-form-btn.wjportal-save-btn {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";}
    div.wjportal-view-job-page-job-apply-form-wraper .wjportal-form-btn.wjportal-save-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}

    div.wjportal-view-job-page-job-apply-form-wraper .wjportal-login-to-apply-btn {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color1).";}
    div.wjportal-view-job-page-job-apply-form-wraper .wjportal-login-to-apply-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";}


    div.wjportal-view-job-page-job-apply-form-wraper .wjportal-apply-as-visitor-btn {background: ".esc_attr($color2).";color: ".esc_attr($color7).";border-color: ".esc_attr($color2).";}
    div.wjportal-view-job-page-job-apply-form-wraper .wjportal-apply-as-visitor-btn:hover {background: ".esc_attr($color7).";color: ".esc_attr($color2).";}
    div.wjportal-view-job-page-job-apply-form-wraper .job-detail-jobapply-message-wrap .job-detail-jobapply-message-msg {border: 1px solid ".esc_attr($color5).";background: #fafafa;color: ".esc_attr($color3).";}
    /* apply jobs */
    
    div.wjportal-view-job-page-job-apply-form-wraper .wpjobportal-quickapply-form .wjportal-login-to-apply-btn {border: 1px solid ".esc_attr($color7).";background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-view-job-page-job-apply-form-wraper .wpjobportal-quickapply-form .wjportal-login-to-apply-btn:hover {color: ".esc_attr($color1).";background: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";}
        div.wjportal-view-job-page-job-apply-form-wraper div.wjportal-form-wrp.wpjobportal-quickapply-form .job-detail-jobapply-message-wrap .job-detail-jobapply-message-link {color: ".esc_attr($color7).";border: 1px solid ".esc_attr($color1).";background: ".esc_attr($color7).";color: ".esc_attr($color1).";}
        div.wjportal-view-job-page-job-apply-form-wraper div.wjportal-form-wrp.wpjobportal-quickapply-form .job-detail-jobapply-message-wrap .job-detail-jobapply-message-link:hover {color: ".esc_attr($color7).";background: ".esc_attr($color1).";}
    div.wjportal-view-job-page-job-apply-form-wraper .wpjobportal-quickapply-form .wjportal-apply-as-visitor-btn{border: 1px solid ".esc_attr($color2).";}

    div.wjportal-resume-list div.wjportal-resume-list-btm-wrp div.wjportal-resume-action-wrp .wjportal-resume-act-btn.wjportal-resume-act-btn-ai-suggested-jobs {background: ".esc_attr($color1).";color: ".esc_attr($color7).";}
    div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-act-btn.wjportal-jobs-act-btn-ai-suggested-resumes {background: ".esc_attr($color1).";color: ".esc_attr($color7).";border-color: ".esc_attr($color1).";}


    /* responsive */
    @media (max-width: 782px) {

    }

    @media (max-width: 650px) {

    }

    @media (max-width: 480px) {

    }
";
$result4 ="";
    /* rtl */
if (is_rtl()) {
    $result4 ="
        /**********************
            header
        **********************/
        div.wjportal-page-header div.wjportal-header-actions div.wjportal-filter-wrp div.wjportal-filter-image {border-right: 0;border-left: 1px solid ".esc_attr($color5).";}




        /* responsive */
        @media (max-width: 782px) {

        }

        @media (max-width: 650px) {

        }

        @media (max-width: 480px) {

        }
    ";
}

$color_string_css = $result.$result1.$result2.$result3.$result4;



if ( ! function_exists( 'WP_Filesystem' ) ) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
}
global $wp_filesystem;
if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
    $creds = request_filesystem_credentials( site_url() );
    wp_filesystem( $creds );
}

$file = WPJOBPORTAL_PLUGIN_PATH . 'includes/css/color.css';
$response = $wp_filesystem->put_contents( $file, $color_string_css );
return 1;
