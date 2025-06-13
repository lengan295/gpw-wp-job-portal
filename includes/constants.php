<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

if (!defined('WPJOBPORTAL_FILE_TYPE_ERROR')) {
    define('WPJOBPORTAL_FILE_TYPE_ERROR', 'WPJOBPORTAL_FILE_TYPE_ERROR');
    define('WPJOBPORTAL_FILE_SIZE_ERROR', 'WPJOBPORTAL_FILE_SIZE_ERROR');
    define('WPJOBPORTAL_ALREADY_EXIST', 'WPJOBPORTAL_ALREADY_EXIST');
    define('WPJOBPORTAL_NOT_EXIST', 'WPJOBPORTAL_NOT_EXIST');
    define('WPJOBPORTAL_IN_USE', 'WPJOBPORTAL_IN_USE');
    define('WPJOBPORTAL_SET_DEFAULT', 'WPJOBPORTAL_SET_DEFAULT');
    define('WPJOBPORTAL_SET_DEFAULT_ERROR', 'WPJOBPORTAL_SET_DEFAULT_ERROR');
    define('WPJOBPORTAL_STATUS_CHANGED', 'WPJOBPORTAL_STATUS_CHANGED');
    define('WPJOBPORTAL_STATUS_CHANGED_ERROR', 'WPJOBPORTAL_STATUS_CHANGED_ERROR');
    define('WPJOBPORTAL_APPROVED', 'WPJOBPORTAL_APPROVED');
    define('WPJOBPORTAL_APPROVE_ERROR', 'WPJOBPORTAL_APPROVE_ERROR');
    define('WPJOBPORTAL_REJECTED', 'WPJOBPORTAL_REJECTED');
    define('WPJOBPORTAL_REJECT_ERROR', 'WPJOBPORTAL_REJECT_ERROR');
    define('WPJOBPORTAL_UN_PUBLISHED', 'WPJOBPORTAL_UN_PUBLISHED');
    define('WPJOBPORTAL_UN_PUBLISH_ERROR', 'WPJOBPORTAL_UN_PUBLISH_ERROR');
    define('WPJOBPORTAL_UNPUBLISH_DEFAULT_ERROR', 'WPJOBPORTAL_UNPUBLISH_DEFAULT_ERROR');
    define('WPJOBPORTAL_PUBLISHED', 'WPJOBPORTAL_PUBLISHED');
    define('WPJOBPORTAL_PUBLISH_ERROR', 'WPJOBPORTAL_PUBLISH_ERROR');
    define('WPJOBPORTAL_REQUIRED', 'WPJOBPORTAL_REQUIRED');
    define('WPJOBPORTAL_REQUIRED_ERROR', 'WPJOBPORTAL_REQUIRED_ERROR');
    define('WPJOBPORTAL_NOT_REQUIRED', 'WPJOBPORTAL_NOT_REQUIRED');
    define('WPJOBPORTAL_NOT_REQUIRED_ERROR', 'WPJOBPORTAL_NOT_REQUIRED_ERROR');
    define('WPJOBPORTAL_ORDER_UP', 'WPJOBPORTAL_ORDER_UP');
    define('WPJOBPORTAL_ORDER_UP_ERROR', 'WPJOBPORTAL_ORDER_UP_ERROR');
    define('WPJOBPORTAL_ORDER_DOWN', 'WPJOBPORTAL_ORDER_DOWN');
    define('WPJOBPORTAL_ORDER_DOWN_ERROR', 'WPJOBPORTAL_ORDER_DOWN_ERROR');
    define('WPJOBPORTAL_SAVED', 'WPJOBPORTAL_SAVED');
    define('WPJOBPORTAL_SAVE_ERROR', 'WPJOBPORTAL_SAVE_ERROR');
    define('WPJOBPORTAL_ALREADY_ADD', 'WPJOBPORTAL_ALREADY_ADD');
    define('WPJOBPORTAL_DELETED', 'WPJOBPORTAL_DELETED');
    define('WPJOBPORTAL_DELETE_ERROR', 'WPJOBPORTAL_DELETE_ERROR');
    define('WPJOBPORTAL_VERIFIED', 'WPJOBPORTAL_VERIFIED');
    define('WPJOBPORTAL_APPLY', 'WPJOBPORTAL_APPLY');
    define('WPJOBPORTAL_APPLY_ERROR', 'WPJOBPORTAL_APPLY_ERROR');
    define('WPJOBPORTAL_UN_VERIFIED', 'WPJOBPORTAL_UN_VERIFIED');
    define('WPJOBPORTAL_VERIFIED_ERROR', 'WPJOBPORTAL_VERIFIED_ERROR');
    define('WPJOBPORTAL_UN_VERIFIED_ERROR', 'WPJOBPORTAL_UN_VERIFIED_ERROR');
    define('WPJOBPORTAL_INVALID_REQUEST', 'WPJOBPORTAL_INVALID_REQUEST');
    define('WPJOBPORTAL_ENABLED', 'WPJOBPORTAL_ENABLED');
    define('WPJOBPORTAL_DISABLED', 'WPJOBPORTAL_DISABLED');
    define('WPJOBPORTAL_NOTENOUGHCREDITS', 'NOTENOUGHCREDITS' );
    define('WPJOBPORTAL_PACKAGE_ALREADY_PURCHASED', 'WPJOBPORTAL_PACKAGE_ALREADY_PURCHASED');
    define('WPJOBPORTAL_PLUGIN_PATH', plugin_dir_path( __DIR__ ));
    define('WPJOBPORTAL_PLUGIN_URL', plugin_dir_url( __DIR__ ));

    define('WPJOBPORTAL_COMPANY',1);
    define('WPJOBPORTAL_JOB',2);
    define('WPJOBPORTAL_RESUME',3);
    define('WPJOBPORTAL_SALARYRANGE',4);
    define('WPJOBPORTAL_JOBTYPE',5);
    define('WPJOBPORTAL_ADDRESSDATA',6);
    define('WPJOBPORTAL_AGE',7);
    define('WPJOBPORTAL_CATEGORY',11);
    define('WPJOBPORTAL_CITY',12);
    define('WPJOBPORTAL_COUNTRY',13);
    define('WPJOBPORTAL_CURRENCY',14);
    define('WPJOBPORTAL_CUSTOMFIELD',15);
    define('WPJOBPORTAL_FIELDORDERING',16);
    define('WPJOBPORTAL_DEPARTMENT',17);
    define('WPJOBPORTAL_EMPLOYERPACKAGES',18);
    define('WPJOBPORTAL_EXPERIENCE',19);
    define('WPJOBPORTAL_HIGHESTEDUCATION',20);
    define('WPJOBPORTAL_JOBSTATUS',21);
    define('WPJOBPORTAL_SALARYRANGETYPE',23);
    define('WPJOBPORTAL_SHIFT',24);
    define('WPJOBPORTAL_STATE',25);
    define('WPJOBPORTAL_USER',26);
    define('WPJOBPORTAL_USERROLE',27);
    define('WPJOBPORTAL_CONFIGURATION',28);
    define('WPJOBPORTAL_EMAILTEMPLATE',29);
    define('WPJOBPORTAL_JOBSAVESEARCH',30);
    define('WPJOBPORTAL_RESUMESEARCH',31);
    define('WPJOBPORTAL_RECORD',32);
    define('WPJOBPORTAL_SLUG',34);
    define('WPJOBPORTAL_PREFIX',35);
    define('WPJOBPORTAL_JOBALERT',36);
    define('folderresume', 37);
    define('folder', 37);
    define('WPJOBPORTAL_SALARY_NEGOTIABLE',1);
    define('WPJOBPORTAL_SALARY_FIXED',2);
    define('WPJOBPORTAL_SALARY_RANGE',3);
    define('WPJOBPORTAL_STATUS_DRAFT',3);
    define('WPJOBPORTAL_ALLOWED_TAGS',array(
        'div'      => array(
            'class'  => array(),
            'id' => array(),
            'data-sitekey' => array(),
            'title' => array(),
            'role' => array(),
            'onclick' => array(),
            'onmouseout' => array(),
            'onmouseover' => array(),
            'data-section' => array(),
            'data-sectionid' => array(),
            'data-sitekey' => array(),
            'data-boxid' => array(),
            'style' => array(),
        ),
        'button'      => array(
            'class'  => array(),
            'id' => array(),
            'type' => array(),
            'title' => array(),
            'role' => array(),
            'data-dismiss' => array(),
            'aria-label' => array(),
            'style' => array(),
        ),
        'i'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h1'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h2'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h3'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h4'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h5'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'h6'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),  
        ),
        'font'      => array(
            'class'  => array(),
            'id' => array(),
            'style' => array(),
        ),
        'span'      => array(
            'class'  => array(),
            'id' => array(),
            'aria-hidden' => array(),
            'style' => array(),
        ),
        'input'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'onclick' => array(),
            'onchange' => array(),
            'data-validation' => array(),
            'ckbox-group-name' => array(),
            'required' => array(),
            'size' => array(),
            'placeholder' => array(),
            'checked' => array(),
            'autocomplete' => array(),
            'multiple' => array(),
            'rel' => array(),
            'maxlength' => array(),
            'disabled' => array(),
            'readonly' => array(),
            'credit_userid' => array(),
            'data-dismiss' => array(),
            'data-validation-optional' => array(),
            'style' => array(),
            'disbled' => array(),
        ),
        'textarea'     => array(
            'rows' => array(),
            'name' => array(),
            'class' => array(),
            'id' => array(),
            'value' => array(),
            'cols' => array(),
            'data-validation' => array(),
            'autocomplete' => array(),
            'style' => array(),
        ),
        'button'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'onclick' => array(),
            'data-validation' => array(),
            'required' => array(),
            'data-dismiss' => array(),
            'style' => array(),
        ),
        'select'      => array(
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'onchange' => array(),
            'data-validation' => array(),
            'required' => array(),
            'multiple' => array(),
            'style' => array(),
            'disabled' => array(),
        ),
        'option'      => array(
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'value' => array(),
            'selected' => array(),
            'style' => array(),
        ),
        'img'      => array(
            'src'  => array(),
            'id' => array(),
            'class' => array(),
            'onclick' => array(),
            'alt' => array(),
            'width' => array(),
            'height' => array(),
            'border' => array(),
            'style' => array(),
        ),
        'link'      => array(
            'src'  => array(),
            'id' => array(),
            'rel' => array(),
            'href' => array(),
            'media' => array(),
            'style' => array(),
        ),
        'meta'      => array(
            'property'  => array(),
            'content' => array(),
            'style' => array(),
        ),
        'a'      => array(
            'href'  => array(),
            'title' => array(),
            'onclick' => array(),
            'id' => array(),
            'class' => array(),
            'name' => array(),
            'data-toggle' => array(),
            'data-id' => array(),
            'data-name' => array(),
            'data-email' => array(),
            'data-id' => array(),
            'data-name' => array(),
            'data-email' => array(),
            'message' => array(),
            'confirmmessage' => array(),
            'data-for' => array(),
            'data-sortby' => array(),
            'data-image1' => array(),
            'data-image2' => array(),
            'target' => array(),
            'style' => array(),
        ),
        'ul'      => array(
            'type'  => array(),
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'ol'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'li'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'dl'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'dt'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'dd'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'table'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'tr'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'td'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'th'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'p'      => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'form'      => array(
            'id' => array(),
            'class' => array(),
            'method' => array(),
            'action' => array(),
            'enctype' => array(),
        ),
        'label'      => array(
            'id' => array(),
            'class' => array(),
            'for' => array(),
            'onclick' => array(),
            'style' => array(),
        ),
        'i'     => array(
            'id' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'script'     => array(
            'type' => array(),
            'class' => array(),
            'style' => array(),
        ),
        'br'     => array(
            'style' => array(),),
        'hr'     => array(
            'style' => array(),),
        'b'     => array(
            'style' => array(),),
        'em'     => array(
            'style' => array(),),
        'strong' => array(
            'style' => array(),
        ),
        'small' => array(
            'style' => array(),),
        ' ' => array(),
    ));
}
?>
