<?php
    if (!defined('ABSPATH'))
        die('Restricted Access');
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <!-- popup -->
        <div id="full_background" style="display:none;"></div>
        <div id="popup_main" style="display:none;">
            <span class="popup-top">
                <span id="popup_title" ></span>
                <img id="popup_cross" alt="<?php echo esc_html(__('popup cross','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/popup-close.png">
            </span>
            <div class="popup-search">
                <form id="userpopupsearch">
                    <div class="popup-form-fields-wrp">
                        <div class="popup-form-field search-value">
                            <input type="text" name="uname" id="uname" placeholder="<?php echo esc_html(__('Username', 'wp-job-portal'));?>" />
                        </div>
                        <div class="popup-form-field search-value">
                            <input type="text" name="name" id="name" placeholder="<?php echo esc_html(__('Name', 'wp-job-portal'));?>" />
                        </div>
                        <div class="popup-form-field search-value">
                            <input type="text" name="email" id="email" placeholder="<?php echo esc_html(__('Email Address', 'wp-job-portal'));?>"/>
                        </div>
                        <div class="popup-form-btn-wrp">
                            <input type="submit" class="popup-search-btn" value="<?php echo esc_html(__('Search', 'wp-job-portal'));?>" />
                            <input type="submit" class="popup-reset-btn" onclick="document.getElementById('name').value = '';document.getElementById('uname').value = ''; document.getElementById('email').value = '';" value="<?php echo esc_html(__('Reset', 'wp-job-portal'));?>" />
                        </div>
                    </div>
                </form>
            </div>
            <div id="popup-record-data"></div>
        </div>
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
                        <li><?php echo esc_html(__('Assign Role','wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>    
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-help-btn" class="wpjobportal-help-btn">
                    <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/help.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>    
        </div>
        <!-- top head -->
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'users' , 'layouts' => 'assignrole')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <form id="wpjobportal-form" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_user&task=assignuserrole")); ?>">
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Select User', 'wp-job-portal')) 
                        . '<font class="required-notifier">*</font>'; ?>
                            
                    </div>
                    <div class="wpjobportal-form-value">
                        <label id="uname"></label>
                        <?php if (!isset(wpjobportal::$_data[0]->uid)) { ?>
                        <a href="#" id="userpopup">
                            <?php echo esc_html(__('User Name', 'wp-job-portal')); ?>
                        </a>
                        <div id="username-div"></div>
                        <?php } ?>               
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('uid', '', array('data-validation' => 'required')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Role', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('roleid', WPJOBPORTALincluder::getJSModel('common')->getRolesForCombo(), '', '', array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('payer_firstname', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('payer_emailadress', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('user-popup-title-text', esc_html(__('Select User','wp-job-portal'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_user_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <div class="wpjobportal-form-button">
                    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Assign role', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function () {
            jQuery.validate();
            jQuery('a#userpopup').click(function (e) {
                e.preventDefault();
                jQuery('div#popup-new-company').css('display', 'none');
                jQuery('img.icon').css('display', 'none');
                jQuery('div#popup-record-data').css('display', 'block');
                jQuery('div#full_background').show();
                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php'))."\";
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'user', task: 'getAllRoleLessUsersAjax', listfor: 1, '_wpnonce':'". esc_attr(wp_create_nonce("get-all-role-less-users-ajax"))."'}, function (data) {
                    if (data) {
                        jQuery('div#popup-record-data').html('');
                        jQuery('span#popup_title').html(jQuery('input#user-popup-title-text').val());
                        jQuery('div#popup-record-data').html(data);
                        setUserLink();
                    }
                });
                jQuery('div#popup_main').slideDown('slow');
            });
        });
        
        function setUserLink() {
            jQuery('a.js-userpopup-link').each(function () {
                var anchor = jQuery(this);
                jQuery(anchor).click(function (e) {
                    var name = jQuery(this).attr('data-name');
                    jQuery('label#uname').html(name);
                    var id = jQuery(this).attr('data-id');
                    var email = jQuery(this).attr('data-email');
                    var name = jQuery(this).attr('data-name');
                    jQuery('input#uid').val(id);
                    jQuery('input#payer_firstname').val(name);
                    jQuery('input#payer_emailadress').val(email);
                    jQuery('div#popup_main').slideUp('slow', function () {
                        jQuery('div#full_background').hide();
                    });
                });
            });
        }
        
        jQuery(document).delegate('form#userpopupsearch', 'submit', function (e) {
            e.preventDefault();
            e.preventDefault();
            var username = jQuery('input#uname').val();
            var name = jQuery('input#name').val();
            var emailaddress = jQuery('input#email').val();
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'user', task: 'getAllRoleLessUsersAjax', name: name, uname: username, email: emailaddress, listfor: 1, '_wpnonce':'". esc_attr(wp_create_nonce("get-all-role-less-users-ajax"))."'}, function (data) {
                if (data) {
                    console.log(data);
                    jQuery('span#popup_title').html(jQuery('input#user-popup-title-text').val());
                    jQuery('div#popup-record-data').html(data);
                    setUserLink();
                }
            });//jquery closed
        });
        jQuery('span.close, div#full_background,img#popup_cross').click(function (e) {
            jQuery('div#popup_main').slideUp('slow', function () {
                jQuery('div#full_background').hide();
            });
        
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
