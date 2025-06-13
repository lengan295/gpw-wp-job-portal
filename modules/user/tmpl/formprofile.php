<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
    <?php
    if ( !WPJOBPORTALincluder::getTemplate('templates/header', array('module' => 'company')) ) {
        return;
    }
    ?>
    <div class="wjportal-main-wrapper wjportal-clearfix">
        <div class="wjportal-form-wrp wjportal-edit-profile-form">
            <div class="wjportal-page-header">
                <?php WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'user','layout'=>'update'));?>
            </div>
            <?php
            if( wpjobportal::$_error_flag_message == null ){ ?>
                <form id="wpjobportal-form" class="wjportal-form" method="post" action="<?php echo esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'user','action'=>'wpjobportaltask','task'=>'saveuser','wpjobportal'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_user_nonce')); ?>" enctype="multipart/form-data">
                    <?php WPJOBPORTALincluder::getTemplate('user/views/frontend/form-field',array('user' => wpjobportal::$_data[0])); ?>
                    <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportalpageid', wpjobportal::wpjobportal_getPageid()),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    <div class="wjportal-form-btn-wrp">
                        <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Update', 'wp-job-portal')), array('class' => 'wjportal-form-btn wjportal-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </form>
            <?php
            }else{
                // $obj = new wpjobportal_Messages();
                echo wp_kses(wpjobportal::$_error_flag_message,WPJOBPORTAL_ALLOWED_TAGS);
            }
            ?>
        </div>
    </div>
</div>
